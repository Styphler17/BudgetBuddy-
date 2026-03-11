import { startOfDay, endOfDay, startOfWeek, endOfWeek, startOfMonth, endOfMonth, startOfYear, endOfYear, format } from "date-fns";

export type Period = "daily" | "weekly" | "monthly" | "yearly";

export const getPeriodRange = (period: Period) => {
    const now = new Date();
    let start: Date;
    let end: Date;

    switch (period) {
        case "daily":
            start = startOfDay(now);
            end = endOfDay(now);
            break;
        case "weekly":
            start = startOfWeek(now, { weekStartsOn: 1 });
            end = endOfWeek(now, { weekStartsOn: 1 });
            break;
        case "monthly":
            start = startOfMonth(now);
            end = endOfMonth(now);
            break;
        case "yearly":
            start = startOfYear(now);
            end = endOfYear(now);
            break;
        default:
            start = startOfMonth(now);
            end = endOfMonth(now);
    }

    return {
        startDate: format(start, "yyyy-MM-dd"),
        endDate: format(end, "yyyy-MM-dd")
    };
};
