<!-- ToastSave Component -->
<div id="toast-save-wrapper" class="fixed bottom-8 left-1/2 -translate-x-1/2 z-[100] pointer-events-none transition-all duration-500 opacity-0 translate-y-10 scale-95">
    <div id="toast-save-container" class="inline-flex h-10 items-center justify-center overflow-hidden rounded-full bg-white/95 dark:bg-slate-900/95 backdrop-blur border border-black/[0.08] dark:border-white/[0.08] shadow-toast pointer-events-auto transition-all duration-500 ease-[cubic-bezier(0.175,0.885,0.32,1.275)]" style="min-width: 140px;">
        <div class="flex h-full items-center justify-between px-3 w-full">
            <!-- Content Section -->
            <div id="toast-save-content" class="flex items-center gap-2 text-slate-900 dark:text-white transition-all duration-300">
                <!-- Initial State -->
                <div id="state-initial" class="flex items-center gap-2">
                    <div class="text-slate-500 dark:text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" class="text-current">
                            <g fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" stroke="currentColor">
                                <circle cx="9" cy="9" r="7.25"></circle>
                                <line x1="9" y1="12.819" x2="9" y2="8.25"></line>
                                <path d="M9,6.75c-.552,0-1-.449-1-1s.448-1,1-1,1,.449,1,1-.448,1-1,1Z" fill="currentColor" data-stroke="none" stroke="none"></path>
                            </g>
                        </svg>
                    </div>
                    <div class="text-[13px] font-normal leading-tight whitespace-nowrap">Unsaved changes</div>
                </div>

                <!-- Loading State (Hidden by default) -->
                <div id="state-loading" class="hidden items-center gap-2">
                    <i data-lucide="loader" class="h-4 w-4 animate-spin text-slate-500"></i>
                    <div class="text-[13px] font-normal leading-tight whitespace-nowrap">Saving</div>
                </div>

                <!-- Success State (Hidden by default) -->
                <div id="state-success" class="hidden items-center gap-2">
                    <div class="p-0.5 bg-emerald-500/10 dark:bg-emerald-500/25 rounded-[99px] shadow-sm border border-emerald-500/20 dark:border-emerald-500/25 justify-center items-center gap-1.5 flex overflow-hidden">
                        <i data-lucide="check" class="w-3.5 h-3.5 text-emerald-600 dark:text-emerald-500"></i>
                    </div>
                    <div class="text-[13px] font-normal leading-tight whitespace-nowrap">Changes Saved</div>
                </div>
            </div>

            <!-- Actions Section -->
            <div id="toast-save-actions" class="ml-2 flex items-center gap-2 transition-all duration-300">
                <button id="toast-reset-btn" class="h-7 px-3 py-0 rounded-[99px] text-[13px] font-normal text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/5 transition-colors">
                    Reset
                </button>
                <button id="toast-save-btn" class="h-7 px-3 py-0 rounded-[99px] text-[13px] font-medium text-white bg-gradient-to-b from-violet-500 to-violet-600 hover:from-violet-400 hover:to-violet-500 shadow-[inset_0px_1px_0px_0px_rgba(255,255,255,0.4)] dark:shadow-[inset_0px_1px_0px_0px_rgba(255,255,255,0.2)] transition-all duration-200">
                    Save
                </button>
            </div>
        </div>
    </div>
</div>

<script>
const ToastSave = {
    wrapper: null,
    container: null,
    states: {
        initial: null,
        loading: null,
        success: null
    },
    actions: null,
    resetBtn: null,
    saveBtn: null,
    onSave: null,
    onReset: null,

    init() {
        this.wrapper = document.getElementById('toast-save-wrapper');
        this.container = document.getElementById('toast-save-container');
        this.states.initial = document.getElementById('state-initial');
        this.states.loading = document.getElementById('state-loading');
        this.states.success = document.getElementById('state-success');
        this.actions = document.getElementById('toast-save-actions');
        this.resetBtn = document.getElementById('toast-reset-btn');
        this.saveBtn = document.getElementById('toast-save-btn');

        this.resetBtn.addEventListener('click', () => {
            if (this.onReset) this.onReset();
        });

        this.saveBtn.addEventListener('click', () => {
            if (this.onSave) this.onSave();
        });
        
        lucide.createIcons();
    },

    show(state = 'initial', options = {}) {
        if (!this.wrapper) this.init();

        // Update Text if provided
        if (options.initialText) this.states.initial.querySelector('div:last-child').innerText = options.initialText;
        if (options.loadingText) this.states.loading.querySelector('div:last-child').innerText = options.loadingText;
        if (options.successText) this.states.success.querySelector('div:last-child').innerText = options.successText;
        
        this.onSave = options.onSave || null;
        this.onReset = options.onReset || null;

        // Set State
        Object.keys(this.states).forEach(k => {
            if (k === state) {
                this.states[k].classList.remove('hidden');
                this.states[k].classList.add('flex');
            } else {
                this.states[k].classList.add('hidden');
                this.states[k].classList.remove('flex');
            }
        });

        // Hide actions if not in initial state
        if (state === 'initial') {
            this.actions.classList.remove('hidden');
            this.actions.classList.add('flex');
        } else {
            this.actions.classList.add('hidden');
            this.actions.classList.remove('flex');
        }

        // Show Toast
        this.wrapper.classList.remove('opacity-0', 'translate-y-10', 'scale-95', 'pointer-events-none');
        this.wrapper.classList.add('opacity-100', 'translate-y-0', 'scale-100', 'pointer-events-auto');

        if (state === 'success' && options.duration !== null) {
            setTimeout(() => this.hide(), options.duration || 3000);
        }
    },

    hide() {
        if (!this.wrapper) return;
        this.wrapper.classList.add('opacity-0', 'translate-y-10', 'scale-95', 'pointer-events-none');
        this.wrapper.classList.remove('opacity-100', 'translate-y-0', 'scale-100', 'pointer-events-auto');
    },

    loading() {
        this.show('loading');
    },

    success(duration = 2000) {
        this.show('success', { duration });
    }
};

document.addEventListener('DOMContentLoaded', () => {
    ToastSave.init();
});
</script>