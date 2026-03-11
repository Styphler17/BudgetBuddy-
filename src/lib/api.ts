import storageService from "./storage";
import bcrypt from "bcryptjs";

type RowDataPacket = Record<string, unknown>;

export interface UserRecord {
  id: number;
  email: string;
  name: string;
  password_hash: string;
  username?: string | null;
  currency?: string;
  first_name?: string | null;
  last_name?: string | null;
}

export interface AdminRecord {
  id: number;
  email: string;
  name: string;
  password_hash: string;
  role: string;
  is_active: boolean;
  last_login?: string | null;
  created_at?: string;
}

export type BlogPostStatus = 'draft' | 'published' | 'archived';

export type BlogContentBlockType = 'paragraph' | 'heading' | 'image' | 'embed' | 'quote' | 'list';

export interface BlogContentBlock {
  type: BlogContentBlockType;
  text?: string;
  level?: 1 | 2 | 3 | 4 | 5 | 6;
  url?: string;
  alt?: string;
  caption?: string;
  items?: string[];
}

export interface BlogPostSummary {
  id: number;
  title: string;
  slug: string;
  excerpt: string | null;
  coverImageUrl: string | null;
  coverImageAlt: string | null;
  status: BlogPostStatus;
  tags: string[];
  readingTime: number;
  publishedAt: string | null;
  createdAt: string;
  updatedAt: string;
  authorId: number;
}

export interface BlogPostDetail extends BlogPostSummary {
  contentBlocks: BlogContentBlock[];
  metaTitle: string | null;
  metaDescription: string | null;
  metaKeywords: string[];
  featureEmbedUrl: string | null;
  authorId: number;
}

export interface BlogPostCreateInput {
  adminId: number;
  title: string;
  slug?: string;
  excerpt?: string | null;
  coverImageUrl?: string | null;
  coverImageAlt?: string | null;
  status?: BlogPostStatus;
  contentBlocks: BlogContentBlock[];
  tags?: string[];
  metaTitle?: string | null;
  metaDescription?: string | null;
  metaKeywords?: string[];
  featureEmbedUrl?: string | null;
  publishedAt?: string | null;
}

export interface BlogPostUpdateInput {
  title?: string;
  slug?: string;
  excerpt?: string | null;
  coverImageUrl?: string | null;
  coverImageAlt?: string | null;
  status?: BlogPostStatus;
  contentBlocks?: BlogContentBlock[];
  tags?: string[];
  metaTitle?: string | null;
  metaDescription?: string | null;
  metaKeywords?: string[];
  featureEmbedUrl?: string | null;
  publishedAt?: string | null;
}

export interface BlogListOptions {
  limit?: number;
  offset?: number;
  tag?: string;
  search?: string;
  excludeId?: number;
}

export const API_URL = import.meta.env.VITE_API_URL ? `${import.meta.env.VITE_API_URL}/api` : "http://127.0.0.1:5001/api";
const API_BASE = (import.meta.env.VITE_API_URL || "http://127.0.0.1:5001").replace(/\/$/, "");

const buildQuery = (params: Record<string, string | number | undefined>) => {
  const searchParams = new URLSearchParams();
  Object.entries(params).forEach(([key, value]) => {
    if (value === undefined || value === null || value === "") return;
    searchParams.append(key, String(value));
  });
  const qs = searchParams.toString();
  return qs ? `?${qs}` : "";
};

async function apiRequest<T>(path: string, options: RequestInit = {}) {
  const headers: HeadersInit = {
    "Content-Type": "application/json",
    ...(options.headers || {})
  };

  const fullUrl = `${API_BASE}${path}`;

  try {
    const response = await fetch(fullUrl, { ...options, headers });

    if (!response.ok) {
      let message = response.statusText;
      try {
        const data = await response.json();
        message = data?.message || message;
      } catch {
        // ignore JSON parse failure
      }
      const error = new Error(message);
      (error as any).status = response.status;
      throw error;
    }

    if (response.status === 204) {
      return undefined as T;
    }

    return (await response.json()) as T;
  } catch (error) {
    if (error instanceof TypeError && error.message === "Failed to fetch") {
      console.error(`API Fetch Error: Could not connect to ${fullUrl}. Ensure your backend server is running on port 5001.`);
    }
    throw error;
  }
}

