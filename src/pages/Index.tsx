import { useState, useEffect } from "react";
import { Download, DollarSign, TrendingUp, Wallet, ShoppingBag, Home, Car, Coffee, Utensils, Plus, X } from "lucide-react";
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
import { getCurrencySymbol } from "@/utils/currency";
import { useCurrency } from "@/hooks/useCurrency";

type Period = "daily" | "weekly" | "monthly" | "yearly";

interface IndexProps {
  period: Period;
}

const Index = ({ period }: IndexProps) => {
  const [userName, setUserName] = useState(() => {
    const user = JSON.parse(storageService.getItem("user") || "null");
    return user?.name || "User";
  });
  const [budgetData, setBudgetData] = useState(() => {
    const user = JSON.parse(storageService.getItem("user") || "null");
    const symbol = user?.currency ? getCurrencySymbol(user.currency) : "$";
    return {
      total: `${symbol}0.00`,
      spent: `${symbol}0.00`,
      remaining: `${symbol}0.00`,
      income: `${symbol}0.00`,
      netBalance: `${symbol}0.00`,
      isNetPositive: true,
      percentage: 0,
    };
  });
  const { currencySymbol } = useCurrency();

  const [transactions, setTransactions] = useState<Array<{
    category: string;
    amount: string;
    timestamp: string;
    icon: JSX.Element;
    type: "expense" | "income";
  }>>([]);

  const [showGreeting, setShowGreeting] = useState(() => {
    const hidden = storageService.getItem("hide-dashboard-greeting");
    return !hidden;
  });

  useEffect(() => {
    if (showGreeting) {
      const timer = setTimeout(() => {
        setShowGreeting(false);
        storageService.setItem("hide-dashboard-greeting", "true");
      }, 10000); // 10 seconds
      return () => clearTimeout(timer);
    }
  }, [showGreeting]);

  // Fetch live data from database based on logged in user
  useEffect(() => {
    const fetchDashboardData = async () => {
      const user = JSON.parse(storageService.getItem("user") || "null");
      if (!user) return;

      try {
        // Get user name and currency
        setUserName(user.name);
        const symbol = currencySymbol;

        // Get all transactions to calculate income and get recent ones
        const userTransactionsAll = (await transactionAPI.findByUserId(user.id)) as any[];
        const recentTransactions = userTransactionsAll.slice(0, 10);

        setDbTransactions(recentTransactions);
        const displayTransactions = recentTransactions.map((transaction: any) => ({
          category: transaction.category_name || transaction.description || 'Unknown',
          amount: `${symbol}${parseFloat(transaction.amount).toFixed(2)}`,
          timestamp: new Date(transaction.date).toLocaleDateString(),
          icon: <ShoppingBag className="h-5 w-5" />,
          type: transaction.type as "expense" | "income"
        }));
        setTransactions(displayTransactions);

        // Get categories for the user
        const userCategories = (await categoryAPI.findByUserId(user.id)) as any[];
        const categorySpendingPromises = userCategories.map(async (category: any) => {
          const spent = await categoryAPI.getSpendingByCategory(user.id, category.id);
          const budgetAmount = parseFloat(category.budget);
          const percentage = budgetAmount > 0 ? (spent / budgetAmount) * 100 : 0;
          return {
            name: category.name,
            amount: `${symbol}${spent.toFixed(2)}`,
            budget: `${symbol}${budgetAmount.toFixed(2)}`,
            percentage: Math.min(percentage, 100),
            icon: category.emoji || "📦"
          };
        });

        const updatedCategories = await Promise.all(categorySpendingPromises);
        setCategories(updatedCategories);
        setDbCategories(userCategories);

        // Calculate total budget and spending
        const totalBudget = updatedCategories.reduce((sum, cat) => {
          const val = parseFloat(cat.budget.replace(/[^0-9.-]+/g, ""));
          return sum + (isNaN(val) ? 0 : val);
        }, 0);

        const totalSpent = updatedCategories.reduce((sum, cat) => {
          const val = parseFloat(cat.amount.replace(/[^0-9.-]+/g, ""));
          return sum + (isNaN(val) ? 0 : val);
        }, 0);

        const totalIncome = userTransactionsAll
          .filter(t => t.type === 'income')
          .reduce((sum, t) => sum + parseFloat(t.amount || '0'), 0);

        const netBalance = totalIncome - totalSpent;
        const percentage = totalBudget > 0 ? (totalSpent / totalBudget) * 100 : 0;

        const remaining = totalBudget - totalSpent;
        setBudgetData({
          total: `${symbol}${totalBudget.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`,
          spent: `${symbol}${totalSpent.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`,
          remaining: `${symbol}${Math.max(0, remaining).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`,
          income: `${symbol}${totalIncome.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`,
          netBalance: `${symbol}${netBalance.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`,
          isNetPositive: netBalance >= 0,
          percentage: Math.min(percentage, 100),
        });

      } catch (error) {
        console.error('Error fetching dashboard data:', error);
      }
    };

    fetchDashboardData();

    // Set up polling for live updates (every 30 seconds)
    const interval = setInterval(fetchDashboardData, 30000);

    return () => clearInterval(interval);
  }, [period, currencySymbol]);

  const [editingIndex, setEditingIndex] = useState<number | null>(null);
  const [deletingIndex, setDeletingIndex] = useState<number | null>(null);
  const [transactionDialogOpen, setTransactionDialogOpen] = useState(false);
  const [dbCategories, setDbCategories] = useState<any[]>([]);
  const [dbTransactions, setDbTransactions] = useState<any[]>([]);
  const [categories, setCategories] = useState<Array<{
    name: string;
    amount: string;
    budget: string;
    percentage: number;
    icon: string;
  }>>([]);
  const [categoryDialogOpen, setCategoryDialogOpen] = useState(false);
  const [newCategoryName, setNewCategoryName] = useState("");
  const [newCategoryBudget, setNewCategoryBudget] = useState("");
  const [newCategoryIcon, setNewCategoryIcon] = useState("");

  const handleSetBudget = (amount: number) => {
    setBudgetData(prev => ({ ...prev, total: `${currencySymbol}${amount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}` }));
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
      amount: `${currencySymbol}0.00`,
      budget: `${currencySymbol}${budgetAmount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`,
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

  const handleAddTransaction = async (data: any) => {
    const user = JSON.parse(storageService.getItem("user") || "null");
    if (!user) {
      toast.error("Please log in to add transactions");
      return;
    }

    try {
      await transactionAPI.create({
        userId: user.id,
        categoryId: data.categoryId,
        amount: data.amount,
        type: data.type,
        date: data.date,
        description: data.description
      });
      // Polling will update the list
    } catch (error) {
      console.error("Failed to add transaction:", error);
      throw error;
    }
  };

  const handleEditTransaction = (index: number) => {
    setEditingIndex(index);
    setTransactionDialogOpen(true);
  };

  const handleUpdateTransaction = async (data: any) => {
    if (editingIndex !== null) {
      try {
        const transactionId = dbTransactions[editingIndex].id;
        await transactionAPI.update(transactionId, {
          categoryId: data.categoryId,
          amount: data.amount,
          type: data.type,
          date: data.date,
          description: data.description
        });
        setEditingIndex(null);
      } catch (error) {
        console.error("Failed to update transaction:", error);
        throw error;
      }
    }
  };

  const handleDeleteTransaction = (index: number) => {
    setDeletingIndex(index);
  };

  const confirmDelete = async () => {
    if (deletingIndex !== null) {
      try {
        const transactionId = dbTransactions[deletingIndex].id;
        await transactionAPI.delete(transactionId);
        toast.success("Transaction deleted");
        setDeletingIndex(null);
      } catch (error) {
        console.error("Failed to delete transaction:", error);
        toast.error("Failed to delete transaction");
      }
    }
  };

  // Update data when period changes
  useEffect(() => {
    toast.info(`Viewing ${period} data`);
    // In a real app, you would fetch data based on the period here
  }, [period]);

  return (
    <div className="max-w-7xl mx-auto px-4 sm:px-6 py-6 sm:py-8 space-y-6 sm:space-y-8 animate-in fade-in duration-500">
      {/* Greeting Section */}
      {showGreeting && (
        <div className="relative bg-primary/5 p-6 sm:p-8 rounded-3xl border border-primary/10 transition-all duration-500 animate-in slide-in-from-top-4">
          <button
            onClick={() => {
              setShowGreeting(false);
              storageService.setItem("hide-dashboard-greeting", "true");
            }}
            className="absolute top-4 right-4 p-2 text-muted-foreground hover:text-foreground transition-colors"
            title="Close"
          >
            <X className="h-5 w-5" />
          </button>
          <div className="space-y-2 pr-8">
            <h1 className="text-4xl font-heading font-bold text-foreground">
              Welcome back, {userName}! 👋
            </h1>
            <p className="text-lg text-muted-foreground font-body">
              You're viewing your <span className="font-semibold text-primary capitalize">{period}</span> budget. Keep up the great work!
            </p>
          </div>
        </div>
      )}

      {!showGreeting && <div className="h-2" />}

      <hr className="border-border/40" />

      {/* Quick Actions */}
      <div className="flex flex-wrap gap-3">
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
          categories={dbCategories}
        />
      </div>

      {/* Budget Overview Cards */}
      <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <BudgetCard
          title="Total Income"
          amount={budgetData.income || `${currencySymbol}0.00`}
          icon={<TrendingUp className="h-4 w-4 sm:h-5 sm:w-5" />}
          variant="success"
        />
        <BudgetCard
          title="Total Spent"
          amount={budgetData.spent}
          percentage={budgetData.percentage}
          icon={<TrendingUp className="h-4 w-4 sm:h-5 sm:w-5" />}
          variant="warning"
        />
        <BudgetCard
          title="Net Balance"
          amount={budgetData.netBalance || `${currencySymbol}0.00`}
          icon={<span className="text-lg font-bold">{currencySymbol}</span>}
          variant={budgetData.isNetPositive ? "success" : "destructive"}
        />
        <BudgetCard
          title="Total Budget"
          amount={budgetData.total}
          icon={<span className="text-lg font-bold">{currencySymbol}</span>}
          variant="default"
        />
      </div>

      <hr className="border-border/40" />

      {/* Category Breakdown */}
      <div className="space-y-4">
        <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <h2 className="text-2xl font-heading font-bold text-foreground">
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
                    <span className="absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground">{currencySymbol}</span>
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
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6 px-1">
          {categories.map((category, index) => (
            <CategoryCard key={index} {...category} />
          ))}
        </div>
      </div>

      <hr className="border-border/40" />

      {/* Recent Transactions */}
      <div className="space-y-4">
        <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <h2 className="text-2xl font-heading font-bold text-foreground">
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
          transaction={dbTransactions[editingIndex] ? {
            id: dbTransactions[editingIndex].id,
            categoryId: dbTransactions[editingIndex].category_id,
            amount: parseFloat(dbTransactions[editingIndex].amount),
            type: dbTransactions[editingIndex].type,
            date: new Date(dbTransactions[editingIndex].date).toISOString().split('T')[0],
            description: dbTransactions[editingIndex].description
          } : undefined}
          categories={dbCategories}
          onSave={handleUpdateTransaction}
          onDelete={() => handleDeleteTransaction(editingIndex)}
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
