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
import { Sheet, SheetContent, SheetDescription, SheetHeader, SheetTitle, SheetTrigger } from "@/components/ui/sheet";
import { Badge } from "@/components/ui/badge";
import { LogOut, Menu, UserPlus, Shield, BarChart3, Activity, Users, Edit, Settings } from "lucide-react";
import { BackToTop } from "@/components/BackToTop";
import storageService from "@/lib/storage";

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
    const storedAdmin = storageService.getItem("admin");
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
    const storedAdmin = JSON.parse(storageService.getItem("admin") || "null");
    setAdminProfileName(storedAdmin?.name || "");
    setAdminProfileEmail(storedAdmin?.email || "");
  };

  const handleAdminProfileSave = async () => {
    const storedAdmin = JSON.parse(storageService.getItem("admin") || "null");
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
      storageService.setItem("admin", JSON.stringify(updatedAdmin));
      setAdminProfileName(trimmedName);
      setAdminProfileEmail(trimmedEmail);
      setIsEditingAdminProfile(false);

      toast({
        title: "Profile updated",
        description: "Your admin details have been saved."
      });

      // Log the action
      adminAPI.logAction(
        storedAdmin.id,
        "update_profile",
        "system",
        storedAdmin.id,
        `Updated own admin profile: ${trimmedName} (${trimmedEmail})`
      );
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
    const storedAdmin = JSON.parse(storageService.getItem("admin") || "null");
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
      storageService.setItem("admin", JSON.stringify(updatedAdmin));

      toast({
        title: "Password updated",
        description: "Your admin password has been updated."
      });

      // Log the action
      adminAPI.logAction(
        storedAdmin.id,
        "update_password",
        "system",
        storedAdmin.id,
        "Updated own admin password"
      );
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

      // Log the action
      const storedAdmin = JSON.parse(storageService.getItem("admin") || "null");
      if (storedAdmin?.id) {
        adminAPI.logAction(
          storedAdmin.id,
          "create_admin",
          "system",
          null,
          `Created new admin: ${newAdmin.name} (${newAdmin.email})`
        );
      }
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
    storageService.removeItem("admin");
    toast({
      title: "Admin logged out",
      description: "You have been successfully logged out from admin panel."
    });
    navigate("/admin-login");
  };

  const navItems = [
    { to: "/admin", label: "Overview", icon: BarChart3 },
    { to: "/admin/profile", label: "Profile Settings", icon: Settings },
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
          <Link
            key={item.to}
            to={item.to}
            className={cn(
              "flex w-full items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition-all duration-200",
              location.pathname === item.to
                ? "bg-primary text-primary-foreground shadow-md shadow-primary/10"
                : "text-muted-foreground hover:bg-muted hover:text-foreground"
            )}
          >
            <item.icon className={cn("h-4 w-4", location.pathname === item.to ? "text-primary-foreground" : "text-muted-foreground/70")} />
            {item.label}
          </Link>
        ))}
      </nav>
      <div className="mt-auto border-t pt-6 px-2 space-y-4">
        <div className="rounded-xl border bg-muted/30 p-4">
          <p className="text-[10px] font-black uppercase tracking-widest text-muted-foreground mb-2">Session</p>
          <div className="flex items-center gap-3">
            <div className="h-8 w-8 rounded-full bg-primary/20 flex items-center justify-center text-xs font-bold text-primary">
              {(adminProfileName || 'A')[0].toUpperCase()}
            </div>
            <div className="min-w-0">
              <p className="text-xs font-bold truncate">{adminProfileName || "Administrator"}</p>
              <p className="text-[10px] text-muted-foreground truncate">{adminProfileEmail}</p>
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
                  Mobile navigation menu for administrative tasks and profile settings.
                </SheetDescription>
              </SheetHeader>
              <div className="mt-6 flex-1 space-y-2">
                {navItems.map((item) => (
                  <Link
                    key={item.to}
                    to={item.to}
                    onClick={() => setIsMobileMenuOpen(false)}
                    className={cn(
                      "flex w-full items-center gap-3 rounded-xl h-11 px-4 text-sm font-medium transition-all",
                      location.pathname === item.to
                        ? "bg-primary/10 text-primary border-l-4 border-primary rounded-l-none"
                        : "text-muted-foreground hover:bg-muted"
                    )}
                  >
                    <item.icon className="h-4 w-4" />
                    {item.label}
                  </Link>
                ))}
              </div>
              <div className="mt-auto pt-6 border-t space-y-4">
                <div className="rounded-xl border bg-muted/30 p-4">
                  <p className="text-xs text-muted-foreground uppercase tracking-wider font-bold mb-1">Session</p>
                  <p className="text-sm font-semibold truncate">{adminProfileName || "Administrator"}</p>
                  <p className="text-xs text-muted-foreground truncate">{adminProfileEmail}</p>
                </div>
                <div className="grid grid-cols-2 gap-2">
                  <Button asChild variant="outline" size="sm" className="text-xs" onClick={() => setIsMobileMenuOpen(false)}>
                    <Link to="/">Live Site</Link>
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
          <h1 className="text-base lg:text-lg font-bold tracking-tight truncate">Profile Settings</h1>
          <p className="text-[10px] lg:text-xs text-muted-foreground hidden lg:block">Manage your admin account &amp; security</p>
        </div>
        <div className="flex md:hidden flex-col">
          <h1 className="text-base font-bold truncate">Profile</h1>
        </div>
      </div>

      <div className="flex items-center gap-2 shrink-0">
        <div className="hidden lg:flex flex-col items-end mr-1 text-right min-w-0">
          <p className="text-xs font-bold truncate max-w-[120px]">{adminProfileName || "Admin"}</p>
          <Badge variant="outline" className="text-[9px] h-3.5 py-0 font-bold uppercase tracking-tighter">admin</Badge>
        </div>
        <div className="h-9 w-9 rounded-full bg-primary/10 flex items-center justify-center text-primary text-xs font-bold border border-primary/20">
          {(adminProfileName || 'A')[0].toUpperCase()}
        </div>
        <Button asChild size="sm" variant="outline" className="hidden md:flex h-9 px-3">
          <Link to="/admin">← Dashboard</Link>
        </Button>
        <Dialog open={showCreateAdmin} onOpenChange={setShowCreateAdmin}>
          <DialogTrigger asChild>
            <Button size="sm" className="hidden md:flex gap-2 h-9 px-3">
              <UserPlus className="h-4 w-4" />
              <span className="hidden lg:inline">Add Admin</span>
            </Button>
          </DialogTrigger>
          <DialogContent className="sm:max-w-md rounded-2xl">
            <DialogHeader>
              <DialogTitle className="text-xl font-black">Provision New Admin</DialogTitle>
              <DialogDescription>
                Assign administrative privileges to a new system operator.
              </DialogDescription>
            </DialogHeader>
            <div className="space-y-4 py-2">
              <div className="space-y-1.5">
                <Label htmlFor="ap-admin-email" className="text-xs font-bold uppercase text-muted-foreground">Officer Email</Label>
                <Input id="ap-admin-email" type="email" value={newAdmin.email} onChange={(e) => setNewAdmin({ ...newAdmin, email: e.target.value })} placeholder="admin@budgetbuddy.com" className="bg-muted/30 border-none" />
              </div>
              <div className="space-y-1.5">
                <Label htmlFor="ap-admin-name" className="text-xs font-bold uppercase text-muted-foreground">Full Name</Label>
                <Input id="ap-admin-name" value={newAdmin.name} onChange={(e) => setNewAdmin({ ...newAdmin, name: e.target.value })} placeholder="John Doe" className="bg-muted/30 border-none" />
              </div>
              <div className="space-y-1.5">
                <Label htmlFor="ap-admin-password" className="text-xs font-bold uppercase text-muted-foreground">Secure Password</Label>
                <Input id="ap-admin-password" type="password" value={newAdmin.password} onChange={(e) => setNewAdmin({ ...newAdmin, password: e.target.value })} placeholder="••••••••" className="bg-muted/30 border-none" />
              </div>
              <div className="space-y-1.5">
                <Label htmlFor="ap-admin-role" className="text-xs font-bold uppercase text-muted-foreground">Permission Level</Label>
                <Select value={newAdmin.role} onValueChange={(value: "admin" | "moderator") => setNewAdmin({ ...newAdmin, role: value })}>
                  <SelectTrigger className="bg-muted/30 border-none"><SelectValue placeholder="Select role" /></SelectTrigger>
                  <SelectContent>
                    <SelectItem value="admin" className="font-bold">Administrator (Full)</SelectItem>
                    <SelectItem value="moderator" className="font-bold">Moderator (Limited)</SelectItem>
                  </SelectContent>
                </Select>
              </div>
            </div>
            <DialogFooter className="gap-2 sm:gap-0 mt-2">
              <Button variant="ghost" onClick={() => setShowCreateAdmin(false)} className="font-bold">Dismiss</Button>
              <Button onClick={handleCreateAdmin} className="shadow-lg shadow-primary/20 font-bold">Create Admin</Button>
            </DialogFooter>
          </DialogContent>
        </Dialog>
        <Button variant="ghost" size="icon" className="h-9 w-9 text-destructive hover:bg-destructive/10" onClick={handleAdminLogout} title="Logout">
          <LogOut className="h-4 w-4" />
        </Button>
      </div>
    </div>
  );

  return (
    <AdminLayout sidebar={sidebarContent} topbar={topbarContent}>
      <div className="space-y-6">
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
        <BackToTop />
      </div>
    </AdminLayout>
  );
}

export default AdminProfile;
