-- Upgrade existing 4 blog posts:
-- Fix BudgetBuddy → SpendScribe branding
-- Replace Unsplash cover images with local paths
-- Expand thin content to full articles
-- Add missing meta fields for post ID 5

-- ============================================================
-- POST 1: Zero-Based Budget Blueprint for 2025
-- ============================================================
UPDATE blog_posts SET
  cover_image_url     = '/public/blog/zero-based-budget.webp',
  cover_image_alt     = 'Open notebook with a zero-based budget written out on a wooden desk',
  meta_title          = 'Zero-Based Budget Blueprint for 2025 | SpendScribe',
  meta_description    = 'Learn how to build a zero-based budget that gives every dollar a job. Step-by-step framework for tracking income, expenses, and savings without bank syncing.',
  meta_keywords       = 'zero based budget, zero-based budgeting, budgeting for beginners, monthly budget plan, zero based budget template',
  content             = '[
  {"type":"paragraph","text":"A zero-based budget starts with a simple premise: your income minus your expenses should equal exactly zero. Not because you spend everything, but because every single dollar has a deliberate destination — bills, groceries, savings, debt repayment, or even fun money. Nothing leaks."},
  {"type":"paragraph","text":"This is not about restriction. It is about intention. When you tell every dollar where to go before the month starts, you stop wondering where your money went after it has already left."},
  {"type":"heading","text":"Step 1: Start With Your True Monthly Income"},
  {"type":"paragraph","text":"Open SpendScribe and create a new budget period. Enter your after-tax take-home income — the number that lands in your bank account. If you are salaried, this is consistent. If you are freelance, use your lowest month from the past three as your baseline. Always budget conservatively."},
  {"type":"heading","text":"Step 2: List Every Expense Category"},
  {"type":"paragraph","text":"Work through your expenses in priority order. Start with fixed non-negotiables: rent or mortgage, utilities, insurance, minimum debt payments. Then move to variable essentials: groceries, fuel, phone. Finally, discretionary items: dining out, subscriptions, entertainment, clothing."},
  {"type":"list","items":["Fixed: rent, mortgage, car payment, insurance, internet, phone","Variable essentials: groceries, petrol, medications, household items","Savings targets: emergency fund, retirement contribution, sinking funds","Debt repayment: any amount above the minimum you can throw at debt","Discretionary: dining, streaming, hobbies, personal care, gifts"]},
  {"type":"heading","text":"Step 3: Assign Every Dollar Until You Hit Zero"},
  {"type":"paragraph","text":"Add up all your category allocations. Subtract from your income. If the result is positive, you have unallocated dollars — assign them somewhere before the month starts, even if that means boosting a savings category. If the result is negative, trim discretionary categories until you reach zero."},
  {"type":"heading","text":"Step 4: Log Expenses Against Categories As You Spend"},
  {"type":"paragraph","text":"This is where SpendScribe does its job. Every time you spend, log it against the matching category. The manual act of entering it reinforces awareness. Check your category balances every few days — not to stress, but to stay informed. Catching a category running hot on the 10th gives you 20 days to adjust."},
  {"type":"heading","text":"Step 5: The 30-Minute Monthly Reset"},
  {"type":"paragraph","text":"On the last day of each month, review every category. Which ones came in under? Which went over? Carry nothing forward — start fresh with a new zero-based plan for the next month. Tweak allocations based on what you learned. Over three months, your budget becomes highly accurate to your real life."},
  {"type":"heading","text":"Common Zero-Based Budget Mistakes"},
  {"type":"list","items":["Forgetting irregular expenses: car registration, annual subscriptions, birthday gifts — create sinking funds for these","Budgeting net income but forgetting to account for taxes if self-employed","Setting discretionary allowances so tight that you blow the budget within a week — realistic beats perfect","Not logging expenses daily — batch-entering a week later means you are guessing, not tracking"]},
  {"type":"paragraph","text":"Zero-based budgeting is the most powerful budgeting method available because it forces a monthly conversation with your own priorities. It is not a spreadsheet exercise — it is a clarity exercise. Once you have done it for three months, you will never go back to guessing."}
]',
  updated_at          = NOW()
WHERE id = 1;

