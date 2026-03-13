<?php
/**
 * Notifications View
 */
?>

<div class="max-w-4xl mx-auto space-y-10 animate-fade-in">
    <!-- Header -->
    <header class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white font-outfit tracking-tight">Notifications</h1>
            <p class="text-gray-500 dark:text-slate-300 font-medium mt-1">Stay updated with your financial activity and alerts.</p>
        </div>
        <button class="text-sm font-bold text-primary dark:text-accent hover:underline">Mark all as read</button>
    </header>

    <!-- Notification List -->
    <div class="glass-card overflow-hidden">
        <div class="divide-y divide-gray-100/50 dark:divide-white/5">
            <?php 
            $notifications = [
                ['title' => 'Budget Alert: Food & Drink', 'message' => "You've spent 90% of your budget for Food & Drink. Slow down!", 'time' => '2 hours ago', 'type' => 'warning', 'icon' => 'alert-triangle', 'read' => false],
                ['title' => 'Large Transaction Detected', 'message' => "A transaction of $999.00 was recorded at Apple Store.", 'time' => '5 hours ago', 'type' => 'info', 'icon' => 'info', 'read' => false],
                ['title' => 'Goal Achieved! 🎊', 'message' => "Congratulations! You've reached your 'Emergency Fund' goal.", 'time' => '1 day ago', 'type' => 'success', 'icon' => 'check-circle', 'read' => true],
                ['title' => 'New Feature: AI Insights', 'message' => "We've added AI-powered insights to your analytics dashboard.", 'time' => '2 days ago', 'type' => 'system', 'icon' => 'sparkles', 'read' => true],
                ['title' => 'Account Connected', 'message' => "Your 'Main Checking' account has been successfully synced.", 'time' => '3 days ago', 'type' => 'success', 'icon' => 'link', 'read' => true],
            ];
            foreach ($notifications as $n): 
                $iconColor = 'text-primary';
                $bgColor = 'bg-primary/10';
                
                switch($n['type']) {
                    case 'warning': $iconColor = 'text-amber-500'; $bgColor = 'bg-amber-50'; break;
                    case 'success': $iconColor = 'text-accent'; $bgColor = 'bg-primary/5'; break;
                    case 'info': $iconColor = 'text-sky-500'; $bgColor = 'bg-sky-50'; break;
                    case 'system': $iconColor = 'text-brand'; $bgColor = 'bg-brand/5'; break;
                }
            ?>
            <div class="p-8 flex items-start gap-6 hover:bg-primary/[0.01] transition-all group relative <?php echo $n['read'] ? '' : 'bg-primary/[0.03] dark:bg-slate-800/20 lg:before:absolute lg:before:left-0 lg:before:top-0 lg:before:bottom-0 lg:before:w-1 lg:before:bg-primary'; ?>">
                <div class="h-14 w-14 rounded-2xl flex items-center justify-center shrink-0 shadow-sm border border-black/5 dark:border-white/10 <?php echo $bgColor; ?> dark:bg-slate-900 <?php echo $iconColor; ?> group-hover:scale-110 transition-transform duration-500">
                    <i data-lucide="<?php echo $n['icon']; ?>" class="h-6 w-6"></i>
                </div>
                <div class="flex-1 space-y-2">
                    <div class="flex items-center justify-between">
                        <h4 class="text-lg font-black text-gray-900 dark:text-white font-outfit tracking-tight"><?php echo $n['title']; ?></h4>
                        <span class="text-[10px] font-black text-gray-400 dark:text-slate-500 uppercase tracking-[0.2em]"><?php echo $n['time']; ?></span>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-slate-300 leading-relaxed font-medium max-w-2xl"><?php echo $n['message']; ?></p>
                </div>
                <?php if (!$n['read']): ?>
                <div class="h-3 w-3 rounded-full bg-primary mt-3 shadow-[0_0_10px_rgba(16,35,127,0.3)]"></div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="p-8 bg-primary/5 dark:bg-slate-900/50 text-center">
            <button class="h-12 px-8 rounded-2xl bg-white dark:bg-slate-800 border border-primary/5 dark:border-white/5 text-xs font-black uppercase tracking-widest text-gray-500 dark:text-slate-400 hover:text-primary dark:hover:text-accent hover:shadow-xl transition-all active:scale-95">
                Load older notifications
            </button>
        </div>
    </div>
</div>

<script>
    window.addEventListener('load', () => {
        lucide.createIcons();
    });
</script>
