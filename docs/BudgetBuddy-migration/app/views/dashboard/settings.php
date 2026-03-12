<?php /** Settings View – profile update + password change */ ?>

<?php if (!empty($flash['success'])): ?>
<div class="mb-6 flex items-center gap-3 p-4 rounded-2xl bg-accent/10 border border-accent/20 text-accent font-bold text-sm">
    <i data-lucide="check-circle" class="h-5 w-5 flex-shrink-0"></i>
    <?php echo htmlspecialchars($flash['success']); ?>
    <button onclick="this.parentElement.remove()" class="ml-auto">✕</button>
</div>
<?php endif; ?>
<?php if (!empty($flash['error'])): ?>
<div class="mb-6 flex items-center gap-3 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-600 font-bold text-sm">
    <i data-lucide="alert-circle" class="h-5 w-5 flex-shrink-0"></i>
    <?php echo htmlspecialchars($flash['error']); ?>
    <button onclick="this.parentElement.remove()" class="ml-auto">✕</button>
</div>
<?php endif; ?>

<div class="space-y-10 animate-fade-in">
    <header class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 font-outfit tracking-tight">System Settings</h1>
            <p class="text-gray-500 font-medium mt-1">Configure your profile, security, and app preferences.</p>
        </div>
    </header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Sidebar nav -->
        <div class="space-y-3">
            <button onclick="showSection('profile')" id="nav-profile" class="settings-nav active w-full flex items-center justify-between p-5 rounded-3xl bg-primary text-white font-black shadow-2xl shadow-primary/20 transition-all group active:scale-95">
                <div class="flex items-center gap-4">
                    <div class="h-10 w-10 bg-white/20 rounded-xl flex items-center justify-center"><i data-lucide="user" class="h-5 w-5"></i></div>
                    <span class="text-sm uppercase tracking-widest">Profile</span>
                </div>
                <i data-lucide="chevron-right" class="h-4 w-4 opacity-50"></i>
            </button>
            <button onclick="showSection('security')" id="nav-security" class="settings-nav w-full flex items-center justify-between p-5 rounded-3xl bg-white border border-primary/5 text-gray-500 font-black hover:bg-primary hover:text-white hover:shadow-2xl hover:shadow-primary/10 transition-all group active:scale-95">
                <div class="flex items-center gap-4">
                    <div class="h-10 w-10 bg-primary/5 rounded-xl flex items-center justify-center group-hover:bg-white/20"><i data-lucide="shield" class="h-5 w-5"></i></div>
                    <span class="text-sm uppercase tracking-widest">Security</span>
                </div>
                <i data-lucide="chevron-right" class="h-4 w-4 opacity-0 group-hover:opacity-50"></i>
            </button>
        </div>

        <!-- Main content -->
        <div class="lg:col-span-2 space-y-8">

            <!-- PROFILE SECTION -->
            <section id="section-profile" class="glass-card p-10 space-y-8 relative overflow-hidden">
                <div class="absolute -right-20 -top-20 h-64 w-64 bg-primary opacity-[0.02] rounded-full blur-3xl pointer-events-none"></div>
                <div>
                    <h3 class="text-2xl font-black text-gray-900 font-outfit tracking-tight">Public Profile</h3>
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mt-1">Your name, email and currency preferences.</p>
                </div>
                <form method="POST" action="/BudgetBuddy-/settings/profile" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-primary uppercase tracking-[0.2em] ml-1">Full Name</label>
                            <div class="relative">
                                <i data-lucide="user" class="absolute left-4 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400"></i>
                                <input type="text" name="name" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" required class="bb-input w-full pl-12">
                            </div>
                        </div>
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-primary uppercase tracking-[0.2em] ml-1">Email Address</label>
                            <div class="relative">
                                <i data-lucide="mail" class="absolute left-4 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400"></i>
                                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required class="bb-input w-full pl-12">
                            </div>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-primary uppercase tracking-[0.2em] ml-1">Default Currency</label>
                        <div class="relative">
                            <i data-lucide="coins" class="absolute left-4 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400"></i>
                            <select name="currency" class="bb-input w-full pl-12 appearance-none">
                                <?php $currencies = ['USD'=>'US Dollar','EUR'=>'Euro','GBP'=>'Pound','JPY'=>'Yen','CAD'=>'Canadian Dollar','AUD'=>'Australian Dollar','NGN'=>'Nigerian Naira','GHS'=>'Ghanaian Cedi','KES'=>'Kenyan Shilling','ZAR'=>'South African Rand']; ?>
                                <?php foreach ($currencies as $code => $name): ?>
                                <option value="<?php echo $code; ?>" <?php echo ($user['currency'] ?? 'USD') === $code ? 'selected' : ''; ?>>
                                    <?php echo $code; ?> – <?php echo $name; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <i data-lucide="chevron-down" class="absolute right-4 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400 pointer-events-none"></i>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="h-11 px-8 rounded-2xl bg-primary text-white text-sm font-bold shadow-lg shadow-primary/20 hover:scale-[1.02] active:scale-[0.98] transition-all">
                            Save Profile
                        </button>
                    </div>
                </form>
            </section>

            <!-- SECURITY SECTION -->
            <section id="section-security" class="hidden glass-card p-10 space-y-8 relative overflow-hidden">
                <div>
                    <h3 class="text-2xl font-black text-gray-900 font-outfit tracking-tight">Change Password</h3>
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mt-1">Use a strong password of at least 6 characters.</p>
                </div>
                <form method="POST" action="/BudgetBuddy-/settings/password" class="space-y-6">
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-primary uppercase tracking-[0.2em] ml-1">Current Password</label>
                        <div class="relative">
                            <i data-lucide="lock" class="absolute left-4 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400"></i>
                            <input type="password" name="current_password" required placeholder="••••••••" class="bb-input w-full pl-12">
                        </div>
                    </div>
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-primary uppercase tracking-[0.2em] ml-1">New Password</label>
                        <div class="relative">
                            <i data-lucide="key" class="absolute left-4 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400"></i>
                            <input type="password" name="new_password" required minlength="6" placeholder="••••••••" class="bb-input w-full pl-12">
                        </div>
                    </div>
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-primary uppercase tracking-[0.2em] ml-1">Confirm New Password</label>
                        <div class="relative">
                            <i data-lucide="key" class="absolute left-4 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400"></i>
                            <input type="password" name="confirm_password" required minlength="6" placeholder="••••••••" class="bb-input w-full pl-12">
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="h-11 px-8 rounded-2xl bg-primary text-white text-sm font-bold shadow-lg shadow-primary/20 hover:scale-[1.02] active:scale-[0.98] transition-all">
                            Change Password
                        </button>
                    </div>
                </form>

                <!-- Danger zone -->
                <div class="mt-8 p-8 rounded-[1.5rem] border border-rose-100 bg-rose-50/30 space-y-4">
                    <div class="flex items-center gap-3 text-rose-500">
                        <i data-lucide="alert-triangle" class="h-5 w-5"></i>
                        <h3 class="text-lg font-bold font-outfit">Danger Zone</h3>
                    </div>
                    <p class="text-sm text-rose-600 font-medium">Once you delete your account, there is no going back. Please be certain.</p>
                    <button onclick="openModal('delete-account')" class="inline-flex h-10 items-center justify-center rounded-xl bg-white border border-rose-200 px-6 text-xs font-bold text-rose-500 hover:bg-rose-500 hover:text-white transition-all">
                        Delete My Account
                    </button>
                </div>
            </section>

        </div>
    </div>
