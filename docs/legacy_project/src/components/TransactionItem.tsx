import { Pencil, Trash2 } from "lucide-react";
import { Button } from "@/components/ui/button";

interface TransactionItemProps {
  category: string;
  amount: string;
  timestamp: string;
  icon: React.ReactNode;
  type: "expense" | "income";
  onEdit?: () => void;
  onDelete?: () => void;
}

export const TransactionItem = ({
  category,
  amount,
  timestamp,
  icon,
  type,
  onEdit,
  onDelete
}: TransactionItemProps) => {
  return (
    <div className="bg-card rounded-xl p-3 sm:p-4 shadow-sm hover:shadow-md transition-all duration-200 flex items-center gap-2 sm:gap-4">
      <div className="w-9 h-9 sm:w-10 sm:h-10 rounded-lg bg-primary/10 flex items-center justify-center text-primary flex-shrink-0">
        {icon}
      </div>

      <div className="flex-1 min-w-0">
        <h4 className="font-body font-semibold text-card-foreground truncate text-sm sm:text-base">{category}</h4>
        <p className="text-xs text-muted-foreground font-body">{timestamp}</p>
      </div>

      <div className="flex items-center gap-1 sm:gap-3 flex-shrink-0">
        <span className={`text-sm sm:text-lg font-heading font-bold whitespace-nowrap ${type === "expense" ? "text-destructive" : "text-success"
          }`}>
          {type === "expense" ? "-" : "+"}{amount}
        </span>

        <div className="flex gap-0.5 sm:gap-1">
          <Button
            variant="ghost"
            size="icon"
            onClick={onEdit}
            className="h-7 w-7 sm:h-8 sm:w-8 text-muted-foreground hover:text-primary"
          >
            <Pencil className="h-3.5 w-3.5 sm:h-4 sm:w-4" />
          </Button>
          <Button
            variant="ghost"
            size="icon"
            onClick={onDelete}
            className="h-7 w-7 sm:h-8 sm:w-8 text-muted-foreground hover:text-destructive"
          >
            <Trash2 className="h-3.5 w-3.5 sm:h-4 sm:w-4" />
          </Button>
        </div>
      </div>
    </div>
  );
};
