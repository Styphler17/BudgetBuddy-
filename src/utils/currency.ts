export const SUPPORTED_CURRENCIES = [
    { code: "USD", symbol: "$", name: "US Dollar" },
    { code: "EUR", symbol: "€", name: "Euro" },
    { code: "GBP", symbol: "£", name: "British Pound" },
    { code: "JPY", symbol: "¥", name: "Japanese Yen" },
    { code: "CAD", symbol: "$", name: "Canadian Dollar" },
    { code: "AUD", symbol: "$", name: "Australian Dollar" },
    { code: "GHS", symbol: "₵", name: "Ghanaian Cedi" },
    { code: "NGN", symbol: "₦", name: "Nigerian Naira" },
];

export const getCurrencySymbol = (input: string = "USD") => {
    // Normalise: extract a 3-letter uppercase currency code from any format
    // e.g. "USD ($) - US Dollar" → "USD", "eur" → "EUR", "USD" → "USD"
    const match = (input || "").match(/[A-Z]{3}/);
    const code = match ? match[0] : (input || "").toUpperCase().slice(0, 3);
    const currency = SUPPORTED_CURRENCIES.find((c) => c.code === code);
    return currency ? currency.symbol : "$";
};

export const formatCurrency = (amount: number | string, code: string = "USD") => {
    const symbol = getCurrencySymbol(code);
    const value = typeof amount === "string" ? parseFloat(amount) : amount;
    return `${symbol}${value.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
};
