import { useState, useEffect } from "react";
import { Download, Wallet, Plus, X, PieChart as PieIcon, BarChart3, TrendingUp, TrendingDown } from "lucide-react";
import { Button } from "@/components/ui/button";
import { BudgetCard } from "@/components/BudgetCard";
import { CategoryCard } from "@/components/CategoryCard";
import { TransactionItem } from "@/components/TransactionItem";
import { BudgetDialog } from "@/components/BudgetDialog";
import { TransactionDialog } from "@/components/TransactionDialog";
import { AllTransactionsSheet } from "@/components/AllTransactionsSheet";
import { AlertDialog, AlertDialogAction, AlertDialogCancel, AlertDialogContent, AlertDialogDescription, AlertDialogFooter, AlertDialogHeader, AlertDialogTitle } from "@/components/ui/alert-dialog";
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle, DialogTrigger } from "@/components/ui/dialog";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { toast } from "sonner";
import { exportToCSV } from "@/utils/exportData";
import { transactionAPI, categoryAPI, budgetAPI } from "@/lib/api";
import storageService from "@/lib/storage";
import { useCurrency } from "@/hooks/useCurrency";
import { motion, AnimatePresence } from "framer-motion";
import { getPeriodRange, Period } from "@/utils/date";
import {
  PieChart,
  Pie,
  Cell,
  ResponsiveContainer,
  Tooltip as RechartsTooltip,
  Legend,
  BarChart,
  Bar,
  XAxis,
  YAxis,
  CartesianGrid,
  AreaChart,
  Area
} from 'recharts';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from "@/components/ui/card";

interface IndexProps {
  period: Period;
}

