import { Toaster } from "@/components/ui/toaster";
import { Toaster as Sonner } from "@/components/ui/sonner";
import { TooltipProvider } from "@/components/ui/tooltip";
import { QueryClient, QueryClientProvider } from "@tanstack/react-query";
import { BrowserRouter, Routes, Route } from "react-router-dom";
import { HelmetProvider } from "react-helmet-async";
import { DashboardLayout } from "./layouts/DashboardLayout";
import Index from "./pages/Index";
import Transactions from "./pages/Transactions";
import Analytics from "./pages/Analytics";
import Goals from "./pages/Goals";
import Accounts from "./pages/Accounts";
import Notifications from "./pages/Notifications";
import Settings from "./pages/Settings";
import Categories from "./pages/Categories";
import { AdminDashboard } from "./pages/AdminDashboard";
import { Login } from "./pages/Login";
import { Register } from "./pages/Register";
import { AdminLogin } from "./pages/AdminLogin";
import { Landing } from "./pages/Landing";
import { HelpCenter } from "./pages/HelpCenter";
import { ContactUs } from "./pages/ContactUs";
import { PrivacyPolicy } from "./pages/PrivacyPolicy";
import NotFound from "./pages/NotFound";

const queryClient = new QueryClient();

const App = () => (
  <HelmetProvider>
    <QueryClientProvider client={queryClient}>
      <TooltipProvider>
        <Toaster />
        <Sonner />
        <BrowserRouter>
          <Routes>
          {/* Landing page for non-authenticated users */}
          <Route path="/" element={<Landing />} />

          {/* Public pages */}
          <Route path="/help" element={<HelpCenter />} />
          <Route path="/contact" element={<ContactUs />} />
          <Route path="/privacy" element={<PrivacyPolicy />} />

          {/* Auth routes - no dashboard layout */}
          <Route path="/login" element={<Login />} />
          <Route path="/register" element={<Register />} />
          <Route path="/admin-login" element={<AdminLogin />} />

          {/* Admin routes - no dashboard layout */}
          <Route path="/admin" element={<AdminDashboard />} />

          {/* Protected app routes - with dashboard layout */}
          <Route path="/app/*" element={
            <DashboardLayout>
              {(period) => (
                <Routes>
                  <Route path="/dashboard" element={<Index period={period} />} />
                  <Route path="/transactions" element={<Transactions period={period} />} />
                  <Route path="/analytics" element={<Analytics period={period} />} />
                  <Route path="/goals" element={<Goals period={period} />} />
                  <Route path="/accounts" element={<Accounts period={period} />} />
                  <Route path="/categories" element={<Categories period={period} />} />
                  <Route path="/notifications" element={<Notifications period={period} />} />
                  <Route path="/settings" element={<Settings period={period} />} />
                  {/* ADD ALL CUSTOM ROUTES ABOVE THE CATCH-ALL "*" ROUTE */}
                  <Route path="*" element={<NotFound />} />
                </Routes>
              )}
            </DashboardLayout>
          } />
          </Routes>
        </BrowserRouter>
      </TooltipProvider>
    </QueryClientProvider>
  </HelmetProvider>
);

export default App;
