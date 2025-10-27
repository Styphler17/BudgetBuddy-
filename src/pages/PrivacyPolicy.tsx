import { Helmet } from "react-helmet-async";
import { Link } from "react-router-dom";
import { Button } from "@/components/ui/button";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Badge } from "@/components/ui/badge";
import {
  Shield,
  Lock,
  Eye,
  FileText,
  TrendingUp,
  ArrowRight
} from "lucide-react";

export function PrivacyPolicy() {
  const sections = [
    {
      title: "Information We Collect",
      content: [
        "Personal information you provide (name, email, account details)",
        "Financial data you input (transactions, budgets, goals)",
        "Usage data and analytics to improve our service",
        "Device and browser information for security"
      ]
    },
    {
      title: "How We Use Your Information",
      content: [
        "To provide and maintain your BudgetBuddy account",
        "To process and display your financial data",
        "To send important updates and notifications",
        "To improve our services and develop new features",
        "To ensure security and prevent fraud"
      ]
    },
    {
      title: "Information Sharing",
      content: [
        "We never sell your personal information to third parties",
        "Data is only shared with service providers who help us operate",
        "We may share anonymized, aggregated data for analytics",
        "Legal requirements may compel us to share data when required by law"
      ]
    },
    {
      title: "Data Security",
      content: [
        "Bank-level encryption protects your data in transit and at rest",
        "Regular security audits and updates",
        "Access controls and authentication requirements",
        "Secure data centers with physical and digital protections"
      ]
    },
    {
      title: "Your Rights",
      content: [
        "Access and download your personal data",
        "Correct or update your information",
        "Delete your account and associated data",
        "Opt out of non-essential communications",
        "Data portability to other services"
      ]
    },
    {
      title: "Cookies and Tracking",
      content: [
        "Essential cookies for app functionality",
        "Analytics cookies to improve user experience",
        "No tracking for advertising purposes",
        "Clear options to manage cookie preferences"
      ]
    }
  ];

  return (
    <>
      <Helmet>
        <title>Privacy Policy - BudgetBuddy</title>
        <meta name="description" content="Learn about BudgetBuddy's privacy policy. We are committed to protecting your financial data and personal information with bank-level security." />
        <meta name="keywords" content="privacy policy, data protection, financial privacy, security, BudgetBuddy privacy" />
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
            <div className="w-20 h-20 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-6">
              <Shield className="w-10 h-10 text-primary" />
            </div>
            <h1 className="text-5xl font-bold text-gray-900 mb-6">
              Privacy Policy
            </h1>
            <p className="text-xl text-gray-600 mb-8">
              Your privacy and security are our top priorities. Learn how we protect
              and handle your personal and financial information.
            </p>
            <Badge variant="secondary" className="mb-4">
              Last updated: December 2024
            </Badge>
          </div>
        </section>

        {/* Overview Section */}
        <section className="container mx-auto px-4 py-20">
          <div className="max-w-4xl mx-auto">
            <Card className="mb-12">
              <CardHeader>
                <CardTitle className="text-2xl flex items-center gap-2">
                  <Lock className="w-6 h-6 text-primary" />
                  Our Commitment to Privacy
                </CardTitle>
              </CardHeader>
              <CardContent className="text-lg text-gray-600">
                <p className="mb-4">
                  At BudgetBuddy, we believe your financial data belongs to you. We are committed to
                  protecting your privacy and ensuring the security of your personal information.
                  This privacy policy explains how we collect, use, and protect your data.
                </p>
                <p>
                  We use bank-level encryption and security measures to keep your information safe.
                  We never sell your data to third parties, and we only use it to provide you with
                  the best possible budgeting experience.
                </p>
              </CardContent>
            </Card>
          </div>
        </section>

        {/* Detailed Sections */}
        <section className="bg-white py-20">
          <div className="container mx-auto px-4">
            <div className="max-w-4xl mx-auto">
              <div className="text-center mb-16">
                <h2 className="text-3xl font-bold text-gray-900 mb-4">
                  Detailed Privacy Information
                </h2>
                <p className="text-lg text-gray-600">
                  Here's a breakdown of how we handle different aspects of your privacy.
                </p>
              </div>

              <div className="space-y-8">
                {sections.map((section, index) => (
                  <Card key={index} className="border-l-4 border-l-primary">
                    <CardHeader>
                      <CardTitle className="text-xl">{section.title}</CardTitle>
                    </CardHeader>
                    <CardContent>
                      <ul className="space-y-2">
                        {section.content.map((item, i) => (
                          <li key={i} className="flex items-start gap-2">
                            <div className="w-2 h-2 bg-primary rounded-full mt-2 flex-shrink-0"></div>
                            <span className="text-gray-600">{item}</span>
                          </li>
                        ))}
                      </ul>
                    </CardContent>
                  </Card>
                ))}
              </div>
            </div>
          </div>
        </section>

        {/* Contact Section */}
        <section className="py-20">
          <div className="container mx-auto px-4 text-center">
            <div className="max-w-2xl mx-auto">
              <Eye className="w-16 h-16 text-primary mx-auto mb-6" />
              <h2 className="text-3xl font-bold text-gray-900 mb-4">
                Questions About Privacy?
              </h2>
              <p className="text-lg text-gray-600 mb-8">
                If you have any questions about our privacy policy or how we handle your data,
                please don't hesitate to contact us.
              </p>
              <div className="flex flex-col sm:flex-row gap-4 justify-center">
                <Link to="/contact">
                  <Button size="lg">
                    Contact Us
                    <ArrowRight className="ml-2 w-5 h-5" />
                  </Button>
                </Link>
                <Button variant="outline" size="lg" asChild>
                  <a href="mailto:privacy@budgetbuddy.com">
                    Email Privacy Team
                  </a>
                </Button>
              </div>
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
                <h3 className="font-semibold text-white mb-4">Legal</h3>
                <ul className="space-y-2">
                  <li><Link to="/privacy" className="hover:text-white transition-colors">Privacy Policy</Link></li>
                  <li><a href="#" className="hover:text-white transition-colors">Terms of Service</a></li>
                  <li><a href="#" className="hover:text-white transition-colors">Cookie Policy</a></li>
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
