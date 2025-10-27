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
    <div className="bg-card rounded-xl p-4 shadow-sm hover:shadow-md transition-all duration-200 flex items-center gap-4">
      <div className="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center text-primary flex-shrink-0">
        {icon}
      </div>
      
      <div className="flex-1 min-w-0">
        <h4 className="font-body font-semibold text-card-foreground truncate">{category}</h4>
        <p className="text-xs text-muted-foreground font-body">{timestamp}</p>
      </div>
      
      <div className="flex items-center gap-3">
        <span className={`text-lg font-heading font-bold ${
          type === "expense" ? "text-destructive" : "text-secondary"
        }`}>
          {type === "expense" ? "-" : "+"}{amount}
        </span>
        
        <div className="flex gap-1">
          <Button
            variant="ghost"
            size="icon"
            onClick={onEdit}
            className="h-8 w-8 text-muted-foreground hover:text-primary"
          >
            <Pencil className="h-4 w-4" />
          </Button>
          <Button
            variant="ghost"
            size="icon"
            onClick={onDelete}
            className="h-8 w-8 text-muted-foreground hover:text-destructive"
          >
            <Trash2 className="h-4 w-4" />
          </Button>
        </div>
      </div>
    </div>
  );
};