-- ============================================================
-- POST 2: Automate Your Cash Flow in Under an Hour
-- ============================================================
UPDATE blog_posts SET
  cover_image_url     = '/public/blog/cash-flow-automation.webp',
  cover_image_alt     = 'Minimalist desk with a planner showing recurring payment schedule',
  meta_title          = 'Automate Your Cash Flow in Under an Hour | SpendScribe',
  meta_description    = 'Build a bulletproof cash flow system in one hour. Learn how to automate bill payments, savings transfers, and debt repayment so your money moves itself every month.',
  meta_keywords       = 'automate cash flow, automate savings, automate bill payments, cash flow system, money automation, personal finance automation',
  content             = '[
  {"type":"paragraph","text":"Automation is the most underused tool in personal finance. Most people know they should save more and pay bills on time — but they rely on willpower and memory to make it happen. Willpower is finite. Memory fails. Automation does not."},
  {"type":"paragraph","text":"You can build a cash flow system that moves money to the right places the moment your paycheck lands — before you have a chance to spend it on something else. Here is how to set it up in under an hour."},
  {"type":"heading","text":"The Core Principle: Pay Yourself First, Automate Everything Else"},
  {"type":"paragraph","text":"The single most effective financial habit is saving before you spend, not after. When savings comes out of your account the same day your paycheck arrives, you never see it as available money. You adjust your lifestyle to what remains. This is not deprivation — it is engineering."},
  {"type":"heading","text":"Step 1: Map Your Fixed Outflows"},
  {"type":"paragraph","text":"Open SpendScribe and list every recurring payment with its due date and amount: rent, mortgage, car insurance, phone, utilities, streaming subscriptions. Group the ones that fall in the first half of the month and the second half. This is your cash flow calendar."},
  {"type":"heading","text":"Step 2: Set Up Automated Savings Transfers"},
  {"type":"paragraph","text":"Log in to your bank and create automatic transfers for payday — ideally the same day you are paid. Set up transfers for:"},
  {"type":"list","items":["Emergency fund: target 3–6 months of expenses, automate a fixed amount monthly until you hit it","Retirement / pension contribution: if your employer matches, contribute at least enough to capture the full match","Sinking funds: annual car registration, travel fund, Christmas gifts, home maintenance — divide the annual cost by 12 and automate that amount monthly"]},
  {"type":"heading","text":"Step 3: Schedule Bill Payments Two Days Early"},
  {"type":"paragraph","text":"Late fees are the most pointless expense in a budget. Set every bill payment to process two days before its due date — not on the due date. This buffer covers weekends, bank processing delays, and the occasional brain fog. Two days early means you never pay a late fee again."},
  {"type":"heading","text":"Step 4: Log Automated Transactions in SpendScribe"},
  {"type":"paragraph","text":"Automation does not mean ignorance. Each time a recurring payment processes, log it in SpendScribe. This takes 10 seconds and keeps your budget current. If you only log it at month end, you lose the mid-month visibility that prevents overspending. Set a weekly reminder to log any automated transactions from the past seven days."},
  {"type":"heading","text":"Step 5: Build a Small Buffer in Your Checking Account"},
  {"type":"paragraph","text":"Keep a permanent float of one to two weeks of expenses in your main account at all times. This is not savings — it is an operational buffer. It prevents overdrafts when the timing of automated payments and income deposits misalign by a day or two."},
  {"type":"heading","text":"Review Quarterly, Not Monthly"},
  {"type":"paragraph","text":"Once your automation is running, you do not need to touch it every month. Schedule a quarterly review: check that all bill amounts are still correct, adjust savings rates if income has changed, and add or remove any subscriptions. The system runs itself in between."},
  {"type":"quote","text":"Simple automation beats heroic willpower every time. Build the system once, then let it work.","caption":"SpendScribe team"},
  {"type":"paragraph","text":"An automated cash flow system is not passive. It is proactively designed. You spend one hour now so that you never have to scramble at the end of a month again. That is one of the best returns on time available in personal finance."}
]',
  updated_at          = NOW()
WHERE id = 2;

