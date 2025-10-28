import { useEffect, useMemo, useState } from "react";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Plus, Edit, Trash2 } from "lucide-react";
import { TransactionDialog } from "@/components/TransactionDialog";
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from "@/components/ui/dropdown-menu";
import { toast } from "sonner";
import { categoryAPI, transactionAPI } from "@/lib/api";

type Period = "daily" | "weekly" | "monthly" | "yearly";

interface Transaction {
  id: number;
  categoryId: number | null;
  categoryName: string;
  categoryEmoji: string | null;
  amount: number;
  date: string;
  description?: string;
  type: "expense" | "income";
}

interface TransactionsProps {
  period: Period;
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
  amount: string | number;
  description: string | null;
  type: "income" | "expense";
  date: string;
  created_at: string;
  category_name?: string | null;
  category_emoji?: string | null;
}

interface TransactionFormValues {
  id?: number;
  categoryId: number | null;
  amount: number;
  type: "expense" | "income";
  date: string;
  description?: string;
}

export default function Transactions({ period }: TransactionsProps) {
  const [transactions, setTransactions] = useState<Transaction[]>([]);
  const [categories, setCategories] = useState<Array<{ id: number; name: string; emoji: string | null }>>([]);
  const [dialogOpen, setDialogOpen] = useState(false);
  const [editingTransaction, setEditingTransaction] = useState<Transaction | null>(null);
  const [loading, setLoading] = useState(true);

  const categoryMap = useMemo(() => {
    const map = new Map<number, { name: string; emoji: string | null }>();
    categories.forEach((category) => {
      map.set(category.id, { name: category.name, emoji: category.emoji });
    });
    return map;
  }, [categories]);

  const normalizeTransaction = (
    record: DatabaseTransaction,
    map: Map<number, { name: string; emoji: string | null }>
  ): Transaction => {
    const rawAmount = typeof record.amount === "string" ? parseFloat(record.amount) : record.amount;
    const categoryInfo =
      record.category_id !== null
        ? record.category_name || record.category_emoji
          ? {
              name: record.category_name ?? "Uncategorised",
              emoji: record.category_emoji ?? null
            }
          : map.get(record.category_id) ?? { name: "Uncategorised", emoji: null }
        : { name: "Uncategorised", emoji: null };

    return {
      id: record.id,
      categoryId: record.category_id,
      categoryName: categoryInfo?.name ?? "Uncategorised",
      categoryEmoji: categoryInfo?.emoji ?? null,
      amount: Number.isFinite(rawAmount) ? rawAmount : 0,
      type: record.type,
      date: record.date,
      description: record.description ?? undefined
    };
  };

  const loadData = async () => {
    const user = JSON.parse(localStorage.getItem("user") || "null");
    if (!user) {
      setLoading(false);
      return;
    }

    try {
      setLoading(true);
      const [userCategories, userTransactions] = await Promise.all([
        categoryAPI.findByUserId(user.id) as Promise<DatabaseCategory[]>,
        transactionAPI.findByUserId(user.id) as Promise<DatabaseTransaction[]>
      ]);

      const mappedCategories = userCategories.map((category) => ({
        id: category.id,
        name: category.name,
        emoji: category.emoji
      }));
      setCategories(mappedCategories);

      const categoryLookup = new Map<number, { name: string; emoji: string | null }>();
      mappedCategories.forEach((category) => {
        categoryLookup.set(category.id, { name: category.name, emoji: category.emoji });
      });

      const normalisedTransactions = userTransactions.map((transaction) =>
        normalizeTransaction(transaction, categoryLookup)
      );

      setTransactions(normalisedTransactions);
    } catch (error) {
      console.error("Error loading transactions:", error);
      toast.error("Failed to load transactions");
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadData();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  const handleSaveTransaction = async (values: TransactionFormValues) => {
    const user = JSON.parse(localStorage.getItem("user") || "null");
    if (!user) {
      toast.error("Please log in to manage transactions");
      return;
    }

    try {
      if (values.id) {
        const updated = await transactionAPI.update(
          values.id,
          {
            categoryId: values.categoryId ?? undefined,
            amount: values.amount,
            description: values.description,
            type: values.type,
            date: values.date
          },
          user.id
        );

        const updatedTransaction = normalizeTransaction(
          updated as DatabaseTransaction,
          categoryMap
        );

        setTransactions((prev) =>
          prev.map((transaction) => (transaction.id === updatedTransaction.id ? updatedTransaction : transaction))
        );
        setEditingTransaction(null);
      } else {
        const created = await transactionAPI.create({
          userId: user.id,
          categoryId: values.categoryId ?? undefined,
          amount: values.amount,
          description: values.description,
          type: values.type,
          date: values.date
        });

        const createdTransaction = normalizeTransaction(
          created as DatabaseTransaction,
          categoryMap
        );

        setTransactions((prev) => [createdTransaction, ...prev]);
      }
    } catch (error) {
      console.error("Error saving transaction:", error);
      toast.error("Failed to save transaction");
    } finally {
      setDialogOpen(false);
    }
  };

  const handleDeleteTransaction = async (transactionId: number) => {
    const user = JSON.parse(localStorage.getItem("user") || "null");
    if (!user) {
      toast.error("Please log in to delete transactions");
      return;
    }

    try {
      await transactionAPI.delete(transactionId, user.id);
      setTransactions((prev) => prev.filter((transaction) => transaction.id !== transactionId));
      setEditingTransaction(null);
      setDialogOpen(false);
      toast.success("Transaction deleted");
    } catch (error) {
      console.error("Error deleting transaction:", error);
      toast.error("Failed to delete transaction");
    }
  };

  const openCreateDialog = () => {
    setEditingTransaction(null);
    setDialogOpen(true);
  };

  const handleEditTransaction = (transaction: Transaction) => {
    setEditingTransaction(transaction);
    setDialogOpen(true);
  };

  const dialogTransaction = editingTransaction
    ? {
        id: editingTransaction.id,
        categoryId: editingTransaction.categoryId,
        amount: editingTransaction.amount,
        type: editingTransaction.type,
        date: editingTransaction.date,
        description: editingTransaction.description
      }
    : undefined;

  return (
    <div className="p-4 sm:p-6 space-y-6">
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h1 className="text-2xl sm:text-3xl font-heading font-bold text-foreground">Transactions</h1>
          <p className="text-muted-foreground font-body text-sm sm:text-base">Manage your financial transactions for {period} period</p>
        </div>
        <TransactionDialog
          trigger={
            <Button className="bg-primary hover:bg-primary/90 w-full sm:w-auto" onClick={openCreateDialog}>
              <Plus className="h-4 w-4 mr-2" />
              Add Transaction
            </Button>
          }
          categories={categories}
          transaction={dialogTransaction}
          open={dialogOpen}
          onOpenChange={(open) => {
            setDialogOpen(open);
            if (!open) setEditingTransaction(null);
          }}
          onSave={handleSaveTransaction}
          onDelete={editingTransaction ? () => handleDeleteTransaction(editingTransaction.id) : undefined}
        />
      </div>

      <Card>
        <CardHeader>
          <CardTitle className="font-heading">Recent Transactions</CardTitle>
        </CardHeader>
        <CardContent>
          {loading ? (
            <p className="text-muted-foreground font-body">Loading transactions...</p>
          ) : transactions.length === 0 ? (
            <p className="text-muted-foreground font-body">
              No transactions yet. Click &quot;Add Transaction&quot; to get started.
            </p>
          ) : (
            <div className="space-y-3">
              {transactions.map((transaction, index) => (
                <div
                  key={transaction.id}
                  className="flex flex-col sm:flex-row sm:items-center justify-between p-3 border rounded-lg group gap-3"
                >
                  <div className="flex items-center gap-3">
                    <div
                      className={`w-3 h-3 rounded-full ${
                        transaction.type === "income" ? "bg-success" : "bg-destructive"
                      }`}
                    />
                    <div className="min-w-0 flex-1">
                      <p className="font-body font-medium truncate">
                        {transaction.categoryEmoji ? `${transaction.categoryEmoji} ` : ""}
                        {transaction.categoryName}
                      </p>
                      <p className="text-sm text-muted-foreground">
                        {new Date(transaction.date).toLocaleDateString()}
                      </p>
                      {transaction.description && (
                        <p className="text-xs text-muted-foreground truncate">{transaction.description}</p>
                      )}
                    </div>
                  </div>
                  <div className="flex items-center justify-between sm:justify-end gap-2">
                    <div
                      className={`font-body font-semibold ${
                        transaction.type === "income" ? "text-success" : "text-destructive"
                      }`}
                    >
                      {transaction.type === "income" ? "+" : "-"}
                      {transaction.amount.toLocaleString(undefined, {
                        style: "currency",
                        currency: "USD"
                      })}
                    </div>
                    <DropdownMenu>
                      <DropdownMenuTrigger asChild>
                        <Button
                          variant="ghost"
                          size="sm"
                          className="h-8 w-8 p-0 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity"
                          onClick={() => {
                            setEditingTransaction(transaction);
                          }}
                        >
                          <span className="sr-only">Open menu</span>
                          <Edit className="h-4 w-4" />
                        </Button>
                      </DropdownMenuTrigger>
                      <DropdownMenuContent align="end">
                        <DropdownMenuItem onClick={() => handleEditTransaction(transaction)}>
                          <Edit className="mr-2 h-4 w-4" />
                          Edit Transaction
                        </DropdownMenuItem>
                        <DropdownMenuItem
                          onClick={() => handleDeleteTransaction(transaction.id)}
                          className="text-destructive"
                        >
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
