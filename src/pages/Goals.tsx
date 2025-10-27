import { useState, useEffect } from "react";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Progress } from "@/components/ui/progress";
import { Plus, Target, Edit, Trash2, CheckCircle } from "lucide-react";
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle, DialogTrigger } from "@/components/ui/dialog";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from "@/components/ui/dropdown-menu";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import { toast } from "sonner";
import { goalAPI, categoryAPI } from "@/lib/api";

type Period = "daily" | "weekly" | "monthly" | "yearly";

interface Goal {
  id: string;
  name: string;
  current: number;
  target: number;
  deadline: string;
  completed: boolean;
}

interface DatabaseGoal {
  id: number;
  user_id: number;
  name: string;
  target_amount: string;
  current_amount: string;
  deadline: string | null;
  category_id: number | null;
  created_at: string;
  category_name: string | null;
  category_emoji: string | null;
}

interface DatabaseCategory {
  id: number;
  user_id: number;
  name: string;
  emoji: string | null;
  budget: string;
  created_at: string;
}

interface GoalsProps {
  period: Period;
}

export default function Goals({ period }: GoalsProps) {
  const [goals, setGoals] = useState<Goal[]>([]);
  const [categories, setCategories] = useState<DatabaseCategory[]>([]);
  const [dialogOpen, setDialogOpen] = useState(false);
  const [editDialogOpen, setEditDialogOpen] = useState(false);
  const [editingGoal, setEditingGoal] = useState<Goal | null>(null);
  const [newGoalName, setNewGoalName] = useState("");
  const [newGoalTarget, setNewGoalTarget] = useState("");
  const [newGoalDeadline, setNewGoalDeadline] = useState("");
  const [selectedCategoryId, setSelectedCategoryId] = useState<number | null>(null);

  // Fetch live data from database
  useEffect(() => {
    const fetchGoalsData = async () => {
      const user = JSON.parse(localStorage.getItem("user") || "null");
      if (!user) return;

      try {
        // Get user's goals
        const userGoals = await goalAPI.findByUserId(user.id) as DatabaseGoal[];
        const displayGoals = userGoals.map((goal: DatabaseGoal) => ({
          id: goal.id.toString(),
          name: goal.name,
          current: parseFloat(goal.current_amount) || 0,
          target: parseFloat(goal.target_amount),
          deadline: goal.deadline || 'No deadline',
          completed: parseFloat(goal.current_amount) >= parseFloat(goal.target_amount)
        }));
        setGoals(displayGoals);

        // Get user's categories for goal creation
        const userCategories = await categoryAPI.findByUserId(user.id) as DatabaseCategory[];
        setCategories(userCategories);

      } catch (error) {
        console.error('Error fetching goals data:', error);
      }
    };

    fetchGoalsData();

    // Set up polling for live updates (every 5 seconds)
    const interval = setInterval(fetchGoalsData, 5000);

    return () => clearInterval(interval);
  }, []);

  const handleAddGoal = async () => {
    if (!newGoalName || !newGoalTarget || !newGoalDeadline) {
      toast.error("Please fill in all fields");
      return;
    }
    const targetAmount = parseFloat(newGoalTarget);
    if (isNaN(targetAmount) || targetAmount <= 0) {
      toast.error("Please enter a valid target amount");
      return;
    }

    const user = JSON.parse(localStorage.getItem("user") || "null");
    if (!user) {
      toast.error("Please log in to add goals");
      return;
    }

    try {
      await goalAPI.create({
        userId: user.id,
        name: newGoalName,
        targetAmount: targetAmount,
        deadline: newGoalDeadline,
        categoryId: selectedCategoryId
      });

      toast.success("Goal added successfully!");
      setDialogOpen(false);
      setNewGoalName("");
      setNewGoalTarget("");
      setNewGoalDeadline("");
      setSelectedCategoryId(null);

      // Refresh goals data
      const userGoals = await goalAPI.findByUserId(user.id) as DatabaseGoal[];
      const displayGoals = userGoals.map((goal: DatabaseGoal) => ({
        id: goal.id.toString(),
        name: goal.name,
        current: parseFloat(goal.current_amount) || 0,
        target: parseFloat(goal.target_amount),
        deadline: goal.deadline || 'No deadline',
        completed: parseFloat(goal.current_amount) >= parseFloat(goal.target_amount)
      }));
      setGoals(displayGoals);

    } catch (error) {
      console.error('Error adding goal:', error);
      toast.error("Failed to add goal");
    }
  };

  const getProgressColor = (progress: number, completed: boolean) => {
    if (completed) return "text-success";
    if (progress >= 80) return "text-success";
    if (progress >= 50) return "text-warning";
    return "text-primary";
  };

  const handleEditGoal = (goal: Goal) => {
    setEditingGoal(goal);
    setNewGoalName(goal.name);
    setNewGoalTarget(goal.target.toString());
    setNewGoalDeadline(goal.deadline);
    // Find the category ID for this goal
    const user = JSON.parse(localStorage.getItem("user") || "null");
    if (user) {
      goalAPI.findByUserId(user.id).then((userGoals: DatabaseGoal[]) => {
        const dbGoal = userGoals.find((g: DatabaseGoal) => g.id.toString() === goal.id);
        if (dbGoal) {
          setSelectedCategoryId(dbGoal.category_id || null);
        }
      });
    }
    setEditDialogOpen(true);
  };

  const handleUpdateGoal = async () => {
    if (!editingGoal || !newGoalName || !newGoalTarget || !newGoalDeadline) {
      toast.error("Please fill in all fields");
      return;
    }
    const targetAmount = parseFloat(newGoalTarget);
    if (isNaN(targetAmount) || targetAmount <= 0) {
      toast.error("Please enter a valid target amount");
      return;
    }

    const user = JSON.parse(localStorage.getItem("user") || "null");
    if (!user) {
      toast.error("Please log in to update goals");
      return;
    }

    try {
      await goalAPI.update(parseInt(editingGoal.id), {
        name: newGoalName,
        targetAmount: targetAmount,
        deadline: newGoalDeadline,
        categoryId: selectedCategoryId
      });

      toast.success("Goal updated successfully!");
      setEditDialogOpen(false);
      setEditingGoal(null);
      setNewGoalName("");
      setNewGoalTarget("");
      setNewGoalDeadline("");
      setSelectedCategoryId(null);

      // Refresh goals data
      const userGoals = await goalAPI.findByUserId(user.id) as DatabaseGoal[];
      const displayGoals = userGoals.map((goal: DatabaseGoal) => ({
        id: goal.id.toString(),
        name: goal.name,
        current: parseFloat(goal.current_amount) || 0,
        target: parseFloat(goal.target_amount),
        deadline: goal.deadline || 'No deadline',
        completed: parseFloat(goal.current_amount) >= parseFloat(goal.target_amount)
      }));
      setGoals(displayGoals);

    } catch (error) {
      console.error('Error updating goal:', error);
      toast.error("Failed to update goal");
    }
  };

  const handleDeleteGoal = async (goalId: string) => {
    const user = JSON.parse(localStorage.getItem("user") || "null");
    if (!user) {
      toast.error("Please log in to delete goals");
      return;
    }

    try {
      await goalAPI.delete(parseInt(goalId));
      toast.success("Goal deleted successfully!");

      // Refresh goals data
      const userGoals = await goalAPI.findByUserId(user.id) as DatabaseGoal[];
      const displayGoals = userGoals.map((goal: DatabaseGoal) => ({
        id: goal.id.toString(),
        name: goal.name,
        current: parseFloat(goal.current_amount) || 0,
        target: parseFloat(goal.target_amount),
        deadline: goal.deadline || 'No deadline',
        completed: parseFloat(goal.current_amount) >= parseFloat(goal.target_amount)
      }));
      setGoals(displayGoals);

    } catch (error) {
      console.error('Error deleting goal:', error);
      toast.error("Failed to delete goal");
    }
  };

  const handleMarkComplete = async (goalId: string) => {
    const user = JSON.parse(localStorage.getItem("user") || "null");
    if (!user) {
      toast.error("Please log in to update goals");
      return;
    }

    try {
      const goal = goals.find(g => g.id === goalId);
      if (!goal) return;

      const newCurrentAmount = goal.completed ? 0 : goal.target;

      await goalAPI.update(parseInt(goalId), {
        currentAmount: newCurrentAmount
      });

      toast.success("Goal status updated!");

      // Refresh goals data
      const userGoals = await goalAPI.findByUserId(user.id) as DatabaseGoal[];
      const displayGoals = userGoals.map((goal: DatabaseGoal) => ({
        id: goal.id.toString(),
        name: goal.name,
        current: parseFloat(goal.current_amount) || 0,
        target: parseFloat(goal.target_amount),
        deadline: goal.deadline || 'No deadline',
        completed: parseFloat(goal.current_amount) >= parseFloat(goal.target_amount)
      }));
      setGoals(displayGoals);

    } catch (error) {
      console.error('Error updating goal status:', error);
      toast.error("Failed to update goal status");
    }
  };

  return (
    <div className="p-4 sm:p-6 space-y-6">
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h1 className="text-2xl sm:text-3xl font-heading font-bold text-foreground">Budget Goals</h1>
          <p className="text-muted-foreground font-body text-sm sm:text-base">Track your financial goals for {period} period</p>
        </div>
        <Dialog open={dialogOpen} onOpenChange={setDialogOpen}>
          <DialogTrigger asChild>
            <Button className="bg-primary hover:bg-primary/90 w-full sm:w-auto">
              <Plus className="h-4 w-4 mr-2" />
              New Goal
            </Button>
          </DialogTrigger>
          <DialogContent className="sm:max-w-md">
            <DialogHeader>
              <DialogTitle className="font-heading">Add New Goal</DialogTitle>
              <DialogDescription className="font-body">
                Set a new financial goal to track your progress.
              </DialogDescription>
            </DialogHeader>
            <div className="space-y-4 py-4">
              <div className="space-y-2">
                <Label htmlFor="goal-name" className="font-body font-medium">
                  Goal Name
                </Label>
                <Input
                  id="goal-name"
                  placeholder="e.g. Emergency Fund, Vacation, New Car"
                  value={newGoalName}
                  onChange={(e) => setNewGoalName(e.target.value)}
                  className="font-body"
                />
              </div>
              <div className="space-y-2">
                <Label htmlFor="goal-target" className="font-body font-medium">
                  Target Amount
                </Label>
                <div className="relative">
                  <span className="absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground">$</span>
                  <Input
                    id="goal-target"
                    type="number"
                    placeholder="0.00"
                    value={newGoalTarget}
                    onChange={(e) => setNewGoalTarget(e.target.value)}
                    className="pl-7 font-body"
                    step="0.01"
                    min="0"
                  />
                </div>
              </div>
              <div className="space-y-2">
                <Label htmlFor="goal-deadline" className="font-body font-medium">
                  Target Date
                </Label>
                <Input
                  id="goal-deadline"
                  type="text"
                  placeholder="e.g. December 2024"
                  value={newGoalDeadline}
                  onChange={(e) => setNewGoalDeadline(e.target.value)}
                  className="font-body"
                />
              </div>
              <div className="space-y-2">
                <Label htmlFor="goal-category" className="font-body font-medium">
                  Category (Optional)
                </Label>
                <Select value={selectedCategoryId?.toString() || ""} onValueChange={(value) => setSelectedCategoryId(value ? parseInt(value) : null)}>
                  <SelectTrigger className="font-body">
                    <SelectValue placeholder="Select a category" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="">No category</SelectItem>
                    {categories.map((category) => (
                      <SelectItem key={category.id} value={category.id.toString()}>
                        {category.emoji} {category.name}
                      </SelectItem>
                    ))}
                  </SelectContent>
                </Select>
              </div>
            </div>
            <div className="flex justify-end gap-3">
              <Button variant="outline" onClick={() => setDialogOpen(false)} className="font-body">
                Cancel
              </Button>
              <Button onClick={handleAddGoal} className="font-body">
                Add Goal
              </Button>
            </div>
          </DialogContent>
        </Dialog>

        <Dialog open={editDialogOpen} onOpenChange={setEditDialogOpen}>
          <DialogContent className="sm:max-w-md">
            <DialogHeader>
              <DialogTitle className="font-heading">Edit Goal</DialogTitle>
              <DialogDescription className="font-body">
                Update your financial goal details.
              </DialogDescription>
            </DialogHeader>
            <div className="space-y-4 py-4">
              <div className="space-y-2">
                <Label htmlFor="edit-goal-name" className="font-body font-medium">
                  Goal Name
                </Label>
                <Input
                  id="edit-goal-name"
                  placeholder="e.g. Emergency Fund, Vacation, New Car"
                  value={newGoalName}
                  onChange={(e) => setNewGoalName(e.target.value)}
                  className="font-body"
                />
              </div>
              <div className="space-y-2">
                <Label htmlFor="edit-goal-target" className="font-body font-medium">
                  Target Amount
                </Label>
                <div className="relative">
                  <span className="absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground">$</span>
                  <Input
                    id="edit-goal-target"
                    type="number"
                    placeholder="0.00"
                    value={newGoalTarget}
                    onChange={(e) => setNewGoalTarget(e.target.value)}
                    className="pl-7 font-body"
                    step="0.01"
                    min="0"
                  />
                </div>
              </div>
              <div className="space-y-2">
                <Label htmlFor="edit-goal-deadline" className="font-body font-medium">
                  Target Date
                </Label>
                <Input
                  id="edit-goal-deadline"
                  type="text"
                  placeholder="e.g. December 2024"
                  value={newGoalDeadline}
                  onChange={(e) => setNewGoalDeadline(e.target.value)}
                  className="font-body"
                />
              </div>
              <div className="space-y-2">
                <Label htmlFor="edit-goal-category" className="font-body font-medium">
                  Category (Optional)
                </Label>
                <Select value={selectedCategoryId?.toString() || ""} onValueChange={(value) => setSelectedCategoryId(value ? parseInt(value) : null)}>
                  <SelectTrigger className="font-body">
                    <SelectValue placeholder="Select a category" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="">No category</SelectItem>
                    {categories.map((category) => (
                      <SelectItem key={category.id} value={category.id.toString()}>
                        {category.emoji} {category.name}
                      </SelectItem>
                    ))}
                  </SelectContent>
                </Select>
              </div>
            </div>
            <div className="flex justify-end gap-3">
              <Button variant="outline" onClick={() => setEditDialogOpen(false)} className="font-body">
                Cancel
              </Button>
              <Button onClick={handleUpdateGoal} className="font-body">
                Update Goal
              </Button>
            </div>
          </DialogContent>
        </Dialog>
      </div>

      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
        {goals.map((goal) => {
          const progress = (goal.current / goal.target) * 100;
          return (
            <Card key={goal.id} className={goal.completed ? "border-success/50 bg-success/5" : ""}>
              <CardHeader className="pb-3">
                <div className="flex items-start justify-between">
                  <CardTitle className="font-heading flex items-center gap-2 text-base sm:text-lg">
                    <Target className="h-4 w-4 sm:h-5 sm:w-5 text-primary flex-shrink-0" />
                    <span className="truncate">{goal.name}</span>
                    {goal.completed && <CheckCircle className="h-3 w-3 sm:h-4 sm:w-4 text-success flex-shrink-0" />}
                  </CardTitle>
                  <DropdownMenu>
                    <DropdownMenuTrigger asChild>
                      <Button variant="ghost" size="sm" className="h-8 w-8 p-0 flex-shrink-0">
                        <span className="sr-only">Open menu</span>
                        <Edit className="h-4 w-4" />
                      </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent align="end">
                      <DropdownMenuItem onClick={() => handleEditGoal(goal)}>
                        <Edit className="mr-2 h-4 w-4" />
                        Edit Goal
                      </DropdownMenuItem>
                      <DropdownMenuItem onClick={() => handleMarkComplete(goal.id)}>
                        <CheckCircle className="mr-2 h-4 w-4" />
                        {goal.completed ? "Mark Incomplete" : "Mark Complete"}
                      </DropdownMenuItem>
                      <DropdownMenuItem onClick={() => handleDeleteGoal(goal.id)} className="text-destructive">
                        <Trash2 className="mr-2 h-4 w-4" />
                        Delete Goal
                      </DropdownMenuItem>
                    </DropdownMenuContent>
                  </DropdownMenu>
                </div>
              </CardHeader>
              <CardContent className="space-y-3 sm:space-y-4">
                <div className="flex justify-between text-xs sm:text-sm">
                  <span className="font-medium">${goal.current.toLocaleString()} / ${goal.target.toLocaleString()}</span>
                  <span className={getProgressColor(progress, goal.completed)}>{progress.toFixed(0)}%</span>
                </div>
                <Progress value={goal.completed ? 100 : progress} className="h-2 sm:h-3" />
                <p className="text-xs text-muted-foreground">Target: {goal.deadline}</p>
              </CardContent>
            </Card>
          );
        })}
      </div>
    </div>
  );
}
