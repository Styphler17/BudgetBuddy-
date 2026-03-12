<?php
/**
 * Home Controller for Landing Page
 */

class HomeController extends BaseController {
    
    public function index() {
        $this->render('home', [
            'title' => 'Personal Finance App',
            'hero_subtitle' => 'The all-in-one financial dashboard designed to help you track spending, save more, and achieve your financial freedom.'
        ]);
    }

    public function contact() {
        $this->render('contact', [
            'title' => 'Contact Us'
        ]);
    }

    public function privacyPolicy() {
        $this->render('privacy-policy', [
            'title' => 'Privacy Policy'
        ]);
    }

    public function help() {
        $this->render('help', [
            'title' => 'Help Center'
        ]);
    }

    public function terms() {
        $this->render('terms', [
            'title' => 'Terms of Service'
        ]);
    }

    public function security() {
        $this->render('security', [
            'title' => 'Security'
        ]);
    }

    public function cookies() {
        $this->render('cookies', [
            'title' => 'Cookie Policy'
        ]);
    }

    public function notFound() {
        $this->render('error/404', [
            'title' => 'Page Not Found'
        ]);
    }
}
