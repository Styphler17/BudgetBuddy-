const API_BASE = (import.meta.env.VITE_API_URL || "http://localhost:5000").replace(/\/$/, "");

export type BlogPostStatus = "draft" | "published" | "archived";

export type BlogContentBlockType = "paragraph" | "heading" | "image" | "embed" | "quote" | "list";

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

type ListPublishedOptions = {
  limit?: number;
  offset?: number;
  search?: string;
  tag?: string;
  excludeId?: number;
};

type ListAllOptions = {
  status?: BlogPostStatus | "all";
  limit?: number;
  offset?: number;
  search?: string;
  tag?: string;
};

const buildQuery = (params: Record<string, string | number | undefined>) => {
  const query = new URLSearchParams();
  Object.entries(params).forEach(([key, value]) => {
    if (value === undefined || value === null || value === "") return;
    query.append(key, String(value));
  });
  const qs = query.toString();
  return qs ? `?${qs}` : "";
};

async function request<T>(path: string, init?: RequestInit): Promise<T> {
  const response = await fetch(`${API_BASE}${path}`, {
    headers: {
      "Content-Type": "application/json"
    },
    ...init
  });

  if (!response.ok) {
    const message = await response.text();
    const error = new Error(message || `Request failed with status ${response.status}`);
    (error as Error & { status?: number }).status = response.status;
    throw error;
  }

  if (response.status === 204) {
    return undefined as T;
  }

  const data = (await response.json()) as T;
  return data;
}

export const blogAPI = {
  listPublished: (options: ListPublishedOptions = {}): Promise<BlogPostSummary[]> => {
    const query = buildQuery({
      status: "published",
      limit: options.limit,
      offset: options.offset,
      search: options.search,
      tag: options.tag,
      excludeId: options.excludeId
    });
    return request<BlogPostSummary[]>(`/api/blogs${query}`);
  },

  listAll: (options: ListAllOptions = {}): Promise<BlogPostSummary[]> => {
    const query = buildQuery({
      status: options.status ?? "all",
      limit: options.limit,
      offset: options.offset,
      search: options.search,
      tag: options.tag
    });
    return request<BlogPostSummary[]>(`/api/blogs${query}`);
  },

  getById: (id: number): Promise<BlogPostDetail> => {
    return request<BlogPostDetail>(`/api/blogs/${id}`);
  },

  getBySlug: (slug: string): Promise<BlogPostDetail | null> => {
    return request<BlogPostDetail>(`/api/blogs/slug/${slug}`).catch((error: Error & { status?: number }) => {
      if (error.status === 404) {
        return null;
      }
      throw error;
    });
  },

  getRelated: (id: number, options: { limit?: number } = {}): Promise<BlogPostSummary[]> => {
    const query = buildQuery({ limit: options.limit });
    return request<BlogPostSummary[]>(`/api/blogs/${id}/related${query}`);
  },

  create: (input: BlogPostCreateInput): Promise<BlogPostDetail> => {
    return request<BlogPostDetail>(`/api/blogs`, {
      method: "POST",
      body: JSON.stringify(input)
    });
  },

  update: (id: number, input: BlogPostUpdateInput): Promise<BlogPostDetail> => {
    return request<BlogPostDetail>(`/api/blogs/${id}`, {
      method: "PUT",
      body: JSON.stringify(input)
    });
  },

  delete: (id: number): Promise<void> => {
    return request<void>(`/api/blogs/${id}`, {
      method: "DELETE"
    });
  }
};
