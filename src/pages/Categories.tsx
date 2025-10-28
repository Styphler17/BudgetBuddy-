import { useState, useEffect } from "react";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle, DialogTrigger } from "@/components/ui/dialog";
import { AlertDialog, AlertDialogAction, AlertDialogCancel, AlertDialogContent, AlertDialogDescription, AlertDialogFooter, AlertDialogHeader, AlertDialogTitle } from "@/components/ui/alert-dialog";
import { Badge } from "@/components/ui/badge";
import { Progress } from "@/components/ui/progress";
import { Plus, Edit, Trash2, TrendingUp, TrendingDown } from "lucide-react";
import { toast } from "sonner";
import { categoryAPI, transactionAPI } from "@/lib/api";
import storageService from "@/lib/storage";

type Period = "daily" | "weekly" | "monthly" | "yearly";

interface Category {
  id: string;
  name: string;
  emoji: string;
  color: string;
  budget: number;
  spent: number;
  createdAt: string;
}

interface DatabaseCategory {
  id: number;
  user_id: number;
  name: string;
  emoji: string | null;
  budget: string;
  created_at: string;
}

interface DatabaseTransaction {
  id: number;
  user_id: number;
  category_id: number | null;
  amount: string;
  description: string | null;
  type: 'income' | 'expense';
  date: string;
  created_at: string;
}

interface CategoriesProps {
  period: Period;
}

