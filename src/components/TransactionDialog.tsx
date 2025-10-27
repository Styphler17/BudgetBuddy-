import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle, DialogTrigger } from "@/components/ui/dialog";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import { useState } from "react";
import { toast } from "sonner";

interface Transaction {
  category: string;
  amount: string;
  timestamp: string;
  type: "expense" | "income";
}

interface TransactionDialogProps {
  trigger: React.ReactNode;
  transaction?: Transaction;
  onSave?: (transaction: Transaction) => void;
  open?: boolean;
  onOpenChange?: (open: boolean) => void;
}

export const TransactionDialog = ({ trigger, transaction, onSave, open: controlledOpen, onOpenChange }: TransactionDialogProps) => {
  const [internalOpen, setInternalOpen] = useState(false);
  const open = controlledOpen !== undefined ? controlledOpen : internalOpen;
  const setOpen = onOpenChange || setInternalOpen;

  const [category, setCategory] = useState(transaction?.category || "");
  const [amount, setAmount] = useState(transaction?.amount.replace("$", "") || "");
  const [type, setType] = useState<"expense" | "income">(transaction?.type || "expense");

  const handleSave = () => {
    if (!category || !amount) {
      toast.error("Please fill in all fields");
      return;
    }
    const numAmount = parseFloat(amount);
    if (isNaN(numAmount) || numAmount <= 0) {
      toast.error("Please enter a valid amount");
      return;
    }
    
    const newTransaction: Transaction = {
      category,
      amount: `$${numAmount.toFixed(2)}`,
      timestamp: new Date().toLocaleString(),
      type,
    };
    
    onSave?.(newTransaction);
    toast.success(transaction ? "Transaction updated" : "Transaction added");
    setOpen(false);
    if (!transaction) {
      setCategory("");
      setAmount("");
      setType("expense");
    }
  };

  return (
    <Dialog open={open} onOpenChange={setOpen}>
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
            <Input
              id="category"
              placeholder="e.g. Groceries, Rent, Salary"
              value={category}
              onChange={(e) => setCategory(e.target.value)}
              className="font-body"
            />
          </div>
          <div className="space-y-2">
            <Label htmlFor="amount" className="font-body font-medium">
              Amount
            </Label>
            <div className="relative">
              <span className="absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground">$</span>
              <Input
                id="amount"
                type="number"
                placeholder="0.00"
                value={amount}
                onChange={(e) => setAmount(e.target.value)}
                className="pl-7 font-body"
                step="0.01"
                min="0"
              />
            </div>
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
        </div>
        <div className="flex justify-end gap-3">
          <Button variant="outline" onClick={() => setOpen(false)} className="font-body">
            Cancel
          </Button>
          <Button onClick={handleSave} className="font-body">
            {transaction ? "Update" : "Add"}
          </Button>
        </div>
      </DialogContent>
    </Dialog>
  );
};
