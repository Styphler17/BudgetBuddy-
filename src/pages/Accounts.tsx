import { useState, useEffect } from "react";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Badge } from "@/components/ui/badge";
import { Plus, CreditCard, PiggyBank, Wallet, Edit, Trash2 } from "lucide-react";
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle, DialogTrigger } from "@/components/ui/dialog";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from "@/components/ui/dropdown-menu";
import { toast } from "sonner";
import { accountAPI } from "@/lib/api";
import storageService from "@/lib/storage";

type Period = "daily" | "weekly" | "monthly" | "yearly";

interface AccountsProps {
  period: Period;
}

interface Account {
  id: string;
  name: string;
  type: "checking" | "savings" | "cash" | "credit" | "investment";
  balance: number;
  accountNumber?: string;
  isActive: boolean;
}

interface DatabaseAccount {
  id: number;
  user_id: number;
  name: string;
  type: "checking" | "savings" | "cash" | "credit" | "investment";
  balance: string;
  account_number: string | null;
  is_active: boolean;
  created_at: string;
}

interface AccountCreateData {
  userId: number;
  name: string;
  type: "checking" | "savings" | "cash" | "credit" | "investment";
  balance?: number;
  accountNumber?: string | null;
}

interface AccountUpdateData {
  name?: string;
  type?: "checking" | "savings" | "cash" | "credit" | "investment";
  balance?: number;
  accountNumber?: string | null;
}