export default function Categories({ period }: CategoriesProps) {
  const [categories, setCategories] = useState<Category[]>([]);
  const [dialogOpen, setDialogOpen] = useState(false);
  const [editDialogOpen, setEditDialogOpen] = useState(false);
  const [deleteDialogOpen, setDeleteDialogOpen] = useState(false);
  const [editingCategory, setEditingCategory] = useState<Category | null>(null);
  const [deletingCategory, setDeletingCategory] = useState<Category | null>(null);
  const [newCategoryName, setNewCategoryName] = useState("");
  const [newCategoryBudget, setNewCategoryBudget] = useState("");
  const [newCategoryEmoji, setNewCategoryEmoji] = useState("");

  // Fetch live data from database
  useEffect(() => {
    const fetchCategoriesData = async () => {
      const user = JSON.parse(storageService.getItem("user") || "null");
      if (!user) return;

      try {
        const userCategories = await categoryAPI.findByUserId(user.id) as DatabaseCategory[];
        const userTransactions = await transactionAPI.findByUserId(user.id) as DatabaseTransaction[];

        const displayCategories = userCategories.map((category: DatabaseCategory) => {
          const spent = userTransactions
            .filter((t: DatabaseTransaction) => t.category_id === category.id && t.type === 'expense')
            .reduce((sum, t) => sum + parseFloat(t.amount), 0);

          return {
            id: category.id.toString(),
            name: category.name,
            emoji: category.emoji || '📊',
            color: "#3b82f6", // Default blue color
            budget: parseFloat(category.budget) || 0,
            spent: spent,
            createdAt: category.created_at
          };
        });

        setCategories(displayCategories);
      } catch (error) {
        console.error('Error fetching categories data:', error);
      }
    };

    fetchCategoriesData();

    // Set up polling for live updates (every 5 seconds)
    const interval = setInterval(fetchCategoriesData, 5000);

    return () => clearInterval(interval);
  }, []);

  const handleAddCategory = async () => {
    if (!newCategoryName || !newCategoryBudget || !newCategoryEmoji) {
      toast.error("Please fill in all fields");
      return;
    }
    const budgetAmount = parseFloat(newCategoryBudget);
    if (isNaN(budgetAmount) || budgetAmount <= 0) {
      toast.error("Please enter a valid budget amount");
      return;
    }

    const user = JSON.parse(storageService.getItem("user") || "null");
    if (!user) {
      toast.error("Please log in to add categories");
      return;
    }

    try {
      await categoryAPI.create({
        userId: user.id,
        name: newCategoryName,
        emoji: newCategoryEmoji,
        budget: budgetAmount
      });

      toast.success("Category added successfully!");
      setDialogOpen(false);
      setNewCategoryName("");
      setNewCategoryBudget("");
      setNewCategoryEmoji("");

      // Refresh categories data
      const userCategories = await categoryAPI.findByUserId(user.id) as DatabaseCategory[];
      const userTransactions = await transactionAPI.findByUserId(user.id) as DatabaseTransaction[];

      const displayCategories = userCategories.map((category: DatabaseCategory) => {
        const spent = userTransactions
          .filter((t: DatabaseTransaction) => t.category_id === category.id && t.type === 'expense')
          .reduce((sum, t) => sum + parseFloat(t.amount), 0);

        return {
          id: category.id.toString(),
          name: category.name,
          emoji: category.emoji || '📊',
          color: "#3b82f6", // Default blue color
          budget: parseFloat(category.budget) || 0,
          spent: spent,
          createdAt: category.created_at
        };
      });

      setCategories(displayCategories);

    } catch (error) {
      console.error('Error adding category:', error);
      toast.error("Failed to add category");
    }
  };

  const handleEditCategory = (category: Category) => {
    setEditingCategory(category);
    setNewCategoryName(category.name);
    setNewCategoryBudget(category.budget.toString());
    setNewCategoryEmoji(category.emoji);
    setEditDialogOpen(true);
  };

  const handleUpdateCategory = async () => {
    if (!editingCategory || !newCategoryName || !newCategoryBudget || !newCategoryEmoji) {
      toast.error("Please fill in all fields");
      return;
    }
    const budgetAmount = parseFloat(newCategoryBudget);
    if (isNaN(budgetAmount) || budgetAmount <= 0) {
      toast.error("Please enter a valid budget amount");
      return;
    }

    const user = JSON.parse(storageService.getItem("user") || "null");
    if (!user) {
      toast.error("Please log in to update categories");
      return;
    }

    try {
      await categoryAPI.update(parseInt(editingCategory.id), {
        name: newCategoryName,
        emoji: newCategoryEmoji,
        budget: budgetAmount
      });

      toast.success("Category updated successfully!");
      setEditDialogOpen(false);
      setEditingCategory(null);
      setNewCategoryName("");
      setNewCategoryBudget("");
      setNewCategoryEmoji("");

      // Refresh categories data
      const userCategories = await categoryAPI.findByUserId(user.id) as DatabaseCategory[];
      const userTransactions = await transactionAPI.findByUserId(user.id) as DatabaseTransaction[];

      const displayCategories = userCategories.map((category: DatabaseCategory) => {
        const spent = userTransactions
          .filter((t: DatabaseTransaction) => t.category_id === category.id && t.type === 'expense')
          .reduce((sum, t) => sum + parseFloat(t.amount), 0);

        return {
          id: category.id.toString(),
          name: category.name,
          emoji: category.emoji || '📊',
          color: "#3b82f6", // Default blue color
          budget: parseFloat(category.budget) || 0,
          spent: spent,
          createdAt: category.created_at
        };
      });

      setCategories(displayCategories);

    } catch (error) {
      console.error('Error updating category:', error);
      toast.error("Failed to update category");
    }
  };

  const handleDeleteCategory = (category: Category) => {
    setDeletingCategory(category);
    setDeleteDialogOpen(true);
  };

  const confirmDelete = async () => {
    if (!deletingCategory) return;

    const user = JSON.parse(storageService.getItem("user") || "null");
    if (!user) {
      toast.error("Please log in to delete categories");
      return;
    }

    try {
      await categoryAPI.delete(parseInt(deletingCategory.id));
      toast.success("Category deleted successfully!");
      setDeleteDialogOpen(false);
      setDeletingCategory(null);

      // Refresh categories data
      const userCategories = await categoryAPI.findByUserId(user.id) as DatabaseCategory[];
      const userTransactions = await transactionAPI.findByUserId(user.id) as DatabaseTransaction[];

      const displayCategories = userCategories.map((category: DatabaseCategory) => {
        const spent = userTransactions
          .filter((t: DatabaseTransaction) => t.category_id === category.id && t.type === 'expense')
          .reduce((sum, t) => sum + parseFloat(t.amount), 0);

        return {
          id: category.id.toString(),
          name: category.name,
          emoji: category.emoji || '📊',
          color: "#3b82f6", // Default blue color
          budget: parseFloat(category.budget) || 0,
          spent: spent,
          createdAt: category.created_at
        };
      });

      setCategories(displayCategories);

    } catch (error) {
      console.error('Error deleting category:', error);
      toast.error("Failed to delete category");
    }
  };

  const getProgressColor = (percentage: number) => {
    if (percentage >= 100) return "bg-destructive";
    if (percentage >= 80) return "bg-warning";
    return "bg-primary";
  };

  const getStatusBadge = (percentage: number) => {
    if (percentage >= 100) return <Badge variant="destructive">Over Budget</Badge>;
    if (percentage >= 80) return <Badge variant="secondary" className="bg-warning text-warning-foreground">Near Limit</Badge>;
    return <Badge variant="secondary" className="bg-success text-success-foreground">On Track</Badge>;
  };

  return (
    <div className="p-4 sm:p-6 space-y-6">
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h1 className="text-2xl sm:text-3xl font-heading font-bold text-foreground">Category Management</h1>
          <p className="text-muted-foreground font-body text-sm sm:text-base">Manage your budget categories for {period} period</p>
        </div>
        <Dialog open={dialogOpen} onOpenChange={setDialogOpen}>
          <DialogTrigger asChild>
            <Button className="bg-primary hover:bg-primary/90 w-full sm:w-auto">
              <Plus className="h-4 w-4 mr-2" />
              Add Category
            </Button>
          </DialogTrigger>
          <DialogContent className="sm:max-w-md">
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
                <Label htmlFor="category-emoji" className="font-body font-medium">
                  Icon/Emoji
                </Label>
                <div className="space-y-3">
                  <Input
                    id="category-emoji"
                    placeholder="e.g. 🛒, 🚗, 🍽️"
                    value={newCategoryEmoji}
                    onChange={(e) => setNewCategoryEmoji(e.target.value)}
                    className="font-body text-2xl"
                    maxLength={2}
                  />
                  <div className="flex flex-wrap gap-2">
                    {['🛒', '🏠', '🚗', '🍽️', '🎬', '💡', '🏥', '📚', '🎵', '✈️', '🎮', '💄', '🏃', '📱', '🛍️'].map(emoji => (
                      <button
                        key={emoji}
                        type="button"
                        onClick={() => setNewCategoryEmoji(emoji)}
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
              <Button variant="outline" onClick={() => setDialogOpen(false)} className="font-body">
                Cancel
              </Button>
              <Button onClick={handleAddCategory} className="font-body">
                Add Category
              </Button>
            </div>
          </DialogContent>
        </Dialog>

        <Dialog open={editDialogOpen} onOpenChange={setEditDialogOpen}>
          <DialogContent className="sm:max-w-md">
            <DialogHeader>
              <DialogTitle className="font-heading">Edit Category</DialogTitle>
              <DialogDescription className="font-body">
                Update your budget category details.
              </DialogDescription>
            </DialogHeader>
            <div className="space-y-4 py-4">
              <div className="space-y-2">
                <Label htmlFor="edit-category-name" className="font-body font-medium">
                  Category Name
                </Label>
                <Input
                  id="edit-category-name"
                  placeholder="e.g. Groceries, Transportation"
                  value={newCategoryName}
                  onChange={(e) => setNewCategoryName(e.target.value)}
                  className="font-body"
                />
              </div>
              <div className="space-y-2">
                <Label htmlFor="edit-category-budget" className="font-body font-medium">
                  Budget Amount
                </Label>
                <div className="relative">
                  <span className="absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground">$</span>
                  <Input
                    id="edit-category-budget"
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
                <Label htmlFor="edit-category-emoji" className="font-body font-medium">
                  Icon/Emoji
                </Label>
                <div className="space-y-3">
                  <Input
                    id="edit-category-emoji"
                    placeholder="e.g. 🛒, 🚗, 🍽️"
                    value={newCategoryEmoji}
                    onChange={(e) => setNewCategoryEmoji(e.target.value)}
                    className="font-body text-2xl"
                    maxLength={2}
                  />
                  <div className="flex flex-wrap gap-2">
                    {['🛒', '🏠', '🚗', '🍽️', '🎬', '💡', '🏥', '📚', '🎵', '✈️', '🎮', '💄', '🏃', '📱', '🛍️'].map(emoji => (
                      <button
                        key={emoji}
                        type="button"
                        onClick={() => setNewCategoryEmoji(emoji)}
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
              <Button variant="outline" onClick={() => setEditDialogOpen(false)} className="font-body">
                Cancel
              </Button>
              <Button onClick={handleUpdateCategory} className="font-body">
                Update Category
              </Button>
            </div>
          </DialogContent>
        </Dialog>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
        {categories.map((category) => {
          const percentage = category.budget > 0 ? (category.spent / category.budget) * 100 : 0;
          const remaining = category.budget - category.spent;
          const isOverBudget = percentage >= 100;

          return (
            <Card key={category.id} className={isOverBudget ? "border-destructive/50 bg-destructive/5" : ""}>
              <CardHeader className="pb-3">
                <div className="flex items-center justify-between">
                  <CardTitle className="font-heading flex items-center gap-2 text-base sm:text-lg">
                    <span className="text-xl sm:text-2xl">{category.emoji}</span>
                    <span className="truncate">{category.name}</span>
                    {getStatusBadge(percentage)}
                  </CardTitle>
                  <div className="flex gap-1">
                    <Button
                      variant="ghost"
                      size="sm"
                      onClick={() => handleEditCategory(category)}
                      className="h-8 w-8 p-0"
                    >
                      <Edit className="h-4 w-4" />
                    </Button>
                    <Button
                      variant="ghost"
                      size="sm"
                      onClick={() => handleDeleteCategory(category)}
                      className="h-8 w-8 p-0 text-destructive hover:text-destructive"
                    >
                      <Trash2 className="h-4 w-4" />
                    </Button>
                  </div>
                </div>
              </CardHeader>
              <CardContent className="space-y-3 sm:space-y-4">
                <div className="space-y-2">
                  <div className="flex justify-between text-sm">
                    <span className="font-medium">${category.spent.toFixed(2)} / ${category.budget.toFixed(2)}</span>
                    <span className={percentage >= 100 ? "text-destructive" : percentage >= 80 ? "text-warning" : "text-primary"}>
                      {percentage.toFixed(0)}%
                    </span>
                  </div>
                  <Progress
                    value={Math.min(percentage, 100)}
                    className={`h-2 ${percentage >= 100 ? "bg-destructive/20" : ""}`}
                  />
                </div>

                <div className="flex items-center justify-between text-xs sm:text-sm">
                  <div className="flex items-center gap-1">
                    {remaining >= 0 ? (
                      <>
                        <TrendingDown className="h-3 w-3 text-success" />
                        <span className="text-success">${remaining.toFixed(2)} remaining</span>
                      </>
                    ) : (
                      <>
                        <TrendingUp className="h-3 w-3 text-destructive" />
                        <span className="text-destructive">${Math.abs(remaining).toFixed(2)} over</span>
                      </>
                    )}
                  </div>
                  <span className="text-muted-foreground">
                    Created {new Date(category.createdAt).toLocaleDateString()}
                  </span>
                </div>
              </CardContent>
            </Card>
          );
        })}
      </div>

      {categories.length === 0 && (
        <Card>
          <CardContent className="text-center py-12">
            <div className="text-4xl mb-4">📊</div>
            <h3 className="text-lg font-heading font-semibold mb-2">No Categories Yet</h3>
            <p className="text-muted-foreground font-body mb-4">
              Create your first budget category to start tracking your spending.
            </p>
            <Button onClick={() => setDialogOpen(true)} className="bg-primary hover:bg-primary/90">
              <Plus className="h-4 w-4 mr-2" />
              Add Your First Category
            </Button>
          </CardContent>
        </Card>
      )}

      <AlertDialog open={deleteDialogOpen} onOpenChange={setDeleteDialogOpen}>
        <AlertDialogContent>
          <AlertDialogHeader>
            <AlertDialogTitle className="font-heading">Delete Category</AlertDialogTitle>
            <AlertDialogDescription className="font-body">
              Are you sure you want to delete "{deletingCategory?.name}"? This will remove the category from all your budget tracking. This action cannot be undone.
            </AlertDialogDescription>
          </AlertDialogHeader>
          <AlertDialogFooter>
            <AlertDialogCancel className="font-body">Cancel</AlertDialogCancel>
            <AlertDialogAction onClick={confirmDelete} className="font-body bg-destructive text-destructive-foreground hover:bg-destructive/90">
              Delete Category
            </AlertDialogAction>
          </AlertDialogFooter>
        </AlertDialogContent>
      </AlertDialog>
    </div>
  );
}
