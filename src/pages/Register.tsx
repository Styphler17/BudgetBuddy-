import { useState } from "react";
import { Link, useNavigate } from "react-router-dom";
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Alert, AlertDescription } from "@/components/ui/alert";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import { Eye, EyeOff, Mail, Lock, User } from "lucide-react";
import { userAPI } from "@/lib/api";
import { useToast } from "@/hooks/use-toast";
import logo from "@/assets/BudgetBuddy.png";
import pattern from "@/assets/login-pattern.svg";
import { Header } from "@/components/Header";

export function Register() {
  const [formData, setFormData] = useState({
    name: "",
    email: "",
    password: "",
    confirmPassword: "",
    currency: "USD"
  });
  const [showPassword, setShowPassword] = useState(false);
  const [showConfirmPassword, setShowConfirmPassword] = useState(false);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");
  const { toast } = useToast();
  const navigate = useNavigate();

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);
    setError("");

    // Validation
    if (formData.password !== formData.confirmPassword) {
      setError("Passwords do not match");
      setLoading(false);
      return;
    }

    if (formData.password.length < 6) {
      setError("Password must be at least 6 characters long");
      setLoading(false);
      return;
    }

    try {
      // Check if user already exists
      const existingUser = await userAPI.findByEmail(formData.email);
      if (existingUser) {
        setError("An account with this email already exists");
        setLoading(false);
        return;
      }

      // In a real app, you'd hash the password here
      // For demo purposes, we're storing it as plain text
      await userAPI.create({
        email: formData.email,
        name: formData.name,
        passwordHash: formData.password // In production: hash this with bcrypt
      });

      toast({
        title: "Account created!",
        description: "Your account has been created successfully. Please log in."
      });

      navigate("/login");
    } catch (error) {
      console.error("Registration error:", error);
      setError("An error occurred during registration. Please try again.");
    } finally {
      setLoading(false);
    }
  };

  const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    setFormData({
      ...formData,
      [e.target.name]: e.target.value
    });
  };

  return (
    <div className="relative min-h-screen overflow-hidden bg-slate-950">
      <div className="relative z-20">
        <Header />
      </div>
      <div className="absolute inset-0">
        <div className="absolute inset-0 bg-gradient-to-br from-emerald-900 via-slate-950 to-teal-900" />
        <div className="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(56,189,248,0.3),transparent_55%),radial-gradient(circle_at_bottom_right,rgba(34,197,94,0.35),transparent_60%)]" />
        <div
          className="absolute inset-0 opacity-25 mix-blend-soft-light"
          style={{ backgroundImage: `url(${pattern})`, backgroundSize: "420px", backgroundPosition: "center" }}
        />
        <div className="absolute -left-28 top-1/3 h-72 w-72 rounded-full bg-sky-400/20 blur-3xl" />
        <div className="absolute -right-20 -top-16 h-80 w-80 rounded-full bg-emerald-400/25 blur-[140px]" />
      </div>

      <div className="relative z-10 flex min-h-screen items-center justify-center px-4 py-12 pt-24 lg:pt-28">
        <div className="grid w-full max-w-6xl gap-12 lg:grid-cols-[minmax(0,1fr)_minmax(0,420px)] lg:gap-16">
          <div className="flex flex-col justify-center space-y-10 text-center text-slate-100 lg:text-left">
            <div className="flex flex-col items-center gap-6 lg:items-start">
              <div className="rounded-3xl bg-white/10 p-5 shadow-inner shadow-emerald-300/40 backdrop-blur-lg">
                <img
                  src={logo}
                  alt="BudgetBuddy logo"
                  className="h-20 w-20 rounded-2xl object-cover drop-shadow-[0_18px_55px_rgba(16,185,129,0.45)]"
                />
              </div>
              <div className="max-w-xl space-y-4">
                <p className="text-sm uppercase tracking-[0.35em] text-emerald-200/80">Join BudgetBuddy</p>
                <h1 className="text-3xl font-semibold leading-tight sm:text-4xl">
                  Start building better money habits with tailored insights.
                </h1>
                <p className="text-base text-slate-200/85 sm:text-lg">
                  Create your account to unlock goal tracking, budgeting dashboards, and smart recommendations that keep your finances on track.
                </p>
              </div>
            </div>
            <div className="mx-auto flex w-fit flex-col items-center gap-2 rounded-full bg-white/10 px-7 py-3 text-sm font-medium text-slate-100 backdrop-blur xl:mx-0">
              <span className="uppercase tracking-[0.25em] text-emerald-200/90">Get Started</span>
              <span className="text-slate-200/90">“Plan today so tomorrow’s finances feel effortless.”</span>
            </div>
          </div>

          <Card className="w-full border-white/20 bg-white/10 shadow-[0_30px_60px_rgba(8,47,73,0.45)] backdrop-blur-2xl">
            <CardHeader className="flex flex-col items-center space-y-3 text-center text-slate-100">
              <CardTitle className="text-2xl font-semibold">Create Account</CardTitle>
              <CardDescription className="text-slate-200/80">
                Join BudgetBuddy to start managing your finances
              </CardDescription>
            </CardHeader>
            <CardContent>
              <form onSubmit={handleSubmit} className="space-y-4 text-left">
                {error && (
                  <Alert variant="destructive">
                    <AlertDescription>{error}</AlertDescription>
                  </Alert>
                )}

                <div className="space-y-2">
                  <Label htmlFor="name" className="text-slate-100">
                    Full Name
                  </Label>
                  <div className="relative">
                    <User className="absolute left-3 top-3 h-4 w-4 text-slate-300" />
                    <Input
                      id="name"
                      name="name"
                      type="text"
                      placeholder="Enter your full name"
                      value={formData.name}
                      onChange={handleChange}
                      className="border-white/20 bg-white/85 pl-10 text-slate-900 placeholder:text-slate-500"
                      required
                    />
                  </div>
                </div>

                <div className="space-y-2">
                  <Label htmlFor="email" className="text-slate-100">
                    Email
                  </Label>
                  <div className="relative">
                    <Mail className="absolute left-3 top-3 h-4 w-4 text-slate-300" />
                    <Input
                      id="email"
                      name="email"
                      type="email"
                      placeholder="Enter your email"
                      value={formData.email}
                      onChange={handleChange}
                      className="border-white/20 bg-white/85 pl-10 text-slate-900 placeholder:text-slate-500"
                      required
                    />
                  </div>
                </div>

                <div className="space-y-2">
                  <Label htmlFor="password" className="text-slate-100">
                    Password
                  </Label>
                  <div className="relative">
                    <Lock className="absolute left-3 top-3 h-4 w-4 text-slate-300" />
                    <Input
                      id="password"
                      name="password"
                      type={showPassword ? "text" : "password"}
                      placeholder="Create a password"
                      value={formData.password}
                      onChange={handleChange}
                      className="border-white/20 bg-white/85 pl-10 pr-10 text-slate-900 placeholder:text-slate-500"
                      required
                    />
                    <button
                      type="button"
                      onClick={() => setShowPassword(!showPassword)}
                      className="absolute right-3 top-3 text-slate-500 transition hover:text-slate-700"
                    >
                      {showPassword ? <EyeOff className="h-4 w-4" /> : <Eye className="h-4 w-4" />}
                    </button>
                  </div>
                </div>

                <div className="space-y-2">
                  <Label htmlFor="confirmPassword" className="text-slate-100">
                    Confirm Password
                  </Label>
                  <div className="relative">
                    <Lock className="absolute left-3 top-3 h-4 w-4 text-slate-300" />
                    <Input
                      id="confirmPassword"
                      name="confirmPassword"
                      type={showConfirmPassword ? "text" : "password"}
                      placeholder="Confirm your password"
                      value={formData.confirmPassword}
                      onChange={handleChange}
                      className="border-white/20 bg-white/85 pl-10 pr-10 text-slate-900 placeholder:text-slate-500"
                      required
                    />
                    <button
                      type="button"
                      onClick={() => setShowConfirmPassword(!showConfirmPassword)}
                      className="absolute right-3 top-3 text-slate-500 transition hover:text-slate-700"
                    >
                      {showConfirmPassword ? <EyeOff className="h-4 w-4" /> : <Eye className="h-4 w-4" />}
                    </button>
                  </div>
                </div>

                <div className="space-y-2">
                  <Label htmlFor="currency" className="text-slate-100">
                    Preferred Currency
                  </Label>
                  <Select value={formData.currency} onValueChange={(value) => setFormData({ ...formData, currency: value })}>
                    <SelectTrigger className="border-white/20 bg-white/85 text-slate-900">
                      <SelectValue placeholder="Select currency" />
                    </SelectTrigger>
                    <SelectContent className="bg-white text-slate-900">
                      <SelectItem value="USD">USD - US Dollar</SelectItem>
                      <SelectItem value="EUR">EUR - Euro</SelectItem>
                      <SelectItem value="GBP">GBP - British Pound</SelectItem>
                      <SelectItem value="JPY">JPY - Japanese Yen</SelectItem>
                      <SelectItem value="CAD">CAD - Canadian Dollar</SelectItem>
                      <SelectItem value="AUD">AUD - Australian Dollar</SelectItem>
                      <SelectItem value="GHS">GHS - Ghanaian Cedi</SelectItem>
                      <SelectItem value="NGN">NGN - Nigerian Naira</SelectItem>
                    </SelectContent>
                  </Select>
                </div>

                <Button
                  type="submit"
                  className="w-full bg-emerald-500 text-white shadow-lg shadow-emerald-500/25 transition hover:bg-emerald-400 focus-visible:ring-emerald-200"
                  disabled={loading}
                >
                  {loading ? "Creating Account..." : "Create Account"}
                </Button>
              </form>

              <div className="mt-6 text-center">
                <p className="text-sm text-slate-200/85">
                  Already have an account?{" "}
                  <Link to="/login" className="font-medium text-emerald-200 hover:text-emerald-100">
                    Sign in
                  </Link>
                </p>
              </div>
            </CardContent>
          </Card>
        </div>
      </div>
    </div>
  );
}
