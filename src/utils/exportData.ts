interface Transaction {
  category: string;
  amount: string;
  timestamp: string;
  type: "expense" | "income";
}

interface BudgetData {
  total: string;
  spent: string;
  remaining: string;
  percentage: number;
}

export const exportToCSV = (transactions: Transaction[], budgetData: BudgetData) => {
  const csvContent = [
    // Budget Summary
    "Budget Summary",
    "Total Budget,Total Spent,Remaining,Percentage Used",
    `${budgetData.total},${budgetData.spent},${budgetData.remaining},${budgetData.percentage}%`,
    "",
    // Transactions
    "Transactions",
    "Category,Amount,Type,Timestamp",
    ...transactions.map(t => `${t.category},${t.amount},${t.type},${t.timestamp}`)
  ].join("\n");

  const blob = new Blob([csvContent], { type: "text/csv;charset=utf-8;" });
  const link = document.createElement("a");
  const url = URL.createObjectURL(blob);
  
  link.setAttribute("href", url);
  link.setAttribute("download", `budget-report-${new Date().toISOString().split("T")[0]}.csv`);
  link.style.visibility = "hidden";
  
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
};
