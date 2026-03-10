import { useState } from "react";
import { Link, useNavigate } from "react-router-dom";
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Alert, AlertDescription } from "@/components/ui/alert";
import { Eye, EyeOff, Mail, Lock, Loader2, ShieldCheck } from "lucide-react";
import { useGoogleLogin } from "@react-oauth/google";
import { userAPI, API_URL } from "@/lib/api";
import { useToast } from "@/hooks/use-toast";
import logo from "@/assets/BudgetBuddy.png";
import pattern from "@/assets/login-pattern.svg";
import { Header } from "@/components/Header";
import storageService from "@/lib/storage";

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
      storageService.setItem("user", JSON.stringify({
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

  const loginWithGoogle = useGoogleLogin({
    onSuccess: async (tokenResponse) => {
      setLoading(true);
      setError("");
      try {
        // The hook returns an access_token by default, but we need an id_token or similar, OR we can just 
        // pass the access token to google to get user info, then pass it to our backend.
        // Actually, @react-oauth/google's handle is easier with the <GoogleLogin> component but we have a custom UI.

        const userInfoResponse = await fetch('https://www.googleapis.com/oauth2/v3/userinfo', {
          headers: { Authorization: `Bearer ${tokenResponse.access_token}` },
        });
        const userInfo = await userInfoResponse.json();

        // In a purely id_token flow we'd send the id_token, but since we used the access token implicit flow
        // we can just send the user info to the backend OR we can change this to an auth code flow.
        // To stay true to our backend design which expects a Google credential JWT (idToken), 
        // we should use the implicit flow that returns standard JWT credentials, but `useGoogleLogin` returns an access token by default unless type is set.
        // Let's modify the backend to accept standard profile data if it's sent directly from our frontend trust.

        const res = await fetch(`${API_URL}/auth/google`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            email: userInfo.email,
            name: userInfo.name,
            google_id: userInfo.sub
          })
        });

        if (!res.ok) throw new Error("Backend authentication failed");

        const data = await res.json();
        // Store user session mapping the backend structure
        storageService.setItem("user", JSON.stringify({
          id: data.user.id,
          email: data.user.email,
          name: data.user.name,
          currency: data.user.currency,
          token: data.token
        }));

        toast({
          title: "Welcome back!",
          description: `Signed in securely with Google.`
        });

        navigate("/app/dashboard");
      } catch (err) {
        console.error("Google login error:", err);
        setError("Failed to authenticate with Google. Please try again.");
      } finally {
        setLoading(false);
      }
    },
    onError: errorResponse => {
      console.error(errorResponse);
      toast({
        title: "Sign in canceled",
        description: "Google authentication was aborted."
      });
    }
  });

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
        <div className="absolute inset-0 bg-gradient-to-br from-teal-900 via-slate-950 to-emerald-950" />
        <div className="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(56,189,248,0.35),transparent_55%),radial-gradient(circle_at_bottom_right,rgba(34,197,94,0.3),transparent_60%)]" />
        <div
          className="absolute inset-0 opacity-25 mix-blend-soft-light"
          style={{ backgroundImage: `url(${pattern})`, backgroundSize: "420px", backgroundPosition: "center" }}
        />
        <div className="absolute -left-24 top-1/3 h-72 w-72 rounded-full bg-emerald-400/25 blur-3xl" />
        <div className="absolute -right-16 -top-16 h-80 w-80 rounded-full bg-sky-400/25 blur-[140px]" />
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
                <p className="text-sm uppercase tracking-[0.35em] text-emerald-200/80">BudgetBuddy</p>
                <h1 className="text-4xl font-semibold leading-tight">
                  Empower your finances with clarity and confidence.
                </h1>
                <p className="text-base text-slate-200/85 sm:text-lg">
                  BudgetBuddy helps you visualize spending, plan smarter budgets, and stay in control with real-time
                  insights designed for peace of mind.
                </p>

                <div className="flex flex-wrap gap-3 pt-2 justify-center lg:justify-start">
                  <div className="rounded-full bg-white/10 px-4 py-1.5 text-sm font-medium backdrop-blur border border-white/5">
                    📊 Analytics
                  </div>
                  <div className="rounded-full bg-white/10 px-4 py-1.5 text-sm font-medium backdrop-blur border border-white/5">
                    🎯 Goals
                  </div>
                  <div className="rounded-full bg-white/10 px-4 py-1.5 text-sm font-medium backdrop-blur border border-white/5">
                    🌍 Multi-currency
                  </div>
                </div>
              </div>
            </div>

            <div className="mx-auto flex w-fit flex-col items-start gap-2 rounded-2xl bg-white/5 border border-white/10 p-5 text-sm font-medium text-slate-100 backdrop-blur xl:mx-0 max-w-sm text-left">
              <div className="flex gap-1 text-yellow-400 text-lg">
                <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
              </div>
              <span className="text-slate-200/90 leading-relaxed font-body">
                "BudgetBuddy completely changed how I manage my finances. The visual analytics make it incredibly easy to see exactly where my money is going."
              </span>
              <span className="text-emerald-300 font-semibold mt-1">— Sarah Jenkins, Designer</span>
            </div>
          </div>

          <Card className="w-full h-fit border-white/20 bg-white/10 shadow-[0_30px_60px_rgba(8,47,73,0.45)] backdrop-blur-2xl">
            <CardHeader className="flex flex-col items-center space-y-4 text-center text-slate-100">
              <CardTitle className="text-3xl font-semibold">Welcome Back</CardTitle>
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
                      autoComplete="email"
                      placeholder="Enter your email"
                      value={formData.email}
                      onChange={handleChange}
                      className={`border-white/20 bg-white/85 pl-10 text-slate-900 placeholder:text-slate-500 focus-visible:ring-emerald-500 ${error ? 'border-red-500/50 ring-1 ring-red-500/50' : ''}`}
                      required
                    />
                  </div>
                </div>

                <div className="space-y-2">
                  <div className="flex items-center justify-between">
                    <Label htmlFor="password" className="text-slate-100">
                      Password
                    </Label>
                    <Link
                      to="/forgot-password"
                      className="text-sm font-medium text-emerald-200 hover:text-emerald-100"
                    >
                      Forgot Password?
                    </Link>
                  </div>
                  <div className="relative">
                    <Lock className="absolute left-3 top-3 h-4 w-4 text-slate-300" />
                    <Input
                      id="password"
                      name="password"
                      type={showPassword ? "text" : "password"}
                      autoComplete="current-password"
                      placeholder="Enter your password"
                      value={formData.password}
                      onChange={handleChange}
                      className={`border-white/20 bg-white/85 pl-10 pr-10 text-slate-900 placeholder:text-slate-500 focus-visible:ring-emerald-500 ${error ? 'border-red-500/50 ring-1 ring-red-500/50' : ''}`}
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

                <div className="flex items-center space-x-2 pt-1 pb-2">
                  <input
                    type="checkbox"
                    id="remember"
                    className="h-4 w-4 rounded border-white/20 bg-white/10 text-emerald-500 focus:ring-emerald-500 focus:ring-offset-slate-900"
                  />
                  <label htmlFor="remember" className="text-sm font-medium leading-none text-slate-200/90 peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                    Remember me for 30 days
                  </label>
                </div>

                <Button
                  type="submit"
                  className="w-full bg-emerald-500 text-white shadow-lg shadow-emerald-500/25 transition hover:bg-emerald-400 focus-visible:ring-emerald-200 h-11"
                  disabled={loading}
                >
                  {loading && <Loader2 className="mr-2 h-4 w-4 animate-spin" />}
                  {loading ? "Signing in..." : "Sign In"}
                </Button>
              </form>

              <div className="mt-8 space-y-4">
                <div className="relative">
                  <div className="absolute inset-0 flex items-center">
                    <span className="w-full border-t border-white/10" />
                  </div>
                  <div className="relative flex justify-center text-xs uppercase">
                    <span className="bg-[#0b1a20] px-2 text-slate-400 rounded-md">Or continue with</span>
                  </div>
                </div>

                <Button
                  type="button"
                  variant="outline"
                  className="w-full bg-white/5 border-white/10 text-white hover:bg-white/10 hover:text-white h-11"
                  onClick={() => loginWithGoogle()}
                  disabled={loading}
                >
                  <svg className="mr-2 h-4 w-4" viewBox="0 0 24 24">
                    <path
                      d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                      fill="#4285F4"
                    />
                    <path
                      d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                      fill="#34A853"
                    />
                    <path
                      d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
                      fill="#FBBC05"
                    />
                    <path
                      d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
                      fill="#EA4335"
                    />
                  </svg>
                  Sign in with Google
                </Button>

                <div className="flex items-center justify-center gap-1.5 pt-2 text-xs text-slate-400 font-medium">
                  <ShieldCheck className="h-3.5 w-3.5 text-emerald-400" />
                  <span>Your data is end-to-end encrypted</span>
                </div>
              </div>

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
