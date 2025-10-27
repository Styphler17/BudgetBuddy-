import { useState, useEffect } from "react";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Badge } from "@/components/ui/badge";
import { Bell, CheckCircle, AlertCircle, Info, Target, FileText, TrendingUp } from "lucide-react";
import { transactionAPI, goalAPI, categoryAPI } from "@/lib/api";

type Period = "daily" | "weekly" | "monthly" | "yearly";

interface Transaction {
  id: string;
  category: string;
  amount: string;
  timestamp: string;
  type: "expense" | "income";
}

interface Goal {
  id: string;
  name: string;
  current: number;
  target: number;
  deadline: string;
  completed: boolean;
}

interface Category {
  id: string;
  name: string;
  emoji: string;
  color: string;
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
  category_name?: string;
  category_emoji?: string;
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
  category_name?: string;
  category_emoji?: string;
}

interface DatabaseCategory {
  id: number;
  user_id: number;
  name: string;
  emoji: string | null;
  budget: string;
  created_at: string;
}

interface Notification {
  id: string;
  type: "transaction" | "goal" | "budget" | "tip" | "category";
  title: string;
  message: string;
  timestamp: string;
  icon: React.ComponentType<{ className?: string }>;
  badge: string;
  badgeVariant: "default" | "secondary" | "destructive";
}

interface NotificationsProps {
  period: Period;
}

