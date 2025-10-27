import { Helmet } from "react-helmet-async";
import { Link } from "react-router-dom";
import { Button } from "@/components/ui/button";
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Badge } from "@/components/ui/badge";
import {
  Search,
  Book,
  MessageCircle,
  CreditCard,
  TrendingUp,
  Target,
  PieChart,
  HelpCircle,
  ArrowRight,
  Mail,
  Phone
} from "lucide-react";

export function HelpCenter() {
  const categories = [
    {
      icon: Book,
      title: "Getting Started",
      description: "Learn the basics of BudgetBuddy",
      articles: ["Creating your account", "Adding your first transaction", "Setting up budgets"]
    },
    {
      icon: CreditCard,
      title: "Transactions",
      description: "Manage your income and expenses",
      articles: ["Adding transactions", "Categorizing expenses", "Importing data"]
    },
    {
      icon: Target,
      title: "Budgeting",
      description: "Set and track financial goals",
      articles: ["Creating budgets", "Budget categories", "Tracking progress"]
    },
    {
      icon: PieChart,
      title: "Analytics",
      description: "Understanding your financial data",
      articles: ["Reading reports", "Spending insights", "Trend analysis"]
    },
    {
      icon: TrendingUp,
      title: "Accounts",
      description: "Manage multiple accounts",
      articles: ["Adding accounts", "Account types", "Balance tracking"]
    }
  ];

  const faqs = [
    {
      question: "How do I add a new transaction?",
      answer: "Go to the Transactions page and click the 'Add Transaction' button. Fill in the details including amount, category, and date."
    },
    {
      question: "Can I import transactions from my bank?",
      answer: "Yes! We support CSV import. Go to Settings > Import Data to upload your transaction history."
    },
    {
      question: "How do I set up a budget?",
      answer: "Navigate to the Goals page and click 'Create Budget'. Set your spending limit and time period."
    },
    {
      question: "Is my financial data secure?",
      answer: "Absolutely. We use bank-level encryption and never share your personal financial information."
    },
    {
      question: "Can I export my data?",
      answer: "Yes, you can export all your data as CSV or PDF from the Settings page."
    }
  ];

  return (
    <>
      <Helmet>
        <title>Help Center - BudgetBuddy</title>
        <meta name="description" content="Get help with BudgetBuddy. Find guides, tutorials, and answers to common questions about managing your finances." />
        <meta name="keywords" content="BudgetBuddy help, financial management help, budgeting tutorials, expense tracking guide" />
      </Helmet>

      <div className="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50">
        {/* Header */}
        <header className="container mx-auto px-4 py-6">
          <nav className="flex items-center justify-between">
            <Link to="/" className="flex items-center space-x-2">
              <div className="w-8 h-8 bg-primary rounded-lg flex items-center justify-center">
                <TrendingUp className="w-5 h-5 text-primary-foreground" />
              </div>
              <span className="text-xl font-bold text-gray-900">BudgetBuddy</span>
            </Link>
            <div className="flex items-center space-x-4">
              <Link to="/login">
                <Button variant="ghost">Sign In</Button>
              </Link>
              <Link to="/register">
                <Button>Get Started</Button>
              </Link>
            </div>
          </nav>
        </header>

        {/* Hero Section */}
        <section className="container mx-auto px-4 py-20 text-center">
          <div className="max-w-3xl mx-auto">
            <h1 className="text-5xl font-bold text-gray-900 mb-6">
              How can we help you?
            </h1>
            <p className="text-xl text-gray-600 mb-8">
              Find answers, get support, and learn everything you need to know about BudgetBuddy.
            </p>
            <div className="max-w-md mx-auto relative">
              <Search className="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5" />
              <Input
                placeholder="Search for help..."
                className="pl-10 pr-4 py-3 text-lg"
              />
            </div>
          </div>
        </section>

        {/* Categories Section */}
        <section className="container mx-auto px-4 py-20">
          <div className="text-center mb-16">
            <h2 className="text-3xl font-bold text-gray-900 mb-4">
              Browse by Category
            </h2>
            <p className="text-lg text-gray-600">
              Find the help you need organized by topic.
            </p>
          </div>
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            {categories.map((category, index) => (
              <Card key={index} className="hover:shadow-lg transition-shadow cursor-pointer">
                <CardHeader>
                  <div className="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center mb-4">
                    <category.icon className="w-6 h-6 text-primary" />
                  </div>
                  <CardTitle className="text-xl">{category.title}</CardTitle>
                  <CardDescription>{category.description}</CardDescription>
                </CardHeader>
                <CardContent>
                  <ul className="space-y-2">
                    {category.articles.map((article, i) => (
                      <li key={i} className="text-sm text-gray-600 hover:text-primary transition-colors cursor-pointer">
                        • {article}
                      </li>
                    ))}
                  </ul>
                </CardContent>
              </Card>
            ))}
          </div>
        </section>

        {/* FAQ Section */}
        <section className="bg-white py-20">
          <div className="container mx-auto px-4">
            <div className="text-center mb-16">
              <h2 className="text-3xl font-bold text-gray-900 mb-4">
                Frequently Asked Questions
              </h2>
              <p className="text-lg text-gray-600">
                Quick answers to common questions.
              </p>
            </div>
            <div className="max-w-3xl mx-auto space-y-6">
              {faqs.map((faq, index) => (
                <Card key={index} className="border-l-4 border-l-primary">
                  <CardHeader>
                    <CardTitle className="text-lg flex items-center gap-2">
                      <HelpCircle className="w-5 h-5 text-primary" />
                      {faq.question}
                    </CardTitle>
                  </CardHeader>
                  <CardContent>
                    <p className="text-gray-600">{faq.answer}</p>
                  </CardContent>
                </Card>
              ))}
            </div>
          </div>
        </section>

        {/* Contact Support Section */}
        <section className="bg-gray-50 py-20">
          <div className="container mx-auto px-4 text-center">
            <div className="max-w-2xl mx-auto">
              <h2 className="text-3xl font-bold text-gray-900 mb-4">
                Still Need Help?
              </h2>
              <p className="text-lg text-gray-600 mb-8">
                Can't find what you're looking for? Our support team is here to help.
              </p>
              <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <Card className="text-center">
                  <CardContent className="p-6">
                    <Mail className="w-8 h-8 text-primary mx-auto mb-4" />
                    <h3 className="font-semibold mb-2">Email Support</h3>
                    <p className="text-gray-600 mb-4">Get help from our support team</p>
                    <Button variant="outline">
                      Send Email
                      <ArrowRight className="ml-2 w-4 h-4" />
                    </Button>
                  </CardContent>
                </Card>
                <Card className="text-center">
                  <CardContent className="p-6">
                    <MessageCircle className="w-8 h-8 text-primary mx-auto mb-4" />
                    <h3 className="font-semibold mb-2">Live Chat</h3>
                    <p className="text-gray-600 mb-4">Chat with us in real-time</p>
                    <Button variant="outline">
                      Start Chat
                      <ArrowRight className="ml-2 w-4 h-4" />
                    </Button>
                  </CardContent>
                </Card>
              </div>
              <Link to="/contact">
                <Button size="lg">
                  Visit Contact Page
                  <ArrowRight className="ml-2 w-5 h-5" />
                </Button>
              </Link>
            </div>
          </div>
        </section>

        {/* Footer */}
        <footer className="bg-gray-900 text-gray-300 py-12">
          <div className="container mx-auto px-4">
            <div className="grid grid-cols-1 md:grid-cols-4 gap-8">
              <div>
                <div className="flex items-center space-x-2 mb-4">
                  <div className="w-8 h-8 bg-primary rounded-lg flex items-center justify-center">
                    <TrendingUp className="w-5 h-5 text-primary-foreground" />
                  </div>
                  <span className="text-xl font-bold text-white">BudgetBuddy</span>
                </div>
                <p className="text-gray-400">
                  Smart budgeting made simple. Take control of your financial future.
                </p>
              </div>
              <div>
                <h3 className="font-semibold text-white mb-4">Product</h3>
                <ul className="space-y-2">
                  <li><Link to="/" className="hover:text-white transition-colors">Home</Link></li>
                  <li><Link to="/help" className="hover:text-white transition-colors">Help Center</Link></li>
                  <li><Link to="/contact" className="hover:text-white transition-colors">Contact Us</Link></li>
                </ul>
              </div>
              <div>
                <h3 className="font-semibold text-white mb-4">Support</h3>
                <ul className="space-y-2">
                  <li><a href="#" className="hover:text-white transition-colors">Help Center</a></li>
                  <li><a href="#" className="hover:text-white transition-colors">Contact Us</a></li>
                  <li><a href="#" className="hover:text-white transition-colors">Privacy Policy</a></li>
                </ul>
              </div>
              <div>
                <h3 className="font-semibold text-white mb-4">Company</h3>
                <ul className="space-y-2">
                  <li><a href="#" className="hover:text-white transition-colors">About</a></li>
                  <li><a href="#" className="hover:text-white transition-colors">Blog</a></li>
                  <li><a href="#" className="hover:text-white transition-colors">Careers</a></li>
                </ul>
              </div>
            </div>
            <div className="border-t border-gray-800 mt-8 pt-8 text-center">
              <p className="text-gray-400">
                © 2024 BudgetBuddy. All rights reserved.
              </p>
            </div>
          </div>
        </footer>
      </div>
    </>
  );
}
