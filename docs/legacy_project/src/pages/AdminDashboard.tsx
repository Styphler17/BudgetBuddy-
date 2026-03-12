import { useState, useEffect } from "react";
import { Link, useNavigate } from "react-router-dom";
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { LogOut, Menu } from "lucide-react";
import { Badge } from "@/components/ui/badge";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { BlogManager } from "@/components/admin/BlogManager";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table";
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from "@/components/ui/dialog";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import { AlertDialog, AlertDialogAction, AlertDialogCancel, AlertDialogContent, AlertDialogDescription, AlertDialogFooter, AlertDialogHeader, AlertDialogTitle, AlertDialogTrigger } from "@/components/ui/alert-dialog";
import { Users, Shield, Activity, Settings, UserPlus, Edit, Trash2, BarChart3, Globe } from "lucide-react";
import { adminAPI } from "@/lib/api";
import { useToast } from "@/hooks/use-toast";
import { AdminLayout } from "@/layouts/AdminLayout";
import { cn } from "@/lib/utils";
import { Sheet, SheetContent, SheetDescription, SheetHeader, SheetTitle, SheetTrigger } from "@/components/ui/sheet";
import { BackToTop } from "@/components/BackToTop";
import storageService from "@/lib/storage";

interface Admin {
  id: number;
  email: string;
  name: string;
  role: string;
  is_active: boolean;
  last_login: string | null;
  created_at: string;
}

interface User {
  id: number;
  email: string;
  name: string;
  currency: string;
  is_active: boolean;
  email_verified: boolean;
  created_at: string;
}

interface SystemStats {
  totalUsers: number;
  totalAdmins: number;
  totalTransactions: number;
  totalCategories: number;
  totalGoals: number;
  totalAccounts: number;
}

interface AdminLog {
  id: number;
  admin_id: number;
  action: string;
  target_type: string;
  target_id: number | null;
  details: string | null;
  ip_address: string | null;
  created_at: string;
  admin_name: string;
  admin_email: string;
}

