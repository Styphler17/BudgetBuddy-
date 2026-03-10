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
    <div className="bg-card rounded-2xl p-4 sm:p-5 shadow-md hover:shadow-lg transition-all duration-200 border border-border/50">
      <div className="flex items-center gap-2 sm:gap-3 mb-3 sm:mb-4">
        <div className="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-primary/10 flex items-center justify-center text-primary text-lg sm:text-xl flex-shrink-0">
          {icon}
        </div>
        <div className="flex-1 min-w-0">
          <h3 className="font-body font-semibold text-card-foreground text-sm sm:text-base truncate">{name}</h3>
          <p className="text-[10px] sm:text-xs text-muted-foreground font-body truncate">of {budget}</p>
        </div>
      </div>

      <div className="space-y-1.5 sm:space-y-2">
        <div className="flex justify-between items-baseline gap-2">
          <span className="text-xl sm:text-2xl font-heading font-bold text-card-foreground truncate">
            {amount}
          </span>
          <span className={cn(
            "text-xs sm:text-sm font-body font-semibold flex-shrink-0",
            isOverBudget ? "text-destructive" : "text-secondary"
          )}>
            {percentage}%
          </span>
        </div>

        <div className="h-1.5 sm:h-2 bg-muted rounded-full overflow-hidden">
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