const Index = ({ period }: IndexProps) => {
  const { currencySymbol } = useCurrency();
  const [userName, setUserName] = useState(() => {
    const user = JSON.parse(storageService.getItem("user") || "null");
    return user?.name || "User";
  });

  const [isLoading, setIsLoading] = useState(true);
  const [budgetData, setBudgetData] = useState({
    total: 0,
    spent: 0,
    remaining: 0,
    income: 0,
    netBalance: 0,
    isNetPositive: true,
    percentage: 0,
  });

  const [transactions, setTransactions] = useState<any[]>([]);
  const [dbTransactions, setDbTransactions] = useState<any[]>([]);
  const [categories, setCategories] = useState<any[]>([]);
  const [dbCategories, setDbCategories] = useState<any[]>([]);

  const [chartData, setChartData] = useState<any[]>([]);
  const [categoryChartData, setCategoryChartData] = useState<any[]>([]);

  const [showGreeting, setShowGreeting] = useState(() => {
    const hidden = storageService.getItem("hide-dashboard-greeting");
    return !hidden;
  });

  const [categoryDialogOpen, setCategoryDialogOpen] = useState(false);
  const [transactionDialogOpen, setTransactionDialogOpen] = useState(false);
  const [deletingIndex, setDeletingIndex] = useState<number | null>(null);
  const [editingIndex, setEditingIndex] = useState<number | null>(null);

  const [newCategory, setNewCategory] = useState({
    name: "",
    budget: "",
    emoji: ""
  });

  const COLORS = ['#23d783', '#02196b', '#0d0dad', '#a1eb6e', '#f43f5e', '#8b5cf6', '#f59e0b'];

  const fetchDashboardData = async () => {
    const user = JSON.parse(storageService.getItem("user") || "null");
    if (!user) return;

    try {
      const { startDate, endDate } = getPeriodRange(period);

      // Fetch data
      const [allTransactions, userCategories] = await Promise.all([
        transactionAPI.findByUserId(user.id),
        categoryAPI.findByUserId(user.id)
      ]);

      const txList = (allTransactions as any[]) || [];
      const catList = (userCategories as any[]) || [];

      // Filter transactions by period
      const filteredTransactions = txList.filter(t => {
        const d = t.date.split('T')[0];
        return d >= startDate && d <= endDate;
      });

      setDbTransactions(filteredTransactions);
      setDbCategories(catList);

      // Map transitions for display
      setTransactions(filteredTransactions.map(t => ({
        category: t.category_name || t.description || 'General',
        amount: `${currencySymbol}${parseFloat(t.amount).toFixed(2)}`,
        timestamp: new Date(t.date).toLocaleDateString(),
        icon: <span>{t.category_emoji || '💰'}</span>,
        type: t.type
      })));

      // Calculate category spending
      const categoryBudgets: any[] = [];
      const catChart: any[] = [];
      let totalSpent = 0;
      let totalBudget = 0;

      for (const cat of catList) {
        const spent = filteredTransactions
          .filter(t => t.category_id === cat.id && t.type === 'expense')
          .reduce((sum, t) => sum + parseFloat(t.amount), 0);

        const budgetAllowed = parseFloat(cat.budget);
        totalSpent += spent;
        totalBudget += budgetAllowed;

        categoryBudgets.push({
          name: cat.name,
          amount: `${currencySymbol}${spent.toFixed(2)}`,
          budget: `${currencySymbol}${budgetAllowed.toFixed(2)}`,
          percentage: budgetAllowed > 0 ? Math.min((spent / budgetAllowed) * 100, 100) : 0,
          emoji: cat.emoji || '📦'
        });

        if (spent > 0) {
          catChart.push({ name: cat.name, value: spent });
        }
      }

      setCategories(categoryBudgets);
      setCategoryChartData(catChart);

      // Income calculation
      const totalIncome = filteredTransactions
        .filter(t => t.type === 'income')
        .reduce((sum, t) => sum + parseFloat(t.amount), 0);

      const netBalance = totalIncome - totalSpent;

      setBudgetData({
        total: totalBudget,
        spent: totalSpent,
        remaining: Math.max(0, totalBudget - totalSpent),
        income: totalIncome,
        netBalance: netBalance,
        isNetPositive: netBalance >= 0,
        percentage: totalBudget > 0 ? (totalSpent / totalBudget) * 100 : 0
      });

      // Prepare Chart Data (Daily breakdown for the period)
      // For simplicity, showing income vs expense totals
      setChartData([
        { name: 'Income', amount: totalIncome, fill: '#23d783' },
        { name: 'Expense', amount: totalSpent, fill: '#f43f5e' }
      ]);

      setIsLoading(false);
    } catch (error) {
      console.error('Error fetching dashboard data:', error);
      setIsLoading(false);
    }
  };

  useEffect(() => {
    fetchDashboardData();
    const interval = setInterval(fetchDashboardData, 60000);
    return () => clearInterval(interval);
  }, [period, currencySymbol]);

  const handleAddCategory = async () => {
    const user = JSON.parse(storageService.getItem("user") || "null");
    if (!newCategory.name || !newCategory.budget) {
      toast.error("Please fill required fields");
      return;
    }
    try {
      await categoryAPI.create({
        userId: user.id,
        name: newCategory.name,
        budget: parseFloat(newCategory.budget),
        emoji: newCategory.emoji
      });
      toast.success("Category created!");
      setCategoryDialogOpen(false);
      fetchDashboardData();
    } catch (error) {
      toast.error("Failed to create category");
    }
  };

  const handleAddTransaction = async (data: any) => {
    const user = JSON.parse(storageService.getItem("user") || "null");
    try {
      await transactionAPI.create({
        ...data,
        userId: user.id
      });
      toast.success("Transaction added!");
      fetchDashboardData();
    } catch (error) {
      toast.error("Failed to add transaction");
    }
  };

  const handleDeleteTransaction = async () => {
    if (deletingIndex !== null) {
      try {
        await transactionAPI.delete(dbTransactions[deletingIndex].id);
        toast.success("Deleted!");
        setDeletingIndex(null);
        fetchDashboardData();
      } catch (error) {
        toast.error("Delete failed");
      }
    }
  };

  return (
    <div className="max-w-7xl mx-auto px-4 sm:px-6 py-6 sm:py-8 space-y-6 sm:space-y-8">
      {/* Greeting Section */}
      <AnimatePresence>
        {showGreeting && (
          <motion.div
            initial={{ opacity: 0, y: -20 }}
            animate={{ opacity: 1, y: 0 }}
            exit={{ opacity: 0, height: 0, marginBottom: 0 }}
            className="relative bg-primary/5 p-6 rounded-3xl border border-primary/10"
          >
            <button
              onClick={() => {
                setShowGreeting(false);
                storageService.setItem("hide-dashboard-greeting", "true");
              }}
              className="absolute top-4 right-4 p-2 text-muted-foreground hover:text-foreground"
            >
              <X className="h-5 w-5" />
            </button>
            <div className="space-y-2">
              <h1 className="text-3xl font-bold bg-gradient-to-r from-primary to-blue-600 bg-clip-text text-transparent">
                Hi, {userName}! 👋
              </h1>
              <p className="text-muted-foreground">
                You're tracking your <span className="font-bold text-primary capitalize">{period}</span> financial journey.
              </p>
            </div>
          </motion.div>
        )}
      </AnimatePresence>

      {/* Overview Cards */}
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <BudgetCard
          title="Income"
          amount={`${currencySymbol}${budgetData.income.toFixed(2)}`}
          variant="success"
          icon={<TrendingUp className="h-4 w-4" />}
        />
        <BudgetCard
          title="Expenses"
          amount={`${currencySymbol}${budgetData.spent.toFixed(2)}`}
          variant="warning"
          percentage={budgetData.percentage}
          icon={<TrendingDown className="h-4 w-4" />}
        />
        <BudgetCard
          title="Balance"
          amount={`${currencySymbol}${budgetData.netBalance.toFixed(2)}`}
          variant={budgetData.isNetPositive ? "success" : "destructive"}
          icon={<DollarSign className="h-4 w-4" />}
        />
        <BudgetCard
          title="Total Budget"
          amount={`${currencySymbol}${budgetData.total.toFixed(2)}`}
          variant="default"
          icon={<Wallet className="h-4 w-4" />}
        />
      </div>

      {/* Charts Section */}
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <Card className="rounded-3xl border-none shadow-premium bg-card/50 backdrop-blur-sm overflow-hidden border border-border/40">
          <CardHeader>
            <CardTitle className="flex items-center gap-2">
              <PieIcon className="h-5 w-5 text-primary" />
              Spending by Category
            </CardTitle>
            <CardDescription>How much you spend in each category</CardDescription>
          </CardHeader>
          <CardContent className="h-[300px]">
            {categoryChartData.length > 0 ? (
              <ResponsiveContainer width="100%" height="100%">
                <PieChart>
                  <Pie
                    data={categoryChartData}
                    cx="50%"
                    cy="50%"
                    innerRadius={60}
                    outerRadius={80}
                    paddingAngle={5}
                    dataKey="value"
                  >
                    {categoryChartData.map((entry, index) => (
                      <Cell key={`cell-${index}`} fill={COLORS[index % COLORS.length]} />
                    ))}
                  </Pie>
                  <RechartsTooltip
                    contentStyle={{ borderRadius: '16px', border: 'none', boxShadow: '0 10px 15px -3px rgb(0 0 0 / 0.1)' }}
                    formatter={(value: number) => [`${currencySymbol}${value.toFixed(2)}`, 'Spent']}
                  />
                  <Legend />
                </PieChart>
              </ResponsiveContainer>
            ) : (
              <div className="flex items-center justify-center h-full text-muted-foreground italic">
                No spending data for this period
              </div>
            )}
          </CardContent>
        </Card>

        <Card className="rounded-3xl border-none shadow-premium bg-card/50 backdrop-blur-sm overflow-hidden border border-border/40">
          <CardHeader>
            <CardTitle className="flex items-center gap-2">
              <BarChart3 className="h-5 w-5 text-secondary" />
              Income vs Expenses
            </CardTitle>
            <CardDescription>Comparison of your cash flow</CardDescription>
          </CardHeader>
          <CardContent className="h-[300px]">
            <ResponsiveContainer width="100%" height="100%">
              <BarChart data={chartData}>
                <CartesianGrid strokeDasharray="3 3" vertical={false} strokeOpacity={0.1} />
                <XAxis dataKey="name" axisLine={false} tickLine={false} />
                <YAxis axisLine={false} tickLine={false} tickFormatter={(val) => `${currencySymbol}${val}`} />
                <RechartsTooltip
                  cursor={{ fill: 'rgba(0,0,0,0.05)' }}
                  contentStyle={{ borderRadius: '16px', border: 'none', boxShadow: '0 10px 15px -3px rgb(0 0 0 / 0.1)' }}
                />
                <Bar dataKey="amount" radius={[8, 8, 0, 0]} />
              </BarChart>
            </ResponsiveContainer>
          </CardContent>
        </Card>
      </div>

      {/* Category Breakdown */}
      <div className="space-y-4">
        <div className="flex items-center justify-between">
          <h2 className="text-2xl font-bold font-heading">Categories</h2>
          <Button onClick={() => setCategoryDialogOpen(true)} variant="outline" className="rounded-full">
            <Plus className="h-4 w-4 mr-2" /> Add Category
          </Button>
        </div>
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          {categories.map((cat, i) => (
            <CategoryCard
              key={i}
              name={cat.name}
              amount={cat.amount}
              budget={cat.budget}
              percentage={cat.percentage}
              icon={cat.emoji}
            />
          ))}
        </div>
      </div>

      {/* Recent Transactions */}
      <div className="space-y-4">
        <div className="flex items-center justify-between">
          <h2 className="text-2xl font-bold font-heading">Recent Transactions</h2>
          <div className="flex gap-2">
            <TransactionDialog
              categories={dbCategories}
              onSave={handleAddTransaction}
              trigger={<Button className="rounded-full"><Plus className="h-4 w-4 mr-2" /> Add</Button>}
            />
            <AllTransactionsSheet transactions={transactions} trigger={<Button variant="ghost">View All</Button>} />
          </div>
        </div>
        <div className="space-y-3">
          {transactions.slice(0, 5).map((tx, i) => (
            <TransactionItem
              key={i}
              {...tx}
              onDelete={() => setDeletingIndex(i)}
            />
          ))}
        </div>
      </div>

      {/* Category Dialog */}
      <Dialog open={categoryDialogOpen} onOpenChange={setCategoryDialogOpen}>
        <DialogContent>
          <DialogHeader><DialogTitle>New Category</DialogTitle></DialogHeader>
          <div className="space-y-4 py-4">
            <div className="space-y-2">
              <Label>Name</Label>
              <Input value={newCategory.name} onChange={e => setNewCategory({ ...newCategory, name: e.target.value })} placeholder="e.g. Food" />
            </div>
            <div className="space-y-2">
              <Label>Budget Amount</Label>
              <Input type="number" value={newCategory.budget} onChange={e => setNewCategory({ ...newCategory, budget: e.target.value })} placeholder="0.00" />
            </div>
            <div className="space-y-2">
              <Label>Emoji Icon</Label>
              <Input value={newCategory.emoji} onChange={e => setNewCategory({ ...newCategory, emoji: e.target.value })} placeholder="🍔" maxLength={2} />
            </div>
            <Button onClick={handleAddCategory} className="w-full">Create Category</Button>
          </div>
        </DialogContent>
      </Dialog>

      {/* Delete Transaction */}
      <AlertDialog open={deletingIndex !== null} onOpenChange={o => !o && setDeletingIndex(null)}>
        <AlertDialogContent>
          <AlertDialogHeader><AlertDialogTitle>Confirm Delete</AlertDialogTitle></AlertDialogHeader>
          <AlertDialogFooter>
            <AlertDialogCancel>Cancel</AlertDialogCancel>
            <AlertDialogAction onClick={handleDeleteTransaction} className="bg-destructive text-destructive-foreground">Delete</AlertDialogAction>
          </AlertDialogFooter>
        </AlertDialogContent>
      </AlertDialog>
    </div>
  );
};

export default Index;

const DollarSign = ({ className }: { className?: string }) => (
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" className={className}>
    <line x1="12" y1="1" x2="12" y2="23" />
    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
  </svg>
);
