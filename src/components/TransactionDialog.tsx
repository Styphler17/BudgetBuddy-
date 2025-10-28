import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle, DialogTrigger } from "@/components/ui/dialog";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import { Textarea } from "@/components/ui/textarea";
import { useEffect, useMemo, useState } from "react";
import { toast } from "sonner";

interface TransactionFormValues {
  id?: number;
  categoryId: number | null;
  amount: number;
  type: "expense" | "income";
  date: string;
  description?: string;
}

interface TransactionDialogProps {
  trigger: React.ReactNode;
  transaction?: TransactionFormValues;
  categories: Array<{ id: number; name: string; emoji?: string | null }>;
  onSave?: (transaction: TransactionFormValues) => Promise<void> | void;
  onDelete?: (() => Promise<void> | void);
  open?: boolean;
  onOpenChange?: (open: boolean) => void;
}

export const TransactionDialog = ({
  trigger,
  transaction,
  categories,
  onSave,
  onDelete,
  open: controlledOpen,
  onOpenChange
}: TransactionDialogProps) => {
  const [internalOpen, setInternalOpen] = useState(false);
  const open = controlledOpen !== undefined ? controlledOpen : internalOpen;
  const setOpen = onOpenChange || setInternalOpen;

  const [categoryId, setCategoryId] = useState<string>(
    transaction?.categoryId ? String(transaction.categoryId) : "none"
  );
  const [amount, setAmount] = useState(transaction ? String(transaction.amount) : "");
  const [type, setType] = useState<"expense" | "income">(transaction?.type || "expense");
  const [date, setDate] = useState(
    transaction?.date ?? new Date().toISOString().split("T")[0]
  );
  const [description, setDescription] = useState(transaction?.description ?? "");
  const [saving, setSaving] = useState(false);

  useEffect(() => {
    if (transaction) {
      setCategoryId(transaction.categoryId ? String(transaction.categoryId) : "none");
      setAmount(String(transaction.amount));
      setType(transaction.type);
      setDate(transaction.date);
      setDescription(transaction.description ?? "");
    }
  }, [transaction]);

  const categoryOptions = useMemo(() => {
    const base = [
      {
        value: "none",
        label: "Uncategorised"
      }
    ];
    const mapped = categories.map((category) => ({
      value: String(category.id),
      label: category.emoji ? `${category.emoji} ${category.name}` : category.name
    }));
    return [...base, ...mapped];
  }, [categories]);

  const resetForm = () => {
    setCategoryId("none");
    setAmount("");
    setType("expense");
    setDate(new Date().toISOString().split("T")[0]);
    setDescription("");
  };

  const handleClose = (nextOpen: boolean) => {
    if (!nextOpen) {
      resetForm();
    }
    setOpen(nextOpen);
  };

  const handleSave = async () => {
    const selectedCategoryId = categoryId && categoryId !== "none" ? Number(categoryId) : null;

    if (!amount) {
      toast.error("Amount is required");
      return;
    }

    const amountValue = parseFloat(amount);
    if (isNaN(amountValue) || amountValue <= 0) {
      toast.error("Please enter a valid amount");
      return;
    }

    if (!date) {
      toast.error("Please select a date");
      return;
    }

    try {
      setSaving(true);
      await onSave?.({
        id: transaction?.id,
        categoryId: selectedCategoryId,
        amount: amountValue,
        type,
        date,
        description: description.trim() || undefined
      });
      toast.success(transaction ? "Transaction updated" : "Transaction added");
      resetForm();
      setOpen(false);
    } catch (error) {
      console.error("Failed to save transaction:", error);
      toast.error("Failed to save transaction");
    } finally {
      setSaving(false);
    }
  };

  return (
    <Dialog open={open} onOpenChange={handleClose}>
      <DialogTrigger asChild>
        {trigger}
      </DialogTrigger>
      <DialogContent className="sm:max-w-md">
        <DialogHeader>
          <DialogTitle className="font-heading">
            {transaction ? "Edit Transaction" : "Add Transaction"}
          </DialogTitle>
          <DialogDescription className="font-body">
            {transaction ? "Update the transaction details below." : "Enter the details for your new transaction."}
          </DialogDescription>
        </DialogHeader>
        <div className="space-y-4 py-4">
          <div className="space-y-2">
            <Label htmlFor="category" className="font-body font-medium">
              Category
            </Label>
            <Select value={categoryId} onValueChange={setCategoryId}>
              <SelectTrigger id="category" className="font-body">
                <SelectValue placeholder="Select a category" />
              </SelectTrigger>
              <SelectContent>
                {categoryOptions.map((option) => (
                  <SelectItem key={option.value} value={option.value} className="font-body">
                    {option.label}
                  </SelectItem>
                ))}
              </SelectContent>
            </Select>
          </div>
          <div className="space-y-2">
            <Label htmlFor="amount" className="font-body font-medium">
              Amount
            </Label>
            <Input
              id="amount"
              type="number"
              placeholder="0.00"
              value={amount}
              onChange={(e) => setAmount(e.target.value)}
              className="font-body"
              step="0.01"
              min="0"
            />
          </div>
          <div className="space-y-2">
            <Label htmlFor="date" className="font-body font-medium">
              Date
            </Label>
            <Input
              id="date"
              type="date"
              value={date}
              onChange={(e) => setDate(e.target.value)}
              className="font-body"
            />
          </div>
          <div className="space-y-2">
            <Label htmlFor="type" className="font-body font-medium">
              Type
            </Label>
            <Select value={type} onValueChange={(value: "expense" | "income") => setType(value)}>
              <SelectTrigger className="font-body">
                <SelectValue />
              </SelectTrigger>
              <SelectContent>
                <SelectItem value="expense" className="font-body">Expense</SelectItem>
                <SelectItem value="income" className="font-body">Income</SelectItem>
              </SelectContent>
            </Select>
          </div>
          <div className="space-y-2">
            <Label htmlFor="description" className="font-body font-medium">
              Description <span className="text-xs text-muted-foreground">(optional)</span>
            </Label>
            <Textarea
              id="description"
              placeholder="Short note about this transaction"
              value={description}
              onChange={(e) => setDescription(e.target.value)}
              className="font-body"
              rows={3}
            />
          </div>
        </div>
        <div className="flex justify-end gap-3">
          <Button variant="outline" onClick={() => setOpen(false)} className="font-body">
            Cancel
          </Button>
          {!!transaction && onDelete && (
            <Button variant="destructive" onClick={() => onDelete()} className="font-body">
              Delete
            </Button>
          )}
          <Button onClick={handleSave} className="font-body" disabled={saving}>
            {saving ? (transaction ? "Updating..." : "Adding...") : transaction ? "Update" : "Add"}
          </Button>
        </div>
      </DialogContent>
    </Dialog>
  );
};
