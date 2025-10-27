import { cn } from "@/lib/utils";

interface BudgetCardProps {
  title: string;
  amount: string;
  percentage?: number;
  icon?: React.ReactNode;
  variant?: "default" | "success" | "warning" | "accent";
}

export const BudgetCard = ({ 
  title, 
  amount, 
  percentage, 
  icon, 
  variant = "default" 
}: BudgetCardProps) => {
  const variantStyles = {
    default: "border-l-4 border-l-primary",
    success: "border-l-4 border-l-secondary",
    warning: "border-l-4 border-l-destructive",
    accent: "border-l-4 border-l-accent",
  };

  return (
    <div className={cn(
      "bg-card rounded-2xl p-6 shadow-md hover:shadow-lg transition-all duration-200",
      variantStyles[variant]
    )}>
      <div className="flex items-start justify-between mb-3">
        <p className="text-muted-foreground font-body text-sm font-medium">
          {title}
        </p>
        {icon && <div className="text-primary">{icon}</div>}
      </div>
      <p className="text-3xl font-heading font-bold text-card-foreground mb-2">
        {amount}
      </p>
      {percentage !== undefined && (
        <div className="flex items-center gap-2">
          <div className="flex-1 h-2 bg-muted rounded-full overflow-hidden">
            <div 
              className={cn(
                "h-full rounded-full transition-all duration-500",
                variant === "success" ? "bg-secondary" :
                variant === "warning" ? "bg-destructive" :
                variant === "accent" ? "bg-accent" :
                "bg-primary"
              )}
              style={{ width: `${Math.min(percentage, 100)}%` }}
            />
          </div>
          <span className="text-sm font-body font-semibold text-muted-foreground">
            {percentage}%
          </span>
        </div>
      )}
    </div>
  );
};
