<?php
/**
 * Budget Card Partial
 * 
 * Expected variables:
 * @var string $title
 * @var string $amount
 * @var int|null $percentage
 * @var string|null $icon (Lucide icon name)
 * @var string $variant (default|success|warning|destructive|accent)
 */

$variant = $variant ?? 'default';
$variantStyles = [
    'default' => 'border-primary',
    'success' => 'border-secondary',
    'warning' => 'border-orange-500',
    'destructive' => 'border-red-500',
    'accent' => 'border-accent',
];

$progressColors = [
    'default' => 'bg-primary',
    'success' => 'bg-secondary',
    'warning' => 'bg-orange-500',
    'destructive' => 'bg-red-500',
    'accent' => 'bg-accent',
];

$currentStyle = $variantStyles[$variant] ?? $variantStyles['default'];
$progressColor = $progressColors[$variant] ?? $progressColors['default'];

// Defensive defaults
$title = $title ?? 'Metric';
$amount = $amount ?? '$0.00';
?>

<div class="glowing-wrapper">
    <div class="glowing-effect-container"></div>
    <div class="glass-card p-6 border-l-4 hover-lift active:scale-[0.98] transition-all duration-300 <?php echo $currentStyle; ?> relative z-10 h-full">
        <div class="flex items-start justify-between mb-4">
            <p class="text-xs font-bold text-gray-500 dark:text-slate-300 uppercase tracking-widest">
                <?php echo htmlspecialchars((string)$title); ?>
            </p>
            <?php if (isset($icon)): ?>
                <div class="text-primary dark:text-accent">
                    <i data-lucide="<?php echo $icon; ?>" class="h-5 w-5"></i>
                </div>
            <?php endif; ?>
        </div>
        <p class="text-3xl font-display text-gray-900 dark:text-white mb-2">
            <?php echo htmlspecialchars((string)$amount); ?>
        </p>
        <?php if (isset($percentage)): ?>
            <div class="flex items-center gap-2">
                <div class="flex-1 h-1.5 bg-gray-100 dark:bg-slate-800 rounded-full overflow-hidden">
                    <div
                        class="h-full rounded-full transition-all duration-1000 <?php echo $progressColor; ?>"
                        style="width: <?php echo min($percentage, 100); ?>%"
                    ></div>
                </div>
                <span class="text-xs font-bold text-gray-500 dark:text-slate-300">
                    <?php echo $percentage; ?>%
                </span>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
// Cleanup
unset($title, $amount, $percentage, $icon, $variant, $data);
?>
