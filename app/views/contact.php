<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50 dark:from-slate-900 dark:via-slate-950 dark:to-slate-900 transition-colors duration-300">
    <!-- Hero Section -->
    <section class="container mx-auto px-4 py-12 text-center pt-32">
        <div class="max-w-3xl mx-auto">
            <h1 class="text-5xl font-bold text-gray-900 dark:text-white mb-4 font-outfit">
                Get in Touch
            </h1>
            <p class="text-xl text-gray-600 dark:text-slate-300 mb-6 font-medium">
                Have questions about SpendScribe? We'd love to hear from you.
                Send us a message and we'll respond as soon as possible.
            </p>
            <span class="inline-flex items-center rounded-full border border-primary/10 dark:border-accent/20 bg-primary/5 dark:bg-accent/5 px-4 py-1.5 text-xs font-bold uppercase tracking-widest text-primary dark:text-accent mb-4 shadow-sm relative overflow-hidden">
                <span class="relative z-10">Response time: Usually within 24 hours</span>
            </span>
        </div>
    </section>

    <!-- Contact Methods -->
    <section class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16 max-w-6xl mx-auto">
            <?php
            $methods = [
                [
                    "icon" => "mail",
                    "title" => "Email Support",
                    "description" => "Send us an email",
                    "contact" => "brastyphler17@gmail.com",
                    "action" => "mailto:brastyphler17@gmail.com"
                ],
                [
                    "icon" => "message-square",
                    "title" => "Live Chat",
                    "description" => "Chat with our team",
                    "contact" => "Available 9am-5pm EST",
                    "action" => "#"
                ],
                [
                    "icon" => "phone",
                    "title" => "Phone Support",
                    "description" => "Call us directly",
                    "contact" => "+32 467 81 47 42",
                    "action" => "tel:+32467814742"
                ]
            ];

            foreach ($methods as $m): ?>
                <div class="glowing-wrapper">
                    <div class="glowing-effect-container"></div>
                    <div class="relative bg-white dark:bg-slate-900 p-8 rounded-[1.5rem] border border-gray-200 dark:border-white/10 shadow-sm text-center transition-all h-full z-10">
                        <div class="w-16 h-16 bg-primary/10 dark:bg-accent/10 rounded-2xl flex items-center justify-center mx-auto mb-6">
                            <i data-lucide="<?php echo $m['icon']; ?>" class="w-8 h-8 text-primary dark:text-accent"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-1 font-outfit"><?php echo $m['title']; ?></h3>
                        <p class="text-sm text-gray-500 dark:text-slate-400 mb-4 font-medium"><?php echo $m['description']; ?></p>
                        <p class="font-bold text-gray-900 dark:text-white mb-6"><?php echo $m['contact']; ?></p>
                        <?php 
                            $text = 'Contact Now';
                            $type = 'a';
                            $href = $m['action'];
                            $variant = 'outline';
                            $size = 'md';
                            $class = 'rounded-xl border-2 dark:border-white/10 h-11 px-8 dark:text-white dark:hover:bg-white/5';
                            include APP_PATH . '/views/includes/Button.php';
                        ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Contact Form Section -->
    <section class="bg-white dark:bg-slate-950 py-24 border-y border-gray-100 dark:border-white/5">
        <div class="container mx-auto px-4">
            <div class="max-w-5xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-gray-900 dark:text-white mb-4 font-outfit">
                        Send us a Message
                    </h2>
                    <p class="text-lg text-gray-600 dark:text-slate-400 font-medium">
                        Fill out the form below and our team will get back to you shortly.
                    </p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-5 gap-16">
                    <!-- Form -->
                    <div class="lg:col-span-3">
                        <form id="contact-form" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label for="firstName" class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest ml-1">First Name</label>
                                    <input type="text" id="firstName" name="from_name" placeholder="John" class="w-full h-12 border border-gray-200 dark:border-white/10 bg-white dark:bg-slate-900 rounded-xl px-4 text-sm font-medium dark:text-white focus:ring-4 focus:ring-primary/5 dark:focus:ring-accent/5 focus:border-primary dark:focus:border-accent outline-none transition-all" required>
                                </div>
                                <div class="space-y-2">
                                    <label for="lastName" class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest ml-1">Last Name</label>
                                    <input type="text" id="lastName" name="last_name" placeholder="Doe" class="w-full h-12 border border-gray-200 dark:border-white/10 bg-white dark:bg-slate-900 rounded-xl px-4 text-sm font-medium dark:text-white focus:ring-4 focus:ring-primary/5 dark:focus:ring-accent/5 focus:border-primary dark:focus:border-accent outline-none transition-all" required>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label for="email" class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest ml-1">Email Address</label>
                                <input type="email" id="email" name="reply_to" placeholder="john@example.com" class="w-full h-12 border border-gray-200 dark:border-white/10 bg-white dark:bg-slate-900 rounded-xl px-4 text-sm font-medium dark:text-white focus:ring-4 focus:ring-primary/5 dark:focus:ring-accent/5 focus:border-primary dark:focus:border-accent outline-none transition-all" required>
                            </div>
                            <div class="space-y-2">
                                <label for="subject" class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest ml-1">Subject</label>
                                <input type="text" id="subject" name="subject" placeholder="How can we help you?" class="w-full h-12 border border-gray-200 dark:border-white/10 bg-white dark:bg-slate-900 rounded-xl px-4 text-sm font-medium dark:text-white focus:ring-4 focus:ring-primary/5 dark:focus:ring-accent/5 focus:border-primary dark:focus:border-accent outline-none transition-all" required>
                            </div>
                            <div class="space-y-2">
                                <label for="message" class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest ml-1">Message</label>
                                <textarea id="message" name="message" rows="6" placeholder="Tell us more about your inquiry..." class="w-full border border-gray-200 dark:border-white/10 bg-white dark:bg-slate-900 rounded-2xl p-4 text-sm font-medium dark:text-white focus:ring-4 focus:ring-primary/5 dark:focus:ring-accent/5 focus:border-primary dark:focus:border-accent outline-none transition-all resize-none" required></textarea>
                            </div>
                            <?php 
                                $text = 'Send Message';
                                $type = 'submit';
                                $variant = 'primary';
                                $size = 'md';
                                $icon = 'send';
                                $class = 'w-full py-4 rounded-xl';
                                include APP_PATH . '/views/includes/Button.php';
                            ?>
                        </form>
                    </div>

                    <!-- Info -->
                    <div class="lg:col-span-2 space-y-8">
                        <div class="bg-gray-50 dark:bg-slate-900 p-8 rounded-3xl border border-gray-100 dark:border-white/5 shadow-inner">
                            <div class="flex items-center gap-3 mb-8">
                                <div class="h-10 w-10 bg-primary/10 dark:bg-accent/10 rounded-xl flex items-center justify-center text-primary dark:text-accent">
                                    <i data-lucide="clock" class="w-5 h-5"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white font-outfit">Business Hours</h3>
                            </div>
                            <div class="space-y-4 text-sm text-gray-600 dark:text-slate-300 font-medium">
                                <div class="flex justify-between items-center">
                                    <span>Monday - Friday</span>
                                    <span class="text-gray-900 dark:text-white">9:00 AM - 5:00 PM EST</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span>Saturday</span>
                                    <span class="text-gray-900 dark:text-white">10:00 AM - 2:00 PM EST</span>
                                </div>
                                <div class="flex justify-between items-center pt-4 border-t border-gray-200 dark:border-white/5">
                                    <span class="text-gray-400 dark:text-slate-500">Sunday</span>
                                    <span class="text-rose-500 font-bold uppercase tracking-widest text-[10px]">Closed</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-primary/5 dark:bg-slate-900/50 p-8 rounded-3xl border border-primary/10 dark:border-accent/10 relative overflow-hidden group">
                            <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:rotate-12 transition-transform duration-700">
                                <i data-lucide="shield-check" class="w-32 h-32 text-primary dark:text-accent"></i>
                            </div>
                            <div class="flex items-center gap-3 mb-4 relative z-10">
                                <i data-lucide="check-circle" class="w-6 h-6 text-primary dark:text-accent"></i>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white font-outfit">Our Commitment</h3>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-slate-300 leading-relaxed font-medium relative z-10">
                                We aim to respond to all inquiries within 24 hours during business days. For urgent matters, please use our live chat.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-24 bg-gray-50 dark:bg-slate-900/50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 dark:text-white mb-4 font-outfit">Common Questions</h2>
                <p class="text-lg text-gray-600 dark:text-slate-400 font-medium">Quick answers to things you might be wondering about.</p>
            </div>
            
            <div class="max-w-3xl mx-auto space-y-4">
                <?php
                $faqs = [
                    ["q" => "Is my financial data safe?", "a" => "Yes, we use bank-level 256-bit encryption and industry-standard security protocols to ensure your data remains private and secure."],
                    ["q" => "How much does SpendScribe cost?", "a" => "We offer a free version with all essential features. Premium plans with advanced analytics start at $4.99/month."],
                    ["q" => "Can I export my transaction history?", "a" => "Absolutely. You can export your data in CSV or PDF format anytime from your settings page."],
                    ["q" => "How do I link my bank account?", "a" => "Currently, we support manual entry and CSV imports. Automatic bank syncing is coming in late 2026."],
                    ["q" => "What happens if I delete my account?", "a" => "If you choose to delete your account, all your data is permanently wiped from our servers immediately. This cannot be undone."]
                ];
                foreach ($faqs as $i => $faq): ?>
                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200 dark:border-white/5 overflow-hidden">
                        <button onclick="toggleFaq(<?php echo $i; ?>)" class="w-full px-8 py-6 text-left flex justify-between items-center hover:bg-gray-50 dark:hover:bg-slate-800 transition-all">
                            <span class="font-bold text-gray-900 dark:text-white"><?php echo $faq['q']; ?></span>
                            <i data-lucide="chevron-down" id="faq-icon-<?php echo $i; ?>" class="w-5 h-5 text-gray-400 dark:text-slate-500 transition-transform duration-300"></i>
                        </button>
                        <div id="faq-answer-<?php echo $i; ?>" class="hidden px-8 pb-6 text-gray-600 dark:text-slate-400 leading-relaxed font-medium">
                            <?php echo $faq['a']; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const contactForm = document.getElementById('contact-form');
        
        contactForm?.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Use global ToastSave
            ToastSave.show('loading', { loadingText: 'Sending Message...' });
            
            emailjs.sendForm('service_5w533ca', 'template_ic1fwsh', this)
                .then(() => {
                    ToastSave.show('success', { 
                        successText: 'Message Sent Successfully!',
                        duration: 5000 
                    });
                    contactForm.reset();
                }, (error) => {
                    console.error('EmailJS Error:', error);
                    ToastSave.show('initial', { 
                        initialText: 'Failed to send message.',
                        saveText: 'Try Again',
                        onSave: () => contactForm.dispatchEvent(new Event('submit'))
                    });
                });
        });
    });

    function toggleFaq(id) {
        const answer = document.getElementById('faq-answer-' + id);
        const icon = document.getElementById('faq-icon-' + id);
        const isHidden = answer.classList.contains('hidden');
        
        // Close all others (optional, but cleaner)
        document.querySelectorAll('[id^="faq-answer-"]').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('[id^="faq-icon-"]').forEach(el => el.style.transform = 'rotate(0deg)');
        
        if (isHidden) {
            answer.classList.remove('hidden');
            icon.style.transform = 'rotate(180deg)';
        }
    }

    window.addEventListener('load', () => {
        lucide.createIcons();

        // Glowing Effect Controller
        const wrappers = document.querySelectorAll('.glowing-wrapper');
        const proximity = 100; // Activation distance

        const handlePointerMove = (e) => {
            wrappers.forEach(wrapper => {
                const container = wrapper.querySelector('.glowing-effect-container');
                const rect = wrapper.getBoundingClientRect();
                
                const mouseX = e.clientX;
                const mouseY = e.clientY;

                const centerX = rect.left + rect.width / 2;
                const centerY = rect.top + rect.height / 2;

                const isActive = 
                    mouseX > rect.left - proximity &&
                    mouseX < rect.right + proximity &&
                    mouseY > rect.top - proximity &&
                    mouseY < rect.bottom + proximity;

                if (isActive) {
                    container.style.setProperty('--active', '1');
                    
                    // Calculate angle
                    const angle = Math.atan2(mouseY - centerY, mouseX - centerX) * (180 / Math.PI) + 90;
                    container.style.setProperty('--start', angle);
                } else {
                    container.style.setProperty('--active', '0');
                }
            });
        };

        window.addEventListener('pointermove', handlePointerMove);
    });
</script>
