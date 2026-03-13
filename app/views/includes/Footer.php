<footer class="bg-gray-900 text-gray-300 py-20 border-t border-gray-800">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-12 mb-16">
            <!-- Brand Column -->
            <div class="lg:col-span-4 space-y-6">
                <a href="<?php echo BASE_URL; ?>/" class="flex items-center group">
                    <img src="<?php echo BASE_URL; ?>/public/SpendScribe.png" alt="SpendScribe" class="h-12 w-auto object-contain transition-transform group-hover:scale-105">
                </a>
                <p class="text-gray-400 max-w-sm leading-relaxed">
                    Smart budgeting made simple. Join thousands of users who have taken control of their financial future with our intelligent platform.
                </p>
                <div class="flex items-center gap-4 pt-2">
                    <a href="#" class="h-10 w-10 rounded-lg bg-gray-800 flex items-center justify-center hover:bg-primary hover:text-white transition-all">
                        <i data-lucide="twitter" class="h-5 w-5"></i>
                    </a>
                    <a href="#" class="h-10 w-10 rounded-lg bg-gray-800 flex items-center justify-center hover:bg-primary hover:text-white transition-all">
                        <i data-lucide="instagram" class="h-5 w-5"></i>
                    </a>
                    <a href="#" class="h-10 w-10 rounded-lg bg-gray-800 flex items-center justify-center hover:bg-primary hover:text-white transition-all">
                        <i data-lucide="linkedin" class="h-5 w-5"></i>
                    </a>
                    <a href="#" class="h-10 w-10 rounded-lg bg-gray-800 flex items-center justify-center hover:bg-primary hover:text-white transition-all">
                        <i data-lucide="github" class="h-5 w-5"></i>
                    </a>
                </div>
            </div>

            <!-- Links Columns -->
            <div class="lg:col-span-2 space-y-6">
                <h3 class="font-bold text-white uppercase tracking-widest text-xs">Product</h3>
                <ul class="space-y-4 text-sm">
                    <li><a href="<?php echo BASE_URL; ?>/" class="hover:text-white transition-colors">Home</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/register" class="hover:text-white transition-colors">Start Free</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/blog" class="hover:text-white transition-colors">Blog</a></li>
                </ul>
            </div>

            <div class="lg:col-span-2 space-y-6">
                <h3 class="font-bold text-white uppercase tracking-widest text-xs">Support</h3>
                <ul class="space-y-4 text-sm">
                    <li><a href="<?php echo BASE_URL; ?>/help" class="hover:text-white transition-colors">Help Center</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/contact" class="hover:text-white transition-colors">Contact Us</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/privacy-policy" class="hover:text-white transition-colors">Privacy Policy</a></li>
                </ul>
            </div>

            <!-- Newsletter Column -->
            <div class="lg:col-span-4 space-y-6">
                <h3 class="font-bold text-white uppercase tracking-widest text-xs">Stay Updated</h3>
                <p class="text-sm text-gray-400">Get weekly money tips and platform updates directly to your inbox.</p>
                <form action="#" method="POST" class="flex gap-2">
                    <input type="email" placeholder="Email address" class="bg-gray-800 border-none rounded-lg px-4 py-2 text-sm flex-grow focus:ring-2 focus:ring-primary outline-none text-white">
                    <button type="submit" class="bg-primary hover:bg-primary/90 text-white font-bold px-4 py-2 rounded-lg text-xs uppercase tracking-widest transition-all">
                        Join
                    </button>
                </form>
                <div class="flex items-center gap-2 text-[10px] font-bold text-gray-500 uppercase tracking-widest">
                    <i data-lucide="shield-check" class="h-3 w-3"></i>
                    Zero Spam. Unsubscribe anytime.
                </div>
            </div>
        </div>

        <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-xs font-medium text-gray-500">
            <p>© <?php echo date('Y'); ?> SpendScribe. All rights reserved.</p>
            <div class="flex gap-8">
                <a href="<?php echo BASE_URL; ?>/terms" class="hover:text-white transition-colors">Terms</a>
                <a href="<?php echo BASE_URL; ?>/security" class="hover:text-white transition-colors">Security</a>
                <a href="<?php echo BASE_URL; ?>/cookies" class="hover:text-white transition-colors">Cookies</a>
            </div>
        </div>
    </div>
</footer>
