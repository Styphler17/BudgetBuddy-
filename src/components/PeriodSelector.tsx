import { useState } from "react";
import { cn } from "@/lib/utils";

type Period = "daily" | "weekly" | "monthly" | "yearly";

interface PeriodSelectorProps {
  onPeriodChange?: (period: Period) => void;
}

export const PeriodSelector = ({ onPeriodChange }: PeriodSelectorProps) => {
  const [selected, setSelected] = useState<Period>("monthly");

  const periods: { value: Period; label: string }[] = [
    { value: "daily", label: "Daily" },
    { value: "weekly", label: "Weekly" },
    { value: "monthly", label: "Monthly" },
    { value: "yearly", label: "Yearly" },
  ];

  const handleSelect = (period: Period) => {
    setSelected(period);
    onPeriodChange?.(period);
  };

  return (
    <div className="flex items-center gap-0 p-0.5 bg-muted rounded-full overflow-x-auto scrollbar-hide max-w-full">
      {periods.map((period) => (
        <button
          key={period.value}
          onClick={() => handleSelect(period.value)}
          className={cn(
            "flex-shrink-0 px-2.5 sm:px-3 py-1 sm:py-1.5 rounded-full font-body font-medium text-xs transition-all duration-200 whitespace-nowrap",
            selected === period.value
              ? "bg-primary text-primary-foreground shadow-sm"
              : "text-muted-foreground hover:text-foreground"
          )}
        >
          {period.label}
        </button>
      ))}
    </div>
  );
};