const resolveUserId = (explicit?: number): number => {
  if (explicit && Number.isFinite(explicit)) {
    return explicit;
  }
  try {
    const stored = storageService.getItem("user");
    if (stored) {
      const parsed = JSON.parse(stored);
      if (parsed?.id) {
        return parsed.id;
      }
    }
  } catch {
    // ignore
  }
  throw new Error("User context is not available");
};

// Blog API
export const blogAPI = {
  listPublished: async (options: BlogListOptions = {}): Promise<BlogPostSummary[]> => {
    const qs = buildQuery({ ...options, status: 'published' });
    return apiRequest(`/api/blogs${qs}`);
  },

  listAll: async (options: BlogListOptions & { status?: BlogPostStatus | 'all' } = {}): Promise<BlogPostSummary[]> => {
    const qs = buildQuery(options as any);
    return apiRequest(`/api/blogs${qs}`);
  },

  getBySlug: async (slug: string): Promise<BlogPostDetail | null> => {
    return apiRequest(`/api/blogs/slug/${slug}`);
  },

  getById: async (id: number): Promise<BlogPostDetail | null> => {
    return apiRequest(`/api/blogs/${id}`);
  },

  getRelated: async (postId: number, options: { limit?: number } = {}): Promise<BlogPostSummary[]> => {
    return apiRequest(`/api/blogs/${postId}/related${buildQuery(options)}`);
  },

  create: async (input: BlogPostCreateInput): Promise<BlogPostDetail> => {
    return apiRequest('/api/blogs', {
      method: 'POST',
      body: JSON.stringify(input)
    });
  },

  update: async (id: number, input: BlogPostUpdateInput): Promise<BlogPostDetail> => {
    return apiRequest(`/api/blogs/${id}`, {
      method: 'PUT',
      body: JSON.stringify(input)
    });
  },

  delete: async (id: number) => {
    return apiRequest(`/api/blogs/${id}`, {
      method: 'DELETE'
    });
  }
};

// User API
export const userAPI = {
  create: async (userData: { email: string; name: string; passwordHash: string; currency?: string }) => {
    return apiRequest("/api/users", {
      method: "POST",
      body: JSON.stringify(userData)
    });
  },

  findById: async (id: number): Promise<UserRecord | undefined> => {
    return apiRequest(`/api/users/${id}`);
  },

  findByEmail: async (email: string): Promise<UserRecord | undefined> => {
    try {
      return await apiRequest(`/api/users/by-email${buildQuery({ email })}`);
    } catch (error: any) {
      if (error.status === 404) return undefined;
      throw error;
    }
  },

  update: async (id: number, userData: Partial<UserRecord>) => {
    return apiRequest(`/api/users/${id}`, {
      method: "PUT",
      body: JSON.stringify(userData)
    });
  }
};

// Category API
export const categoryAPI = {
  create: async (categoryData: { userId: number; name: string; emoji?: string; budget: number }) => {
    return apiRequest(`/api/users/${categoryData.userId}/categories`, {
      method: "POST",
      body: JSON.stringify(categoryData)
    });
  },

  findByUserId: async (userId: number) => {
    return apiRequest(`/api/users/${userId}/categories`);
  },

  update: async (id: number, categoryData: Partial<{ name: string; emoji: string; budget: number }>, userId?: number) => {
    const resolvedUserId = resolveUserId(userId);
    return apiRequest(`/api/users/${resolvedUserId}/categories/${id}`, {
      method: "PUT",
      body: JSON.stringify(categoryData)
    });
  },

  delete: async (id: number, userId?: number) => {
    const resolvedUserId = resolveUserId(userId);
    return apiRequest(`/api/users/${resolvedUserId}/categories/${id}`, {
      method: "DELETE"
    });
  },

  getSpendingByCategory: async (userId: number, categoryId: number) => {
    const transactions = await apiRequest<any[]>(`/api/users/${userId}/transactions${buildQuery({ categoryId })}`);
    if (Array.isArray(transactions)) {
      return transactions
        .filter((t) => t.type === "expense")
        .reduce((sum, t) => sum + parseFloat(t.amount || "0"), 0);
    }
    return 0;
  }
};

