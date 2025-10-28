import { Link, useLocation } from "react-router-dom";
import { Button } from "@/components/ui/button";
import { Menu, X } from "lucide-react";
import { useState } from "react";
import { cn } from "@/lib/utils";
import logo from "@/assets/BudgetBuddy.png";
import storageService from "@/lib/storage";

export function Header() {
  const user = JSON.parse(storageService.getItem("user") || "null");
  const location = useLocation();
  const [isMenuOpen, setIsMenuOpen] = useState(false);

  const isActive = (path: string) => location.pathname === path;

  return (
    <header className="sticky top-0 z-50 bg-white/95 backdrop-blur-sm border-b border-gray-200">
      <div className="container mx-auto px-4 py-2">
        <nav className="flex items-center justify-between">
          <Link to="/" className="flex items-center space-x-3">
            <img src={logo} alt="BudgetBuddy" className="h-14 w-14 rounded-lg object-cover" />
            <span className="text-xl font-bold text-gray-900">BudgetBuddy</span>
          </Link>

        {/* Desktop Navigation */}
        <div className="hidden md:flex items-center space-x-8">
          <Link
            to="/"
            className={cn(
              "transition-colors",
              isActive("/")
                ? "text-primary font-semibold"
                : "text-gray-600 hover:text-gray-900"
            )}
            >
              Home
          </Link>
          <Link
            to="/blog"
            className={cn(
              "transition-colors",
              location.pathname.startsWith("/blog")
                ? "text-primary font-semibold"
                : "text-gray-600 hover:text-gray-900"
            )}
          >
            Blog
          </Link>
          <Link
            to="/contact"
            className={cn(
              "transition-colors",
              isActive("/contact")
                ? "text-primary font-semibold"
                : "text-gray-600 hover:text-gray-900"
            )}
          >
            Contact
          </Link>
          {user && (
            <>
              <Link
                to="/app"
                className={cn(
                  "transition-colors",
                  isActive("/app")
                    ? "text-primary font-semibold"
                    : "text-gray-600 hover:text-gray-900"
                )}
              >
                Dashboard
              </Link>
              <Link
                to="/app/transactions"
                className={cn(
                  "transition-colors",
                  isActive("/app/transactions")
                    ? "text-primary font-semibold"
                    : "text-gray-600 hover:text-gray-900"
                )}
              >
                Transactions
              </Link>
              <Link
                to="/app/analytics"
                className={cn(
                  "transition-colors",
                  isActive("/app/analytics")
                    ? "text-primary font-semibold"
                    : "text-gray-600 hover:text-gray-900"
                )}
              >
                Analytics
              </Link>
            </>
          )}
        </div>

        {/* Auth Buttons */}
        <div className="flex items-center space-x-4">
          {user ? (
            <Button
              variant="ghost"
                onClick={() => {
                  storageService.removeItem("user");
                  window.location.href = "/";
                }}
            >
              Sign Out
            </Button>
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

          {/* Mobile Menu Button */}
          <button
            onClick={() => setIsMenuOpen(!isMenuOpen)}
            className="md:hidden p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100"
          >
            {isMenuOpen ? <X className="w-6 h-6" /> : <Menu className="w-6 h-6" />}
          </button>
        </div>
      </nav>

      {/* Mobile Navigation Menu */}
      {isMenuOpen && (
        <div className="md:hidden mt-4 pb-4 border-t border-gray-200">
          <div className="flex flex-col space-y-4 pt-4">
            <Link
              to="/"
              className={cn(
                "transition-colors",
                isActive("/")
                  ? "text-primary font-semibold"
                  : "text-gray-600 hover:text-gray-900"
              )}
              onClick={() => setIsMenuOpen(false)}
            >
              Home
            </Link>
            <Link
              to="/blog"
              className={cn(
                "transition-colors",
                location.pathname.startsWith("/blog")
                  ? "text-primary font-semibold"
                  : "text-gray-600 hover:text-gray-900"
              )}
              onClick={() => setIsMenuOpen(false)}
            >
              Blog
            </Link>
            <Link
              to="/contact"
              className={cn(
                "transition-colors",
                isActive("/contact")
                  ? "text-primary font-semibold"
                  : "text-gray-600 hover:text-gray-900"
              )}
              onClick={() => setIsMenuOpen(false)}
            >
              Contact
            </Link>
            {user && (
              <>
                <Link
                  to="/app"
                  className={cn(
                    "transition-colors",
                    isActive("/app")
                      ? "text-primary font-semibold"
                      : "text-gray-600 hover:text-gray-900"
                  )}
                  onClick={() => setIsMenuOpen(false)}
                >
                  Dashboard
                </Link>
                <Link
                  to="/app/transactions"
                  className={cn(
                    "transition-colors",
                    isActive("/app/transactions")
                      ? "text-primary font-semibold"
                      : "text-gray-600 hover:text-gray-900"
                  )}
                  onClick={() => setIsMenuOpen(false)}
                >
                  Transactions
                </Link>
                <Link
                  to="/app/analytics"
                  className={cn(
                    "transition-colors",
                    isActive("/app/analytics")
                      ? "text-primary font-semibold"
                      : "text-gray-600 hover:text-gray-900"
                  )}
                  onClick={() => setIsMenuOpen(false)}
                >
                  Analytics
                </Link>
              </>
            )}
          </div>
        </div>
      )}
      </div>
    </header>
  );
}
