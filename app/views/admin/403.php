<div class="flex flex-col items-center justify-center min-h-[60vh] text-center px-4">
    <div class="text-6xl font-bold text-red-500 mb-4">403</div>
    <h1 class="text-2xl font-semibold text-gray-800 mb-2">Access Denied</h1>
    <p class="text-gray-500 mb-6"><?php echo htmlspecialchars($message ?? 'You do not have permission to perform this action.'); ?></p>
    <a href="<?php echo BASE_URL; ?>/admin" class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
        Back to Dashboard
    </a>
</div>