export default function Notifications({ period }: NotificationsProps) {
  const [notifications, setNotifications] = useState<Notification[]>([]);
  const [transactions, setTransactions] = useState<Transaction[]>([]);
  const [goals, setGoals] = useState<Goal[]>([]);
  const [categories, setCategories] = useState<Category[]>([]);
  const [userCategories, setUserCategories] = useState<DatabaseCategory[]>([]);

  // Fetch live data from database every 5 seconds
  useEffect(() => {
    const fetchData = async () => {
      const user = JSON.parse(localStorage.getItem("user") || "null");
      if (!user) return;

      try {
        // Fetch transactions
        const userTransactions = await transactionAPI.findByUserId(user.id, 10) as DatabaseTransaction[];
        const displayTransactions = userTransactions.map((t: DatabaseTransaction) => ({
          id: t.id.toString(),
          category: t.category_name || 'Uncategorized',
          amount: t.amount,
          timestamp: t.created_at,
          type: t.type
        }));
        setTransactions(displayTransactions);

        // Fetch goals
        const userGoals = await goalAPI.findByUserId(user.id) as DatabaseGoal[];
        const displayGoals = userGoals.map((g: DatabaseGoal) => ({
          id: g.id.toString(),
          name: g.name,
          current: parseFloat(g.current_amount) || 0,
          target: parseFloat(g.target_amount) || 0,
          deadline: g.deadline || '',
          completed: parseFloat(g.current_amount) >= parseFloat(g.target_amount)
        }));
        setGoals(displayGoals);

        // Fetch categories
        const userCategoriesData = await categoryAPI.findByUserId(user.id) as DatabaseCategory[];
        setUserCategories(userCategoriesData);
        const displayCategories = userCategoriesData.map((c: DatabaseCategory) => ({
          id: c.id.toString(),
          name: c.name,
          emoji: c.emoji || '📊',
          color: "#3b82f6"
        }));
        setCategories(displayCategories);

      } catch (error) {
        console.error('Error fetching notification data:', error);
      }
    };

    fetchData();
    const interval = setInterval(fetchData, 5000);
    return () => clearInterval(interval);
  }, []);

  // Generate notifications based on live data
  useEffect(() => {
    const generateNotifications = () => {
      const newNotifications: Notification[] = [];

      // Transaction notifications
      const recentTransactions = transactions.slice(-5); // Last 5 transactions
      recentTransactions.forEach((transaction) => {
        newNotifications.push({
          id: `transaction-${transaction.id}`,
          type: "transaction",
          title: "New Transaction",
          message: `${transaction.type === 'income' ? 'Received' : 'Spent'} $${transaction.amount} on ${transaction.category}`,
          timestamp: transaction.timestamp,
          icon: FileText,
          badge: "Transaction",
          badgeVariant: "default",
        });
      });

      // Goal notifications
      goals.forEach((goal) => {
        const progress = (goal.current / goal.target) * 100;
        if (progress >= 100 && !goal.completed) {
          newNotifications.push({
            id: `goal-${goal.id}`,
            type: "goal",
            title: "Goal Achieved",
            message: `Congratulations! You've reached your ${goal.name} goal.`,
            timestamp: new Date().toLocaleString(),
            icon: CheckCircle,
            badge: "Goal Achieved",
            badgeVariant: "secondary",
          });
        } else if (progress >= 80) {
          newNotifications.push({
            id: `goal-progress-${goal.id}`,
            type: "goal",
            title: "Goal Progress",
            message: `You're ${progress.toFixed(0)}% towards your ${goal.name} goal.`,
            timestamp: new Date().toLocaleString(),
            icon: Target,
            badge: "Progress",
            badgeVariant: "secondary",
          });
        }
      });

      // Category notifications
      if (categories.length > 0) {
        newNotifications.push({
          id: "category-update",
          type: "category",
          title: "Categories Updated",
          message: `You have ${categories.length} custom categories configured.`,
          timestamp: new Date().toLocaleString(),
          icon: TrendingUp,
          badge: "Categories",
          badgeVariant: "secondary",
        });
      }

      // Budget alerts (based on categories)
      const totalExpenses = transactions
        .filter(t => t.type === 'expense')
        .reduce((sum, t) => sum + parseFloat(t.amount), 0);

      const totalBudget = categories.reduce((sum, c) => {
        // Get budget from database categories
        const dbCategory = userCategories.find(dc => dc.id.toString() === c.id);
        return sum + (dbCategory ? parseFloat(dbCategory.budget) : 0);
      }, 0);

      if (totalExpenses > totalBudget * 0.8 && totalBudget > 0) {
        const percentage = (totalExpenses / totalBudget) * 100;
        newNotifications.push({
          id: "budget-alert",
          type: "budget",
          title: "Budget Alert",
          message: `You've spent ${percentage.toFixed(0)}% of your total budget ($${totalExpenses.toFixed(2)}). Consider reviewing your expenses.`,
          timestamp: new Date().toLocaleString(),
          icon: AlertCircle,
          badge: "Budget Alert",
          badgeVariant: "destructive",
        });
      }

      // Sort by timestamp (most recent first)
      newNotifications.sort((a, b) => new Date(b.timestamp).getTime() - new Date(a.timestamp).getTime());

      setNotifications(newNotifications.slice(0, 10)); // Keep only 10 most recent
    };

    generateNotifications();
  }, [transactions, goals, categories]);

  return (
    <div className="p-4 sm:p-6 space-y-6">
      <div>
        <h1 className="text-2xl sm:text-3xl font-heading font-bold text-foreground">Notifications</h1>
        <p className="text-muted-foreground font-body text-sm sm:text-base">Stay updated with your financial notifications for {period} period</p>
      </div>

      <Card>
        <CardHeader>
          <CardTitle className="font-heading">Recent Notifications</CardTitle>
        </CardHeader>
        <CardContent className="space-y-4">
          {notifications.length === 0 ? (
            <p className="text-muted-foreground font-body text-center py-8">No notifications yet. Start adding transactions and goals to see updates here.</p>
          ) : (
            notifications.map((notification) => (
              <div key={notification.id} className="flex items-start gap-3 p-3 border rounded-lg bg-accent/5">
                <notification.icon className="h-5 w-5 text-primary mt-0.5 flex-shrink-0" />
                <div className="flex-1 min-w-0">
                  <div className="flex items-center gap-2 mb-1 flex-wrap">
                    <Badge variant={notification.badgeVariant} className={
                      notification.badgeVariant === 'default' ? 'bg-primary text-primary-foreground' :
                      notification.badgeVariant === 'secondary' ? 'bg-secondary text-secondary-foreground' :
                      'bg-destructive text-destructive-foreground'
                    }>
                      {notification.badge}
                    </Badge>
                    <span className="text-xs sm:text-sm text-muted-foreground truncate">{notification.timestamp}</span>
                  </div>
                  <p className="text-sm font-body text-foreground">{notification.message}</p>
                </div>
              </div>
            ))
          )}
        </CardContent>
      </Card>
    </div>
  );
}
