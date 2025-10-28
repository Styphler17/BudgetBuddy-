import { useState, useEffect } from "react";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Progress } from "@/components/ui/progress";
import { transactionAPI, categoryAPI } from "@/lib/api";
import storageService from "@/lib/storage";

type Period = "daily" | "weekly" | "monthly" | "yearly";

interface AnalyticsProps {
  period: Period;
}

interface Transaction {
  category: string;
  amount: string;
  timestamp: string;
  type: "expense" | "income";
}

interface DatabaseTransaction {
  id: number;
  user_id: number;
  category_id: number | null;
  amount: string;
  description: string | null;
  type: "income" | "expense";
  date: string;
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

interface AnalyticsData {
  spendingByCategory: { [key: string]: number };
  totalIncome: number;
  totalExpenses: number;
  budgetPerformance: number;
}

export default function Analytics({ period }: AnalyticsProps) {
  const [analyticsData, setAnalyticsData] = useState<AnalyticsData>({
    spendingByCategory: {},
    totalIncome: 0,
    totalExpenses: 0,
    budgetPerformance: 0
  });
  const [transactions, setTransactions] = useState<Transaction[]>([]);

  // Fetch live data from database
  useEffect(() => {
    const fetchAnalyticsData = async () => {
      const user = JSON.parse(storageService.getItem("user") || "null");
      if (!user) return;

      try {
        // Get all transactions for the user
        const userTransactions = await transactionAPI.findByUserId(user.id) as DatabaseTransaction[];
        const displayTransactions = userTransactions.map((transaction: DatabaseTransaction) => ({
          category: transaction.category_name || transaction.description || 'Unknown',
          amount: `$${parseFloat(transaction.amount).toFixed(2)}`,
          timestamp: transaction.date,
          type: transaction.type as "expense" | "income"
        }));

        setTransactions(displayTransactions);

        // Calculate analytics
        const spendingByCategory: { [key: string]: number } = {};
        let totalIncome = 0;
        let totalExpenses = 0;

        displayTransactions.forEach(transaction => {
          const amount = parseFloat(transaction.amount.replace('$', ''));
          if (transaction.type === 'expense') {
            totalExpenses += amount;
            spendingByCategory[transaction.category] = (spendingByCategory[transaction.category] || 0) + amount;
          } else {
            totalIncome += amount;
          }
        });

        // Get user's categories to calculate total budget
        const userCategories = await categoryAPI.findByUserId(user.id) as DatabaseCategory[];
        const totalBudget = userCategories.reduce((sum: number, category: DatabaseCategory) => {
          const budget = parseFloat(String(category.budget));
          return sum + (isNaN(budget) ? 0 : budget);
        }, 0);
        const budgetPerformance = Number(totalBudget) > 0 ? (totalExpenses / Number(totalBudget)) * 100 : 0;

        setAnalyticsData({
          spendingByCategory,
          totalIncome,
          totalExpenses,
          budgetPerformance: Math.min(budgetPerformance, 100) // Cap at 100%
        });

      } catch (error) {
        console.error('Error fetching analytics data:', error);
      }
    };

    fetchAnalyticsData();

    // Set up polling for live updates (every 5 seconds)
    const interval = setInterval(fetchAnalyticsData, 5000);

    return () => clearInterval(interval);
  }, [period]);

  const getCategoryProgress = (amount: number) => {
    const maxSpending = Math.max(...Object.values(analyticsData.spendingByCategory));
    return maxSpending > 0 ? (amount / maxSpending) * 100 : 0;
  };

  return (
    <div className="p-6 space-y-6">
      <div>
        <h1 className="text-3xl font-heading font-bold text-foreground">Analytics</h1>
        <p className="text-muted-foreground font-body">Financial insights and trends for {period} period</p>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <Card>
          <CardHeader>
            <CardTitle className="font-heading">Spending by Category</CardTitle>
          </CardHeader>
          <CardContent className="space-y-4">
            {Object.entries(analyticsData.spendingByCategory).length > 0 ? (
              Object.entries(analyticsData.spendingByCategory)
                .sort(([,a], [,b]) => b - a)
                .map(([category, amount]) => (
                  <div key={category} className="space-y-2">
                    <div className="flex justify-between text-sm">
                      <span>{category}</span>
                      <span>${amount.toFixed(2)}</span>
                    </div>
                    <Progress value={getCategoryProgress(amount)} className="h-2" />
                  </div>
                ))
            ) : (
              <p className="text-muted-foreground font-body text-sm">No expense data available</p>
            )}
          </CardContent>
        </Card>

        <Card>
          <CardHeader>
            <CardTitle className="font-heading">Income vs Expenses</CardTitle>
          </CardHeader>
          <CardContent className="space-y-4">
            <div className="flex justify-between items-center">
              <span className="text-sm font-medium">Total Income</span>
              <span className="text-lg font-bold text-success">${analyticsData.totalIncome.toFixed(2)}</span>
            </div>
            <div className="flex justify-between items-center">
              <span className="text-sm font-medium">Total Expenses</span>
              <span className="text-lg font-bold text-destructive">${analyticsData.totalExpenses.toFixed(2)}</span>
            </div>
            <div className="flex justify-between items-center pt-2 border-t">
              <span className="text-sm font-medium">Net Balance</span>
              <span className={`text-lg font-bold ${analyticsData.totalIncome - analyticsData.totalExpenses >= 0 ? 'text-success' : 'text-destructive'}`}>
                ${(analyticsData.totalIncome - analyticsData.totalExpenses).toFixed(2)}
              </span>
            </div>
          </CardContent>
        </Card>

        <Card>
          <CardHeader>
            <CardTitle className="font-heading">Budget Performance</CardTitle>
          </CardHeader>
          <CardContent className="space-y-4">
            <div className="space-y-2">
              <div className="flex justify-between text-sm">
                <span>Budget Used</span>
                <span>{analyticsData.budgetPerformance.toFixed(1)}%</span>
              </div>
              <Progress value={analyticsData.budgetPerformance} className="h-3" />
            </div>
            <div className="text-xs text-muted-foreground">
              {analyticsData.budgetPerformance > 100 ? (
                <span className="text-destructive">Over budget by ${(analyticsData.totalExpenses - (analyticsData.totalExpenses / analyticsData.budgetPerformance * 100)).toFixed(2)}</span>
              ) : analyticsData.budgetPerformance > 80 ? (
                <span className="text-warning">Approaching budget limit</span>
              ) : (
                <span className="text-success">Within budget</span>
              )}
            </div>
          </CardContent>
        </Card>
      </div>
    </div>
  );
}
