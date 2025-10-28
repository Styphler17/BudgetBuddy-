import { useState, useEffect } from "react";
import { Download, DollarSign, TrendingUp, Wallet, ShoppingBag, Home, Car, Coffee, Utensils, Plus } from "lucide-react";
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
import { transactionAPI, categoryAPI } from "@/lib/api";
import storageService from "@/lib/storage";

type Period = "daily" | "weekly" | "monthly" | "yearly";

interface IndexProps {
  period: Period;
}

const Index = ({ period }: IndexProps) => {
  const [userName, setUserName] = useState("Alex");
  const [budgetData, setBudgetData] = useState({
    total: "$3,500",
    spent: "$2,247",
    remaining: "$1,253",
    percentage: 64,
  });

  const [transactions, setTransactions] = useState<Array<{
    category: string;
    amount: string;
    timestamp: string;
    icon: JSX.Element;
    type: "expense" | "income";
  }>>([
    { category: "Whole Foods", amount: "$87.50", timestamp: "Today, 2:45 PM", icon: <ShoppingBag className="h-5 w-5" />, type: "expense" },
    { category: "Monthly Rent", amount: "$950.00", timestamp: "Yesterday, 9:00 AM", icon: <Home className="h-5 w-5" />, type: "expense" },
    { category: "Gas Station", amount: "$45.20", timestamp: "Yesterday, 5:30 PM", icon: <Car className="h-5 w-5" />, type: "expense" },
    { category: "Starbucks", amount: "$6.75", timestamp: "2 days ago", icon: <Coffee className="h-5 w-5" />, type: "expense" },
    { category: "Restaurant", amount: "$52.30", timestamp: "2 days ago", icon: <Utensils className="h-5 w-5" />, type: "expense" },
  ]);

  // Fetch live data from database based on logged in user
  useEffect(() => {
    const fetchDashboardData = async () => {
      const user = JSON.parse(storageService.getItem("user") || "null");
      if (!user) return;

      try {
        // Get user name
        setUserName(user.name);

        // Get transactions for the user
        const userTransactions = await transactionAPI.findByUserId(user.id, 5);
        const displayTransactions = userTransactions.map((transaction: any) => ({
          category: transaction.category_name || transaction.description || 'Unknown',
          amount: `$${parseFloat(transaction.amount).toFixed(2)}`,
          timestamp: new Date(transaction.date).toLocaleDateString(),
          icon: <ShoppingBag className="h-5 w-5" />,
          type: transaction.type as "expense" | "income"
        }));
        setTransactions(displayTransactions);

        // Get categories for the user
        const userCategories = await categoryAPI.findByUserId(user.id);
        const categorySpendingPromises = userCategories.map(async (category: any) => {
          const spent = await categoryAPI.getSpendingByCategory(user.id, category.id);
          const budgetAmount = parseFloat(category.budget);
          const percentage = budgetAmount > 0 ? (spent / budgetAmount) * 100 : 0;
          return {
            name: category.name,
            amount: `$${spent.toFixed(2)}`,
            budget: `$${budgetAmount.toFixed(2)}`,
            percentage: Math.min(percentage, 100),
            icon: category.emoji || "📦"
          };
        });

        const updatedCategories = await Promise.all(categorySpendingPromises);
        setCategories(updatedCategories);

        // Calculate total budget and spending
        const totalBudget = updatedCategories.reduce((sum, cat) => sum + parseFloat(cat.budget.replace('$', '')), 0);
        const totalSpent = updatedCategories.reduce((sum, cat) => sum + parseFloat(cat.amount.replace('$', '')), 0);
        const remaining = totalBudget - totalSpent;
        const percentage = totalBudget > 0 ? (totalSpent / totalBudget) * 100 : 0;

        setBudgetData({
          total: `$${totalBudget.toFixed(2)}`,
          spent: `$${totalSpent.toFixed(2)}`,
          remaining: `$${Math.max(0, remaining).toFixed(2)}`,
          percentage: Math.min(percentage, 100),
        });

      } catch (error) {
        console.error('Error fetching dashboard data:', error);
      }
    };

    fetchDashboardData();

    // Set up polling for live updates (every 5 seconds)
    const interval = setInterval(fetchDashboardData, 5000);

    return () => clearInterval(interval);
  }, [period]);

  const [editingIndex, setEditingIndex] = useState<number | null>(null);
  const [deletingIndex, setDeletingIndex] = useState<number | null>(null);
  const [transactionDialogOpen, setTransactionDialogOpen] = useState(false);
  const [categories, setCategories] = useState<Array<{
    name: string;
    amount: string;
    budget: string;
    percentage: number;
    icon: string;
  }>>([
    { name: "Groceries", amount: "$426", budget: "$500", percentage: 85, icon: "🛒" },
    { name: "Housing", amount: "$950", budget: "$1,200", percentage: 79, icon: "🏠" },
    { name: "Transportation", amount: "$215", budget: "$300", percentage: 72, icon: "🚗" },
    { name: "Dining Out", amount: "$387", budget: "$400", percentage: 97, icon: "🍽️" },
    { name: "Entertainment", amount: "$269", budget: "$250", percentage: 108, icon: "🎬" },
  ]);
  const [categoryDialogOpen, setCategoryDialogOpen] = useState(false);
  const [newCategoryName, setNewCategoryName] = useState("");
  const [newCategoryBudget, setNewCategoryBudget] = useState("");
  const [newCategoryIcon, setNewCategoryIcon] = useState("");

  const handleSetBudget = (amount: number) => {
    setBudgetData(prev => ({ ...prev, total: `$${amount.toFixed(2)}` }));
  };

  const handleAddCategory = () => {
    if (!newCategoryName || !newCategoryBudget || !newCategoryIcon) {
      toast.error("Please fill in all fields");
      return;
    }
    const budgetAmount = parseFloat(newCategoryBudget);
    if (isNaN(budgetAmount) || budgetAmount <= 0) {
      toast.error("Please enter a valid budget amount");
      return;
    }

    const newCategory = {
      name: newCategoryName,
      amount: "$0.00",
      budget: `$${budgetAmount.toFixed(2)}`,
      percentage: 0,
      icon: newCategoryIcon
    };

    setCategories(prev => [...prev, newCategory]);
    // Store categories in localStorage
    const stored = storageService.getItem("budgetbuddy-categories");
    const existing = stored ? JSON.parse(stored) : [];
    storageService.setItem("budgetbuddy-categories", JSON.stringify([...existing, newCategory]));

    toast.success("Category added successfully!");
    setCategoryDialogOpen(false);
    setNewCategoryName("");
    setNewCategoryBudget("");
    setNewCategoryIcon("");
  };

  const handleExport = () => {
    exportToCSV(
      transactions.map(t => ({ ...t, icon: undefined })),
      budgetData
    );
    toast.success("Report exported successfully");
  };

  const handleAddTransaction = (transaction: { category: string; amount: string; timestamp: string; type: "expense" | "income" }) => {
    setTransactions(prev => [{ ...transaction, icon: <ShoppingBag className="h-5 w-5" /> }, ...prev]);
  };

  const handleEditTransaction = (index: number) => {
    setEditingIndex(index);
    setTransactionDialogOpen(true);
  };

  const handleUpdateTransaction = (transaction: { category: string; amount: string; timestamp: string; type: "expense" | "income" }) => {
    if (editingIndex !== null) {
      setTransactions(prev => {
        const newTransactions = [...prev];
        newTransactions[editingIndex] = { ...transaction, icon: prev[editingIndex].icon };
        return newTransactions;
      });
      setEditingIndex(null);
    }
  };

  const handleDeleteTransaction = (index: number) => {
    setDeletingIndex(index);
  };

  const confirmDelete = () => {
    if (deletingIndex !== null) {
      setTransactions(prev => prev.filter((_, i) => i !== deletingIndex));
      toast.success("Transaction deleted");
      setDeletingIndex(null);
    }
  };

  // Update data when period changes
  useEffect(() => {
    toast.info(`Viewing ${period} data`);
    // In a real app, you would fetch data based on the period here
  }, [period]);

  return (
    <div className="max-w-7xl mx-auto px-0 sm:px-0 py-6 sm:py-8 space-y-6 sm:space-y-8">
      {/* Greeting Section */}
      <div className="space-y-2">
        <h1 className="text-2xl sm:text-3xl lg:text-4xl font-heading font-bold text-foreground">
          Welcome back, {userName}! 👋
        </h1>
        <p className="text-base sm:text-lg text-muted-foreground font-body">
          You're viewing your <span className="font-semibold text-primary capitalize">{period}</span> budget. Keep up the great work!
        </p>
      </div>

      {/* Quick Actions */}
      <div className="flex flex-col sm:flex-row gap-3">
          <BudgetDialog
            trigger={
              <Button className="bg-primary hover:bg-primary/90 text-primary-foreground font-body font-semibold rounded-full px-4 sm:px-6 py-4 sm:py-6 shadow-md hover:shadow-lg transition-all w-full sm:w-auto">
                <Wallet className="mr-2 h-4 w-4 sm:h-5 sm:w-5" />
                Set Budget
              </Button>
            }
            onSave={handleSetBudget}
          />
          <Button
            onClick={handleExport}
            variant="outline"
            className="font-body font-semibold rounded-full px-4 sm:px-6 py-4 sm:py-6 border-2 hover:bg-accent hover:text-accent-foreground hover:border-accent transition-all w-full sm:w-auto"
          >
            <Download className="mr-2 h-4 w-4 sm:h-5 sm:w-5" />
            Export Report
          </Button>
          <TransactionDialog
            trigger={
              <Button className="bg-secondary hover:bg-secondary/90 text-secondary-foreground font-body font-semibold rounded-full px-4 sm:px-6 py-4 sm:py-6 shadow-md hover:shadow-lg transition-all w-full sm:w-auto">
                <Plus className="mr-2 h-4 w-4 sm:h-5 sm:w-5" />
                Add Transaction
              </Button>
            }
            onSave={handleAddTransaction}
          />
      </div>

      {/* Budget Overview Cards */}
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
          <BudgetCard
            title="Total Budget"
            amount={budgetData.total}
            icon={<DollarSign className="h-4 w-4 sm:h-5 sm:w-5" />}
            variant="default"
          />
          <BudgetCard
            title="Total Spent"
            amount={budgetData.spent}
            percentage={budgetData.percentage}
            icon={<TrendingUp className="h-4 w-4 sm:h-5 sm:w-5" />}
            variant="warning"
          />
          <BudgetCard
            title="Remaining"
            amount={budgetData.remaining}
            icon={<Wallet className="h-4 w-4 sm:h-5 sm:w-5" />}
            variant="success"
          />
          <BudgetCard
            title="Budget Used"
            amount={`${budgetData.percentage}%`}
            percentage={budgetData.percentage}
            icon={<TrendingUp className="h-4 w-4 sm:h-5 sm:w-5" />}
            variant="accent"
          />
      </div>

      {/* Category Breakdown */}
      <div className="space-y-4">
          <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <h2 className="text-xl sm:text-2xl font-heading font-bold text-foreground">
              Category Breakdown
            </h2>
            <Dialog open={categoryDialogOpen} onOpenChange={setCategoryDialogOpen}>
              <DialogTrigger asChild>
                <Button className="bg-secondary hover:bg-secondary/90 w-full sm:w-auto">
                  <Plus className="h-4 w-4 mr-2" />
                  Add Category
                </Button>
              </DialogTrigger>
              <DialogContent className="sm:max-w-lg">
                <DialogHeader>
                  <DialogTitle className="font-heading">Add New Category</DialogTitle>
                  <DialogDescription className="font-body">
                    Create a new budget category to track your spending.
                  </DialogDescription>
                </DialogHeader>
                <div className="space-y-4 py-4">
                  <div className="space-y-2">
                    <Label htmlFor="category-name" className="font-body font-medium">
                      Category Name
                    </Label>
                    <Input
                      id="category-name"
                      placeholder="e.g. Groceries, Transportation"
                      value={newCategoryName}
                      onChange={(e) => setNewCategoryName(e.target.value)}
                      className="font-body"
                    />
                  </div>
                  <div className="space-y-2">
                    <Label htmlFor="category-budget" className="font-body font-medium">
                      Budget Amount
                    </Label>
                    <div className="relative">
                      <span className="absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground">$</span>
                      <Input
                        id="category-budget"
                        type="number"
                        placeholder="0.00"
                        value={newCategoryBudget}
                        onChange={(e) => setNewCategoryBudget(e.target.value)}
                        className="pl-7 font-body"
                        step="0.01"
                        min="0"
                      />
                    </div>
                  </div>
                  <div className="space-y-2">
                    <Label htmlFor="category-icon" className="font-body font-medium">
                      Icon/Emoji
                    </Label>
                    <div className="space-y-3">
                      <Input
                        id="category-icon"
                        placeholder="e.g. 🛒, 🚗, 🍽️"
                        value={newCategoryIcon}
                        onChange={(e) => setNewCategoryIcon(e.target.value)}
                        className="font-body text-2xl"
                        maxLength={2}
                      />
                      <div className="flex flex-wrap gap-2">
                        {['🛒', '🏠', '🚗', '🍽️', '🎬', '💡', '🏥', '📚', '🎵', '✈️', '🎮', '💄', '🏃', '📱', '🛍️'].map(emoji => (
                          <button
                            key={emoji}
                            type="button"
                            onClick={() => setNewCategoryIcon(emoji)}
                            className="text-2xl hover:scale-110 transition-transform p-2 rounded-lg hover:bg-accent border border-border"
                          >
                            {emoji}
                          </button>
                        ))}
                      </div>
                    </div>
                  </div>
                </div>
                <div className="flex justify-end gap-3">
                  <Button variant="outline" onClick={() => setCategoryDialogOpen(false)} className="font-body">
                    Cancel
                  </Button>
                  <Button onClick={handleAddCategory} className="font-body">
                    Add Category
                  </Button>
                </div>
              </DialogContent>
            </Dialog>
          </div>
          <div className="flex gap-3 sm:gap-4 overflow-x-auto pb-4 scrollbar-hide">
            {categories.map((category, index) => (
              <CategoryCard key={index} {...category} />
            ))}
        </div>
      </div>

      {/* Recent Transactions */}
      <div className="space-y-4">
          <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <h2 className="text-xl sm:text-2xl font-heading font-bold text-foreground">
              Recent Transactions
            </h2>
            <AllTransactionsSheet
              trigger={
                <Button variant="ghost" className="font-body font-medium text-primary hover:text-primary/80 w-full sm:w-auto">
                  View All
                </Button>
              }
              transactions={transactions}
              onEdit={handleEditTransaction}
              onDelete={handleDeleteTransaction}
            />
          </div>
          <div className="space-y-3">
            {transactions.slice(0, 5).map((transaction, index) => (
              <TransactionItem
                key={index}
                {...transaction}
                onEdit={() => handleEditTransaction(index)}
                onDelete={() => handleDeleteTransaction(index)}
              />
            ))}
        </div>
      </div>

      {/* Edit Transaction Dialog */}
      {editingIndex !== null && (
        <TransactionDialog
          trigger={<div />}
          transaction={transactions[editingIndex]}
          onSave={handleUpdateTransaction}
          open={transactionDialogOpen}
          onOpenChange={(open) => {
            setTransactionDialogOpen(open);
            if (!open) setEditingIndex(null);
          }}
        />
      )}

      {/* Delete Confirmation Dialog */}
      <AlertDialog open={deletingIndex !== null} onOpenChange={(open) => !open && setDeletingIndex(null)}>
        <AlertDialogContent>
          <AlertDialogHeader>
            <AlertDialogTitle className="font-heading">Delete Transaction</AlertDialogTitle>
            <AlertDialogDescription className="font-body">
              Are you sure you want to delete this transaction? This action cannot be undone.
            </AlertDialogDescription>
          </AlertDialogHeader>
          <AlertDialogFooter>
            <AlertDialogCancel className="font-body">Cancel</AlertDialogCancel>
            <AlertDialogAction onClick={confirmDelete} className="font-body bg-destructive text-destructive-foreground hover:bg-destructive/90">
              Delete
            </AlertDialogAction>
          </AlertDialogFooter>
        </AlertDialogContent>
      </AlertDialog>
    </div>
  );
};

export default Index;
