<!-- Back to Top Component -->
<button 
    id="back-to-top" 
    class="fixed bottom-8 right-8 z-50 h-12 w-12 rounded-full bg-primary dark:bg-accent text-white dark:text-primary shadow-2xl transition-all duration-500 opacity-0 invisible translate-y-10 hover:scale-110 active:scale-95 flex items-center justify-center group"
    aria-label="Back to top"
>
    <i data-lucide="arrow-up" class="h-6 w-6 group-hover:-translate-y-1 transition-transform"></i>
</button>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const backToTopBtn = document.getElementById('back-to-top');
    
    const toggleBackToTop = () => {
        if (window.scrollY > 300) {
            backToTopBtn.classList.remove('opacity-0', 'invisible', 'translate-y-10');
            backToTopBtn.classList.add('opacity-100', 'visible', 'translate-y-0');
        } else {
            backToTopBtn.classList.add('opacity-0', 'invisible', 'translate-y-10');
            backToTopBtn.classList.remove('opacity-100', 'visible', 'translate-y-0');
        }
    };

    window.addEventListener('scroll', toggleBackToTop);
    
    backToTopBtn.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
});
</script>