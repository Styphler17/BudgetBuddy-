import { Sheet, SheetContent, SheetDescription, SheetHeader, SheetTitle, SheetTrigger } from "@/components/ui/sheet";
import { TransactionItem } from "./TransactionItem";
import { ScrollArea } from "@/components/ui/scroll-area";

interface Transaction {
  category: string;
  amount: string;
  timestamp: string;
  icon: React.ReactNode;
  type: "expense" | "income";
}

interface AllTransactionsSheetProps {
  trigger: React.ReactNode;
  transactions: Transaction[];
  onEdit?: (index: number) => void;
  onDelete?: (index: number) => void;
}

export const AllTransactionsSheet = ({ trigger, transactions, onEdit, onDelete }: AllTransactionsSheetProps) => {
  return (
    <Sheet>
      <SheetTrigger asChild>
        {trigger}
      </SheetTrigger>
      <SheetContent className="w-full sm:max-w-xl">
        <SheetHeader>
          <SheetTitle className="font-heading">All Transactions</SheetTitle>
          <SheetDescription className="font-body">
            View and manage all your transactions
          </SheetDescription>
        </SheetHeader>
        <ScrollArea className="h-[calc(100vh-120px)] mt-6 pr-4">
          <div className="space-y-3">
            {transactions.map((transaction, index) => (
              <TransactionItem
                key={index}
                {...transaction}
                onEdit={() => onEdit?.(index)}
                onDelete={() => onDelete?.(index)}
              />
            ))}
          </div>
        </ScrollArea>
      </SheetContent>
    </Sheet>
  );
};