-- ============================================================
-- POST 3: Smart Savings Playbook: From Micro Wins to Mega Goals
-- ============================================================
UPDATE blog_posts SET
  cover_image_url     = '/public/blog/smart-savings.webp',
  cover_image_alt     = 'Clear glass jar with coins and a savings goal label on a wooden surface',
  meta_title          = 'Smart Savings Playbook: From Micro Wins to Mega Goals | SpendScribe',
  meta_description    = 'Turn small, consistent deposits into life-changing savings milestones. A practical savings playbook covering goal-setting, micro-saving strategies, and how to stay motivated long-term.',
  meta_keywords       = 'savings goals, micro savings, smart savings, savings tips, how to save money, personal savings plan, savings motivation',
  content             = '[
  {"type":"paragraph","text":"The gap between knowing you should save and actually doing it consistently is not a knowledge problem. It is a system problem. Most people fail at saving not because they lack discipline, but because their savings plan has no structure to carry them through the months when motivation runs dry."},
  {"type":"paragraph","text":"This playbook gives you that structure — from setting the right goals to building the habits that make saving automatic, even when life gets expensive."},
  {"type":"heading","text":"Part 1: Set Goals That Pull You Forward"},
  {"type":"paragraph","text":"Vague goals fail. \"Save more money\" is not a goal — it is a wish. A real savings goal has three components: a specific target amount, a clear deadline, and a reason that matters to you personally."},
  {"type":"list","items":["Weak goal: save for an emergency fund","Strong goal: save £3,000 emergency fund by December 31 so I never have to put a car repair on a credit card again"]},
  {"type":"paragraph","text":"Write your goal as the strong version. The emotional reason — the why — is what keeps you depositing when you could be spending."},
  {"type":"heading","text":"Part 2: Break Goals Into Monthly and Weekly Micro-Targets"},
  {"type":"paragraph","text":"Once you have a target and deadline, work backwards. A £3,000 emergency fund in 12 months requires £250 per month, or roughly £58 per week. Suddenly it is not an overwhelming number — it is a weekly habit. Log this as a fixed budget category in SpendScribe so it is treated the same as rent: non-negotiable."},
  {"type":"heading","text":"Part 3: The Micro-Win Strategy"},
  {"type":"paragraph","text":"Micro-wins are small, unexpected savings deposits made whenever you spend less than expected. Had a cheap week on groceries? Transfer the difference to savings immediately. Got a refund? It goes to savings before you even register it as available money. Skipped takeaway this week? Log the saving and transfer it."},
  {"type":"paragraph","text":"These micro-wins compound psychologically as much as financially. Each small transfer reinforces the identity of being someone who saves — and identity is the most powerful driver of long-term behaviour change."},
  {"type":"heading","text":"Part 4: Stack Multiple Goals With Separate Pots"},
  {"type":"paragraph","text":"Do not dump all savings into one account. Open separate named savings pots or accounts for each goal: Emergency Fund, Holiday, Home Deposit, New Laptop, Christmas. Seeing individual balances grow toward specific targets is far more motivating than watching one large number inch upward."},
  {"type":"list","items":["Emergency fund: first priority, 3–6 months of essential expenses","Sinking funds: known future expenses broken into monthly deposits","Dream goal: the one that excites you — holiday, home deposit, sabbatical","Opportunity fund: a smaller pot for unexpected good opportunities — a course, a tool, a chance"]},
  {"type":"heading","text":"Part 5: Protect Your Savings From Yourself"},
  {"type":"paragraph","text":"The savings account you can access with one tap is the savings account you will raid. Put meaningful savings in an account that requires 24–48 hours to withdraw, or a separate bank altogether. The friction is the point. You will spend less impulsively if access requires effort."},
  {"type":"heading","text":"Part 6: Track and Celebrate Milestones"},
  {"type":"paragraph","text":"Every 25% of the way to a goal is worth acknowledging. Not necessarily with spending — but with recognition. Tell someone. Take a screenshot of the balance. Write the milestone date in your SpendScribe notes. The act of acknowledging progress activates the reward circuit that keeps behaviour repeating."},
  {"type":"paragraph","text":"Big financial goals are built from thousands of small decisions made in the right direction. The playbook is not complicated. The consistency is. Build the system, protect it from your impulsive moments, and celebrate every milestone. The mega goal takes care of itself."}
]',
  updated_at          = NOW()
WHERE id = 3;

-- ============================================================
-- POST 5: The Best Budget App Like YNAB (YNAB Alternative)
-- Add missing meta fields and fix cover image only
-- Content left as-is (HTML format, already mostly SpendScribe branded)
-- ============================================================
UPDATE blog_posts SET
  cover_image_url     = '/public/blog/ynab-alternative.webp',
  cover_image_alt     = 'Clean budgeting interface on a laptop next to a notebook and coffee',
  meta_title          = 'Best YNAB Alternative Without Bank Sync | SpendScribe',
  meta_description    = 'Looking for a YNAB alternative that does not require bank syncing? SpendScribe is the best manual budget app — free, private, and beautifully designed.',
  meta_keywords       = 'YNAB alternative, budget app without bank sync, manual budget app, free budget app, YNAB replacement, privacy budget app',
  updated_at          = NOW()
WHERE id = 5;