export default function Accounts({ period }: AccountsProps) {
  const [accounts, setAccounts] = useState<Account[]>([]);
  const [dialogOpen, setDialogOpen] = useState(false);
  const [editDialogOpen, setEditDialogOpen] = useState(false);
  const [editingAccount, setEditingAccount] = useState<Account | null>(null);
  const [newAccountName, setNewAccountName] = useState("");
  const [newAccountType, setNewAccountType] = useState<Account["type"]>("checking");
  const [newAccountBalance, setNewAccountBalance] = useState("");
  const [newAccountNumber, setNewAccountNumber] = useState("");

  // Fetch live data from database
  useEffect(() => {
    const fetchAccountsData = async () => {
      const user = JSON.parse(storageService.getItem("user") || "null");
      if (!user) return;

      try {
        const userAccounts = await accountAPI.findByUserId(user.id) as DatabaseAccount[];
        const displayAccounts = userAccounts.map((account: DatabaseAccount) => ({
          id: account.id.toString(),
          name: account.name,
          type: account.type,
          balance: parseFloat(account.balance) || 0,
          accountNumber: account.account_number || undefined,
          isActive: account.is_active
        }));
        setAccounts(displayAccounts);
      } catch (error) {
        console.error('Error fetching accounts data:', error);
      }
    };

    fetchAccountsData();

    // Set up polling for live updates (every 5 seconds)
    const interval = setInterval(fetchAccountsData, 5000);

    return () => clearInterval(interval);
  }, []);

  const handleAddAccount = async () => {
    if (!newAccountName || !newAccountBalance) {
      toast.error("Please fill in all required fields");
      return;
    }
    const balanceAmount = parseFloat(newAccountBalance);
    if (isNaN(balanceAmount)) {
      toast.error("Please enter a valid balance amount");
      return;
    }

    const user = JSON.parse(storageService.getItem("user") || "null");
    if (!user) {
      toast.error("Please log in to add accounts");
      return;
    }

    try {
      await accountAPI.create({
        userId: user.id,
        name: newAccountName,
        type: newAccountType as "checking" | "savings" | "credit" | "investment",
        balance: balanceAmount
      });

      toast.success("Account added successfully!");
      setDialogOpen(false);
      setNewAccountName("");
      setNewAccountType("checking" as Account["type"]);
      setNewAccountBalance("");
      setNewAccountNumber("");

      // Refresh accounts data
      const userAccounts = await accountAPI.findByUserId(user.id) as DatabaseAccount[];
      const displayAccounts = userAccounts.map((account: DatabaseAccount) => ({
        id: account.id.toString(),
        name: account.name,
        type: account.type,
        balance: parseFloat(account.balance) || 0,
        accountNumber: account.account_number || undefined,
        isActive: account.is_active
      }));
      setAccounts(displayAccounts);

    } catch (error) {
      console.error('Error adding account:', error);
      toast.error("Failed to add account");
    }
  };

  const handleEditAccount = (account: Account) => {
    setEditingAccount(account);
    setNewAccountName(account.name);
    setNewAccountType(account.type);
    setNewAccountBalance(account.balance.toString());
    setNewAccountNumber(account.accountNumber || "");
    setEditDialogOpen(true);
  };

  const handleUpdateAccount = async () => {
    if (!editingAccount || !newAccountName || !newAccountBalance) {
      toast.error("Please fill in all required fields");
      return;
    }
    const balanceAmount = parseFloat(newAccountBalance);
    if (isNaN(balanceAmount)) {
      toast.error("Please enter a valid balance amount");
      return;
    }

    const user = JSON.parse(storageService.getItem("user") || "null");
    if (!user) {
      toast.error("Please log in to update accounts");
      return;
    }

    try {
      await accountAPI.update(parseInt(editingAccount.id), {
        name: newAccountName,
        type: newAccountType as "checking" | "savings" | "credit" | "investment",
        balance: balanceAmount
      });

      toast.success("Account updated successfully!");
      setEditDialogOpen(false);
      setEditingAccount(null);
      setNewAccountName("");
      setNewAccountType("checking" as Account["type"]);
      setNewAccountBalance("");
      setNewAccountNumber("");

      // Refresh accounts data
      const userAccounts = await accountAPI.findByUserId(user.id) as DatabaseAccount[];
      const displayAccounts = userAccounts.map((account: DatabaseAccount) => ({
        id: account.id.toString(),
        name: account.name,
        type: account.type,
        balance: parseFloat(account.balance) || 0,
        accountNumber: account.account_number || undefined,
        isActive: account.is_active
      }));
      setAccounts(displayAccounts);

    } catch (error) {
      console.error('Error updating account:', error);
      toast.error("Failed to update account");
    }
  };

  const handleDeleteAccount = async (accountId: string) => {
    const user = JSON.parse(storageService.getItem("user") || "null");
    if (!user) {
      toast.error("Please log in to delete accounts");
      return;
    }

    try {
      await accountAPI.delete(parseInt(accountId));
      toast.success("Account deleted successfully!");

      // Refresh accounts data
      const userAccounts = await accountAPI.findByUserId(user.id) as DatabaseAccount[];
      const displayAccounts = userAccounts.map((account: DatabaseAccount) => ({
        id: account.id.toString(),
        name: account.name,
        type: account.type,
        balance: parseFloat(account.balance) || 0,
        accountNumber: account.account_number || undefined,
        isActive: account.is_active
      }));
      setAccounts(displayAccounts);

    } catch (error) {
      console.error('Error deleting account:', error);
      toast.error("Failed to delete account");
    }
  };

  const getAccountIcon = (type: Account["type"]) => {
    switch (type) {
      case "checking":
        return <CreditCard className="h-4 w-4 sm:h-5 sm:w-5 text-primary flex-shrink-0" />;
      case "savings":
        return <PiggyBank className="h-4 w-4 sm:h-5 sm:w-5 text-secondary flex-shrink-0" />;
      case "cash":
        return <Wallet className="h-4 w-4 sm:h-5 sm:w-5 text-accent flex-shrink-0" />;
      case "credit":
        return <CreditCard className="h-4 w-4 sm:h-5 sm:w-5 text-destructive flex-shrink-0" />;
      case "investment":
        return <PiggyBank className="h-4 w-4 sm:h-5 sm:w-5 text-warning flex-shrink-0" />;
      default:
        return <Wallet className="h-4 w-4 sm:h-5 sm:w-5 text-muted-foreground flex-shrink-0" />;
    }
  };

  return (
    <div className="p-4 sm:p-6 space-y-6">
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h1 className="text-2xl sm:text-3xl font-heading font-bold text-foreground">Accounts</h1>
          <p className="text-muted-foreground font-body text-sm sm:text-base">Manage your financial accounts for {period} period</p>
        </div>
        <Dialog open={dialogOpen} onOpenChange={setDialogOpen}>
          <DialogTrigger asChild>
            <Button className="bg-primary hover:bg-primary/90 w-full sm:w-auto">
              <Plus className="h-4 w-4 mr-2" />
              Add Account
            </Button>
          </DialogTrigger>
          <DialogContent className="sm:max-w-md">
            <DialogHeader>
              <DialogTitle className="font-heading">Add New Account</DialogTitle>
              <DialogDescription className="font-body">
                Add a new financial account to track your money.
              </DialogDescription>
            </DialogHeader>
            <div className="space-y-4 py-4">
              <div className="space-y-2">
                <Label htmlFor="account-name" className="font-body font-medium">
                  Account Name
                </Label>
                <Input
                  id="account-name"
                  placeholder="e.g. Main Checking, Savings Account"
                  value={newAccountName}
                  onChange={(e) => setNewAccountName(e.target.value)}
                  className="font-body"
                />
              </div>
              <div className="space-y-2">
                <Label htmlFor="account-type" className="font-body font-medium">
                  Account Type
                </Label>
                <Select value={newAccountType} onValueChange={(value: Account["type"]) => setNewAccountType(value)}>
                  <SelectTrigger className="font-body">
                    <SelectValue placeholder="Select account type" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="checking">Checking</SelectItem>
                    <SelectItem value="savings">Savings</SelectItem>
                    <SelectItem value="cash">Cash</SelectItem>
                    <SelectItem value="credit">Credit Card</SelectItem>
                    <SelectItem value="investment">Investment</SelectItem>
                  </SelectContent>
                </Select>
              </div>
              <div className="space-y-2">
                <Label htmlFor="account-balance" className="font-body font-medium">
                  Initial Balance
                </Label>
                <div className="relative">
                  <span className="absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground">$</span>
                  <Input
                    id="account-balance"
                    type="number"
                    placeholder="0.00"
                    value={newAccountBalance}
                    onChange={(e) => setNewAccountBalance(e.target.value)}
                    className="pl-7 font-body"
                    step="0.01"
                  />
                </div>
              </div>
              <div className="space-y-2">
                <Label htmlFor="account-number" className="font-body font-medium">
                  Account Number (Optional)
                </Label>
                <Input
                  id="account-number"
                  placeholder="**** 1234"
                  value={newAccountNumber}
                  onChange={(e) => setNewAccountNumber(e.target.value)}
                  className="font-body"
                />
              </div>
            </div>
            <div className="flex justify-end gap-3">
              <Button variant="outline" onClick={() => setDialogOpen(false)} className="font-body">
                Cancel
              </Button>
              <Button onClick={handleAddAccount} className="font-body">
                Add Account
              </Button>
            </div>
          </DialogContent>
        </Dialog>

        <Dialog open={editDialogOpen} onOpenChange={setEditDialogOpen}>
          <DialogContent className="sm:max-w-md">
            <DialogHeader>
              <DialogTitle className="font-heading">Edit Account</DialogTitle>
              <DialogDescription className="font-body">
                Update your account details.
              </DialogDescription>
            </DialogHeader>
            <div className="space-y-4 py-4">
              <div className="space-y-2">
                <Label htmlFor="edit-account-name" className="font-body font-medium">
                  Account Name
                </Label>
                <Input
                  id="edit-account-name"
                  placeholder="e.g. Main Checking, Savings Account"
                  value={newAccountName}
                  onChange={(e) => setNewAccountName(e.target.value)}
                  className="font-body"
                />
              </div>
              <div className="space-y-2">
                <Label htmlFor="edit-account-type" className="font-body font-medium">
                  Account Type
                </Label>
                <Select value={newAccountType} onValueChange={(value: Account["type"]) => setNewAccountType(value)}>
                  <SelectTrigger className="font-body">
                    <SelectValue placeholder="Select account type" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="checking">Checking</SelectItem>
                    <SelectItem value="savings">Savings</SelectItem>
                    <SelectItem value="cash">Cash</SelectItem>
                    <SelectItem value="credit">Credit Card</SelectItem>
                    <SelectItem value="investment">Investment</SelectItem>
                  </SelectContent>
                </Select>
              </div>
              <div className="space-y-2">
                <Label htmlFor="edit-account-balance" className="font-body font-medium">
                  Balance
                </Label>
                <div className="relative">
                  <span className="absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground">$</span>
                  <Input
                    id="edit-account-balance"
                    type="number"
                    placeholder="0.00"
                    value={newAccountBalance}
                    onChange={(e) => setNewAccountBalance(e.target.value)}
                    className="pl-7 font-body"
                    step="0.01"
                  />
                </div>
              </div>
              <div className="space-y-2">
                <Label htmlFor="edit-account-number" className="font-body font-medium">
                  Account Number (Optional)
                </Label>
                <Input
                  id="edit-account-number"
                  placeholder="**** 1234"
                  value={newAccountNumber}
                  onChange={(e) => setNewAccountNumber(e.target.value)}
                  className="font-body"
                />
              </div>
            </div>
            <div className="flex justify-end gap-3">
              <Button variant="outline" onClick={() => setEditDialogOpen(false)} className="font-body">
                Cancel
              </Button>
              <Button onClick={handleUpdateAccount} className="font-body">
                Update Account
              </Button>
            </div>
          </DialogContent>
        </Dialog>
      </div>

      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
        {accounts.length === 0 ? (
          <div className="col-span-full text-center py-12">
            <Wallet className="h-12 w-12 text-muted-foreground mx-auto mb-4" />
            <h3 className="text-lg font-heading font-medium text-foreground mb-2">No accounts yet</h3>
            <p className="text-muted-foreground font-body">Add your first account to start tracking your finances.</p>
          </div>
        ) : (
          accounts.map((account) => (
            <Card key={account.id}>
              <CardHeader className="pb-3">
                <div className="flex items-start justify-between">
                  <CardTitle className="font-heading flex items-center gap-2 text-base sm:text-lg">
                    {getAccountIcon(account.type)}
                    <span className="truncate">{account.name}</span>
                  </CardTitle>
                  <DropdownMenu>
                    <DropdownMenuTrigger asChild>
                      <Button variant="ghost" size="sm" className="h-8 w-8 p-0 flex-shrink-0">
                        <span className="sr-only">Open menu</span>
                        <Edit className="h-4 w-4" />
                      </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent align="end">
                      <DropdownMenuItem onClick={() => handleEditAccount(account)}>
                        <Edit className="mr-2 h-4 w-4" />
                        Edit Account
                      </DropdownMenuItem>
                      <DropdownMenuItem onClick={() => handleDeleteAccount(account.id)} className="text-destructive">
                        <Trash2 className="mr-2 h-4 w-4" />
                        Delete Account
                      </DropdownMenuItem>
                    </DropdownMenuContent>
                  </DropdownMenu>
                </div>
              </CardHeader>
              <CardContent className="space-y-3 sm:space-y-4">
                <div className="text-xl sm:text-2xl font-bold text-foreground">
                  ${account.balance.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
                </div>
                <Badge
                  variant="secondary"
                  className={`text-xs ${account.isActive ? 'bg-success/10 text-success' : 'bg-muted text-muted-foreground'}`}
                >
                  {account.isActive ? 'Active' : 'Inactive'}
                </Badge>
                <p className="text-xs text-muted-foreground">
                  {account.accountNumber ? `**** ${account.accountNumber.slice(-4)}` : 'No account number'}
                </p>
              </CardContent>
            </Card>
          ))
        )}
      </div>
    </div>
  );
}
