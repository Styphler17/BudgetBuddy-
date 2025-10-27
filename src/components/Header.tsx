import { Link } from "react-router-dom";
import { Button } from "@/components/ui/button";
import { TrendingUp } from "lucide-react";

export function Header() {
  const user = JSON.parse(localStorage.getItem("user") || "null");

  return (
    <header className="container mx-auto px-4 py-6">
      <nav className="flex items-center justify-between">
        <Link to="/" className="flex items-center space-x-2">
          <div className="w-8 h-8 bg-primary rounded-lg flex items-center justify-center">
            <TrendingUp className="w-5 h-5 text-primary-foreground" />
          </div>
          <span className="text-xl font-bold text-gray-900">BudgetBuddy</span>
        </Link>

        {/* Main Navigation Links - Always visible */}
        <div className="hidden md:flex items-center space-x-8">
          <Link to="/" className="text-gray-600 hover:text-gray-900 transition-colors">
            Home
          </Link>
          <Link to="/help" className="text-gray-600 hover:text-gray-900 transition-colors">
            Help Center
          </Link>
          <Link to="/contact" className="text-gray-600 hover:text-gray-900 transition-colors">
            Contact
          </Link>
          <Link to="/privacy" className="text-gray-600 hover:text-gray-900 transition-colors">
            Privacy
          </Link>
        </div>

        {/* Auth Buttons */}
        <div className="flex items-center space-x-4">
          {user ? (
            <>
              {/* Show nav links for logged in users too */}
              <div className="hidden md:flex items-center space-x-8 mr-4">
                <Link to="/app" className="text-gray-600 hover:text-gray-900 transition-colors">
                  Dashboard
                </Link>
                <Link to="/app/transactions" className="text-gray-600 hover:text-gray-900 transition-colors">
                  Transactions
                </Link>
                <Link to="/app/analytics" className="text-gray-600 hover:text-gray-900 transition-colors">
                  Analytics
                </Link>
              </div>
              <Button variant="ghost" onClick={() => {
                localStorage.removeItem("user");
                window.location.href = "/";
              }}>
                Sign Out
              </Button>
            </>
          ) : (
            <>
              <Link to="/login">
                <Button variant="ghost">Sign In</Button>
              </Link>
              <Link to="/register">
                <Button>Get Started</Button>
              </Link>
            </>
          )}
        </div>
      </nav>
    </header>
  );
}
