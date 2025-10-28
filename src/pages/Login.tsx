import { useState } from "react";
import { Link, useNavigate } from "react-router-dom";
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Alert, AlertDescription } from "@/components/ui/alert";
import { Eye, EyeOff, Mail, Lock } from "lucide-react";
import { userAPI } from "@/lib/api";
import { useToast } from "@/hooks/use-toast";
import logo from "@/assets/BudgetBuddy.png";
import pattern from "@/assets/login-pattern.svg";

export function Login() {
  const [formData, setFormData] = useState({
    email: "",
    password: ""
  });
  const [showPassword, setShowPassword] = useState(false);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");
  const { toast } = useToast();
  const navigate = useNavigate();

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);
    setError("");

    try {
      // In a real app, you'd hash the password and verify against database
      const user = await userAPI.findByEmail(formData.email);

      if (!user) {
        setError("Invalid email or password");
        return;
      }

      // For demo purposes, we'll just check if password matches (not hashed)
      // In production, use proper password hashing like bcrypt
      if (user.password_hash !== formData.password) {
        setError("Invalid email or password");
        return;
      }

      // Store user session (in a real app, use JWT or session management)
      localStorage.setItem("user", JSON.stringify({
        id: user.id,
        email: user.email,
        name: user.name,
        currency: user.currency
      }));

      toast({
        title: "Welcome back!",
        description: `Hello ${user.name}, you've been logged in successfully.`
      });

      navigate("/app/dashboard");
    } catch (error) {
      console.error("Login error:", error);
      setError("An error occurred during login. Please try again.");
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
      <div className="absolute inset-0">
        <div className="absolute inset-0 bg-gradient-to-br from-teal-900 via-slate-950 to-emerald-950" />
        <div className="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(56,189,248,0.35),transparent_55%),radial-gradient(circle_at_bottom_right,rgba(34,197,94,0.3),transparent_60%)]" />
        <div
          className="absolute inset-0 opacity-25 mix-blend-soft-light"
          style={{ backgroundImage: `url(${pattern})`, backgroundSize: "420px", backgroundPosition: "center" }}
        />
        <div className="absolute -left-24 top-1/3 h-72 w-72 rounded-full bg-emerald-400/25 blur-3xl" />
        <div className="absolute -right-16 -top-16 h-80 w-80 rounded-full bg-sky-400/25 blur-[140px]" />
      </div>

      <div className="relative z-10 flex min-h-screen items-center justify-center px-4 py-12">
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
                <p className="text-sm uppercase tracking-[0.35em] text-emerald-200/80">BudgetBuddy</p>
                <h1 className="text-3xl font-semibold leading-tight sm:text-4xl">
                  Empower your finances with clarity and confidence.
                </h1>
                <p className="text-base text-slate-200/85 sm:text-lg">
                  BudgetBuddy helps you visualize spending, plan smarter budgets, and stay in control with real-time
                  insights designed for peace of mind.
                </p>
              </div>
            </div>
            <div className="mx-auto flex w-fit flex-col items-center gap-2 rounded-full bg-white/10 px-7 py-3 text-sm font-medium text-slate-100 backdrop-blur xl:mx-0">
              <span className="uppercase tracking-[0.25em] text-emerald-200/90">Trusted Guidance</span>
              <span className="text-slate-200/90">“Every budget is a step toward financial freedom.”</span>
            </div>
          </div>

          <Card className="w-full border-white/20 bg-white/10 shadow-[0_30px_60px_rgba(8,47,73,0.45)] backdrop-blur-2xl">
            <CardHeader className="flex flex-col items-center space-y-4 text-center text-slate-100">
              <img
                src={logo}
                alt="BudgetBuddy logo"
                className="h-16 w-16 rounded-2xl object-cover shadow-[0_18px_45px_rgba(16,185,129,0.45)]"
              />
              <CardTitle className="text-2xl font-semibold">Welcome Back</CardTitle>
              <CardDescription className="text-slate-200/80">
                Sign in to your BudgetBuddy account
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
                      placeholder="Enter your password"
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

                <Button
                  type="submit"
                  className="w-full bg-emerald-500 text-white shadow-lg shadow-emerald-500/25 transition hover:bg-emerald-400 focus-visible:ring-emerald-200"
                  disabled={loading}
                >
                  {loading ? "Signing in..." : "Sign In"}
                </Button>
              </form>

              <div className="mt-6 text-center">
                <p className="text-sm text-slate-200/85">
                  Don't have an account?{" "}
                  <Link to="/register" className="font-medium text-emerald-200 hover:text-emerald-100">
                    Sign up
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
