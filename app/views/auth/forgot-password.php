<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50 flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">
        <div class="bg-white border border-gray-200 rounded-2xl shadow-xl overflow-hidden">
            <div class="p-8 flex flex-col items-center space-y-4 text-center">
                <h2 class="text-3xl font-bold text-gray-900 font-outfit">Forgot Password</h2>
                <p class="text-gray-500 text-sm">
                    Enter your email to verify your account
                </p>
            </div>
            
            <div class="p-8 pt-0">
                <form action="<?php echo BASE_URL; ?>/forgot-password" method="POST" class="space-y-4 text-left">
                    <?php if (isset($error)): ?>
                        <div class="p-3 rounded-md bg-red-50 border border-red-200 text-red-600 text-sm">
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>

                    <div class="space-y-2">
                        <label for="email" class="text-sm font-medium text-gray-700">
                            Email Address
                        </label>
                        <div class="relative">
                            <i data-lucide="mail" class="absolute left-3 top-3 h-4 w-4 text-gray-400"></i>
                            <input
                                id="email"
                                name="email"
                                type="email"
                                placeholder="Enter your registered email"
                                class="w-full h-10 border border-gray-300 rounded-md pl-10 text-gray-900 placeholder:text-gray-400 focus:ring-2 focus:ring-primary outline-none transition-all"
                                required
                            >
                        </div>
                    </div>

                    <button
                        type="submit"
                        class="w-full h-11 bg-primary hover:bg-primary/90 text-white font-bold rounded-md shadow-lg transition-all active:scale-95 mt-2"
                    >
                        Continue
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <a href="<?php echo BASE_URL; ?>/login" class="inline-flex items-center text-sm font-medium text-primary hover:underline">
                        <i data-lucide="arrow-left" class="mr-2 h-4 w-4"></i>
                        Back to Sign In
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
