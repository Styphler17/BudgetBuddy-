<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50 flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">
        <div class="bg-white border border-gray-200 rounded-2xl shadow-xl overflow-hidden">
            <div class="p-8 flex flex-col items-center space-y-4 text-center">
                <h2 class="text-3xl font-bold text-gray-900 font-outfit">Set New Password</h2>
                <p class="text-gray-500 text-sm">
                    Choose a strong password for your account.
                </p>
            </div>

            <div class="p-8 pt-0">
                <?php if (isset($error)): ?>
                    <div class="p-3 rounded-md bg-red-50 border border-red-200 text-red-600 text-sm mb-4">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <form action="<?php echo BASE_URL; ?>/reset-password" method="POST" class="space-y-4 text-left">
                    <?php echo BaseController::csrfField(); ?>
                    <input type="hidden" name="token" value="<?php echo $token; ?>">

                    <div class="space-y-2">
                        <label for="password" class="text-sm font-medium text-gray-700">New Password</label>
                        <div class="relative">
                            <i data-lucide="lock" class="absolute left-3 top-3 h-4 w-4 text-gray-400"></i>
                            <input
                                id="password"
                                name="password"
                                type="password"
                                placeholder="At least 8 characters"
                                minlength="8"
                                class="w-full h-10 border border-gray-300 rounded-md pl-10 text-gray-900 placeholder:text-gray-400 focus:ring-2 focus:ring-primary outline-none transition-all"
                                required
                            >
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="confirm_password" class="text-sm font-medium text-gray-700">Confirm Password</label>
                        <div class="relative">
                            <i data-lucide="lock" class="absolute left-3 top-3 h-4 w-4 text-gray-400"></i>
                            <input
                                id="confirm_password"
                                name="confirm_password"
                                type="password"
                                placeholder="Repeat your new password"
                                minlength="8"
                                class="w-full h-10 border border-gray-300 rounded-md pl-10 text-gray-900 placeholder:text-gray-400 focus:ring-2 focus:ring-primary outline-none transition-all"
                                required
                            >
                        </div>
                    </div>

                    <button
                        type="submit"
                        class="w-full h-11 bg-primary hover:bg-primary/90 text-white font-bold rounded-md shadow-lg transition-all active:scale-95 mt-2"
                    >
                        Reset Password
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
