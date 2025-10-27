import { Link } from "react-router-dom";
import { Button } from "@/components/ui/button";
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card";
import { Badge } from "@/components/ui/badge";
import { Header } from "@/components/Header";
import { Footer } from "@/components/Footer";
import { BackToTop } from "@/components/BackToTop";
import { Seo } from "@/components/Seo";
import { BlogCard } from "@/components/blog/BlogCard";
import { blogAPI, type BlogPostSummary } from "@/lib/api";
import {
  TrendingUp,
  Shield,
  BarChart3,
  Wallet,
  Target,
  PieChart,
  ArrowRight,
  CheckCircle,
  Star,
  ArrowUp
} from "lucide-react";
import { useState, useEffect } from "react";

export function Landing() {
  const [showBackToTop, setShowBackToTop] = useState(false);
  const [featuredPosts, setFeaturedPosts] = useState<BlogPostSummary[]>([]);
  const [loadingPosts, setLoadingPosts] = useState(true);

  useEffect(() => {
    const handleScroll = () => {
      setShowBackToTop(window.scrollY > 100);
    };

    window.addEventListener('scroll', handleScroll);
    return () => window.removeEventListener('scroll', handleScroll);
  }, []);

  useEffect(() => {
    const loadFeaturedPosts = async () => {
      try {
        const posts = await blogAPI.listPublished({ limit: 5 });
        setFeaturedPosts(posts);
      } catch (error) {
        console.error("Failed to load featured blog posts:", error);
      } finally {
        setLoadingPosts(false);
      }
    };

    void loadFeaturedPosts();
  }, []);

  const scrollToTop = () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  };

  const features = [
    {
      icon: BarChart3,
      title: "Transaction Tracking",
      description: "Monitor all your income and expenses in one place"
    },
    {
      icon: Target,
      title: "Budget Goals",
      description: "Set and track financial goals with smart budgeting"
    },
    {
      icon: PieChart,
      title: "Analytics & Insights",
      description: "Visual reports and analytics for better financial decisions"
    },
    {
      icon: Wallet,
      title: "Multi-Account Support",
      description: "Manage checking, savings, credit, and investment accounts"
    },
    {
      icon: Shield,
      title: "Secure & Private",
      description: "Your financial data is protected with enterprise-grade security"
    },
    {
      icon: TrendingUp,
      title: "Smart Categories",
      description: "Automatic categorization with customizable spending categories"
    }
  ];

  const testimonials = [
    {
      name: "Sarah Johnson",
      role: "Small Business Owner",
      content: "BudgetBuddy has transformed how I manage my business finances. The analytics are incredible!",
      rating: 5
    },
    {
      name: "Mike Chen",
      role: "Freelancer",
      content: "Finally, a budgeting app that understands freelancers. The goal tracking is a game-changer.",
      rating: 5
    },
    {
      name: "Emily Davis",
      role: "Student",
      content: "Simple, intuitive, and helps me stay on top of my student loans and expenses.",
      rating: 5
    }
  ];

  return (
    <>
      <Seo
        title="BudgetBuddy | Smart Budgeting & Personal Finance Platform"
        description="Track spending, crush savings goals, and manage every account in one command center. BudgetBuddy puts real-time insights and automation in your pocket."
        canonical={typeof window !== "undefined" ? `${window.location.origin}/` : undefined}
        keywords={[
          "budgeting app",
          "personal finance",
          "expense tracking",
          "savings goals",
          "BudgetBuddy"
        ]}
      />
      <div className="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50">
      <Header />

      {/* Hero Section */}
      <section className="container mx-auto px-4 py-20 text-center">
        <div className="max-w-4xl mx-auto">
          <Badge variant="secondary" className="mb-4">
            🎉 Smart Budgeting Made Simple
          </Badge>
          <h1 className="text-5xl font-bold text-gray-900 mb-6">
            Take Control of Your
            <span className="text-primary"> Finances</span>
          </h1>
          <p className="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
            BudgetBuddy helps you track expenses, set goals, and make smarter financial decisions.
            Join thousands of users who have transformed their financial habits.
          </p>
          <div className="flex flex-col sm:flex-row gap-4 justify-center">
            <Link to="/register">
              <Button size="lg" className="text-lg px-8 py-3">
                Start Free Today
                <ArrowRight className="ml-2 w-5 h-5" />
              </Button>
            </Link>
            <Link to="/login">
              <Button variant="outline" size="lg" className="text-lg px-8 py-3">
                Sign In
              </Button>
            </Link>
          </div>
        </div>
      </section>

      {/* Features Section */}
      <section className="container mx-auto px-4 py-20">
        <div className="text-center mb-16">
          <h2 className="text-3xl font-bold text-gray-900 mb-4">
            Everything You Need to Budget Better
          </h2>
          <p className="text-lg text-gray-600 max-w-2xl mx-auto">
            Powerful features designed to help you understand and improve your financial health.
          </p>
        </div>
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          {features.map((feature, index) => (
            <Card key={index} className="border-0 shadow-lg hover:shadow-xl transition-shadow">
              <CardHeader>
                <div className="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center mb-4">
                  <feature.icon className="w-6 h-6 text-primary" />
                </div>
                <CardTitle className="text-xl">{feature.title}</CardTitle>
              </CardHeader>
              <CardContent>
                <CardDescription className="text-base">{feature.description}</CardDescription>
              </CardContent>
            </Card>
          ))}
        </div>
      </section>

      {/* Stats Section */}
      <section className="bg-primary text-primary-foreground py-20">
        <div className="container mx-auto px-4">
          <div className="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
            <div>
              <div className="text-4xl font-bold mb-2">10,000+</div>
              <div className="text-primary-foreground/80">Active Users</div>
            </div>
            <div>
              <div className="text-4xl font-bold mb-2">$2M+</div>
              <div className="text-primary-foreground/80">Money Tracked</div>
            </div>
            <div>
              <div className="text-4xl font-bold mb-2">4.9★</div>
              <div className="text-primary-foreground/80">User Rating</div>
            </div>
          </div>
        </div>
      </section>

      {/* Featured Blog Posts */}
      <section className="container mx-auto px-4 py-20">
        <div className="flex flex-col items-center justify-between gap-6 text-center md:flex-row md:text-left">
          <div>
            <h2 className="text-3xl font-bold text-gray-900">Latest from the BudgetBuddy blog</h2>
            <p className="mt-2 text-lg text-gray-600">
              Fresh insights and product tips to help you make smarter money moves.
            </p>
          </div>
          <Button asChild variant="outline">
            <Link to="/blog">View all blog posts</Link>
          </Button>
        </div>

        <div className="mt-12">
          {loadingPosts ? (
            <div className="flex min-h-[200px] items-center justify-center text-muted-foreground">
              Loading featured stories...
            </div>
          ) : featuredPosts.length === 0 ? (
            <div className="rounded-lg border bg-muted/40 p-8 text-center text-muted-foreground">
              New blog articles will appear here soon. Stay tuned!
            </div>
          ) : (
            <div className="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
              {featuredPosts.map((post) => (
                <BlogCard key={post.id} post={post} />
              ))}
            </div>
          )}
        </div>
      </section>

      {/* Testimonials Section */}
      <section className="container mx-auto px-4 py-20">
        <div className="text-center mb-16">
          <h2 className="text-3xl font-bold text-gray-900 mb-4">
            Loved by Users Worldwide
          </h2>
          <p className="text-lg text-gray-600">
            See what our community has to say about BudgetBuddy.
          </p>
        </div>
        <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
          {testimonials.map((testimonial, index) => (
            <Card key={index} className="border-0 shadow-lg">
              <CardContent className="p-6">
                <div className="flex mb-4">
                  {[...Array(testimonial.rating)].map((_, i) => (
                    <Star key={i} className="w-5 h-5 text-yellow-400 fill-current" />
                  ))}
                </div>
                <p className="text-gray-600 mb-4">"{testimonial.content}"</p>
                <div>
                  <div className="font-semibold text-gray-900">{testimonial.name}</div>
                  <div className="text-sm text-gray-500">{testimonial.role}</div>
                </div>
              </CardContent>
            </Card>
          ))}
        </div>
      </section>

      {/* CTA Section */}
      <section className="bg-gray-50 py-20">
        <div className="container mx-auto px-4 text-center">
          <div className="max-w-2xl mx-auto">
            <h2 className="text-3xl font-bold text-gray-900 mb-4">
              Ready to Transform Your Finances?
            </h2>
            <p className="text-lg text-gray-600 mb-8">
              Join thousands of users who have taken control of their financial future with BudgetBuddy.
            </p>
            <Link to="/register">
              <Button size="lg" className="text-lg px-8 py-3">
                Get Started Free
                <ArrowRight className="ml-2 w-5 h-5" />
              </Button>
            </Link>
          </div>
        </div>
      </section>

        <Footer />
        <BackToTop />
      </div>
    </>
  );
}
