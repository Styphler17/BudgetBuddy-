import { PeriodSelector } from "./PeriodSelector";
import { SidebarTrigger } from "@/components/ui/sidebar";
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar";
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuLabel, DropdownMenuSeparator, DropdownMenuTrigger } from "@/components/ui/dropdown-menu";
import { User, LogOut } from "lucide-react";
import { useNavigate } from "react-router-dom";
import { useToast } from "@/hooks/use-toast";
import storageService from "@/lib/storage";

type Period = "daily" | "weekly" | "monthly" | "yearly";

interface TopBarProps {
  onPeriodChange?: (period: Period) => void;
}

export const TopBar = ({ onPeriodChange }: TopBarProps) => {
  const navigate = useNavigate();
  const { toast } = useToast();

  const user = JSON.parse(storageService.getItem("user") || "null");

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
    <header className="border-b border-border bg-card sticky top-0 z-50">
      <div className="flex items-center justify-between px-4 sm:px-6 py-4">
        <SidebarTrigger />
        <div className="flex-1 flex justify-center">
          <PeriodSelector onPeriodChange={onPeriodChange} />
        </div>
        {user && (
          <DropdownMenu>
            <DropdownMenuTrigger asChild>
              <Avatar className="h-8 w-8 cursor-pointer hover:ring-2 hover:ring-primary/20 transition-all">
                <AvatarImage src="" alt={user.name} />
                <AvatarFallback className="bg-primary text-primary-foreground text-sm">
                  {getInitials(user.name)}
                </AvatarFallback>
              </Avatar>
            </DropdownMenuTrigger>
            <DropdownMenuContent align="end" className="w-56">
              <DropdownMenuLabel className="font-normal">
                <div className="flex flex-col space-y-1">
                  <p className="text-sm font-medium leading-none">{user.name}</p>
                  <p className="text-xs leading-none text-muted-foreground">{user.email}</p>
                </div>
              </DropdownMenuLabel>
              <DropdownMenuSeparator />
              <DropdownMenuItem onClick={() => navigate("/app/settings")}>
                <User className="mr-2 h-4 w-4" />
                <span>Settings</span>
              </DropdownMenuItem>
              <DropdownMenuSeparator />
              <DropdownMenuItem onClick={handleLogout}>
                <LogOut className="mr-2 h-4 w-4" />
                <span>Log out</span>
              </DropdownMenuItem>
            </DropdownMenuContent>
          </DropdownMenu>
        )}
      </div>
    </header>
  );
};
