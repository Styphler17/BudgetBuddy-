import { useState, useEffect } from "react";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Progress } from "@/components/ui/progress";
import { transactionAPI, categoryAPI } from "@/lib/api";
import storageService from "@/lib/storage";
import { getCurrencySymbol } from "@/utils/currency";
import { useCurrency } from "@/hooks/useCurrency";
import { PieChart, Pie, Cell, BarChart, Bar, LineChart, Line, XAxis, YAxis, CartesianGrid, Tooltip, ResponsiveContainer, Legend } from "recharts";

const COLORS = ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#14b8a6', '#f43f5e', '#6366f1'];

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
  dailyData: { date: string; income: number; expense: number }[];
  pieData: { name: string; value: number }[];
}

export default function Analytics({ period }: AnalyticsProps) {
  const [analyticsData, setAnalyticsData] = useState<AnalyticsData>({
    spendingByCategory: {},
    totalIncome: 0,
    totalExpenses: 0,
    budgetPerformance: 0,
    dailyData: [],
    pieData: []
  });
  const [transactions, setTransactions] = useState<Transaction[]>([]);
  const { currencySymbol } = useCurrency();

  // Fetch live data from database
  useEffect(() => {
    const fetchAnalyticsData = async () => {
      const user = JSON.parse(storageService.getItem("user") || "null");
      if (!user) return;

      try {
        // Get user name and currency preference
        const symbol = currencySymbol;

        // Get all transactions for the user
        const userTransactions = await transactionAPI.findByUserId(user.id) as DatabaseTransaction[];
        const displayTransactions = userTransactions.map((transaction: DatabaseTransaction) => ({
          category: transaction.category_name || transaction.description || 'Unknown',
          amount: `${symbol}${parseFloat(transaction.amount).toFixed(2)}`,
          timestamp: transaction.date,
          type: transaction.type as "expense" | "income"
        }));

        setTransactions(displayTransactions);

        // Calculate analytics
        const spendingByCategory: { [key: string]: number } = {};
        const dailyMap: Record<string, { dateStr: string; timestamp: number; income: number; expense: number }> = {};
        let totalIncome = 0;
        let totalExpenses = 0;

        displayTransactions.forEach(transaction => {
          const amount = parseFloat(transaction.amount.replace(/[^0-9.-]+/g, ""));

          // Time-series grouping
          const dateObj = new Date(transaction.timestamp);
          const dateStr = dateObj.toLocaleDateString(undefined, { month: 'short', day: 'numeric' });
          const timestamp = new Date(dateObj.getFullYear(), dateObj.getMonth(), dateObj.getDate()).getTime();

          if (!dailyMap[dateStr]) {
            dailyMap[dateStr] = { dateStr, timestamp, income: 0, expense: 0 };
          }

          if (transaction.type === 'expense') {
            totalExpenses += amount;
            spendingByCategory[transaction.category] = (spendingByCategory[transaction.category] || 0) + amount;
            dailyMap[dateStr].expense += amount;
          } else {
            totalIncome += amount;
            dailyMap[dateStr].income += amount;
          }
        });

        // Format pie chart data
        const pieData = Object.entries(spendingByCategory)
          .map(([name, value]) => ({ name, value }))
          .sort((a, b) => b.value - a.value);

        // Format daily time-series data
        const dailyData = Object.values(dailyMap)
          .sort((a, b) => a.timestamp - b.timestamp)
          .map(d => ({ date: d.dateStr, income: d.income, expense: d.expense }));

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
          budgetPerformance: Math.min(budgetPerformance, 100), // Cap at 100%
          dailyData,
          pieData
        });

      } catch (error) {
        console.error('Error fetching analytics data:', error);
      }
    };

    fetchAnalyticsData();

    // Set up polling for live updates (every 30 seconds)
    const interval = setInterval(fetchAnalyticsData, 30000);

    return () => clearInterval(interval);
  }, [period, currencySymbol]);

  const getCategoryProgress = (amount: number) => {
    const maxSpending = Math.max(...Object.values(analyticsData.spendingByCategory));
    return maxSpending > 0 ? (amount / maxSpending) * 100 : 0;
  };

  return (
    <div className="p-4 sm:p-6 space-y-6">
      <div>
        <h1 className="text-3xl font-heading font-bold text-foreground">Analytics</h1>
        <p className="text-muted-foreground font-body text-sm sm:text-base">Financial insights and trends for {period} period</p>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {/* Spending by Category (Donut Chart) */}
        <Card className="lg:col-span-1">
          <CardHeader>
            <CardTitle className="font-heading">Spending by Category</CardTitle>
          </CardHeader>
          <CardContent>
            {analyticsData.pieData.length > 0 ? (
              <div className="h-[250px] w-full">
                <ResponsiveContainer width="100%" height="100%">
                  <PieChart>
                    <Pie
                      data={analyticsData.pieData}
                      cx="50%"
                      cy="50%"
                      innerRadius={60}
                      outerRadius={80}
                      paddingAngle={5}
                      dataKey="value"
                    >
                      {analyticsData.pieData.map((entry, index) => (
                        <Cell key={`cell-${index}`} fill={COLORS[index % COLORS.length]} />
                      ))}
                    </Pie>
                    <Tooltip
                      formatter={(value: number) => `${currencySymbol}${value.toFixed(2)}`}
                      contentStyle={{ borderRadius: '8px', border: 'none', boxShadow: '0 4px 6px -1px rgb(0 0 0 / 0.1)' }}
                    />
                    <Legend
                      verticalAlign="bottom"
                      height={36}
                      iconType="circle"
                      formatter={(value, entry: any) => <span className="text-xs text-muted-foreground">{value}</span>}
                    />
                  </PieChart>
                </ResponsiveContainer>
              </div>
            ) : (
              <div className="h-[250px] flex items-center justify-center">
                <p className="text-muted-foreground font-body text-sm">No expense data available</p>
              </div>
            )}
          </CardContent>
        </Card>

        {/* Income vs Expenses (Bar Chart) */}
        <Card className="md:col-span-2 lg:col-span-2">
          <CardHeader>
            <CardTitle className="font-heading flex items-center justify-between">
              <span>Income vs Expenses Trends</span>
            </CardTitle>
          </CardHeader>
          <CardContent>
            {analyticsData.dailyData.length > 0 ? (
              <div className="h-[250px] w-full">
                <ResponsiveContainer width="100%" height="100%">
                  <BarChart data={analyticsData.dailyData} margin={{ top: 10, right: 10, left: -20, bottom: 0 }}>
                    <CartesianGrid strokeDasharray="3 3" vertical={false} stroke="hsl(var(--border))" />
                    <XAxis
                      dataKey="date"
                      axisLine={false}
                      tickLine={false}
                      tick={{ fontSize: 12, fill: "hsl(var(--muted-foreground))" }}
                      dy={10}
                    />
                    <YAxis
                      axisLine={false}
                      tickLine={false}
                      tick={{ fontSize: 12, fill: "hsl(var(--muted-foreground))" }}
                      tickFormatter={(value) => `${currencySymbol}${value}`}
                    />
                    <Tooltip
                      cursor={{ fill: 'hsl(var(--muted))', opacity: 0.4 }}
                      contentStyle={{ borderRadius: '8px', border: 'none', boxShadow: '0 4px 6px -1px rgb(0 0 0 / 0.1)' }}
                      formatter={(value: number) => `${currencySymbol}${value.toFixed(2)}`}
                    />
                    <Legend iconType="circle" wrapperStyle={{ paddingTop: '20px' }} />
                    <Bar dataKey="income" name="Income" fill="#10b981" radius={[4, 4, 0, 0]} maxBarSize={40} />
                    <Bar dataKey="expense" name="Expenses" fill="#ef4444" radius={[4, 4, 0, 0]} maxBarSize={40} />
                  </BarChart>
                </ResponsiveContainer>
              </div>
            ) : (
              <div className="h-[250px] flex items-center justify-center">
                <p className="text-muted-foreground font-body text-sm">No transaction data available</p>
              </div>
            )}
          </CardContent>
        </Card>

        {/* Budget Performance Overview */}
        <Card className="md:col-span-1 lg:col-span-1">
          <CardHeader>
            <CardTitle className="font-heading">Budget Overview</CardTitle>
          </CardHeader>
          <CardContent className="space-y-6">
            <div className="space-y-2">
              <span className="text-sm font-medium text-muted-foreground">Net Balance</span>
              <div className={`text-3xl font-bold ${analyticsData.totalIncome - analyticsData.totalExpenses >= 0 ? 'text-success' : 'text-destructive'}`}>
                {currencySymbol}{(analyticsData.totalIncome - analyticsData.totalExpenses).toFixed(2)}
              </div>
            </div>

            <div className="space-y-3 pt-4 border-t">
              <div className="flex justify-between items-center text-sm">
                <span className="font-medium">Total Income</span>
                <span className="font-bold text-success">{currencySymbol}{analyticsData.totalIncome.toFixed(2)}</span>
              </div>
              <div className="flex justify-between items-center text-sm">
                <span className="font-medium">Total Expenses</span>
                <span className="font-bold text-destructive">{currencySymbol}{analyticsData.totalExpenses.toFixed(2)}</span>
              </div>
            </div>

            <div className="space-y-2 pt-4 border-t">
              <div className="flex justify-between text-sm">
                <span className="font-medium text-muted-foreground">Budget Used</span>
                <span className="font-bold">{analyticsData.budgetPerformance.toFixed(1)}%</span>
              </div>
              <Progress value={analyticsData.budgetPerformance} className="h-2" />
              <div className="text-xs text-right mt-1">
                {analyticsData.budgetPerformance > 100 ? (
                  <span className="text-destructive">Over budget!</span>
                ) : analyticsData.budgetPerformance > 80 ? (
                  <span className="text-warning">Approaching limit</span>
                ) : (
                  <span className="text-success">Looking good</span>
                )}
              </div>
            </div>
          </CardContent>
        </Card>

        {/* Cumulative Expense Trend (Line Chart) */}
        <Card className="md:col-span-1 lg:col-span-2">
          <CardHeader>
            <CardTitle className="font-heading flex items-center justify-between">
              <span>Expense Trend (Cumulative)</span>
            </CardTitle>
          </CardHeader>
          <CardContent>
            {analyticsData.dailyData.length > 0 ? (
              <div className="h-[250px] w-full">
                <ResponsiveContainer width="100%" height="100%">
                  <LineChart data={analyticsData.dailyData} margin={{ top: 10, right: 10, left: -20, bottom: 0 }}>
                    <CartesianGrid strokeDasharray="3 3" vertical={false} stroke="hsl(var(--border))" />
                    <XAxis
                      dataKey="date"
                      axisLine={false}
                      tickLine={false}
                      tick={{ fontSize: 12, fill: "hsl(var(--muted-foreground))" }}
                      dy={10}
                    />
                    <YAxis
                      axisLine={false}
                      tickLine={false}
                      tick={{ fontSize: 12, fill: "hsl(var(--muted-foreground))" }}
                      tickFormatter={(value) => `${currencySymbol}${value}`}
                    />
                    <Tooltip
                      contentStyle={{ borderRadius: '8px', border: 'none', boxShadow: '0 4px 6px -1px rgb(0 0 0 / 0.1)' }}
                      formatter={(value: number) => `${currencySymbol}${value.toFixed(2)}`}
                    />
                    <Legend iconType="circle" wrapperStyle={{ paddingTop: '20px' }} />
                    {/* Calculate cumulative on the fly by mapping data or simply plot daily expense */}
                    <Line
                      type="monotone"
                      dataKey="expense"
                      name="Daily Spend"
                      stroke="#ef4444"
                      strokeWidth={3}
                      dot={{ r: 4, strokeWidth: 2 }}
                      activeDot={{ r: 6 }}
                    />
                  </LineChart>
                </ResponsiveContainer>
              </div>
            ) : (
              <div className="h-[250px] flex items-center justify-center">
                <p className="text-muted-foreground font-body text-sm">No transaction data available</p>
              </div>
            )}
          </CardContent>
        </Card>
      </div>
    </div>
  );
}
