import { useState } from "react";
import { Link, useNavigate } from "react-router-dom";
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Alert, AlertDescription } from "@/components/ui/alert";
import { Eye, EyeOff, Mail, Lock, ArrowLeft } from "lucide-react";
import { userAPI } from "@/lib/api";
import { toast } from "sonner";
import logo from "@/assets/BudgetBuddy.png";
import pattern from "@/assets/login-pattern.svg";
import { Header } from "@/components/Header";

export function ForgotPassword() {
    const [step, setStep] = useState<1 | 2>(1);
    const [email, setEmail] = useState("");
    const [newPassword, setNewPassword] = useState("");
    const [confirmPassword, setConfirmPassword] = useState("");
    const [showPassword, setShowPassword] = useState(false);
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState("");
    const [userId, setUserId] = useState<number | null>(null);

    const navigate = useNavigate();

    const handleEmailSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        setLoading(true);
        setError("");

        try {
            const user = await userAPI.findByEmail(email);

            if (!user) {
                setError("We couldn't find an account associated with that email address.");
                return;
            }

            setUserId(user.id);
            setStep(2);
            toast.success("Account located. Please enter your new password.");
        } catch (error) {
            console.error("Verification error:", error);
            setError("An error occurred during verification. Please try again.");
        } finally {
            setLoading(false);
        }
    };

    const handlePasswordReset = async (e: React.FormEvent) => {
        e.preventDefault();
        setLoading(true);
        setError("");

        if (newPassword.length < 8) {
            setError("Password must be at least 8 characters long.");
            setLoading(false);
            return;
        }

        if (newPassword !== confirmPassword) {
            setError("Passwords do not match.");
            setLoading(false);
            return;
        }

        if (!userId) {
            setError("Session expired. Please start over.");
            setStep(1);
            setLoading(false);
            return;
        }

        try {
            await userAPI.update(userId, {
                password_hash: newPassword
            });

            // Synchronize back to localStorage if this is the currently logged in user implicitly
            const currentUserStr = localStorage.getItem("user");
            if (currentUserStr) {
                try {
                    const currentUser = JSON.parse(currentUserStr);
                    if (currentUser.id === userId) {
                        localStorage.setItem("user", JSON.stringify({
                            ...currentUser,
                            password_hash: newPassword,
                            password: newPassword
                        }));
                    }
                } catch (e) {
                    // Ignore
                }
            }

            toast.success("Password reset successfully! You can now sign in.");
            navigate("/login");
        } catch (error) {
            console.error("Reset error:", error);
            setError("An error occurred resetting your password. Please try again.");
        } finally {
            setLoading(false);
        }
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
                <div className="w-full max-w-md">
                    <Card className="w-full border-white/20 bg-white/10 shadow-[0_30px_60px_rgba(8,47,73,0.45)] backdrop-blur-2xl">
                        <CardHeader className="flex flex-col items-center space-y-4 text-center text-slate-100">
                            <div className="rounded-3xl bg-white/10 p-4 shadow-inner shadow-emerald-300/40 backdrop-blur-lg mb-2">
                                <img
                                    src={logo}
                                    alt="BudgetBuddy logo"
                                    className="h-14 w-14 rounded-2xl object-cover drop-shadow-[0_18px_55px_rgba(16,185,129,0.45)]"
                                />
                            </div>
                            <CardTitle className="text-3xl font-semibold">
                                {step === 1 ? "Forgot Password" : "Reset Password"}
                            </CardTitle>
                            <CardDescription className="text-slate-200/80">
                                {step === 1
                                    ? "Enter your email to verify your account"
                                    : "Enter your new password below"}
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            {step === 1 ? (
                                <form onSubmit={handleEmailSubmit} className="space-y-4 text-left">
                                    {error && (
                                        <Alert variant="destructive">
                                            <AlertDescription>{error}</AlertDescription>
                                        </Alert>
                                    )}

                                    <div className="space-y-2">
                                        <Label htmlFor="email" className="text-slate-100">
                                            Email Address
                                        </Label>
                                        <div className="relative">
                                            <Mail className="absolute left-3 top-3 h-4 w-4 text-slate-300" />
                                            <Input
                                                id="email"
                                                name="email"
                                                type="email"
                                                placeholder="Enter your registered email"
                                                value={email}
                                                onChange={(e) => setEmail(e.target.value)}
                                                className="border-white/20 bg-white/85 pl-10 text-slate-900 placeholder:text-slate-500"
                                                required
                                            />
                                        </div>
                                    </div>

                                    <Button
                                        type="submit"
                                        className="w-full bg-emerald-500 text-white shadow-lg shadow-emerald-500/25 transition hover:bg-emerald-400 focus-visible:ring-emerald-200 mt-2"
                                        disabled={loading}
                                    >
                                        {loading ? "Verifying..." : "Continue"}
                                    </Button>
                                </form>
                            ) : (
                                <form onSubmit={handlePasswordReset} className="space-y-4 text-left">
                                    {error && (
                                        <Alert variant="destructive">
                                            <AlertDescription>{error}</AlertDescription>
                                        </Alert>
                                    )}

                                    <div className="space-y-2">
                                        <Label htmlFor="newPassword" className="text-slate-100">
                                            New Password
                                        </Label>
                                        <div className="relative">
                                            <Lock className="absolute left-3 top-3 h-4 w-4 text-slate-300" />
                                            <Input
                                                id="newPassword"
                                                name="newPassword"
                                                type={showPassword ? "text" : "password"}
                                                placeholder="At least 8 characters"
                                                value={newPassword}
                                                onChange={(e) => setNewPassword(e.target.value)}
                                                className="border-white/20 bg-white/85 pl-10 pr-10 text-slate-900 placeholder:text-slate-500"
                                                required
                                                minLength={8}
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
                                            Confirm New Password
                                        </Label>
                                        <div className="relative">
                                            <Lock className="absolute left-3 top-3 h-4 w-4 text-slate-300" />
                                            <Input
                                                id="confirmPassword"
                                                name="confirmPassword"
                                                type={showPassword ? "text" : "password"}
                                                placeholder="Re-enter your new password"
                                                value={confirmPassword}
                                                onChange={(e) => setConfirmPassword(e.target.value)}
                                                className="border-white/20 bg-white/85 pl-10 pr-10 text-slate-900 placeholder:text-slate-500"
                                                required
                                                minLength={8}
                                            />
                                        </div>
                                    </div>

                                    <Button
                                        type="submit"
                                        className="w-full bg-emerald-500 text-white shadow-lg shadow-emerald-500/25 transition hover:bg-emerald-400 focus-visible:ring-emerald-200 mt-2"
                                        disabled={loading}
                                    >
                                        {loading ? "Resetting..." : "Reset Password"}
                                    </Button>
                                </form>
                            )}

                            <div className="mt-6 text-center">
                                <Link
                                    to="/login"
                                    className="inline-flex items-center text-sm font-medium text-emerald-200 hover:text-emerald-100"
                                >
                                    <ArrowLeft className="mr-2 h-4 w-4" />
                                    Back to Sign In
                                </Link>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    );
}
