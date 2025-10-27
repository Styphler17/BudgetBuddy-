import { cn } from "@/lib/utils";

interface CategoryCardProps {
  name: string;
  amount: string;
  budget: string;
  percentage: number;
  icon: React.ReactNode;
}

export const CategoryCard = ({ name, amount, budget, percentage, icon }: CategoryCardProps) => {
  const isOverBudget = percentage > 100;
  
  return (
    <div className="flex-shrink-0 w-64 bg-card rounded-2xl p-5 shadow-md hover:shadow-lg transition-all duration-200">
      <div className="flex items-center gap-3 mb-4">
        <div className="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center text-primary text-xl">
          {icon}
        </div>
        <div className="flex-1">
          <h3 className="font-body font-semibold text-card-foreground">{name}</h3>
          <p className="text-xs text-muted-foreground font-body">of {budget}</p>
        </div>
      </div>
      
      <div className="space-y-2">
        <div className="flex justify-between items-baseline">
          <span className="text-2xl font-heading font-bold text-card-foreground">
            {amount}
          </span>
          <span className={cn(
            "text-sm font-body font-semibold",
            isOverBudget ? "text-destructive" : "text-secondary"
          )}>
            {percentage}%
          </span>
        </div>
        
        <div className="h-2 bg-muted rounded-full overflow-hidden">
          <div 
            className={cn(
              "h-full rounded-full transition-all duration-500",
              isOverBudget ? "bg-destructive" : "bg-secondary"
            )}
            style={{ width: `${Math.min(percentage, 100)}%` }}
          />
        </div>
      </div>
    </div>
  );
};
