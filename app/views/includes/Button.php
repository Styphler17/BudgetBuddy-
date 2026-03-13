<?php
/**
 * Reusable Button Component with Dark Mode Support
 */

$text = $text ?? '';
$type = $type ?? 'button';
$href = $href ?? '#';
$variant = $variant ?? 'primary';
$size = $size ?? 'md';
$icon = $icon ?? null;
$class = $class ?? '';
$id = $id ?? '';
$attr = $attr ?? '';

// Base classes
$baseClasses = "inline-flex items-center justify-center rounded-xl font-bold transition-all active:scale-95 btn-press cursor-pointer";

// Variant classes with dark mode support
$variants = [
    'primary' => 'bg-primary text-white hover:bg-primary/90 shadow-lg shadow-primary/20 dark:shadow-none',
    'secondary' => 'bg-accent text-primary hover:bg-accent/90 shadow-lg shadow-accent/20 dark:shadow-none',
    'outline' => 'border-2 border-gray-200 dark:border-white/10 bg-white dark:bg-transparent text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5 hover:border-primary/20 dark:hover:border-accent hover:text-primary dark:hover:text-accent',
    'ghost' => 'bg-transparent text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white',
    'destructive' => 'bg-red-600 text-white hover:bg-red-700 shadow-lg shadow-red-600/20 dark:shadow-none',
    'success' => 'bg-green-600 text-white hover:bg-green-700 shadow-lg shadow-green-600/20 dark:shadow-none',
    'glow' => 'bg-white dark:bg-slate-900 text-gray-900 dark:text-white border border-gray-200 dark:border-white/10 btn-glow-sophisticated',
];

// Size classes
$sizes = [
    'sm' => 'h-9 px-4 text-xs',
    'md' => 'h-11 px-6 text-sm',
    'lg' => 'h-14 px-10 text-lg',
];

$variantClass = $variants[$variant] ?? $variants['primary'];
$sizeClass = $sizes[$size] ?? $sizes['md'];
$finalClasses = "{$baseClasses} {$variantClass} {$sizeClass} {$class}";

if ($type === 'a'): ?>
    <a href="<?php echo $href; ?>" class="<?php echo $finalClasses; ?>" <?php echo $id ? "id='$id'" : ''; ?> <?php echo $attr; ?>>
        <?php if ($variant === 'glow'): ?><div class="glowing-effect-container" style="position: absolute; z-index: 0;"></div><?php endif; ?>
        <span class="relative z-10 flex items-center">
            <?php if ($icon): ?><i data-lucide="<?php echo $icon; ?>" class="mr-2 <?php echo $size === 'sm' ? 'w-3 h-3' : ($size === 'lg' ? 'w-5 h-5' : 'w-4 h-4'); ?>"></i><?php endif; ?>
            <?php echo htmlspecialchars($text); ?>
        </span>
    </a>
<?php else: ?>
    <button type="<?php echo $type; ?>" class="<?php echo $finalClasses; ?>" <?php echo $id ? "id='$id'" : ''; ?> <?php echo $attr; ?>>
        <?php if ($variant === 'glow'): ?><div class="glowing-effect-container" style="position: absolute; z-index: 0;"></div><?php endif; ?>
        <span class="relative z-10 flex items-center">
            <?php if ($icon): ?><i data-lucide="<?php echo $icon; ?>" class="mr-2 <?php echo $size === 'sm' ? 'w-3 h-3' : ($size === 'lg' ? 'w-5 h-5' : 'w-4 h-4'); ?>"></i><?php endif; ?>
            <?php echo htmlspecialchars($text); ?>
        </span>
    </button>
<?php endif; ?>

<?php
// Cleanup variables to prevent leakage to the next include
unset($text, $type, $href, $variant, $size, $icon, $class, $id, $attr);
?>
