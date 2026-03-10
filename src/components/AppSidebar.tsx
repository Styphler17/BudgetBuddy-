import { LayoutDashboard, TrendingUp, Wallet, Settings, FileText, PieChart, Bell, LogOut, User } from "lucide-react";
import { NavLink, useNavigate } from "react-router-dom";
import { cn } from "@/lib/utils";
import budgetBuddyLogo from "@/assets/BudgetBuddy.png";
import { useToast } from "@/hooks/use-toast";
import storageService from "@/lib/storage";
import { Avatar, AvatarFallback } from "@/components/ui/avatar";
import {
  Sidebar,
  SidebarContent,
  SidebarFooter,
  SidebarGroup,
  SidebarGroupContent,
  SidebarGroupLabel,
  SidebarHeader,
  SidebarMenu,
  SidebarMenuButton,
  SidebarMenuItem,
  useSidebar,
} from "@/components/ui/sidebar";

const items = [
  { title: "Dashboard", url: "/app/dashboard", icon: LayoutDashboard },
  { title: "Transactions", url: "/app/transactions", icon: FileText },
  { title: "Analytics", url: "/app/analytics", icon: PieChart },
  { title: "Budget Goals", url: "/app/goals", icon: TrendingUp },
  { title: "Accounts", url: "/app/accounts", icon: Wallet },
  { title: "Categories", url: "/app/categories", icon: TrendingUp },
  { title: "Notifications", url: "/app/notifications", icon: Bell },
  { title: "Settings", url: "/app/settings", icon: Settings },
];

export function AppSidebar() {
  const { open, isMobile, setOpenMobile } = useSidebar();
  const navigate = useNavigate();
  const { toast } = useToast();

  const handleLogout = () => {
    storageService.removeItem("user");
    toast({
      title: "Logged out",
      description: "You have been successfully logged out."
    });
    navigate("/");
  };

  const user = JSON.parse(storageService.getItem("user") || "null");

  const getInitials = (name: string) => {
    return name.split(' ').map((n: string) => n[0]).join('').toUpperCase();
  };

  return (
    <Sidebar collapsible="icon">
      <SidebarHeader className="border-b border-sidebar-border">
        <div className="flex items-center gap-2 px-2 py-2">
          <img src={budgetBuddyLogo} alt="BudgetBuddy" className="h-8 w-auto" />
          {open && <span className="font-heading text-lg font-bold text-sidebar-foreground">BudgetBuddy</span>}
        </div>
      </SidebarHeader>
      <SidebarContent>
        <SidebarGroup>
          <SidebarGroupLabel className="font-heading">Menu</SidebarGroupLabel>
          <SidebarGroupContent>
            <SidebarMenu>
              {items.map((item) => (
                <SidebarMenuItem key={item.title}>
                  <SidebarMenuButton asChild>
                    <NavLink
                      to={item.url}
                      end
                      onClick={() => { if (isMobile) setOpenMobile(false); }}
                      className={({ isActive }) =>
                        cn(
                          "flex items-center gap-3 px-3 py-2.5 rounded-md transition-all duration-200 group w-full",
                          isActive
                            ? "bg-primary/10 text-primary font-bold shadow-sm"
                            : "text-sidebar-foreground hover:bg-sidebar-accent hover:text-sidebar-accent-foreground"
                        )
                      }
                    >
                      {({ isActive }) => (
                        <>
                          <item.icon className={cn(
                            "h-5 w-5 transition-transform group-hover:scale-105",
                            isActive ? "text-primary" : "text-sidebar-foreground/70 group-hover:text-sidebar-accent-foreground"
                          )} />
                          {open && <span className="font-body text-sm tracking-tight">{item.title}</span>}
                        </>
                      )}
                    </NavLink>
                  </SidebarMenuButton>
                </SidebarMenuItem>
              ))}
            </SidebarMenu>
          </SidebarGroupContent>
        </SidebarGroup>
      </SidebarContent>

      {/* User profile footer */}
      {user && (
        <SidebarFooter className="border-t border-sidebar-border p-3">
          <div className={cn(
            "flex items-center gap-3",
            !open && "justify-center"
          )}>
            <Avatar className="h-8 w-8 flex-shrink-0 ring-2 ring-primary/20">
              <AvatarFallback className="bg-primary text-primary-foreground text-xs font-bold">
                {getInitials(user.name)}
              </AvatarFallback>
            </Avatar>
            {open && (
              <div className="flex-1 min-w-0">
                <p className="text-sm font-semibold text-sidebar-foreground truncate">{user.name}</p>
                <p className="text-xs text-sidebar-foreground/60 truncate">{user.email}</p>
              </div>
            )}
            {open && (
              <button
                onClick={handleLogout}
                title="Log out"
                className="flex-shrink-0 p-1.5 rounded-md text-sidebar-foreground/60 hover:text-destructive hover:bg-destructive/10 transition-colors"
              >
                <LogOut className="h-4 w-4" />
              </button>
            )}
          </div>
          {!open && (
            <button
              onClick={handleLogout}
              title="Log out"
              className="mt-2 w-full flex justify-center p-1.5 rounded-md text-sidebar-foreground/60 hover:text-destructive hover:bg-destructive/10 transition-colors"
            >
              <LogOut className="h-4 w-4" />
            </button>
          )}
        </SidebarFooter>
      )}
    </Sidebar>
  );
}
