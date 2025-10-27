import { Helmet } from "react-helmet-async";
import { Link } from "react-router-dom";
import { Button } from "@/components/ui/button";
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Textarea } from "@/components/ui/textarea";
import { Badge } from "@/components/ui/badge";
import emailjs from '@emailjs/browser';
import { useRef, useState } from "react";
import { useToast } from "@/hooks/use-toast";
import {
  Mail,
  MessageSquare,
  Phone,
  MapPin,
  Clock,
  Send,
  Loader2,
  CheckCircle,
  TrendingUp
} from "lucide-react";

export function ContactUs() {
  const { toast } = useToast();
  const form = useRef<HTMLFormElement>(null);
  const [isSubmitting, setIsSubmitting] = useState(false);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();

    if (!form.current) return;

    setIsSubmitting(true);

    try {
      // Replace 'YOUR_TEMPLATE_ID' with your actual EmailJS template ID
      await emailjs.sendForm(
        "service_5w533ca",
        "template_ic1fwsh",
        form.current,
        "1k54EwsJh8dEEwjlq"
      );

      toast({
        title: "Message Sent!",
        description: "We'll get back to you as soon as possible.",
      });

      // Reset form
      form.current.reset();
    } catch (error) {
      console.error('EmailJS send error:', error);
      toast({
        title: "Failed to Send Message",
        description: "Please try again later or contact us directly at brastyphler17@gmail.com",
        variant: "destructive",
      });
    } finally {
      setIsSubmitting(false);
    }
  };

  const contactMethods = [
    {
      icon: Mail,
      title: "Email Support",
      description: "Send us an email",
      contact: "brastyphler17@gmail.com",
      action: "mailto:brastyphler17@gmail.com"
    },
    {
      icon: MessageSquare,
      title: "Live Chat",
      description: "Chat with our team",
      contact: "Available 9am-5pm EST",
      action: "#"
    },
    {
      icon: Phone,
      title: "Phone Support",
      description: "Call us directly",
      contact: "+32 467 81 47 42",
      action: "tel:+32467814742"
    }
  ];

  const offices = [
    {
      city: "New York",
      address: "123 Finance Street, NY 10001",
      phone: "+1 (555) 123-4567"
    },
    {
      city: "San Francisco",
      address: "456 Tech Boulevard, CA 94105",
      phone: "+1 (555) 987-6543"
    }
  ];

  return (
    <>
      <Helmet>
        <title>Contact Us - BudgetBuddy</title>
        <meta name="description" content="Get in touch with BudgetBuddy. Contact our support team for help with budgeting, financial management, and any questions you may have." />
        <meta name="keywords" content="contact BudgetBuddy, support, help, customer service, financial assistance" />
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
              Get in Touch
            </h1>
            <p className="text-xl text-gray-600 mb-8">
              Have questions about BudgetBuddy? We'd love to hear from you.
              Send us a message and we'll respond as soon as possible.
            </p>
            <Badge variant="secondary" className="mb-4">
              Response time: Usually within 24 hours
            </Badge>
          </div>
        </section>

        {/* Contact Methods */}
        <section className="container mx-auto px-4 py-20">
          <div className="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
            {contactMethods.map((method, index) => (
              <Card key={index} className="text-center hover:shadow-lg transition-shadow">
                <CardHeader>
                  <div className="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                    <method.icon className="w-8 h-8 text-primary" />
                  </div>
                  <CardTitle className="text-xl">{method.title}</CardTitle>
                  <CardDescription>{method.description}</CardDescription>
                </CardHeader>
                <CardContent>
                  <p className="font-semibold text-gray-900 mb-4">{method.contact}</p>
                  <Button variant="outline" asChild>
                    <a href={method.action}>
                      Contact Now
                    </a>
                  </Button>
                </CardContent>
              </Card>
            ))}
          </div>
        </section>

        {/* Contact Form */}
        <section className="bg-white py-20">
          <div className="container mx-auto px-4">
            <div className="max-w-2xl mx-auto">
              <div className="text-center mb-12">
                <h2 className="text-3xl font-bold text-gray-900 mb-4">
                  Send us a Message
                </h2>
                <p className="text-lg text-gray-600">
                  Fill out the form below and we'll get back to you soon.
                </p>
              </div>

              <Card>
                <CardContent className="p-8">
                  <form ref={form} onSubmit={handleSubmit} className="space-y-6">
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                      <div>
                        <label htmlFor="firstName" className="block text-sm font-medium mb-2">
                          First Name
                        </label>
                        <Input
                          id="firstName"
                          name="first_name"
                          placeholder="John"
                          required
                          className="glass"
                        />
                      </div>
                      <div>
                        <label htmlFor="lastName" className="block text-sm font-medium mb-2">
                          Last Name
                        </label>
                        <Input
                          id="lastName"
                          name="last_name"
                          placeholder="Doe"
                          required
                          className="glass"
                        />
                      </div>
                    </div>

                    <div>
                      <label htmlFor="email" className="block text-sm font-medium mb-2">
                        Email
                      </label>
                      <Input
                        id="email"
                        name="user_email"
                        type="email"
                        placeholder="john@example.com"
                        required
                        className="glass"
                      />
                    </div>

                    <div>
                      <label htmlFor="subject" className="block text-sm font-medium mb-2">
                        Subject
                      </label>
                      <Input
                        id="subject"
                        name="subject"
                        placeholder="How can we help you?"
                        required
                        className="glass"
                      />
                    </div>

                    <div>
                      <label htmlFor="message" className="block text-sm font-medium mb-2">
                        Message
                      </label>
                      <Textarea
                        id="message"
                        name="message"
                        placeholder="Tell us more about your question or issue..."
                        rows={6}
                        required
                        className="glass resize-none"
                      />
                    </div>

                    <Button
                      type="submit"
                      disabled={isSubmitting}
                      className="w-full bg-primary hover:bg-primary/90 text-primary-foreground py-6 rounded-xl text-lg disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                      {isSubmitting ? (
                        <>
                          <Loader2 className="mr-2 h-5 w-5 animate-spin" />
                          Sending...
                        </>
                      ) : (
                        <>
                          <Send className="mr-2 h-5 w-5" />
                          Send Message
                        </>
                      )}
                    </Button>
                  </form>
                </CardContent>
              </Card>
            </div>
          </div>
        </section>

        {/* Office Locations */}
        <section className="py-20">
          <div className="container mx-auto px-4">
            <div className="text-center mb-16">
              <h2 className="text-3xl font-bold text-gray-900 mb-4">
                Our Offices
              </h2>
              <p className="text-lg text-gray-600">
                Visit us at one of our locations.
              </p>
            </div>
            <div className="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
              {offices.map((office, index) => (
                <Card key={index} className="hover:shadow-lg transition-shadow">
                  <CardContent className="p-6">
                    <div className="flex items-start gap-4">
                      <MapPin className="w-6 h-6 text-primary mt-1" />
                      <div>
                        <h3 className="font-semibold text-lg mb-2">{office.city}</h3>
                        <p className="text-gray-600 mb-2">{office.address}</p>
                        <p className="text-gray-600">{office.phone}</p>
                      </div>
                    </div>
                  </CardContent>
                </Card>
              ))}
            </div>
          </div>
        </section>

        {/* Business Hours */}
        <section className="bg-gray-50 py-20">
          <div className="container mx-auto px-4 text-center">
            <div className="max-w-2xl mx-auto">
              <Clock className="w-16 h-16 text-primary mx-auto mb-6" />
              <h2 className="text-3xl font-bold text-gray-900 mb-4">
                Business Hours
              </h2>
              <div className="space-y-2 text-lg">
                <p><strong>Monday - Friday:</strong> 9:00 AM - 5:00 PM EST</p>
                <p><strong>Saturday:</strong> 10:00 AM - 2:00 PM EST</p>
                <p><strong>Sunday:</strong> Closed</p>
              </div>
              <p className="text-gray-600 mt-6">
                We typically respond to emails within 24 hours during business days.
              </p>
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
                  <li><Link to="/help" className="hover:text-white transition-colors">Help Center</Link></li>
                  <li><Link to="/contact" className="hover:text-white transition-colors">Contact Us</Link></li>
                  <li><Link to="/privacy" className="hover:text-white transition-colors">Privacy Policy</Link></li>
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
