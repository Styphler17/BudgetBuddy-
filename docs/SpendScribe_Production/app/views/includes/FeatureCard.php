<?php
/**
 * Feature Card Component
 * @param string $icon Lucide icon name
 * @param string $title Card title
 * @param string $description Card description
 */
?>
<div class="glass-card p-8 group hover:bg-white/5 transition-colors">
  <div class="h-12 w-12 rounded-xl bg-primary/10 flex items-center justify-center mb-6 group-hover:bg-primary/20 transition-colors">
    <i data-lucide="<?php echo $icon; ?>" class="h-6 w-6 text-primary"></i>
  </div>
  <h3 class="text-xl font-bold mb-3 font-outfit"><?php echo $title; ?></h3>
  <p class="text-muted-foreground text-sm leading-relaxed">
    <?php echo $description; ?>
  </p>
</div>
