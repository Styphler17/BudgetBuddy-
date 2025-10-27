import { useState, useEffect } from "react";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Switch } from "@/components/ui/switch";
import { Label } from "@/components/ui/label";
import { Separator } from "@/components/ui/separator";
import { Input } from "@/components/ui/input";
import { User, Bell, Shield, Palette, Eye, EyeOff } from "lucide-react";
import { toast } from "sonner";
import { settingsAPI, userAPI } from "@/lib/api";

type Period = "daily" | "weekly" | "monthly" | "yearly";

interface SettingsProps {
  period: Period;
}

interface DatabaseUser {
  id: number;
  username: string;
  email: string;
  first_name: string | null;
  last_name: string | null;
  currency: string;
  password_hash?: string;
  created_at: string;
}

interface DatabaseSettings {
  id: number;
  user_id: number;
  email_notifications: boolean;
  budget_alerts: boolean;
  goal_reminders: boolean;
  dark_mode: boolean;
  currency: string;
  created_at: string;
  updated_at: string;
}

export default function Settings({ period }: SettingsProps) {
  const [user, setUser] = useState<DatabaseUser | null>(null);
  const [settings, setSettings] = useState<DatabaseSettings | null>(null);
  const [isEditingProfile, setIsEditingProfile] = useState(false);
  const [editFirstName, setEditFirstName] = useState("");
  const [editLastName, setEditLastName] = useState("");
  const [editEmail, setEditEmail] = useState("");
  const [editPassword, setEditPassword] = useState("");
  const [oldPassword, setOldPassword] = useState("");
  const [showPassword, setShowPassword] = useState(false);
  const [showOldPassword, setShowOldPassword] = useState(false);
  const [isChangingPassword, setIsChangingPassword] = useState(false);

  // Fetch user data and settings on mount
  useEffect(() => {
    const fetchUserData = async () => {
      const currentUser = JSON.parse(localStorage.getItem("user") || "null");
      if (!currentUser) return;

      try {
        // Fetch user data from database
        let userData: DatabaseUser | null = null;
        try {
          userData = await userAPI.findById(currentUser.id) as DatabaseUser;
        } catch (error) {
          console.error('Database fetch failed, using localStorage:', error);
        }

        // If no database data, fall back to localStorage
        const fallbackUserData: DatabaseUser = {
          id: currentUser.id,
          username: currentUser.username || currentUser.email,
          email: currentUser.email,
          first_name: currentUser.first_name || currentUser.name?.split(' ')[0] || null,
          last_name: currentUser.last_name || currentUser.name?.split(' ').slice(1).join(' ') || null,
          currency: currentUser.currency || 'USD',
          password_hash: currentUser.password_hash || currentUser.password || "",
          created_at: currentUser.created_at || new Date().toISOString()
        };

        const finalUserData = userData || fallbackUserData;

        // Get user settings from localStorage or create defaults
        const userSettingsKey = `settings_${currentUser.id}`;
        const storedSettings = localStorage.getItem(userSettingsKey);
        let userSettings: DatabaseSettings;

        if (storedSettings) {
          userSettings = JSON.parse(storedSettings);
        } else {
          // Create default settings
          userSettings = {
            id: Date.now(),
            user_id: currentUser.id,
            email_notifications: true,
            budget_alerts: true,
            goal_reminders: false,
            dark_mode: false,
            currency: userData.currency,
            created_at: new Date().toISOString(),
            updated_at: new Date().toISOString()
          };
          localStorage.setItem(userSettingsKey, JSON.stringify(userSettings));
        }

        setUser(finalUserData);
        setSettings(userSettings);
        setEditFirstName(finalUserData.first_name || "");
        setEditLastName(finalUserData.last_name || "");
        setEditEmail(finalUserData.email);
        setEditPassword(finalUserData.password_hash || "");

        // Debug logging to verify password fetching
        console.log('User data fetched:', finalUserData);
        console.log('Password hash:', finalUserData.password_hash);
      } catch (error) {
        console.error('Error fetching user data:', error);
      }
    };

    fetchUserData();
  }, []);

  const handleUpdateProfile = async () => {
    if (!user) return;

    try {
      // Update database
      await userAPI.update(user.id, {
        first_name: editFirstName,
        last_name: editLastName,
        email: editEmail
      });

      // Update localStorage user data
      const currentUser = JSON.parse(localStorage.getItem("user") || "null");
      const updatedUser = {
        ...currentUser,
        first_name: editFirstName,
        last_name: editLastName,
        email: editEmail
      };

      localStorage.setItem("user", JSON.stringify(updatedUser));

      // Update state
      setUser(prev => prev ? {
        ...prev,
        first_name: editFirstName,
        last_name: editLastName,
        email: editEmail
      } : null);

      toast.success("Profile updated successfully!");
      setIsEditingProfile(false);
    } catch (error) {
      console.error('Error updating profile:', error);
      toast.error("Failed to update profile");
    }
  };

  const handleUpdatePassword = async () => {
    if (!user || !editPassword) return;

    // Verify old password if user has an existing password
    if (user.password_hash && oldPassword !== user.password_hash) {
      toast.error("Old password is incorrect");
      return;
    }

    try {
      // Update database
      await userAPI.update(user.id, {
        password_hash: editPassword
      });

      // Update localStorage user data
      const currentUser = JSON.parse(localStorage.getItem("user") || "null");
      const updatedUser = {
        ...currentUser,
        password_hash: editPassword,
        password: editPassword // Keep both for compatibility
      };

      localStorage.setItem("user", JSON.stringify(updatedUser));

      // Update state
      setUser(prev => prev ? {
        ...prev,
        password_hash: editPassword
      } : null);

      toast.success("Password updated successfully!");
      setIsChangingPassword(false);
      setEditPassword("");
      setOldPassword("");
    } catch (error) {
      console.error('Error updating password:', error);
      toast.error("Failed to update password");
    }
  };

  const handleUpdateSettings = async (key: keyof DatabaseSettings, value: boolean | string) => {
    if (!settings || !user) return;

    try {
      const updatedSettings = { ...settings, [key]: value, updated_at: new Date().toISOString() };
      const userSettingsKey = `settings_${user.id}`;
      localStorage.setItem(userSettingsKey, JSON.stringify(updatedSettings));

      setSettings(updatedSettings);
      toast.success("Settings updated successfully!");
    } catch (error) {
      console.error('Error updating settings:', error);
      toast.error("Failed to update settings");
    }
  };

  return (
    <div className="p-4 sm:p-6 space-y-6">
      <div>
        <h1 className="text-2xl sm:text-3xl font-heading font-bold text-foreground">Settings</h1>
        <p className="text-muted-foreground font-body text-sm sm:text-base">Customize your BudgetBuddy experience for {period} period</p>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
        <Card>
          <CardHeader className="pb-3">
            <CardTitle className="font-heading flex items-center gap-2 text-base sm:text-lg">
              <User className="h-4 w-4 sm:h-5 sm:w-5 text-primary flex-shrink-0" />
              <span className="truncate">Profile Settings</span>
            </CardTitle>
          </CardHeader>
          <CardContent className="space-y-3 sm:space-y-4">
            {isEditingProfile ? (
              <>
                <div className="space-y-2">
                  <Label htmlFor="firstName">First Name</Label>
                  <Input
                    id="firstName"
                    value={editFirstName}
                    onChange={(e) => setEditFirstName(e.target.value)}
                    placeholder="Enter first name"
                  />
                </div>
                <div className="space-y-2">
                  <Label htmlFor="lastName">Last Name</Label>
                  <Input
                    id="lastName"
                    value={editLastName}
                    onChange={(e) => setEditLastName(e.target.value)}
                    placeholder="Enter last name"
                  />
                </div>
                <div className="space-y-2">
                  <Label htmlFor="email">Email</Label>
                  <Input
                    id="email"
                    type="email"
                    value={editEmail}
                    onChange={(e) => setEditEmail(e.target.value)}
                    placeholder="Enter email"
                  />
                </div>
                <div className="flex gap-2">
                  <Button onClick={handleUpdateProfile} size="sm">Save</Button>
                  <Button variant="outline" onClick={() => setIsEditingProfile(false)} size="sm">Cancel</Button>
                </div>
              </>
            ) : (
              <>
                <div className="space-y-2">
                  <Label>Display Name</Label>
                  <p className="text-sm text-muted-foreground">
                    {user ? `${user.first_name || ''} ${user.last_name || ''}`.trim() || user.username : 'Loading...'}
                  </p>
                </div>
                <div className="space-y-2">
                  <Label>Email</Label>
                  <p className="text-sm text-muted-foreground">{user?.email || 'Loading...'}</p>
                </div>
                <Button variant="outline" size="sm" onClick={() => setIsEditingProfile(true)} className="w-full sm:w-auto">Edit Profile</Button>
              </>
            )}
          </CardContent>
        </Card>

        <Card>
          <CardHeader className="pb-3">
            <CardTitle className="font-heading flex items-center gap-2 text-base sm:text-lg">
              <Bell className="h-4 w-4 sm:h-5 sm:w-5 text-secondary flex-shrink-0" />
              <span className="truncate">Notification Preferences</span>
            </CardTitle>
          </CardHeader>
          <CardContent className="space-y-3 sm:space-y-4">
            <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
              <Label htmlFor="email-notifications">Email Notifications</Label>
              <Switch
                id="email-notifications"
                checked={settings?.email_notifications ?? true}
                onCheckedChange={(checked) => handleUpdateSettings('email_notifications', checked)}
              />
            </div>
            <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
              <Label htmlFor="budget-alerts">Budget Alerts</Label>
              <Switch
                id="budget-alerts"
                checked={settings?.budget_alerts ?? true}
                onCheckedChange={(checked) => handleUpdateSettings('budget_alerts', checked)}
              />
            </div>
            <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
              <Label htmlFor="goal-reminders">Goal Reminders</Label>
              <Switch
                id="goal-reminders"
                checked={settings?.goal_reminders ?? false}
                onCheckedChange={(checked) => handleUpdateSettings('goal_reminders', checked)}
              />
            </div>
          </CardContent>
        </Card>

        <Card>
          <CardHeader className="pb-3">
            <CardTitle className="font-heading flex items-center gap-2 text-base sm:text-lg">
              <Shield className="h-4 w-4 sm:h-5 sm:w-5 text-accent flex-shrink-0" />
              <span className="truncate">Security</span>
            </CardTitle>
          </CardHeader>
          <CardContent className="space-y-3 sm:space-y-4">
            {isChangingPassword ? (
              <>
                {user?.password_hash && (
                  <div className="space-y-2">
                    <Label htmlFor="old-password">Current Password</Label>
                    <div className="relative">
                      <Input
                        id="old-password"
                        type={showOldPassword ? "text" : "password"}
                        value={oldPassword}
                        onChange={(e) => setOldPassword(e.target.value)}
                        placeholder="Enter current password"
                        className="pr-10"
                      />
                      <Button
                        type="button"
                        variant="ghost"
                        size="sm"
                        className="absolute right-0 top-0 h-full px-3 py-2 hover:bg-transparent"
                        onClick={() => setShowOldPassword(!showOldPassword)}
                        aria-label={showOldPassword ? "Hide old password" : "Show old password"}
                      >
                        {showOldPassword ? (
                          <EyeOff className="h-4 w-4" />
                        ) : (
                          <Eye className="h-4 w-4" />
                        )}
                      </Button>
                    </div>
                  </div>
                )}
                <div className="space-y-2">
                  <Label htmlFor="new-password">New Password</Label>
                  <div className="relative">
                    <Input
                      id="new-password"
                      type={showPassword ? "text" : "password"}
                      value={editPassword}
                      onChange={(e) => setEditPassword(e.target.value)}
                      placeholder="Enter new password"
                      className="pr-10"
                    />
                    <Button
                      type="button"
                      variant="ghost"
                      size="sm"
                      className="absolute right-0 top-0 h-full px-3 py-2 hover:bg-transparent"
                      onClick={() => setShowPassword(!showPassword)}
                      aria-label={showPassword ? "Hide password" : "Show password"}
                    >
                      {showPassword ? (
                        <EyeOff className="h-4 w-4" />
                      ) : (
                        <Eye className="h-4 w-4" />
                      )}
                    </Button>
                  </div>
                </div>
                <div className="flex gap-2">
                  <Button onClick={handleUpdatePassword} size="sm">Update Password</Button>
                  <Button variant="outline" onClick={() => {
                    setIsChangingPassword(false);
                    setOldPassword("");
                    setEditPassword("");
                  }} size="sm">Cancel</Button>
                </div>
              </>
            ) : (
              <>
                <div className="space-y-2">
                  <Label>Current Password</Label>
                  <div className="flex items-center gap-2">
                    <Input
                      type={showPassword ? "text" : "password"}
                      value={user?.password_hash || ""}
                      readOnly
                      className="flex-1"
                    />
                    <Button
                      type="button"
                      variant="ghost"
                      size="sm"
                      onClick={() => setShowPassword(!showPassword)}
                      aria-label={showPassword ? "Hide password" : "Show password"}
                    >
                      {showPassword ? (
                        <EyeOff className="h-4 w-4" />
                      ) : (
                        <Eye className="h-4 w-4" />
                      )}
                    </Button>
                  </div>
                  <p className="text-xs text-muted-foreground">
                    {user?.password_hash ? "Click the eye icon to view your current password" : "No password set"}
                  </p>
                </div>
                <div className="space-y-2">
                  <Label>Two-Factor Authentication</Label>
                  <p className="text-sm text-muted-foreground">Not enabled</p>
                </div>
                <Button
                  variant="outline"
                  size="sm"
                  onClick={() => setIsChangingPassword(true)}
                  className="w-full sm:w-auto"
                >
                  Change Password
                </Button>
              </>
            )}
          </CardContent>
        </Card>

        <Card>
          <CardHeader className="pb-3">
            <CardTitle className="font-heading flex items-center gap-2 text-base sm:text-lg">
              <Palette className="h-4 w-4 sm:h-5 sm:w-5 text-warning flex-shrink-0" />
              <span className="truncate">Appearance</span>
            </CardTitle>
          </CardHeader>
          <CardContent className="space-y-3 sm:space-y-4">
            <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
              <Label htmlFor="dark-mode">Dark Mode</Label>
              <Switch
                id="dark-mode"
                checked={settings?.dark_mode ?? false}
                onCheckedChange={(checked) => handleUpdateSettings('dark_mode', checked)}
              />
            </div>
            <div className="space-y-2">
              <Label htmlFor="currency-select">Currency</Label>
              <select
                id="currency-select"
                name="currency"
                className="w-full p-2 border border-input rounded-md bg-background text-sm"
                value={settings?.currency ?? user?.currency ?? 'USD'}
                onChange={(e) => handleUpdateSettings('currency', e.target.value)}
                aria-label="Select currency"
              >
                <option value="USD">USD ($) - US Dollar</option>
                <option value="EUR">EUR (€) - Euro</option>
                <option value="GHS">GHS (₵) - Ghanaian Cedi</option>
                <option value="NGN">NGN (₦) - Nigerian Naira</option>
              </select>
            </div>
            <Button variant="outline" size="sm" className="w-full sm:w-auto">Customize Theme</Button>
          </CardContent>
        </Card>
      </div>

      <Card>
        <CardHeader className="pb-3">
          <CardTitle className="font-heading text-base sm:text-lg">Data Management</CardTitle>
        </CardHeader>
        <CardContent className="space-y-3 sm:space-y-4">
          <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div className="flex-1">
              <Label>Export Data</Label>
              <p className="text-sm text-muted-foreground">Download all your financial data</p>
            </div>
            <Button variant="outline" size="sm" className="w-full sm:w-auto">Export</Button>
          </div>
          <Separator />
          <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div className="flex-1">
              <Label className="text-destructive">Delete Account</Label>
              <p className="text-sm text-muted-foreground">Permanently delete your account and all data</p>
            </div>
            <Button variant="destructive" size="sm" className="w-full sm:w-auto">Delete</Button>
          </div>
        </CardContent>
      </Card>
    </div>
  );
}
