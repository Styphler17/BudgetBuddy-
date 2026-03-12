import { useState, useEffect } from "react";
import { getCurrencySymbol } from "@/utils/currency";
import storageService from "@/lib/storage";

/**
 * Reads the user's currency code from storage.
 * - Tries localStorage first (updated by Settings on currency change)
 * - Falls back to sessionStorage (used by storageService everywhere else)
 */
const readCurrencyCode = (): string => {
    try {
        // localStorage — written by Settings when currency changes
        const lsUser = JSON.parse(localStorage.getItem("user") || "null");
        if (lsUser?.currency) return lsUser.currency;

        // sessionStorage — written at login (primary app store via storageService)
        const ssUser = JSON.parse(storageService.getItem("user") || "null");
        if (ssUser?.currency) return ssUser.currency;
    } catch {
        // ignore JSON parse errors
    }
    return "USD";
};

/**
 * Reactive currency hook.
 * Re-renders any component using it whenever the user changes their
 * currency preference in Settings — both in the same tab and across tabs.
 *
 * Returns { currencySymbol, currencyCode }
 */
export const useCurrency = () => {
    const [currencyCode, setCurrencyCode] = useState<string>(readCurrencyCode);
    const [currencySymbol, setCurrencySymbol] = useState<string>(() =>
        getCurrencySymbol(readCurrencyCode())
    );

    useEffect(() => {
        const refresh = () => {
            const code = readCurrencyCode();
            setCurrencyCode(code);
            setCurrencySymbol(getCurrencySymbol(code));
        };

        // Re-read immediately in case storage was updated between first render
        // and this effect mounting (e.g. initial page load race)
        refresh();

        // Fires when Settings saves a new currency (same tab)
        window.addEventListener("currency-changed", refresh);
        // Fires when localStorage changes from a different tab
        window.addEventListener("storage", refresh);

        return () => {
            window.removeEventListener("currency-changed", refresh);
            window.removeEventListener("storage", refresh);
        };
    }, []);

    return { currencySymbol, currencyCode };
};