export function AdminDashboard() {
  const [stats, setStats] = useState<SystemStats | null>(null);
  const [admins, setAdmins] = useState<Admin[]>([]);
  const [users, setUsers] = useState<User[]>([]);
  const [logs, setLogs] = useState<AdminLog[]>([]);
  const [loading, setLoading] = useState(true);
  const [activeTab, setActiveTab] = useState("overview");
  const { toast } = useToast();
  const navigate = useNavigate();

  // Check if admin is logged in
  useEffect(() => {
    const admin = storageService.getItem("admin");
    if (!admin) {
      navigate("/admin-login");
      return;
    }
  }, [navigate]);

  // Admin creation state
  const [newAdmin, setNewAdmin] = useState({
    email: "",
    name: "",
    password: "",
    role: "admin" as "admin" | "moderator"
  });
  const [showCreateAdmin, setShowCreateAdmin] = useState(false);

  // User editing state
  const [editingUser, setEditingUser] = useState<User | null>(null);
  const [showEditUser, setShowEditUser] = useState(false);

  // Admin profile state (display only)
  const [adminProfileName, setAdminProfileName] = useState("");
  const [adminProfileEmail, setAdminProfileEmail] = useState("");
  const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);

  useEffect(() => {
    loadData();
  }, []);

  useEffect(() => {
    const storedAdmin = JSON.parse(storageService.getItem("admin") || "null");
    if (storedAdmin) {
      setAdminProfileName(storedAdmin.name || "");
      setAdminProfileEmail(storedAdmin.email || "");
    }
  }, []);

  const loadData = async () => {
    try {
      setLoading(true);
      const [statsData, adminsData, usersData, logsData] = await Promise.all([
        adminAPI.getSystemStats(),
        adminAPI.findAll(50),
        adminAPI.getAllUsers(100),
        adminAPI.getLogs(100)
      ]);

      setStats(statsData as SystemStats);
      setAdmins(adminsData as Admin[]);
      setUsers(usersData as User[]);
      setLogs(logsData as AdminLog[]);
    } catch (error) {
      console.error("Error loading admin data:", error);
      toast({
        title: "Error",
        description: "Failed to load admin dashboard data",
        variant: "destructive"
      });
    } finally {
      setLoading(false);
    }
  };

  const handleCreateAdmin = async () => {
    try {
      await adminAPI.create({
        email: newAdmin.email,
        name: newAdmin.name,
        passwordHash: newAdmin.password,
        role: newAdmin.role
      });

      toast({
        title: "Success",
        description: "Admin created successfully"
      });

      setShowCreateAdmin(false);
      setNewAdmin({ email: "", name: "", password: "", role: "admin" });

      // Log the action
      if (admin?.id) {
        adminAPI.logAction(
          admin.id,
          "create_admin",
          "system",
          null,
          `Created new admin: ${newAdmin.name} (${newAdmin.email})`
        );
      }

      loadData();
    } catch (error) {
      console.error("Error creating admin:", error);
      toast({
        title: "Error",
        description: "Failed to create admin",
        variant: "destructive"
      });
    }
  };

  const handleUpdateUser = async () => {
    if (!editingUser) return;

    try {
      await adminAPI.updateUser(editingUser.id, {
        name: editingUser.name,
        currency: editingUser.currency,
        is_active: editingUser.is_active,
        email_verified: editingUser.email_verified
      });

      toast({
        title: "Success",
        description: "User updated successfully"
      });

      setShowEditUser(false);
      setEditingUser(null);

      // Log the action
      if (admin?.id) {
        adminAPI.logAction(
          admin.id,
          "update_user",
          "user",
          editingUser.id,
          `Updated user: ${editingUser.name} (${editingUser.email})`
        );
      }

      loadData();
    } catch (error) {
      console.error("Error updating user:", error);
      toast({
        title: "Error",
        description: "Failed to update user",
        variant: "destructive"
      });
    }
  };

  const handleDeleteUser = async (userId: number) => {
    try {
      await adminAPI.deleteUser(userId);

      toast({
        title: "Success",
        description: "User deleted successfully"
      });

      // Log the action
      if (admin?.id) {
        adminAPI.logAction(
          admin.id,
          "delete_user",
          "user",
          userId,
          `Deleted user with ID #${userId}`
        );
      }

      loadData();
    } catch (error) {
      console.error("Error deleting user:", error);
      toast({
        title: "Error",
        description: "Failed to delete user",
        variant: "destructive"
      });
    }
  };

  if (loading) {
    return (
      <div className="flex min-h-screen items-center justify-center">
        <div className="text-center">
          <div className="mx-auto h-8 w-8 animate-spin rounded-full border-b-2 border-primary"></div>
          <p className="mt-2 text-muted-foreground">Loading admin dashboard...</p>
        </div>
      </div>
    );
  }

  const handleAdminLogout = () => {
    storageService.removeItem("admin");
    toast({
      title: "Admin logged out",
      description: "You have been successfully logged out from admin panel."
    });
    navigate("/admin-login");
  };

  const handleBackToSite = () => {
    window.open("/", "_blank", "noopener,noreferrer");
    window.location.reload();
  };

  const admin = JSON.parse(storageService.getItem("admin") || "null");

  const navItems = [
    { value: "overview", label: "Overview", icon: BarChart3 },
    { value: "users", label: "Users", icon: Users },
    { value: "admins", label: "Admins", icon: Shield },
    { value: "blog", label: "Blog", icon: Edit },
    { value: "logs", label: "Activity Logs", icon: Activity }
  ];

  const sidebarContent = (
    <div className="flex h-full flex-col">
      <div className="mb-8 flex items-center gap-3 px-2">
        <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-primary text-primary-foreground shadow-lg shadow-primary/20">
          <Shield className="h-6 w-6" />
        </div>
        <div>
          <h2 className="text-base font-bold leading-none tracking-tight">BudgetBuddy</h2>
          <p className="mt-1 text-[10px] font-medium uppercase tracking-widest text-muted-foreground/60">Admin Portal</p>
        </div>
      </div>
      <nav className="flex-1 space-y-1.5">
        {navItems.map((item) => (
          <button
            key={item.value}
            type="button"
            onClick={() => setActiveTab(item.value)}
            className={cn(
              "flex w-full items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition-all duration-200 group",
              activeTab === item.value
                ? "bg-primary text-primary-foreground shadow-md shadow-primary/10"
                : "text-muted-foreground hover:bg-muted hover:text-foreground"
            )}
          >
            <item.icon className={cn("h-4 w-4 transition-transform group-hover:scale-110", activeTab === item.value ? "text-primary-foreground" : "text-muted-foreground/70")} />
            {item.label}
          </button>
        ))}
      </nav>
      <div className="mt-auto border-t pt-6 px-2 space-y-4">
        <div className="rounded-xl border bg-muted/30 p-4">
          <p className="text-[10px] font-black uppercase tracking-widest text-muted-foreground mb-2">Session</p>
          <div className="flex items-center gap-3">
            <div className="h-8 w-8 rounded-full bg-primary/20 flex items-center justify-center text-xs font-bold text-primary">
              {(adminProfileName || admin?.name || 'A')[0].toUpperCase()}
            </div>
            <div className="min-w-0">
              <p className="text-xs font-bold truncate">{adminProfileName || admin?.name || "Administrator"}</p>
              <p className="text-[10px] text-muted-foreground truncate">{adminProfileEmail || admin?.email}</p>
            </div>
          </div>
        </div>
        <Button variant="ghost" size="sm" className="w-full justify-start gap-2 text-xs font-bold text-destructive hover:bg-destructive/10" onClick={handleAdminLogout}>
          <LogOut className="h-3.5 w-3.5" />
          Logout System
        </Button>
      </div>
    </div>
  );

  const topbarContent = (
    <div className="flex h-16 items-center px-4 md:px-8 gap-4">
      <div className="flex items-center gap-3 flex-1 min-w-0">
        <Sheet open={isMobileMenuOpen} onOpenChange={setIsMobileMenuOpen}>
          <SheetTrigger asChild>
            <Button variant="ghost" size="icon" className="md:hidden -ml-2">
              <Menu className="h-5 w-5" />
              <span className="sr-only">Toggle menu</span>
            </Button>
          </SheetTrigger>
          <SheetContent side="left" className="w-[300px] p-0 border-r-0">
            <div className="h-full bg-background p-6 flex flex-col shadow-2xl">
              <SheetHeader className="text-left py-4">
                <SheetTitle className="text-xl font-bold flex items-center gap-2">
                  <Shield className="h-5 w-5 text-primary" />
                  BudgetBuddy Admin
                </SheetTitle>
                <SheetDescription className="sr-only">
                  Mobile navigation menu for administrative tasks and system management.
                </SheetDescription>
              </SheetHeader>
              <div className="mt-6 flex-1 space-y-2">
                {navItems.map((item) => (
                  <Button
                    key={item.value}
                    variant={activeTab === item.value ? "secondary" : "ghost"}
                    className={cn(
                      "w-full justify-start gap-3 h-11 px-4 text-sm font-medium",
                      activeTab === item.value ? "bg-primary/10 text-primary border-l-4 border-primary rounded-l-none" : ""
                    )}
                    onClick={() => {
                      setActiveTab(item.value);
                      setIsMobileMenuOpen(false);
                    }}
                  >
                    <item.icon className="h-4 w-4" />
                    {item.label}
                  </Button>
                ))}
              </div>
              <div className="mt-auto pt-6 border-t space-y-4">
                <div className="rounded-xl border bg-muted/30 p-4">
                  <p className="text-xs text-muted-foreground uppercase tracking-wider font-bold mb-1">Session</p>
                  <p className="text-sm font-semibold truncate">{adminProfileName || admin?.name || "Administrator"}</p>
                  <p className="text-xs text-muted-foreground truncate">{adminProfileEmail || admin?.email}</p>
                </div>
                <div className="grid grid-cols-2 gap-2">
                  <Button variant="outline" size="sm" onClick={handleBackToSite} className="text-xs">
                    Live Site
                  </Button>
                  <Button variant="outline" size="sm" onClick={handleAdminLogout} className="text-xs text-destructive hover:bg-destructive/10">
                    Logout
                  </Button>
                </div>
              </div>
            </div>
          </SheetContent>
        </Sheet>
        <div className="hidden md:flex flex-col min-w-0">
          <h1 className="text-base lg:text-lg font-bold tracking-tight truncate">Admin Dashboard</h1>
          <p className="text-[10px] lg:text-xs text-muted-foreground hidden lg:block truncate">System management</p>
        </div>
        <div className="flex md:hidden flex-col">
          <h1 className="text-base font-bold truncate">Admin</h1>
        </div>
      </div>

      <div className="flex items-center gap-2 shrink-0">
        <div className="hidden lg:flex flex-col items-end mr-1 text-right min-w-0">
          <p className="text-xs font-bold truncate max-w-[120px]">{adminProfileName || admin?.name}</p>
          <Badge variant="outline" className="text-[9px] h-3.5 py-0 font-bold uppercase tracking-tighter">{admin?.role || "Admin"}</Badge>
        </div>

        <DropdownMenu>
          <DropdownMenuTrigger asChild>
            <Button variant="ghost" size="icon" className="h-9 w-9 rounded-full ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
              <div className="h-8 w-8 rounded-full bg-primary/10 flex items-center justify-center text-primary text-xs font-bold border border-primary/20">
                {(adminProfileName || admin?.name || 'A')[0].toUpperCase()}
              </div>
            </Button>
          </DropdownMenuTrigger>
          <DropdownMenuContent align="end" className="w-56 mt-2">
            <DropdownMenuLabel className="font-normal">
              <div className="flex flex-col space-y-1">
                <p className="text-sm font-semibold leading-none">{adminProfileName || admin?.name}</p>
                <p className="text-xs leading-none text-muted-foreground">{adminProfileEmail || admin?.email}</p>
              </div>
            </DropdownMenuLabel>
            <DropdownMenuSeparator />
            <DropdownMenuItem onClick={() => navigate("/admin/profile")} className="cursor-pointer">
              <Shield className="mr-2 h-4 w-4" />
              <span>Profile Settings</span>
            </DropdownMenuItem>
            <DropdownMenuItem onClick={handleBackToSite} className="cursor-pointer">
              <Globe className="mr-2 h-4 w-4" />
              <span>View Website</span>
            </DropdownMenuItem>
            <DropdownMenuSeparator />
            <DropdownMenuItem onClick={handleAdminLogout} className="cursor-pointer text-destructive focus:text-destructive">
              <LogOut className="mr-2 h-4 w-4" />
              <span>Logout</span>
            </DropdownMenuItem>
          </DropdownMenuContent>
        </DropdownMenu>

        <Button
          size="sm"
          className="hidden md:flex gap-2 h-9 px-3"
          onClick={() => setShowCreateAdmin(true)}
        >
          <UserPlus className="h-4 w-4" />
          <span className="hidden lg:inline">Add Admin</span>
        </Button>
      </div>
    </div>
  );

  const renderContent = () => {
    switch (activeTab) {
      case "overview":
        return (
          <div className="space-y-6">
            <div className="grid grid-cols-2 gap-4 lg:grid-cols-3">
              <Card>
                <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                  <CardTitle className="text-sm font-medium">Total Users</CardTitle>
                  <Users className="h-4 w-4 text-muted-foreground" />
                </CardHeader>
                <CardContent>
                  <div className="text-2xl font-bold">{stats?.totalUsers || 0}</div>
                  <p className="text-xs text-muted-foreground">Registered users</p>
                </CardContent>
              </Card>

              <Card>
                <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                  <CardTitle className="text-sm font-medium">Total Admins</CardTitle>
                  <Shield className="h-4 w-4 text-muted-foreground" />
                </CardHeader>
                <CardContent>
                  <div className="text-2xl font-bold">{stats?.totalAdmins || 0}</div>
                  <p className="text-xs text-muted-foreground">System administrators</p>
                </CardContent>
              </Card>

              <Card>
                <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                  <CardTitle className="text-sm font-medium">Total Transactions</CardTitle>
                  <Activity className="h-4 w-4 text-muted-foreground" />
                </CardHeader>
                <CardContent>
                  <div className="text-2xl font-bold">{stats?.totalTransactions || 0}</div>
                  <p className="text-xs text-muted-foreground">Recorded transactions</p>
                </CardContent>
              </Card>

              <Card>
                <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                  <CardTitle className="text-sm font-medium">Categories</CardTitle>
                  <BarChart3 className="h-4 w-4 text-muted-foreground" />
                </CardHeader>
                <CardContent>
                  <div className="text-2xl font-bold">{stats?.totalCategories || 0}</div>
                  <p className="text-xs text-muted-foreground">Budget categories</p>
                </CardContent>
              </Card>

              <Card>
                <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                  <CardTitle className="text-sm font-medium">Goals</CardTitle>
                  <Settings className="h-4 w-4 text-muted-foreground" />
                </CardHeader>
                <CardContent>
                  <div className="text-2xl font-bold">{stats?.totalGoals || 0}</div>
                  <p className="text-xs text-muted-foreground">Financial goals</p>
                </CardContent>
              </Card>

              <Card>
                <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                  <CardTitle className="text-sm font-medium">Accounts</CardTitle>
                  <Settings className="h-4 w-4 text-muted-foreground" />
                </CardHeader>
                <CardContent>
                  <div className="text-2xl font-bold">{stats?.totalAccounts || 0}</div>
                  <p className="text-xs text-muted-foreground">User accounts</p>
                </CardContent>
              </Card>
            </div>
          </div>
        );

      case "users":
        return (
          <div className="space-y-4">
            <div>
              <h2 className="text-lg font-bold tracking-tight">User Management</h2>
              <p className="text-sm text-muted-foreground">Manage registered users and their account status</p>
            </div>
            {/* Cards — shown below 1024px */}
            <div className="grid gap-3 lg:hidden">
              {users.map((user) => (
                <div key={user.id} className="rounded-2xl border bg-card shadow-sm overflow-hidden">
                  {/* Card Header */}
                  <div className="flex items-center gap-3 p-4 border-b bg-muted/20">
                    <div className="h-10 w-10 rounded-full bg-primary/15 flex items-center justify-center text-primary font-black text-sm shrink-0 border border-primary/20">
                      {user.name[0]?.toUpperCase()}
                    </div>
                    <div className="flex-1 min-w-0">
                      <p className="font-bold text-sm text-foreground leading-tight">{user.name}</p>
                      <p className="text-xs text-muted-foreground mt-0.5 break-all">{user.email}</p>
                    </div>
                    <Badge variant={user.is_active ? "default" : "secondary"} className="shrink-0 font-bold uppercase text-[9px] px-2">
                      {user.is_active ? "Active" : "Inactive"}
                    </Badge>
                  </div>
                  {/* Card Metadata */}
                  <div className="grid grid-cols-3 divide-x text-center">
                    <div className="py-3 px-2">
                      <p className="text-[9px] font-black uppercase tracking-widest text-muted-foreground/60">Currency</p>
                      <p className="font-bold text-xs mt-1">{user.currency}</p>
                    </div>
                    <div className="py-3 px-2">
                      <p className="text-[9px] font-black uppercase tracking-widest text-muted-foreground/60">Verified</p>
                      <div className="mt-1 flex justify-center">
                        <Badge variant={user.email_verified ? "default" : "outline"} className="h-4 text-[9px] py-0 font-bold">
                          {user.email_verified ? "Yes" : "No"}
                        </Badge>
                      </div>
                    </div>
                    <div className="py-3 px-2">
                      <p className="text-[9px] font-black uppercase tracking-widest text-muted-foreground/60">Joined</p>
                      <p className="font-bold text-xs mt-1">{new Date(user.created_at).toLocaleDateString([], { month: 'short', year: '2-digit' })}</p>
                    </div>
                  </div>
                  {/* Card Actions */}
                  <div className="flex border-t">
                    <Button
                      variant="ghost"
                      className="flex-1 h-10 rounded-none text-xs font-bold text-primary hover:bg-primary/5 gap-1.5"
                      onClick={() => { setEditingUser(user); setShowEditUser(true); }}
                    >
                      <Edit className="h-3.5 w-3.5" />Edit
                    </Button>
                    <div className="w-px bg-border" />
                    <AlertDialog>
                      <AlertDialogTrigger asChild>
                        <Button variant="ghost" className="flex-1 h-10 rounded-none text-xs font-bold text-destructive hover:bg-destructive/5 gap-1.5">
                          <Trash2 className="h-3.5 w-3.5" />Delete
                        </Button>
                      </AlertDialogTrigger>
                      <AlertDialogContent className="rounded-2xl">
                        <AlertDialogHeader>
                          <AlertDialogTitle className="font-black">Delete User?</AlertDialogTitle>
                          <AlertDialogDescription>Are you sure you want to remove <span className="font-bold text-foreground">{user.name}</span>? This action is irreversible.</AlertDialogDescription>
                        </AlertDialogHeader>
                        <AlertDialogFooter className="gap-2 sm:gap-0">
                          <AlertDialogCancel className="font-bold rounded-xl">Cancel</AlertDialogCancel>
                          <AlertDialogAction onClick={() => handleDeleteUser(user.id)} className="font-bold rounded-xl bg-destructive hover:bg-destructive/90">Delete Permanently</AlertDialogAction>
                        </AlertDialogFooter>
                      </AlertDialogContent>
                    </AlertDialog>
                  </div>
                </div>
              ))}
            </div>

            {/* Table — shown at 1024px+ */}
            <div className="hidden lg:block">
              <div className="rounded-xl border bg-card/50 overflow-hidden shadow-sm">
                <div className="overflow-x-auto">
                  <Table className="min-w-[600px]">
                    <TableHeader className="bg-muted/30">
                      <TableRow>
                        <TableHead className="font-bold">User</TableHead>
                        <TableHead className="font-bold">Currency</TableHead>
                        <TableHead className="font-bold">Status</TableHead>
                        <TableHead className="font-bold text-center">Verified</TableHead>
                        <TableHead className="font-bold">Joined</TableHead>
                        <TableHead className="font-bold text-right pr-4">Actions</TableHead>
                      </TableRow>
                    </TableHeader>
                    <TableBody>
                      {users.map((user) => (
                        <TableRow key={user.id} className="group hover:bg-muted/10 transition-colors">
                          <TableCell className="py-3">
                            <div className="flex items-center gap-3">
                              <div className="h-8 w-8 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-xs shrink-0">{user.name[0]?.toUpperCase()}</div>
                              <div>
                                <p className="font-semibold text-sm">{user.name}</p>
                                <p className="text-xs text-muted-foreground">{user.email}</p>
                              </div>
                            </div>
                          </TableCell>
                          <TableCell className="font-mono text-xs">{user.currency}</TableCell>
                          <TableCell><Badge variant={user.is_active ? "default" : "secondary"} className="h-5 px-1.5 text-[10px] font-bold">{user.is_active ? "Active" : "Inactive"}</Badge></TableCell>
                          <TableCell className="text-center"><Badge variant={user.email_verified ? "default" : "outline"} className="h-4 py-0 text-[9px] font-bold">{user.email_verified ? "Yes" : "No"}</Badge></TableCell>
                          <TableCell className="text-xs text-muted-foreground">{new Date(user.created_at).toLocaleDateString()}</TableCell>
                          <TableCell className="text-right pr-4">
                            <div className="flex justify-end gap-1">
                              <Button variant="ghost" size="icon" className="h-7 w-7 text-primary hover:bg-primary/10" onClick={() => { setEditingUser(user); setShowEditUser(true); }} title="Edit"><Edit className="h-3.5 w-3.5" /></Button>
                              <AlertDialog>
                                <AlertDialogTrigger asChild>
                                  <Button variant="ghost" size="icon" className="h-7 w-7 text-destructive hover:bg-destructive/10" title="Delete"><Trash2 className="h-3.5 w-3.5" /></Button>
                                </AlertDialogTrigger>
                                <AlertDialogContent className="rounded-2xl">
                                  <AlertDialogHeader>
                                    <AlertDialogTitle className="font-black">Delete User?</AlertDialogTitle>
                                    <AlertDialogDescription>Are you sure you want to remove <span className="font-bold text-foreground">{user.name}</span>? This action is irreversible.</AlertDialogDescription>
                                  </AlertDialogHeader>
                                  <AlertDialogFooter className="gap-2 sm:gap-0">
                                    <AlertDialogCancel className="font-bold rounded-xl">Cancel</AlertDialogCancel>
                                    <AlertDialogAction onClick={() => handleDeleteUser(user.id)} className="font-bold rounded-xl bg-destructive hover:bg-destructive/90 text-white">Delete Permanently</AlertDialogAction>
                                  </AlertDialogFooter>
                                </AlertDialogContent>
                              </AlertDialog>
                            </div>
                          </TableCell>
                        </TableRow>
                      ))}
                    </TableBody>
                  </Table>
                </div>
              </div>
            </div>
          </div>
        );


      case "admins":
        return (
          <div className="space-y-4">
            <div>
              <h2 className="text-lg font-bold tracking-tight">Admin Management</h2>
              <p className="text-sm text-muted-foreground">Manage system administrators and their permissions</p>
            </div>
            {/* Cards — shown below 1024px */}
            <div className="grid gap-3 lg:hidden">
              {admins.map((admin) => (
                <div key={admin.id} className="rounded-2xl border bg-card shadow-sm overflow-hidden">
                  <div className="flex items-center gap-3 p-4 border-b bg-muted/20">
                    <div className="h-10 w-10 rounded-full bg-primary/15 flex items-center justify-center text-primary font-black text-sm shrink-0 border border-primary/20">
                      {admin.name[0]?.toUpperCase()}
                    </div>
                    <div className="flex-1 min-w-0">
                      <p className="font-bold text-sm text-foreground leading-tight">{admin.name}</p>
                      <p className="text-xs text-muted-foreground mt-0.5 break-all">{admin.email}</p>
                    </div>
                    <Badge variant={admin.is_active ? "default" : "secondary"} className="shrink-0 font-bold uppercase text-[9px] px-2">
                      {admin.is_active ? "Active" : "Inactive"}
                    </Badge>
                  </div>
                  <div className="grid grid-cols-3 divide-x text-center">
                    <div className="py-3 px-2">
                      <p className="text-[9px] font-black uppercase tracking-widest text-muted-foreground/60">Role</p>
                      <Badge variant="outline" className="h-4 py-0 text-[9px] font-bold mt-1 uppercase border-primary/20 bg-primary/5">{admin.role}</Badge>
                    </div>
                    <div className="py-3 px-2">
                      <p className="text-[9px] font-black uppercase tracking-widest text-muted-foreground/60">Last Login</p>
                      <p className="font-bold text-xs mt-1">{admin.last_login ? new Date(admin.last_login).toLocaleDateString([], { month: 'short', day: 'numeric' }) : <span className="opacity-40 italic">Never</span>}</p>
                    </div>
                    <div className="py-3 px-2">
                      <p className="text-[9px] font-black uppercase tracking-widest text-muted-foreground/60">Since</p>
                      <p className="font-bold text-xs mt-1">{new Date(admin.created_at).toLocaleDateString([], { month: 'short', year: '2-digit' })}</p>
                    </div>
                  </div>
                </div>
              ))}
            </div>

            {/* Table — shown at 1024px+ */}
            <div className="hidden lg:block">
              <div className="rounded-xl border bg-card/50 overflow-hidden shadow-sm">
                <div className="overflow-x-auto">
                  <Table className="min-w-[560px]">
                    <TableHeader className="bg-muted/30">
                      <TableRow>
                        <TableHead className="font-bold">Admin</TableHead>
                        <TableHead className="font-bold">Role</TableHead>
                        <TableHead className="font-bold">Status</TableHead>
                        <TableHead className="font-bold">Last Login</TableHead>
                        <TableHead className="font-bold">Since</TableHead>
                      </TableRow>
                    </TableHeader>
                    <TableBody>
                      {admins.map((admin) => (
                        <TableRow key={admin.id} className="hover:bg-muted/5 transition-colors">
                          <TableCell className="py-3">
                            <div className="flex items-center gap-3">
                              <div className="h-8 w-8 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-xs shrink-0">{admin.name[0]?.toUpperCase()}</div>
                              <div>
                                <p className="font-semibold text-sm">{admin.name}</p>
                                <p className="text-xs text-muted-foreground">{admin.email}</p>
                              </div>
                            </div>
                          </TableCell>
                          <TableCell><Badge variant="outline" className="h-4 py-0 text-[9px] uppercase font-bold bg-primary/5 border-primary/20">{admin.role}</Badge></TableCell>
                          <TableCell><Badge variant={admin.is_active ? "default" : "secondary"} className="h-5 px-1.5 text-[10px] font-bold">{admin.is_active ? "Active" : "Inactive"}</Badge></TableCell>
                          <TableCell className="text-xs font-medium">{admin.last_login ? new Date(admin.last_login).toLocaleDateString() : <span className="opacity-40 italic">Never</span>}</TableCell>
                          <TableCell className="text-xs text-muted-foreground">{new Date(admin.created_at).toLocaleDateString()}</TableCell>
                        </TableRow>
                      ))}
                    </TableBody>
                  </Table>
                </div>
              </div>
            </div>
          </div>
        );


      case "blog":
        return <BlogManager adminId={admin?.id ?? null} />;


      case "logs":
        return (
          <div className="space-y-4">
            <div>
              <h2 className="text-lg font-bold tracking-tight">Activity Logs</h2>
              <p className="text-sm text-muted-foreground">View system activity and admin actions</p>
            </div>
            {/* Cards — shown below 1024px */}
            <div className="grid gap-3 lg:hidden">
              {logs.map((log) => (
                <div key={log.id} className="rounded-2xl border bg-card shadow-sm overflow-hidden">
                  <div className="flex items-start justify-between gap-3 p-4 border-b bg-muted/20">
                    <div>
                      <p className="font-bold text-sm">{log.admin_name}</p>
                      <p className="text-[10px] text-muted-foreground mt-0.5">{new Date(log.created_at).toLocaleString([], { dateStyle: 'medium', timeStyle: 'short' })}</p>
                    </div>
                    <Badge variant="secondary" className="shrink-0 font-bold text-[9px] uppercase bg-muted-foreground/10">{log.action}</Badge>
                  </div>
                  <div className="p-4 space-y-3 text-xs">
                    <div className="flex items-start gap-2">
                      <span className="font-black uppercase tracking-widest text-[9px] text-muted-foreground/60 w-12 shrink-0 pt-px">Target</span>
                      <span className="font-medium">{log.target_type}{log.target_id ? ` (#${log.target_id})` : ""}</span>
                    </div>
                    {log.details && (
                      <div className="flex items-start gap-2">
                        <span className="font-black uppercase tracking-widest text-[9px] text-muted-foreground/60 w-12 shrink-0 pt-px">Notes</span>
                        <span className="text-muted-foreground italic">{log.details}</span>
                      </div>
                    )}
                  </div>
                </div>
              ))}
            </div>

            {/* Table — shown at 1024px+ */}
            <div className="hidden lg:block">
              <div className="rounded-xl border bg-card/50 overflow-hidden shadow-sm">
                <Table>
                  <TableHeader className="bg-muted/30 text-xs font-bold uppercase tracking-wider">
                    <TableRow>
                      <TableHead className="py-4">Admin</TableHead>
                      <TableHead>Action</TableHead>
                      <TableHead>Target</TableHead>
                      <TableHead className="hidden xl:table-cell">Details</TableHead>
                      <TableHead className="text-right pr-6">Timestamp</TableHead>
                    </TableRow>
                  </TableHeader>
                  <TableBody>
                    {logs.map((log) => (
                      <TableRow key={log.id} className="text-sm border-b last:border-0 hover:bg-muted/5">
                        <TableCell className="font-bold py-3">{log.admin_name}</TableCell>
                        <TableCell>
                          <Badge variant="secondary" className="font-bold text-[10px] h-5 bg-muted-foreground/10">{log.action}</Badge>
                        </TableCell>
                        <TableCell className="text-xs opacity-70">
                          {log.target_type} {log.target_id ? `(#${log.target_id})` : ""}
                        </TableCell>
                        <TableCell className="hidden xl:table-cell max-w-[200px] truncate text-xs italic">{log.details || "-"}</TableCell>
                        <TableCell className="text-right pr-6 text-xs font-mono text-muted-foreground">
                          {new Date(log.created_at).toLocaleString([], { dateStyle: 'short', timeStyle: 'short' })}
                        </TableCell>
                      </TableRow>
                    ))}
                  </TableBody>
                </Table>
              </div>
            </div>
          </div>
        );

      default:
        return null;
    }
  };

  return (
    <>
      <AdminLayout sidebar={sidebarContent} topbar={topbarContent}>
        <div className="space-y-6">
          <div className="md:hidden">
            <Label htmlFor="admin-nav" className="sr-only">
              Section
            </Label>
            <Select value={activeTab} onValueChange={setActiveTab}>
              <SelectTrigger id="admin-nav">
                <SelectValue placeholder="Select section" />
              </SelectTrigger>
              <SelectContent>
                {navItems.map((item) => (
                  <SelectItem key={item.value} value={item.value}>
                    {item.label}
                  </SelectItem>
                ))}
              </SelectContent>
            </Select>
          </div>

          <div>{renderContent()}</div>

          <BackToTop />
        </div>
      </AdminLayout>

      {/* Create Admin Dialog */}
      <Dialog open={showCreateAdmin} onOpenChange={setShowCreateAdmin}>
        <DialogContent className="sm:max-w-md rounded-2xl">
          <DialogHeader>
            <DialogTitle className="text-xl font-black">Provision New Admin</DialogTitle>
            <DialogDescription>
              Assign administrative privileges to a new system operator.
            </DialogDescription>
          </DialogHeader>
          <div className="space-y-4 py-2">
            <div className="space-y-1.5">
              <Label htmlFor="admin-email" className="text-xs font-bold uppercase text-muted-foreground">Officer Email</Label>
              <Input
                id="admin-email"
                type="email"
                value={newAdmin.email}
                onChange={(e) => setNewAdmin({ ...newAdmin, email: e.target.value })}
                placeholder="admin@budgetbuddy.com"
                className="bg-muted/30 border-none"
              />
            </div>
            <div className="space-y-1.5">
              <Label htmlFor="admin-name" className="text-xs font-bold uppercase text-muted-foreground">Full Name</Label>
              <Input
                id="admin-name"
                value={newAdmin.name}
                onChange={(e) => setNewAdmin({ ...newAdmin, name: e.target.value })}
                placeholder="John Doe"
                className="bg-muted/30 border-none"
              />
            </div>
            <div className="space-y-1.5">
              <Label htmlFor="admin-password" className="text-xs font-bold uppercase text-muted-foreground">Secure Password</Label>
              <Input
                id="admin-password"
                type="password"
                value={newAdmin.password}
                onChange={(e) => setNewAdmin({ ...newAdmin, password: e.target.value })}
                placeholder="••••••••"
                className="bg-muted/30 border-none"
              />
            </div>
            <div className="space-y-1.5">
              <Label htmlFor="admin-role" className="text-xs font-bold uppercase text-muted-foreground">Permission Level</Label>
              <Select value={newAdmin.role} onValueChange={(value: "admin" | "moderator") => setNewAdmin({ ...newAdmin, role: value })}>
                <SelectTrigger className="bg-muted/30 border-none">
                  <SelectValue placeholder="Select role" />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="admin" className="font-bold">Administrator (Full)</SelectItem>
                  <SelectItem value="moderator" className="font-bold">Moderator (Limited)</SelectItem>
                </SelectContent>
              </Select>
            </div>
          </div>
          <DialogFooter className="gap-2 sm:gap-0 mt-2">
            <Button variant="ghost" onClick={() => setShowCreateAdmin(false)} className="font-bold">
              Dismiss
            </Button>
            <Button onClick={handleCreateAdmin} className="shadow-lg shadow-primary/20 font-bold">
              Create Admin
            </Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>

      {/* Edit User Dialog */}
      <Dialog open={showEditUser} onOpenChange={setShowEditUser}>
        <DialogContent>
          <DialogHeader>
            <DialogTitle>Edit User</DialogTitle>
            <DialogDescription>
              Update user information and settings
            </DialogDescription>
          </DialogHeader>
          {editingUser && (
            <div className="space-y-4">
              <div>
                <Label htmlFor="edit-name">Name</Label>
                <Input
                  id="edit-name"
                  value={editingUser.name}
                  onChange={(e) => setEditingUser({ ...editingUser, name: e.target.value })}
                />
              </div>
              <div>
                <Label htmlFor="edit-currency">Currency</Label>
                <Select
                  value={editingUser.currency}
                  onValueChange={(value) => setEditingUser({ ...editingUser, currency: value })}
                >
                  <SelectTrigger>
                    <SelectValue />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="USD">USD</SelectItem>
                    <SelectItem value="EUR">EUR</SelectItem>
                    <SelectItem value="GBP">GBP</SelectItem>
                    <SelectItem value="JPY">JPY</SelectItem>
                  </SelectContent>
                </Select>
              </div>
              <div className="flex items-center space-x-2">
                <input
                  type="checkbox"
                  id="edit-active"
                  checked={editingUser.is_active}
                  onChange={(e) => setEditingUser({ ...editingUser, is_active: e.target.checked })}
                  title="Active status"
                />
                <Label htmlFor="edit-active">Active</Label>
              </div>
              <div className="flex items-center space-x-2">
                <input
                  type="checkbox"
                  id="edit-verified"
                  checked={editingUser.email_verified}
                  onChange={(e) => setEditingUser({ ...editingUser, email_verified: e.target.checked })}
                  title="Email verification status"
                />
                <Label htmlFor="edit-verified">Email Verified</Label>
              </div>
            </div>
          )}
          <DialogFooter>
            <Button variant="outline" onClick={() => setShowEditUser(false)}>
              Cancel
            </Button>
            <Button onClick={handleUpdateUser}>Update User</Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>
    </>
  );
}
