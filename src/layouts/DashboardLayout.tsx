import { ReactNode, useState } from "react";
import { SidebarProvider } from "@/components/ui/sidebar";
import { AppSidebar } from "@/components/AppSidebar";
import { TopBar } from "@/components/TopBar";
import { useLocation } from "react-router-dom";
import { AnimatePresence, motion } from "framer-motion";

type Period = "daily" | "weekly" | "monthly" | "yearly";

interface DashboardLayoutProps {
  children: (period: Period) => ReactNode;
}

export const DashboardLayout = ({ children }: DashboardLayoutProps) => {
  const [currentPeriod, setCurrentPeriod] = useState<Period>(() => {
    return (localStorage.getItem("dashboard-period") as Period) || "monthly";
  });
  const location = useLocation();

  const handlePeriodChange = (period: Period) => {
    setCurrentPeriod(period);
    localStorage.setItem("dashboard-period", period);
  };

  return (
    <SidebarProvider>
      <div className="min-h-screen flex w-full bg-background">
        <AppSidebar />
        <div className="flex-1 flex flex-col w-full min-w-0">
          <TopBar onPeriodChange={handlePeriodChange} />
          <main className="flex-1 overflow-hidden relative">
            <AnimatePresence mode="wait">
              <motion.div
                key={location.pathname}
                initial={{ opacity: 0, y: 10, filter: "blur(4px)" }}
                animate={{ opacity: 1, y: 0, filter: "blur(0px)" }}
                exit={{ opacity: 0, y: -10, filter: "blur(4px)" }}
                transition={{ duration: 0.3 }}
                className="h-full"
              >
                {children(currentPeriod)}
              </motion.div>
            </AnimatePresence>
          </main>
        </div>
      </div>
    </SidebarProvider>
  );
};
