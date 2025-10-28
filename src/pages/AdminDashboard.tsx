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
import { AlertDialog, AlertDialogAction, AlertDialogCancel, AlertDialogContent, AlertDialogDescription, AlertDialogFooter, AlertDialogHeader, AlertDialogTitle, AlertDialogTrigger } from "@/components/ui/alert-dialog";
import { Users, Shield, Activity, Settings, UserPlus, Edit, Trash2, BarChart3 } from "lucide-react";
import { adminAPI } from "@/lib/api";
import { useToast } from "@/hooks/use-toast";
import { AdminLayout } from "@/layouts/AdminLayout";
import { cn } from "@/lib/utils";
import { Sheet, SheetContent, SheetHeader, SheetTitle, SheetTrigger } from "@/components/ui/sheet";
import { BackToTop } from "@/components/BackToTop";

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
    const admin = localStorage.getItem("admin");
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
    const storedAdmin = JSON.parse(localStorage.getItem("admin") || "null");
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

      setStats(statsData);
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
    localStorage.removeItem("admin");
    toast({
      title: "Admin logged out",
      description: "You have been successfully logged out from admin panel."
    });
    navigate("/admin-login");
  };

  const admin = JSON.parse(localStorage.getItem("admin") || "null");

  const navItems = [
    { value: "overview", label: "Overview", icon: BarChart3 },
    { value: "users", label: "Users", icon: Users },
    { value: "admins", label: "Admins", icon: Shield },
    { value: "blog", label: "Blog", icon: Edit },
    { value: "logs", label: "Activity Logs", icon: Activity }
  ];

  const sidebarContent = (
    <div className="flex h-full flex-col">
      <div className="mb-6">
        <h2 className="text-lg font-semibold text-foreground">BudgetBuddy Admin</h2>
        <p className="text-xs text-muted-foreground">Control centre</p>
      </div>
      <nav className="flex-1 space-y-1">
        {navItems.map((item) => (
          <button
            key={item.value}
            type="button"
            onClick={() => setActiveTab(item.value)}
            className={cn(
              "flex w-full items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition-colors",
              activeTab === item.value
                ? "bg-primary/10 text-primary"
                : "text-muted-foreground hover:bg-muted hover:text-foreground"
            )}
          >
            <item.icon className="h-4 w-4" />
            {item.label}
          </button>
        ))}
      </nav>
      <div className="mt-8 space-y-1 text-xs text-muted-foreground">
        <p>Signed in as</p>
        <p className="font-medium text-foreground">{adminProfileName || admin?.name || "Admin"}</p>
        <p>{adminProfileEmail || admin?.email || "temp.admin@budgetbuddy.com"}</p>
      </div>
    </div>
  );

  const topbarContent = (
    <div className="flex flex-col gap-4 px-4 py-4 md:flex-row md:items-center md:justify-between">
      <div className="flex items-center justify-between gap-3 md:justify-start">
        <div className="space-y-1">
          <h1 className="text-2xl font-semibold tracking-tight">Admin Dashboard</h1>
          <p className="text-sm text-muted-foreground">Manage users, admins, and system settings</p>
        </div>
        <Sheet open={isMobileMenuOpen} onOpenChange={setIsMobileMenuOpen}>
          <SheetTrigger asChild>
            <Button variant="outline" size="icon" className="md:hidden">
              <Menu className="h-5 w-5" />
              <span className="sr-only">Open admin actions</span>
            </Button>
          </SheetTrigger>
          <SheetContent side="right" className="w-[280px] sm:w-[320px]">
            <SheetHeader>
              <SheetTitle>Admin controls</SheetTitle>
            </SheetHeader>
            <div className="mt-6 space-y-4">
              <div className="rounded-lg border bg-muted/40 p-4 text-sm">
                <p className="font-medium text-foreground">{adminProfileName || admin?.name || "Admin"}</p>
                <p className="text-muted-foreground">{adminProfileEmail || admin?.email || "temp.admin@budgetbuddy.com"}</p>
              </div>
              <div className="space-y-2">
                {navItems.map((item) => (
                  <Button
                    key={item.value}
                    variant={activeTab === item.value ? "default" : "ghost"}
                    className="w-full justify-start"
                    onClick={() => {
                      setActiveTab(item.value);
                      setIsMobileMenuOpen(false);
                    }}
                  >
                    <item.icon className="mr-2 h-4 w-4" />
                    {item.label}
                  </Button>
                ))}
              </div>
              <Button asChild variant="outline" className="w-full" onClick={() => setIsMobileMenuOpen(false)}>
                <Link to="/">Back to site</Link>
              </Button>
              <Button asChild className="w-full" variant="secondary" onClick={() => setIsMobileMenuOpen(false)}>
                <Link to="/admin/profile">Profile settings</Link>
              </Button>
              <Button
                className="w-full gap-2"
                onClick={() => {
                  setShowCreateAdmin(true);
                  setIsMobileMenuOpen(false);
                }}
              >
                <UserPlus className="h-4 w-4" />
                Add Admin
              </Button>
              <Button
                variant="outline"
                className="w-full gap-2"
                onClick={() => {
                  setIsMobileMenuOpen(false);
                  handleAdminLogout();
                }}
              >
                <LogOut className="h-4 w-4" />
                Logout
              </Button>
            </div>
          </SheetContent>
        </Sheet>
      </div>
      <div className="hidden flex-wrap items-center gap-2 md:flex">
        <div className="text-left text-xs text-muted-foreground md:text-sm">
          <p className="font-medium text-foreground">{adminProfileName || admin?.name || "Admin"}</p>
          <p>{adminProfileEmail || admin?.email || "temp.admin@budgetbuddy.com"}</p>
        </div>
        <Button asChild variant="outline" size="sm">
          <Link to="/">Back to site</Link>
        </Button>
        <Button asChild variant="secondary" size="sm">
          <Link to="/admin/profile">Profile settings</Link>
        </Button>
        <Dialog open={showCreateAdmin} onOpenChange={setShowCreateAdmin}>
          <DialogTrigger asChild>
            <Button size="sm" className="gap-2">
              <UserPlus className="h-4 w-4" />
              Add Admin
            </Button>
          </DialogTrigger>
          <DialogContent className="sm:max-w-md">
            <DialogHeader>
              <DialogTitle>Create New Admin</DialogTitle>
              <DialogDescription>
                Add a new administrator to the system
              </DialogDescription>
            </DialogHeader>
            <div className="space-y-4">
              <div>
                <Label htmlFor="admin-email">Email</Label>
                <Input
                  id="admin-email"
                  type="email"
                  value={newAdmin.email}
                  onChange={(e) => setNewAdmin({ ...newAdmin, email: e.target.value })}
                  placeholder="admin@example.com"
                />
              </div>
              <div>
                <Label htmlFor="admin-name">Name</Label>
                <Input
                  id="admin-name"
                  value={newAdmin.name}
                  onChange={(e) => setNewAdmin({ ...newAdmin, name: e.target.value })}
                  placeholder="Admin Name"
                />
              </div>
              <div>
                <Label htmlFor="admin-password">Password</Label>
                <Input
                  id="admin-password"
                  type="password"
                  value={newAdmin.password}
                  onChange={(e) => setNewAdmin({ ...newAdmin, password: e.target.value })}
                  placeholder="Initial password"
                />
              </div>
              <div>
                <Label htmlFor="admin-role">Role</Label>
                <Select value={newAdmin.role} onValueChange={(value: "admin" | "moderator") => setNewAdmin({ ...newAdmin, role: value })}>
                  <SelectTrigger>
                    <SelectValue placeholder="Select role" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="admin">Admin</SelectItem>
                    <SelectItem value="moderator">Moderator</SelectItem>
                  </SelectContent>
                </Select>
              </div>
            </div>
            <DialogFooter>
              <Button variant="outline" onClick={() => setShowCreateAdmin(false)}>
                Cancel
              </Button>
              <Button onClick={handleCreateAdmin}>Create Admin</Button>
            </DialogFooter>
          </DialogContent>
        </Dialog>
        <Button variant="outline" size="sm" onClick={handleAdminLogout} className="gap-2">
          <LogOut className="h-4 w-4" />
          Logout
        </Button>
      </div>
    </div>
  );

  const renderContent = () => {
    switch (activeTab) {
      case "overview":
        return (
          <div className="space-y-6">
            <div className="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
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
          <div className="space-y-6">
            <Card>
              <CardHeader>
                <CardTitle>User Management</CardTitle>
                <CardDescription>Manage registered users and their account status</CardDescription>
              </CardHeader>
              <CardContent className="space-y-6">
                <div className="grid gap-4 md:hidden">
                  {users.map((user) => (
                    <div key={user.id} className="rounded-lg border bg-muted/40 p-4 space-y-3">
                      <div className="flex items-center justify-between">
                        <div>
                          <p className="font-semibold text-foreground">{user.name}</p>
                          <p className="text-sm text-muted-foreground">{user.email}</p>
                        </div>
                        <Badge variant={user.is_active ? "default" : "secondary"}>
                          {user.is_active ? "Active" : "Inactive"}
                        </Badge>
                      </div>
                      <div className="grid grid-cols-2 gap-2 text-sm text-muted-foreground">
                        <div>
                          <p className="font-medium text-foreground">Currency</p>
                          <p>{user.currency}</p>
                        </div>
                        <div>
                          <p className="font-medium text-foreground">Verified</p>
                          <Badge variant={user.email_verified ? "default" : "outline"}>
                            {user.email_verified ? "Verified" : "Unverified"}
                          </Badge>
                        </div>
                        <div className="col-span-2">
                          <p className="font-medium text-foreground">Joined</p>
                          <p>{new Date(user.created_at).toLocaleDateString()}</p>
                        </div>
                      </div>
                      <div className="flex items-center gap-2">
                        <Button
                          className="flex-1"
                          variant="secondary"
                          onClick={() => {
                            setEditingUser(user);
                            setShowEditUser(true);
                          }}
                        >
                          Edit
                        </Button>
                        <AlertDialog>
                          <AlertDialogTrigger asChild>
                            <Button className="flex-1" variant="outline">
                              Delete
                            </Button>
                          </AlertDialogTrigger>
                          <AlertDialogContent>
                            <AlertDialogHeader>
                              <AlertDialogTitle>Delete User</AlertDialogTitle>
                              <AlertDialogDescription>
                                Are you sure you want to delete {user.name}? This action cannot be undone.
                              </AlertDialogDescription>
                            </AlertDialogHeader>
                            <AlertDialogFooter>
                              <AlertDialogCancel>Cancel</AlertDialogCancel>
                              <AlertDialogAction onClick={() => handleDeleteUser(user.id)}>
                                Delete
                              </AlertDialogAction>
                            </AlertDialogFooter>
                          </AlertDialogContent>
                        </AlertDialog>
                      </div>
                    </div>
                  ))}
                </div>
                <div className="hidden md:block">
                  <div className="overflow-x-auto">
                    <Table>
                      <TableHeader>
                        <TableRow>
                          <TableHead>Name</TableHead>
                          <TableHead>Email</TableHead>
                          <TableHead>Currency</TableHead>
                          <TableHead>Status</TableHead>
                          <TableHead>Verified</TableHead>
                          <TableHead>Created</TableHead>
                          <TableHead>Actions</TableHead>
                        </TableRow>
                      </TableHeader>
                      <TableBody>
                        {users.map((user) => (
                          <TableRow key={user.id}>
                            <TableCell>{user.name}</TableCell>
                            <TableCell>{user.email}</TableCell>
                            <TableCell>{user.currency}</TableCell>
                            <TableCell>
                              <Badge variant={user.is_active ? "default" : "secondary"}>
                                {user.is_active ? "Active" : "Inactive"}
                              </Badge>
                            </TableCell>
                            <TableCell>
                              <Badge variant={user.email_verified ? "default" : "outline"}>
                                {user.email_verified ? "Verified" : "Unverified"}
                              </Badge>
                            </TableCell>
                            <TableCell>{new Date(user.created_at).toLocaleDateString()}</TableCell>
                            <TableCell>
                              <div className="flex space-x-2">
                                <Button
                                  variant="outline"
                                  size="sm"
                                  onClick={() => {
                                    setEditingUser(user);
                                    setShowEditUser(true);
                                  }}
                                >
                                  <Edit className="h-4 w-4" />
                                </Button>
                                <AlertDialog>
                                  <AlertDialogTrigger asChild>
                                    <Button variant="outline" size="sm">
                                      <Trash2 className="h-4 w-4" />
                                    </Button>
                                  </AlertDialogTrigger>
                                  <AlertDialogContent>
                                    <AlertDialogHeader>
                                      <AlertDialogTitle>Delete User</AlertDialogTitle>
                                      <AlertDialogDescription>
                                        Are you sure you want to delete {user.name}? This action cannot be undone.
                                      </AlertDialogDescription>
                                    </AlertDialogHeader>
                                    <AlertDialogFooter>
                                      <AlertDialogCancel>Cancel</AlertDialogCancel>
                                      <AlertDialogAction onClick={() => handleDeleteUser(user.id)}>
                                        Delete
                                      </AlertDialogAction>
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
              </CardContent>
            </Card>
          </div>
        );

      case "admins":
        return (
          <div className="space-y-6">
            <Card>
              <CardHeader>
                <CardTitle>Admin Management</CardTitle>
                <CardDescription>Manage system administrators and their permissions</CardDescription>
              </CardHeader>
              <CardContent className="space-y-6">
                <div className="grid gap-4 md:hidden">
                  {admins.map((admin) => (
                    <div key={admin.id} className="rounded-lg border bg-muted/40 p-4 space-y-3">
                      <div className="flex items-center justify-between">
                        <div>
                          <p className="font-semibold text-foreground">{admin.name}</p>
                          <p className="text-sm text-muted-foreground">{admin.email}</p>
                        </div>
                        <Badge variant={admin.is_active ? "default" : "secondary"}>
                          {admin.is_active ? "Active" : "Inactive"}
                        </Badge>
                      </div>
                      <div className="grid grid-cols-2 gap-2 text-sm text-muted-foreground">
                        <div>
                          <p className="font-medium text-foreground">Role</p>
                          <Badge variant="outline">{admin.role}</Badge>
                        </div>
                        <div>
                          <p className="font-medium text-foreground">Last login</p>
                          <p>{admin.last_login ? new Date(admin.last_login).toLocaleDateString() : "Never"}</p>
                        </div>
                        <div className="col-span-2">
                          <p className="font-medium text-foreground">Joined</p>
                          <p>{new Date(admin.created_at).toLocaleDateString()}</p>
                        </div>
                      </div>
                    </div>
                  ))}
                </div>
                <div className="hidden md:block">
                  <div className="overflow-x-auto">
                    <Table>
                      <TableHeader>
                        <TableRow>
                          <TableHead>Name</TableHead>
                          <TableHead>Email</TableHead>
                          <TableHead>Role</TableHead>
                          <TableHead>Status</TableHead>
                          <TableHead>Last Login</TableHead>
                          <TableHead>Created</TableHead>
                        </TableRow>
                      </TableHeader>
                      <TableBody>
                        {admins.map((admin) => (
                          <TableRow key={admin.id}>
                            <TableCell>{admin.name}</TableCell>
                            <TableCell>{admin.email}</TableCell>
                            <TableCell>
                              <Badge variant="outline">{admin.role}</Badge>
                            </TableCell>
                            <TableCell>
                              <Badge variant={admin.is_active ? "default" : "secondary"}>
                                {admin.is_active ? "Active" : "Inactive"}
                              </Badge>
                            </TableCell>
                            <TableCell>
                              {admin.last_login ? new Date(admin.last_login).toLocaleDateString() : "Never"}
                            </TableCell>
                            <TableCell>{new Date(admin.created_at).toLocaleDateString()}</TableCell>
                          </TableRow>
                        ))}
                      </TableBody>
                    </Table>
                  </div>
                </div>
              </CardContent>
            </Card>
          </div>
        );

      case "blog":
        return (
          <div className="space-y-6">
            <BlogManager adminId={admin?.id ?? null} />
          </div>
        );

      case "logs":
        return (
          <div className="space-y-6">
            <Card>
              <CardHeader>
                <CardTitle>Activity Logs</CardTitle>
                <CardDescription>View system activity and admin actions</CardDescription>
              </CardHeader>
              <CardContent className="space-y-6">
                <div className="grid gap-4 md:hidden">
                  {logs.map((log) => (
                    <div key={log.id} className="rounded-lg border bg-muted/40 p-4 space-y-2 text-sm">
                      <div className="flex items-center justify-between">
                        <p className="font-medium text-foreground">{log.admin_name}</p>
                        <span className="text-xs text-muted-foreground">
                          {new Date(log.created_at).toLocaleString()}
                        </span>
                      </div>
                      <div className="grid gap-1">
                        <p>
                          <span className="font-medium text-foreground">Action:</span> {log.action}
                        </p>
                        <p>
                          <span className="font-medium text-foreground">Target:</span>{" "}
                          {log.target_type} {log.target_id ? `#${log.target_id}` : ""}
                        </p>
                        <p className="text-muted-foreground">
                          {log.details || "No additional details recorded."}
                        </p>
                      </div>
                    </div>
                  ))}
                </div>
                <div className="hidden md:block">
                  <div className="overflow-x-auto">
                    <Table>
                      <TableHeader>
                        <TableRow>
                          <TableHead>Admin</TableHead>
                          <TableHead>Action</TableHead>
                          <TableHead>Target</TableHead>
                          <TableHead>Details</TableHead>
                          <TableHead>Timestamp</TableHead>
                        </TableRow>
                      </TableHeader>
                      <TableBody>
                        {logs.map((log) => (
                          <TableRow key={log.id}>
                            <TableCell>{log.admin_name}</TableCell>
                            <TableCell>{log.action}</TableCell>
                            <TableCell>
                              {log.target_type} {log.target_id ? `#${log.target_id}` : ""}
                            </TableCell>
                            <TableCell className="max-w-xs truncate">{log.details || "-"}</TableCell>
                            <TableCell>{new Date(log.created_at).toLocaleString()}</TableCell>
                          </TableRow>
                        ))}
                      </TableBody>
                    </Table>
                  </div>
                </div>
              </CardContent>
            </Card>
          </div>
        );

      default:
        return null;
    }
  };

  return (
    <>
      <AdminLayout sidebar={sidebarContent} topbar={topbarContent}>
        <div className="space-y-6 p-4 md:p-6">
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
