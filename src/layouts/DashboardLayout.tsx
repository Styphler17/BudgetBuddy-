import { ReactNode, useState } from "react";
import { SidebarProvider } from "@/components/ui/sidebar";
import { AppSidebar } from "@/components/AppSidebar";
import { TopBar } from "@/components/TopBar";

type Period = "daily" | "weekly" | "monthly" | "yearly";

interface DashboardLayoutProps {
  children: (period: Period) => ReactNode;
}

export const DashboardLayout = ({ children }: DashboardLayoutProps) => {
  const [currentPeriod, setCurrentPeriod] = useState<Period>("monthly");

  return (
    <SidebarProvider>
      <div className="min-h-screen flex w-full bg-background">
        <AppSidebar />
        <div className="flex-1 flex flex-col w-full">
          <TopBar onPeriodChange={setCurrentPeriod} />
          <main className="flex-1">
            {children(currentPeriod)}
          </main>
        </div>
      </div>
    </SidebarProvider>
  );
};
