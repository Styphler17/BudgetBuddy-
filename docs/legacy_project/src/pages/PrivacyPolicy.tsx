import { Link } from "react-router-dom";
import { Button } from "@/components/ui/button";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Badge } from "@/components/ui/badge";
import { Header } from "@/components/Header";
import { Footer } from "@/components/Footer";
import { BackToTop } from "@/components/BackToTop";
import { Seo } from "@/components/Seo";
import { ROUTE_PATHS } from "@/config/site";
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
      <Seo
        title="BudgetBuddy Privacy Policy"
        description="Understand how BudgetBuddy protects your financial data, respects your privacy, and keeps your information secure."
        path={ROUTE_PATHS.privacy}
        keywords={[
          "BudgetBuddy privacy policy",
          "financial data protection",
          "budget app security",
          "personal finance privacy"
        ]}
      />

      <div className="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50">
        <Header />

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

        <Footer />
        <BackToTop />
      </div>
    </>
  );
}
