import { PeriodSelector } from "./PeriodSelector";
import { SidebarTrigger } from "@/components/ui/sidebar";
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar";
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuLabel, DropdownMenuSeparator, DropdownMenuTrigger } from "@/components/ui/dropdown-menu";
import { User, LogOut } from "lucide-react";
import { useNavigate, useLocation } from "react-router-dom";
import { useToast } from "@/hooks/use-toast";
import storageService from "@/lib/storage";
import budgetBuddyLogo from "@/assets/BudgetBuddy.png";

type Period = "daily" | "weekly" | "monthly" | "yearly";

interface TopBarProps {
  onPeriodChange?: (period: Period) => void;
}

// Pages where the period filter is relevant
const PERIOD_PAGES = ["/app/dashboard", "/app/transactions", "/app/analytics", "/app/goals", "/app/categories", "/app/accounts"];

export const TopBar = ({ onPeriodChange }: TopBarProps) => {
  const navigate = useNavigate();
  const { toast } = useToast();
  const location = useLocation();

  const user = JSON.parse(storageService.getItem("user") || "null");

  const showPeriodSelector = PERIOD_PAGES.some(p => location.pathname.startsWith(p));

  const handleLogout = () => {
    storageService.removeItem("user");
    toast({
      title: "Logged out",
      description: "You have been successfully logged out."
    });
    navigate("/");
  };

  const getInitials = (name: string) => {
    return name.split(' ').map(n => n[0]).join('').toUpperCase();
  };

  return (
    <header className="sticky top-0 z-50 w-full border-b border-border/50 bg-background/80 backdrop-blur-xl transition-all">
      <div className="flex h-16 sm:h-20 items-center px-4 sm:px-6 gap-4">

        {/* Left: sidebar toggle + mobile logo */}
        <div className="flex items-center gap-2 flex-shrink-0">
          <SidebarTrigger className="-ml-2 sm:-ml-3 hover:bg-accent hover:text-accent-foreground transition-colors rounded-full" />
          {/* Show logo only on mobile (sm and below) when sidebar is hidden */}
          <img
            src={budgetBuddyLogo}
            alt="BudgetBuddy"
            className="h-7 w-auto block sm:hidden"
          />
        </div>

        {/* Centre: period selector — only on relevant pages */}
        {showPeriodSelector ? (
          <div className="flex-1 overflow-x-auto scrollbar-hide py-1 min-w-0">
            <div className="flex justify-center">
              <PeriodSelector onPeriodChange={onPeriodChange} />
            </div>
          </div>
        ) : (
          <div className="flex-1" />
        )}

        {/* Right: user avatar — hidden on mobile (shown in sidebar footer instead) */}
        {user && (
          <div className="hidden sm:flex items-center flex-shrink-0">
            <DropdownMenu>
              <DropdownMenuTrigger asChild>
                <Avatar className="h-8 w-8 sm:h-9 sm:w-9 cursor-pointer border-2 border-transparent hover:border-primary/20 transition-all">
                  <AvatarImage src="" alt={user.name} />
                  <AvatarFallback className="bg-primary text-primary-foreground text-xs sm:text-sm font-bold">
                    {getInitials(user.name)}
                  </AvatarFallback>
                </Avatar>
              </DropdownMenuTrigger>
              <DropdownMenuContent align="end" className="w-56 mt-2">
                <DropdownMenuLabel className="font-normal">
                  <div className="flex flex-col space-y-1 p-1">
                    <p className="text-sm font-semibold leading-none">{user.name}</p>
                    <p className="text-xs leading-none text-muted-foreground">{user.email}</p>
                  </div>
                </DropdownMenuLabel>
                <DropdownMenuSeparator />
                <DropdownMenuItem onClick={() => navigate("/app/settings")} className="cursor-pointer">
                  <User className="mr-2 h-4 w-4 text-muted-foreground" />
                  <span>Settings</span>
                </DropdownMenuItem>
                <DropdownMenuSeparator />
                <DropdownMenuItem onClick={handleLogout} className="cursor-pointer text-destructive focus:text-destructive">
                  <LogOut className="mr-2 h-4 w-4" />
                  <span>Log out</span>
                </DropdownMenuItem>
              </DropdownMenuContent>
            </DropdownMenu>
          </div>
        )}
      </div>
    </header>
  );
};
