import { useEffect, useState } from "react";
import { Link, useLocation, useNavigate } from "react-router-dom";
import { AdminLayout } from "@/layouts/AdminLayout";
import { adminAPI } from "@/lib/api";
import { useToast } from "@/hooks/use-toast";
import { Button } from "@/components/ui/button";
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { cn } from "@/lib/utils";
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from "@/components/ui/dialog";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import { Sheet, SheetContent, SheetHeader, SheetTitle, SheetTrigger } from "@/components/ui/sheet";
import { LogOut, Menu, UserPlus } from "lucide-react";

export function AdminProfile() {
  const navigate = useNavigate();
  const location = useLocation();
  const { toast } = useToast();

  const [adminProfileName, setAdminProfileName] = useState("");
  const [adminProfileEmail, setAdminProfileEmail] = useState("");
  const [isEditingAdminProfile, setIsEditingAdminProfile] = useState(false);
  const [isEditingAdminPassword, setIsEditingAdminPassword] = useState(false);
  const [adminPassword, setAdminPassword] = useState("");
  const [adminPasswordConfirm, setAdminPasswordConfirm] = useState("");

  const [newAdmin, setNewAdmin] = useState({
    email: "",
    name: "",
    password: "",
    role: "admin" as "admin" | "moderator"
  });
  const [showCreateAdmin, setShowCreateAdmin] = useState(false);
  const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);

  useEffect(() => {
    const storedAdmin = localStorage.getItem("admin");
    if (!storedAdmin) {
      navigate("/admin-login");
      return;
    }

    try {
      const parsed = JSON.parse(storedAdmin);
      setAdminProfileName(parsed.name || "");
      setAdminProfileEmail(parsed.email || "");
    } catch (error) {
      console.error("Failed to parse admin localStorage", error);
      navigate("/admin-login");
    }
  }, [navigate]);

  const resetAdminProfileForm = () => {
    const storedAdmin = JSON.parse(localStorage.getItem("admin") || "null");
    setAdminProfileName(storedAdmin?.name || "");
    setAdminProfileEmail(storedAdmin?.email || "");
  };

  const handleAdminProfileSave = async () => {
    const storedAdmin = JSON.parse(localStorage.getItem("admin") || "null");
    if (!storedAdmin) return;

    const trimmedName = adminProfileName.trim();
    const trimmedEmail = adminProfileEmail.trim();

    if (!trimmedName) {
      toast({
        title: "Display name required",
        description: "Please enter a display name.",
        variant: "destructive"
      });
      return;
    }

    if (!trimmedEmail) {
      toast({
        title: "Email required",
        description: "Please enter an email address.",
        variant: "destructive"
      });
      return;
    }

    try {
      await adminAPI.update(storedAdmin.id, {
        name: trimmedName,
        email: trimmedEmail
      });

      const updatedAdmin = {
        ...storedAdmin,
        name: trimmedName,
        email: trimmedEmail
      };
      localStorage.setItem("admin", JSON.stringify(updatedAdmin));
      setAdminProfileName(trimmedName);
      setAdminProfileEmail(trimmedEmail);
      setIsEditingAdminProfile(false);

      toast({
        title: "Profile updated",
        description: "Your admin details have been saved."
      });
    } catch (error) {
      console.error("Error updating admin profile:", error);
      toast({
        title: "Update failed",
        description: "Could not update admin profile.",
        variant: "destructive"
      });
    }
  };

  const handleAdminPasswordSave = async () => {
    const storedAdmin = JSON.parse(localStorage.getItem("admin") || "null");
    if (!storedAdmin) return;

    const trimmedPassword = adminPassword.trim();
    const trimmedConfirm = adminPasswordConfirm.trim();

    if (!trimmedPassword) {
      toast({
        title: "New password required",
        description: "Please enter a new password.",
        variant: "destructive"
      });
      return;
    }

    if (trimmedPassword.length < 8) {
      toast({
        title: "Password too short",
        description: "Use at least 8 characters for your admin password.",
        variant: "destructive"
      });
      return;
    }

    if (trimmedPassword !== trimmedConfirm) {
      toast({
        title: "Passwords do not match",
        description: "Make sure the confirmation matches the new password.",
        variant: "destructive"
      });
      return;
    }

    try {
      await adminAPI.update(storedAdmin.id, {
        passwordHash: trimmedPassword
      });

      const updatedAdmin = {
        ...storedAdmin,
        password_hash: trimmedPassword
      };
      localStorage.setItem("admin", JSON.stringify(updatedAdmin));

      toast({
        title: "Password updated",
        description: "Your admin password has been updated."
      });
      setIsEditingAdminPassword(false);
      setAdminPassword("");
      setAdminPasswordConfirm("");
    } catch (error) {
      console.error("Error updating admin password:", error);
      toast({
        title: "Update failed",
        description: "Could not update admin password.",
        variant: "destructive"
      });
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
    } catch (error) {
      console.error("Error creating admin:", error);
      toast({
        title: "Error",
        description: "Failed to create admin",
        variant: "destructive"
      });
    }
  };

  const handleAdminLogout = () => {
    localStorage.removeItem("admin");
    toast({
      title: "Admin logged out",
      description: "You have been successfully logged out from admin panel."
    });
    navigate("/admin-login");
  };

  const sidebarContent = (
    <div className="flex h-full flex-col">
      <div className="mb-6">
        <h2 className="text-lg font-semibold text-foreground">BudgetBuddy Admin</h2>
        <p className="text-xs text-muted-foreground">Manage your workspace</p>
      </div>
      <nav className="flex-1 space-y-1">
        <Link
          to="/admin"
          className={cn(
            "flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition-colors",
            location.pathname === "/admin"
              ? "bg-primary/10 text-primary"
              : "text-muted-foreground hover:bg-muted hover:text-foreground"
          )}
        >
          Dashboard
        </Link>
        <Link
          to="/admin/profile"
          className={cn(
            "flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition-colors",
            location.pathname === "/admin/profile"
              ? "bg-primary text-primary-foreground"
              : "text-muted-foreground hover:bg-muted hover:text-foreground"
          )}
        >
          Profile settings
        </Link>
      </nav>
      <div className="mt-8 space-y-1 text-xs text-muted-foreground">
        <p>Signed in as</p>
        <p className="font-medium text-foreground">{adminProfileName || "Admin"}</p>
        <p>{adminProfileEmail || "temp.admin@budgetbuddy.com"}</p>
      </div>
    </div>
  );

  const topbarContent = (
    <div className="flex flex-col gap-4 px-4 py-4 md:flex-row md:items-center md:justify-between">
      <div className="flex items-center justify-between gap-3 md:justify-start">
        <div className="space-y-1">
          <h1 className="text-2xl font-semibold tracking-tight">Admin Profile</h1>
          <p className="text-sm text-muted-foreground">Manage your admin account and security</p>
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
                <p className="font-medium text-foreground">{adminProfileName || "Admin"}</p>
                <p className="text-muted-foreground">{adminProfileEmail || "temp.admin@budgetbuddy.com"}</p>
              </div>
              <Button asChild variant="outline" className="w-full" onClick={() => setIsMobileMenuOpen(false)}>
                <Link to="/">Back to site</Link>
              </Button>
              <Button asChild variant="outline" className="w-full" onClick={() => setIsMobileMenuOpen(false)}>
                <Link to="/admin">View dashboard</Link>
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
          <p className="font-medium text-foreground">{adminProfileName || "Admin"}</p>
          <p>{adminProfileEmail || "temp.admin@budgetbuddy.com"}</p>
        </div>
        <Button asChild variant="outline" size="sm">
          <Link to="/">Back to site</Link>
        </Button>
        <Button asChild variant="outline" size="sm">
          <Link to="/admin">View dashboard</Link>
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

  return (
    <AdminLayout sidebar={sidebarContent} topbar={topbarContent}>
      <div className="space-y-6 p-4 md:p-6">
        <div className="grid gap-4 md:grid-cols-2">
          <Card>
            <CardHeader>
              <CardTitle>My Admin Profile</CardTitle>
              <CardDescription>Update the details used across the dashboard.</CardDescription>
            </CardHeader>
            <CardContent className="space-y-4">
              {isEditingAdminProfile ? (
                <>
                  <div className="space-y-2">
                    <Label htmlFor="admin-profile-name">Display Name</Label>
                    <Input
                      id="admin-profile-name"
                      value={adminProfileName}
                      onChange={(e) => setAdminProfileName(e.target.value)}
                      placeholder="e.g. Temporary Admin"
                    />
                  </div>
                  <div className="space-y-2">
                    <Label htmlFor="admin-profile-email">Email</Label>
                    <Input
                      id="admin-profile-email"
                      type="email"
                      value={adminProfileEmail}
                      onChange={(e) => setAdminProfileEmail(e.target.value)}
                      placeholder="admin@example.com"
                    />
                  </div>
                  <div className="flex gap-2">
                    <Button size="sm" onClick={handleAdminProfileSave}>
                      Save
                    </Button>
                    <Button
                      variant="outline"
                      size="sm"
                      onClick={() => {
                        resetAdminProfileForm();
                        setIsEditingAdminProfile(false);
                      }}
                    >
                      Cancel
                    </Button>
                  </div>
                </>
              ) : (
                <>
                  <div className="space-y-1">
                    <Label>Display Name</Label>
                    <p className="text-sm text-muted-foreground">{adminProfileName || "Not set"}</p>
                  </div>
                  <div className="space-y-1">
                    <Label>Email</Label>
                    <p className="text-sm text-muted-foreground">{adminProfileEmail || "Not set"}</p>
                  </div>
                  <Button
                    variant="outline"
                    size="sm"
                    onClick={() => setIsEditingAdminProfile(true)}
                    className="w-full md:w-auto"
                  >
                    Edit Profile
                  </Button>
                </>
              )}
            </CardContent>
          </Card>

          <Card>
            <CardHeader>
              <CardTitle>Login &amp; Security</CardTitle>
              <CardDescription>Change your admin password whenever you need.</CardDescription>
            </CardHeader>
            <CardContent className="space-y-4">
              {isEditingAdminPassword ? (
                <>
                  <div className="space-y-2">
                    <Label htmlFor="admin-new-password">New Password</Label>
                    <Input
                      id="admin-new-password"
                      type="password"
                      value={adminPassword}
                      onChange={(e) => setAdminPassword(e.target.value)}
                      placeholder="Enter new password"
                    />
                  </div>
                  <div className="space-y-2">
                    <Label htmlFor="admin-confirm-password">Confirm Password</Label>
                    <Input
                      id="admin-confirm-password"
                      type="password"
                      value={adminPasswordConfirm}
                      onChange={(e) => setAdminPasswordConfirm(e.target.value)}
                      placeholder="Re-enter new password"
                    />
                  </div>
                  <div className="flex gap-2">
                    <Button size="sm" onClick={handleAdminPasswordSave}>
                      Update Password
                    </Button>
                    <Button
                      variant="outline"
                      size="sm"
                      onClick={() => {
                        setIsEditingAdminPassword(false);
                        setAdminPassword("");
                        setAdminPasswordConfirm("");
                      }}
                    >
                      Cancel
                    </Button>
                  </div>
                </>
              ) : (
                <>
                  <p className="text-sm text-muted-foreground">
                    Use a strong, unique password to protect sensitive admin tools.
                  </p>
                  <Button
                    variant="outline"
                    size="sm"
                    onClick={() => {
                      setIsEditingAdminPassword(true);
                      setAdminPassword("");
                      setAdminPasswordConfirm("");
                    }}
                    className="w-full md:w-auto"
                  >
                    Update Password
                  </Button>
                </>
              )}
            </CardContent>
          </Card>
        </div>
      </div>
    </AdminLayout>
  );
}

export default AdminProfile;
