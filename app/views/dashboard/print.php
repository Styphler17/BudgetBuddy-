<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financial Report | SpendScribe</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;700;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; color: black !important; }
            .print-container { width: 100% !important; max-width: none !important; margin: 0 !important; padding: 0 !important; }
            .glass-card { border: 1px solid #eee !important; box-shadow: none !important; background: white !important; }
        }
        body { font-family: 'Inter', sans-serif; background: #f8fafc; }
        .font-outfit { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="p-8">
    <div class="max-w-5xl mx-auto print-container space-y-8">
        <!-- Header -->
        <div class="flex justify-between items-start border-b-2 border-primary pb-6">
            <div class="flex items-center gap-4">
                <img src="<?php echo BASE_URL; ?>/public/SpendScribe.png" alt="SpendScribe Logo" class="h-12 w-auto">
                <div>
                    <h1 class="text-3xl font-black text-primary font-outfit tracking-tight uppercase">SpendScribe</h1>
                    <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">Financial Report</p>
                </div>
            </div>
            <div class="text-right">
                <p class="font-bold text-gray-900"><?php echo htmlspecialchars($user['name']); ?></p>
                <p class="text-xs text-gray-500"><?php echo htmlspecialchars($user['email']); ?></p>
                <p class="text-xs text-gray-500 mt-1">Generated: <?php echo date('M d, Y H:i'); ?></p>
            </div>
        </div>

        <!-- Summary Metrics -->
        <div class="grid grid-cols-3 gap-6">
            <?php 
                $income = 0; $expense = 0;
                foreach ($transactions as $tx) {
                    if ($tx['type'] === 'income') $income += $tx['amount'];
                    else $expense += $tx['amount'];
                }
            ?>
            <div class="p-4 bg-white border border-gray-200 rounded-xl">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Income</p>
                <p class="text-xl font-bold text-green-600"><?php echo CurrencyHelper::format($income, $currency); ?></p>
            </div>
            <div class="p-4 bg-white border border-gray-200 rounded-xl">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Expenses</p>
                <p class="text-xl font-bold text-rose-600"><?php echo CurrencyHelper::format($expense, $currency); ?></p>
            </div>
            <div class="p-4 bg-white border border-gray-200 rounded-xl">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Net Savings</p>
                <p class="text-xl font-bold text-primary"><?php echo CurrencyHelper::format($income - $expense, $currency); ?></p>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="px-6 py-4 font-bold text-gray-900">Date</th>
                        <th class="px-6 py-4 font-bold text-gray-900">Description</th>
                        <th class="px-6 py-4 font-bold text-gray-900">Category</th>
                        <th class="px-6 py-4 font-bold text-gray-900">Account</th>
                        <th class="px-6 py-4 font-bold text-gray-900 text-right">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php foreach ($transactions as $tx): ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500"><?php echo date('M d, Y', strtotime($tx['date'])); ?></td>
                        <td class="px-6 py-4 font-medium text-gray-900"><?php echo htmlspecialchars($tx['description']); ?></td>
                        <td class="px-6 py-4 text-gray-500"><?php echo htmlspecialchars($tx['category_name'] ?? 'N/A'); ?></td>
                        <td class="px-6 py-4 text-gray-500"><?php echo htmlspecialchars($tx['account_name'] ?? 'N/A'); ?></td>
                        <td class="px-6 py-4 text-right font-bold <?php echo $tx['type'] === 'income' ? 'text-green-600' : 'text-gray-900'; ?>">
                            <?php echo $tx['type'] === 'income' ? '+' : '-'; ?><?php echo number_format($tx['amount'], 2); ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div class="pt-8 text-center text-[10px] text-gray-400 uppercase tracking-[0.2em]">
            Generated by SpendScribe &bull; Secure Financial Management
        </div>

        <!-- Print Actions -->
        <div class="fixed bottom-8 right-8 no-print flex gap-3">
            <button onclick="window.print()" class="h-12 px-6 bg-primary text-white font-bold rounded-xl shadow-xl hover:bg-primary/90 transition-all transform active:scale-95 flex items-center gap-2">
                Save as PDF / Print
            </button>
            <button onclick="window.close(); window.history.back();" class="h-12 px-6 bg-white text-gray-600 font-bold rounded-xl shadow-lg border border-gray-200 hover:bg-gray-50 transition-all transform active:scale-95">
                Go Back
            </button>
        </div>
    </div>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
        // Auto-open print dialog
        window.onload = () => {
            setTimeout(() => { window.print(); }, 500);
        };
    </script>
</body>
</html>
