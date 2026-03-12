import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle, DialogTrigger } from "@/components/ui/dialog";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { useState } from "react";
import { toast } from "sonner";
import storageService from "@/lib/storage";

interface BudgetDialogProps {
  trigger: React.ReactNode;
  onSave?: (amount: number) => void;
}

export const BudgetDialog = ({ trigger, onSave }: BudgetDialogProps) => {
  const [amount, setAmount] = useState("");
  const [open, setOpen] = useState(false);

  const handleSave = () => {
    const numAmount = parseFloat(amount);
    if (isNaN(numAmount) || numAmount <= 0) {
      toast.error("Please enter a valid amount");
      return;
    }
    storageService.setItem("budgetbuddy-budget", numAmount.toString());
    onSave?.(numAmount);
    toast.success(`Budget set to $${numAmount.toFixed(2)}`);
    setOpen(false);
    setAmount("");
  };

  return (
    <Dialog open={open} onOpenChange={setOpen}>
      <DialogTrigger asChild>
        {trigger}
      </DialogTrigger>
      <DialogContent className="sm:max-w-md">
        <DialogHeader>
          <DialogTitle className="font-heading">Set Your Budget</DialogTitle>
          <DialogDescription className="font-body">
            Enter your total budget amount for the selected period.
          </DialogDescription>
        </DialogHeader>
        <div className="space-y-4 py-4">
          <div className="space-y-2">
            <Label htmlFor="budget" className="font-body font-medium">
              Budget Amount
            </Label>
            <div className="relative">
              <span className="absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground">$</span>
              <Input
                id="budget"
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
        </div>
        <div className="flex justify-end gap-3">
          <Button variant="outline" onClick={() => setOpen(false)} className="font-body">
            Cancel
          </Button>
          <Button onClick={handleSave} className="font-body">
            Save Budget
          </Button>
        </div>
      </DialogContent>
    </Dialog>
  );
};
