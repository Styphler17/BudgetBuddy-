<?php
/**
 * Home Controller for Landing Page
 */

class HomeController extends BaseController {
    
    public function index() {
        $this->render('home', [
            'title' => 'Personal Finance App',
            'metaDescription' => 'SpendScribe is a free manual budget app. Track expenses, plan budgets, and manage spending without bank sync or spreadsheets. Your finances, your way.',
            'hero_subtitle' => 'The all-in-one financial dashboard designed to help you track spending, save more, and achieve your financial freedom.'
        ]);
    }

    public function contact() {
        $this->render('contact', [
            'title' => 'Contact Us',
            'metaDescription' => 'Get in touch with the SpendScribe team. We\'re here to help with questions about the app, your account, or anything else.'
        ]);
    }

    public function privacyPolicy() {
        $this->render('privacy-policy', [
            'title' => 'Privacy Policy',
            'metaDescription' => 'Read SpendScribe\'s privacy policy. We collect minimal data, never sell it, and keep your financial information private and secure.'
        ]);
    }

    public function help() {
        $this->render('help', [
            'title' => 'Help Center',
            'metaDescription' => 'Find answers to common questions about SpendScribe — budgets, expenses, account settings, and more. We\'re here to help.'
        ]);
    }

    public function terms() {
        $this->render('terms', [
            'title' => 'Terms of Service',
            'metaDescription' => 'SpendScribe\'s terms of service. Read our usage guidelines, account policies, and user responsibilities before using the app.'
        ]);
    }

    public function security() {
        $this->render('security', [
            'title' => 'Security',
            'metaDescription' => 'Learn how SpendScribe protects your data with encryption, secure authentication, and a privacy-first approach to personal finance.'
        ]);
    }

    public function cookies() {
        $this->render('cookies', [
            'title' => 'Cookie Policy',
            'metaDescription' => 'SpendScribe\'s cookie policy — what cookies we use, why we use them, and how you can manage or disable them at any time.'
        ]);
    }

    public function notFound() {
        http_response_code(404);
        $this->render('error/404', [
            'title' => 'Page Not Found',
            'layout' => 'main'
        ]);
    }

    public function serverError($message = null) {
        http_response_code(500);
        $this->render('error/500', [
            'title' => 'System Error',
            'layout' => 'main',
            'error_message' => $message
        ]);
    }
}
