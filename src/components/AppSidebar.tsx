import { LayoutDashboard, TrendingUp, Wallet, Settings, FileText, PieChart, Bell, LogOut, User } from "lucide-react";
import { NavLink, useNavigate } from "react-router-dom";
import budgetBuddyLogo from "@/assets/budget-buddy-logo.webp";
import { useToast } from "@/hooks/use-toast";
import {
  Sidebar,
  SidebarContent,
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
  const { open } = useSidebar();
  const navigate = useNavigate();
  const { toast } = useToast();

  const handleLogout = () => {
    localStorage.removeItem("user");
    toast({
      title: "Logged out",
      description: "You have been successfully logged out."
    });
    navigate("/");
  };

  const user = JSON.parse(localStorage.getItem("user") || "null");

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
                      className={({ isActive }) =>
                        isActive
                          ? "bg-primary/10 text-primary font-semibold"
                          : "hover:bg-accent hover:text-accent-foreground"
                      }
                    >
                      <item.icon className="h-4 w-4" />
                      {open && <span className="font-body">{item.title}</span>}
                    </NavLink>
                  </SidebarMenuButton>
                </SidebarMenuItem>
              ))}
            </SidebarMenu>
          </SidebarGroupContent>
        </SidebarGroup>


      </SidebarContent>
    </Sidebar>
  );
}
