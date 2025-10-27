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
    <div className="inline-flex items-center gap-1 p-1 bg-muted rounded-full overflow-x-auto scrollbar-hide">
      {periods.map((period) => (
        <button
          key={period.value}
          onClick={() => handleSelect(period.value)}
          className={cn(
            "px-3 sm:px-6 py-2.5 rounded-full font-body font-medium text-sm transition-all duration-200 whitespace-nowrap",
            selected === period.value
              ? "bg-primary text-primary-foreground shadow-md"
              : "text-muted-foreground hover:text-foreground"
          )}
        >
          {period.label}
        </button>
      ))}
    </div>
  );
};
