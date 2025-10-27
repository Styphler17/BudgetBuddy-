import { useState } from "react";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Plus, Edit, Trash2 } from "lucide-react";
import { TransactionDialog } from "@/components/TransactionDialog";
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from "@/components/ui/dropdown-menu";
import { toast } from "sonner";

type Period = "daily" | "weekly" | "monthly" | "yearly";

interface Transaction {
  id: string;
  category: string;
  amount: string;
  timestamp: string;
  type: "expense" | "income";
}

interface TransactionsProps {
  period: Period;
}

export default function Transactions({ period }: TransactionsProps) {
  const [transactions, setTransactions] = useState<Transaction[]>([]);
  const [dialogOpen, setDialogOpen] = useState(false);
  const [editingTransaction, setEditingTransaction] = useState<Transaction | null>(null);

  const handleAddTransaction = (transaction: Transaction) => {
    const transactionWithId = { ...transaction, id: Date.now().toString() };
    setTransactions(prev => [transactionWithId, ...prev]);
    // Store in localStorage for analytics
    const stored = localStorage.getItem('budgetbuddy-transactions');
    const existing = stored ? JSON.parse(stored) : [];
    localStorage.setItem('budgetbuddy-transactions', JSON.stringify([transactionWithId, ...existing]));
  };

  const handleEditTransaction = (transaction: Transaction) => {
    setEditingTransaction(transaction);
    setDialogOpen(true);
  };

  const handleUpdateTransaction = (updatedTransaction: Transaction) => {
    setTransactions(prev => prev.map(t =>
      t.id === editingTransaction?.id ? updatedTransaction : t
    ));
    // Update in localStorage
    const stored = localStorage.getItem('budgetbuddy-transactions');
    if (stored) {
      const existing = JSON.parse(stored);
      const updated = existing.map((t: Transaction) =>
        t.id === editingTransaction?.id ? updatedTransaction : t
      );
      localStorage.setItem('budgetbuddy-transactions', JSON.stringify(updated));
    }
    setEditingTransaction(null);
    toast.success("Transaction updated successfully!");
  };

  const handleDeleteTransaction = (transactionToDelete: Transaction) => {
    setTransactions(prev => prev.filter(t => t.id !== transactionToDelete.id));
    // Remove from localStorage
    const stored = localStorage.getItem('budgetbuddy-transactions');
    if (stored) {
      const existing = JSON.parse(stored);
      const filtered = existing.filter((t: Transaction) => t.id !== transactionToDelete.id);
      localStorage.setItem('budgetbuddy-transactions', JSON.stringify(filtered));
    }
    toast.success("Transaction deleted successfully!");
  };

  return (
    <div className="p-4 sm:p-6 space-y-6">
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h1 className="text-2xl sm:text-3xl font-heading font-bold text-foreground">Transactions</h1>
          <p className="text-muted-foreground font-body text-sm sm:text-base">Manage your financial transactions for {period} period</p>
        </div>
        <TransactionDialog
          trigger={
            <Button className="bg-primary hover:bg-primary/90 w-full sm:w-auto">
              <Plus className="h-4 w-4 mr-2" />
              Add Transaction
            </Button>
          }
          transaction={editingTransaction}
          open={dialogOpen}
          onOpenChange={(open) => {
            setDialogOpen(open);
            if (!open) setEditingTransaction(null);
          }}
          onSave={editingTransaction ? handleUpdateTransaction : handleAddTransaction}
        />
      </div>

      <Card>
        <CardHeader>
          <CardTitle className="font-heading">Recent Transactions</CardTitle>
        </CardHeader>
        <CardContent>
          {transactions.length === 0 ? (
            <p className="text-muted-foreground font-body">No transactions yet. Click "Add Transaction" to get started.</p>
          ) : (
            <div className="space-y-3">
              {transactions.map((transaction, index) => (
                <div key={transaction.id} className="flex flex-col sm:flex-row sm:items-center justify-between p-3 border rounded-lg group gap-3">
                  <div className="flex items-center gap-3">
                    <div className={`w-3 h-3 rounded-full ${transaction.type === 'income' ? 'bg-success' : 'bg-destructive'}`} />
                    <div className="min-w-0 flex-1">
                      <p className="font-body font-medium truncate">{transaction.category}</p>
                      <p className="text-sm text-muted-foreground">{transaction.timestamp}</p>
                    </div>
                  </div>
                  <div className="flex items-center justify-between sm:justify-end gap-2">
                    <div className={`font-body font-semibold ${transaction.type === 'income' ? 'text-success' : 'text-destructive'}`}>
                      {transaction.type === 'income' ? '+' : '-'}{transaction.amount}
                    </div>
                    <DropdownMenu>
                      <DropdownMenuTrigger asChild>
                        <Button variant="ghost" size="sm" className="h-8 w-8 p-0 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                          <span className="sr-only">Open menu</span>
                          <Edit className="h-4 w-4" />
                        </Button>
                      </DropdownMenuTrigger>
                      <DropdownMenuContent align="end">
                        <DropdownMenuItem onClick={() => handleEditTransaction(transaction)}>
                          <Edit className="mr-2 h-4 w-4" />
                          Edit Transaction
                        </DropdownMenuItem>
                        <DropdownMenuItem onClick={() => handleDeleteTransaction(transaction)} className="text-destructive">
                          <Trash2 className="mr-2 h-4 w-4" />
                          Delete Transaction
                        </DropdownMenuItem>
                      </DropdownMenuContent>
                    </DropdownMenu>
                  </div>
                </div>
              ))}
            </div>
          )}
        </CardContent>
      </Card>
    </div>
  );
}