</div>

<!-- DELETE ACCOUNT CONFIRM MODAL -->
<div id="modal-delete-account" class="modal-overlay hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm p-8 space-y-6 text-center">
        <div class="h-16 w-16 bg-rose-50 rounded-full flex items-center justify-center mx-auto">
            <i data-lucide="alert-triangle" class="h-8 w-8 text-rose-500"></i>
        </div>
        <div>
            <h2 class="text-2xl font-black text-gray-900 font-outfit">Delete Account?</h2>
            <p class="text-sm text-gray-500 mt-2">This will permanently delete all your data including transactions, accounts, goals and categories. This cannot be undone.</p>
        </div>
        <div class="flex gap-3">
            <button type="button" onclick="closeModal('delete-account')" class="flex-1 h-11 rounded-2xl border border-gray-200 text-sm font-bold text-gray-600">Cancel</button>
            <form method="POST" action="/BudgetBuddy-/settings/delete-account" class="flex-1">
                <button type="submit" class="w-full h-11 rounded-2xl bg-rose-500 text-white text-sm font-bold">Delete Everything</button>
            </form>
        </div>
    </div>
</div>

<style>
.bb-input{background:rgba(16,35,127,.03);border:1px solid rgba(16,35,127,.08);border-radius:1rem;padding:.75rem 1rem;font-size:.875rem;font-weight:600;color:#111827;outline:none;transition:all .3s}
.bb-input:focus{background:#fff;border-color:rgba(16,35,127,.2);box-shadow:0 0 0 4px rgba(16,35,127,.05)}
.bb-input.pl-12{padding-left:3rem}
</style>
<script>
function openModal(k){document.getElementById('modal-'+k).classList.remove('hidden')}
function closeModal(k){document.getElementById('modal-'+k).classList.add('hidden')}
document.querySelectorAll('.modal-overlay').forEach(el=>el.addEventListener('click',e=>{if(e.target===el)el.classList.add('hidden')}));

function showSection(key) {
    ['profile','security'].forEach(s => {
        document.getElementById('section-'+s).classList.add('hidden');
        document.getElementById('nav-'+s).classList.remove('bg-primary','text-white','shadow-2xl','shadow-primary/20');
        document.getElementById('nav-'+s).classList.add('bg-white','border','border-primary/5','text-gray-500');
    });
    document.getElementById('section-'+key).classList.remove('hidden');
    const btn = document.getElementById('nav-'+key);
    btn.classList.add('bg-primary','text-white','shadow-2xl','shadow-primary/20');
    btn.classList.remove('bg-white','border','border-primary/5','text-gray-500');
}

window.addEventListener('load', () => lucide.createIcons());
</script>