// Transaction API
export const transactionAPI = {
  create: async (transactionData: {
    userId: number;
    categoryId: number | null;
    amount: number;
    description?: string;
    type: "income" | "expense";
    date: string;
  }) => {
    return apiRequest(`/api/users/${transactionData.userId}/transactions`, {
      method: "POST",
      body: JSON.stringify(transactionData)
    });
  },

  findByUserId: async (userId: number, limit?: number) => {
    const query = buildQuery({ limit });
    return apiRequest(`/api/users/${userId}/transactions${query}`);
  },

  update: async (
    id: number,
    transactionData: Partial<{
      categoryId: number | null;
      amount: number;
      description: string;
      type: "income" | "expense";
      date: string;
    }>,
    userId?: number
  ) => {
    const resolvedUserId = resolveUserId(userId);
    return apiRequest(`/api/users/${resolvedUserId}/transactions/${id}`, {
      method: "PUT",
      body: JSON.stringify(transactionData)
    });
  },

  delete: async (id: number, userId?: number) => {
    const resolvedUserId = resolveUserId(userId);
    return apiRequest(`/api/users/${resolvedUserId}/transactions/${id}`, {
      method: "DELETE"
    });
  }
};

// Budget API
export const budgetAPI = {
  create: async (budgetData: {
    userId: number;
    period: string;
    amount: number;
    startDate: string;
    endDate: string;
  }) => {
    return apiRequest(`/api/users/${budgetData.userId}/budgets`, {
      method: "POST",
      body: JSON.stringify(budgetData)
    });
  },

  findByUserIdAndPeriod: async (userId: number, period: string) => {
    const rows = await apiRequest<any[]>(`/api/users/${userId}/budgets${buildQuery({ period })}`);
    return Array.isArray(rows) ? rows[0] : null;
  },

  update: async (id: number, budgetData: Partial<{ amount: number; startDate: string; endDate: string }>, userId?: number) => {
    const resolvedUserId = resolveUserId(userId);
    return apiRequest(`/api/users/${resolvedUserId}/budgets/${id}`, {
      method: "PUT",
      body: JSON.stringify(budgetData)
    });
  }
};

// Goal API
export const goalAPI = {
  create: async (goalData: {
    userId: number;
    name: string;
    targetAmount: number;
    currentAmount?: number;
    deadline?: string;
    categoryId?: number;
  }) => {
    return apiRequest(`/api/users/${goalData.userId}/goals`, {
      method: "POST",
      body: JSON.stringify(goalData)
    });
  },

  findByUserId: async (userId: number) => {
    return apiRequest(`/api/users/${userId}/goals`);
  },

  update: async (id: number, goalData: any, userId?: number) => {
    const resolvedUserId = resolveUserId(userId);
    return apiRequest(`/api/users/${resolvedUserId}/goals/${id}`, {
      method: "PUT",
      body: JSON.stringify(goalData)
    });
  },

  delete: async (id: number, userId?: number) => {
    const resolvedUserId = resolveUserId(userId);
    return apiRequest(`/api/users/${resolvedUserId}/goals/${id}`, {
      method: "DELETE"
    });
  }
};

