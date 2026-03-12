type StorageLike = Pick<Storage, "getItem" | "setItem" | "removeItem" | "clear" | "key" | "length">;

const createFallbackStorage = (): StorageLike => {
  const store = new Map<string, string>();
  return {
    get length() {
      return store.size;
    },
    clear() {
      store.clear();
    },
    getItem(key: string) {
      return store.has(key) ? store.get(key)! : null;
    },
    key(index: number) {
      return Array.from(store.keys())[index] ?? null;
    },
    removeItem(key: string) {
      store.delete(key);
    },
    setItem(key: string, value: string) {
      store.set(key, value);
    }
  };
};

const resolveStorage = (): StorageLike => {
  if (typeof window === "undefined") {
    return createFallbackStorage();
  }

  try {
    const testKey = "__budgetbuddy_storage_test__";
    window.sessionStorage.setItem(testKey, testKey);
    window.sessionStorage.removeItem(testKey);
    return window.sessionStorage;
  } catch (error) {
    console.warn("Session storage unavailable, falling back to in-memory store:", error);
    return createFallbackStorage();
  }
};

const storage = resolveStorage();

export const storageService = {
  getItem(key: string) {
    return storage.getItem(key);
  },
  setItem(key: string, value: string) {
    storage.setItem(key, value);
  },
  removeItem(key: string) {
    storage.removeItem(key);
  },
  clear() {
    storage.clear();
  }
};

export default storageService;