// Account API
export const accountAPI = {
  create: async (accountData: any) => {
    return apiRequest(`/api/users/${accountData.userId}/accounts`, {
      method: "POST",
      body: JSON.stringify(accountData)
    });
  },

  findByUserId: async (userId: number) => {
    return apiRequest(`/api/users/${userId}/accounts`);
  },

  update: async (id: number, accountData: any, userId?: number) => {
    const resolvedUserId = resolveUserId(userId);
    return apiRequest(`/api/users/${resolvedUserId}/accounts/${id}`, {
      method: "PUT",
      body: JSON.stringify(accountData)
    });
  },

  delete: async (id: number, userId?: number) => {
    const resolvedUserId = resolveUserId(userId);
    return apiRequest(`/api/users/${resolvedUserId}/accounts/${id}`, {
      method: "DELETE"
    });
  }
};

// Settings API
export const settingsAPI = {
  get: async (userId: number, key: string) => {
    const settings = await settingsAPI.getAll(userId);
    return settings?.[key];
  },

  set: async (userId: number, key: string, value: string | boolean) => {
    return apiRequest(`/api/users/${userId}/settings/${key}`, {
      method: "PUT",
      body: JSON.stringify({ value })
    });
  },

  getAll: async (userId: number) => {
    return apiRequest(`/api/users/${userId}/settings`);
  },

  setMany: async (userId: number, values: Record<string, string | boolean>) => {
    return apiRequest(`/api/users/${userId}/settings`, {
      method: "PUT",
      body: JSON.stringify(values)
    });
  }
};

// Admin API
export const adminAPI = {
  create: async (adminData: any) => {
    return apiRequest('/api/admin/create', {
      method: "POST",
      body: JSON.stringify(adminData)
    });
  },

  findByEmail: async (email: string): Promise<AdminRecord | undefined> => {
    try {
      return await apiRequest(`/api/admin/find-by-email?email=${email}`);
    } catch (error: any) {
      if (error.status === 404) return undefined;
      throw error;
    }
  },

  findAll: async (limit?: number, offset?: number) => {
    return apiRequest(`/api/admin/admins${buildQuery({ limit, offset })}`);
  },

  update: async (id: number, adminData: any) => {
    return apiRequest(`/api/admin/update/${id}`, {
      method: "PUT",
      body: JSON.stringify(adminData)
    });
  },

  updateLastLogin: async (id: number) => {
    return apiRequest(`/api/admin/update-last-login/${id}`, {
      method: "PUT"
    });
  },

  delete: async (id: number) => {
    return apiRequest(`/api/admin/delete/${id}`, {
      method: "DELETE"
    });
  },

  getAllUsers: async (limit?: number, offset?: number) => {
    return apiRequest(`/api/admin/users${buildQuery({ limit, offset })}`);
  },

  updateUser: async (id: number, userData: any) => {
    return apiRequest(`/api/admin/users/${id}`, {
      method: "PUT",
      body: JSON.stringify(userData)
    });
  },

  deleteUser: async (id: number) => {
    return apiRequest(`/api/admin/users/${id}`, {
      method: "DELETE"
    });
  },

  getSystemStats: async () => {
    return apiRequest('/api/admin/stats');
  },

  logAction: async (adminId: number, action: string, targetType: string, targetId?: number, details?: string, ipAddress?: string) => {
    return apiRequest('/api/admin/logs', {
      method: "POST",
      body: JSON.stringify({ adminId, action, targetType, targetId, details, ipAddress })
    });
  },

  getLogs: async (limit?: number, offset?: number) => {
    return apiRequest(`/api/admin/logs${buildQuery({ limit, offset })}`);
  }
};

// System Settings API
export const systemSettingsAPI = {
  get: async (key: string) => {
    return apiRequest(`/api/system-settings/${key}`);
  },

  set: async (key: string, value: any, type: string = 'string', description?: string) => {
    return apiRequest('/api/system-settings', {
      method: "PUT",
      body: JSON.stringify({ key, value, type, description })
    });
  },

  getAll: async () => {
    return apiRequest('/api/system-settings');
  },

  delete: async (key: string) => {
    return apiRequest(`/api/system-settings/${key}`, {
      method: "DELETE"
    });
  }
};
