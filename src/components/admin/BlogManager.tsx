import { useCallback, useEffect, useMemo, useState } from "react";
import { Link } from "react-router-dom";
import {
  BlogContentBlock,
  BlogContentBlockType,
  BlogPostDetail,
  BlogPostStatus,
  BlogPostSummary,
  blogAPI
} from "@/lib/api";
import { useToast } from "@/hooks/use-toast";
import { Button } from "@/components/ui/button";
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Textarea } from "@/components/ui/textarea";
import { Label } from "@/components/ui/label";
import { Badge } from "@/components/ui/badge";
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow
} from "@/components/ui/table";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue
} from "@/components/ui/select";
import {
  AlertDialog,
  AlertDialogAction,
  AlertDialogCancel,
  AlertDialogContent,
  AlertDialogDescription,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogTitle
} from "@/components/ui/alert-dialog";
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger
} from "@/components/ui/dropdown-menu";
import { AspectRatio } from "@/components/ui/aspect-ratio";
import { BlogContentRenderer } from "@/components/blog/BlogContentRenderer";
import {
  ArrowDown,
  ArrowUp,
  Copy,
  Edit,
  Eye,
  FilePlus,
  FileText,
  Image as ImageIcon,
  Link2,
  List as ListIcon,
  Loader2,
  Plus,
  Quote,
  RefreshCw,
  Save,
  Trash2,
  Type,
  X
} from "lucide-react";

interface BlogManagerProps {
  adminId: number | null;
}

type BlogFilters = {
  status: "all" | BlogPostStatus;
  search: string;
  tag: string;
};

interface BlogEditorState {
  title: string;
  slug: string;
  excerpt: string;
  coverImageUrl: string;
  coverImageAlt: string;
  featureEmbedUrl: string;
  status: BlogPostStatus;
  tags: string;
  metaTitle: string;
  metaDescription: string;
  metaKeywords: string;
  contentBlocks: BlogContentBlock[];
}

const defaultContentBlocks = (): BlogContentBlock[] => [
  { type: "heading", level: 2, text: "Untitled section" },
  { type: "paragraph", text: "Start writing your story..." }
];

const defaultEditorState = (): BlogEditorState => ({
  title: "",
  slug: "",
  excerpt: "",
  coverImageUrl: "",
  coverImageAlt: "",
  featureEmbedUrl: "",
  status: "draft",
  tags: "",
  metaTitle: "",
  metaDescription: "",
  metaKeywords: "",
  contentBlocks: defaultContentBlocks()
});

const blockTemplates: Record<BlogContentBlockType, BlogContentBlock> = {
  heading: { type: "heading", level: 2, text: "Section heading" },
  paragraph: { type: "paragraph", text: "Write your paragraph content here." },
  image: {
    type: "image",
    url: "",
    alt: "",
    caption: ""
  },
  embed: {
    type: "embed",
    url: "",
    caption: ""
  },
  quote: {
    type: "quote",
    text: "Add a standout quote or testimonial.",
    caption: ""
  },
  list: {
    type: "list",
    items: ["First point", "Second point", "Third point"]
  }
};

const blockLabels: Record<BlogContentBlockType, string> = {
  heading: "Heading",
  paragraph: "Paragraph",
  image: "Image",
  embed: "Embed",
  quote: "Quote",
  list: "List"
};

const blockIcons: Record<BlogContentBlockType, JSX.Element> = {
  heading: <Type className="h-4 w-4" />,
  paragraph: <FileText className="h-4 w-4" />,
  image: <ImageIcon className="h-4 w-4" />,
  embed: <Link2 className="h-4 w-4" />,
  quote: <Quote className="h-4 w-4" />,
  list: <ListIcon className="h-4 w-4" />
};

const statusBadgeVariant: Record<BlogPostStatus, "default" | "secondary" | "outline" | "destructive"> = {
  published: "default",
  draft: "secondary",
  archived: "outline"
};

const cloneBlock = (block: BlogContentBlock): BlogContentBlock => ({
  ...block,
  items: block.items ? [...block.items] : undefined
});

const csvToArray = (value: string) =>
  value
    .split(",")
    .map((item) => item.trim())
    .filter(Boolean);

const arrayToCsv = (values: string[]) => values.join(", ");

const slugifyPreview = (value: string) =>
  value
    .toLowerCase()
    .replace(/[^a-z0-9\s-]/g, "")
    .trim()
    .replace(/\s+/g, "-")
    .replace(/-+/g, "-");

const estimateReadingMinutes = (blocks: BlogContentBlock[]) => {
  const wordsPerMinute = 220;
  const totalWords = blocks.reduce((count, block) => {
    if (block.type === "list" && block.items) {
      return count + block.items.join(" ").split(/\s+/).filter(Boolean).length;
    }
    if ("text" in block && block.text) {
      return count + block.text.split(/\s+/).filter(Boolean).length;
    }
    return count;
  }, 0);
  return Math.max(1, Math.ceil(totalWords / wordsPerMinute));
};

const toEditorState = (post: BlogPostDetail): BlogEditorState => ({
  title: post.title,
  slug: post.slug,
  excerpt: post.excerpt ?? "",
  coverImageUrl: post.coverImageUrl ?? "",
  coverImageAlt: post.coverImageAlt ?? "",
  featureEmbedUrl: post.featureEmbedUrl ?? "",
  status: post.status,
  tags: arrayToCsv(post.tags),
  metaTitle: post.metaTitle ?? "",
  metaDescription: post.metaDescription ?? "",
  metaKeywords: arrayToCsv(post.metaKeywords),
  contentBlocks: post.contentBlocks.map(cloneBlock)
});

const formatDate = (value?: string | null) => {
  if (!value) return "—";
  try {
    return new Date(value).toLocaleDateString(undefined, {
      year: "numeric",
      month: "short",
      day: "numeric"
    });
  } catch {
    return value;
  }
};

export const BlogManager = ({ adminId }: BlogManagerProps) => {
  const { toast } = useToast();
  const [filters, setFilters] = useState<BlogFilters>({ status: "all", search: "", tag: "" });
  const [searchInput, setSearchInput] = useState("");
  const [tagInput, setTagInput] = useState("");
  const [posts, setPosts] = useState<BlogPostSummary[]>([]);
  const [loadingPosts, setLoadingPosts] = useState(false);
  const [editorOpen, setEditorOpen] = useState(false);
  const [editorMode, setEditorMode] = useState<"create" | "edit">("create");
  const [editingPost, setEditingPost] = useState<BlogPostDetail | null>(null);
  const [editorLoading, setEditorLoading] = useState(false);
  const [saving, setSaving] = useState(false);
  const [deleteTarget, setDeleteTarget] = useState<BlogPostSummary | null>(null);
  const [form, setForm] = useState<BlogEditorState>(defaultEditorState());

  const fetchPosts = useCallback(
    async (activeFilters: BlogFilters) => {
      try {
        setLoadingPosts(true);
        const data = await blogAPI.listAll(activeFilters);
        setPosts(data);
      } catch (error) {
        console.error("Failed to load blog posts", error);
        toast({
          title: "Error loading blogs",
          description: "We could not fetch the blog posts. Please try again shortly.",
          variant: "destructive"
        });
      } finally {
        setLoadingPosts(false);
      }
    },
    [toast]
  );

  useEffect(() => {
    void fetchPosts(filters);
  }, [filters, fetchPosts]);

  const uniqueTags = useMemo(() => {
    const tagSet = new Set<string>();
    posts.forEach((post) => {
      post.tags.forEach((tag) => tagSet.add(tag));
    });
    return Array.from(tagSet).sort((a, b) => a.localeCompare(b));
  }, [posts]);

  const stats = useMemo(() => {
    const published = posts.filter((post) => post.status === "published").length;
    const drafts = posts.filter((post) => post.status === "draft").length;
    const archived = posts.filter((post) => post.status === "archived").length;
    return {
      total: posts.length,
      published,
      drafts,
      archived
    };
  }, [posts]);

  const estimatedReadingTime = useMemo(
    () => estimateReadingMinutes(form.contentBlocks),
    [form.contentBlocks]
  );

  const slugPreviewValue = useMemo(() => {
    const value = form.slug || form.title;
    return value ? slugifyPreview(value) : "";
  }, [form.slug, form.title]);

  const previewPath = slugPreviewValue ? `/blog/${slugPreviewValue}` : null;

  const handleApplyFilters = () => {
    setFilters((prev) => ({
      ...prev,
      search: searchInput.trim(),
      tag: tagInput.trim()
    }));
  };

  const handleResetFilters = () => {
    setSearchInput("");
    setTagInput("");
    setFilters({ status: "all", search: "", tag: "" });
  };

  const handleCreateNew = () => {
    setEditorMode("create");
    setEditingPost(null);
    setForm(defaultEditorState());
    setEditorOpen(true);
  };

  const handleEditPost = async (postId: number) => {
    setEditorMode("edit");
    setEditorOpen(true);
    setEditorLoading(true);
    try {
      const detail = await blogAPI.getById(postId);
      if (!detail) {
        throw new Error("Blog post not found");
      }
      setEditingPost(detail);
      setForm(toEditorState(detail));
    } catch (error) {
      console.error("Failed to load blog detail", error);
      toast({
        title: "Unable to open post",
        description: "We ran into an issue loading that blog entry.",
        variant: "destructive"
      });
      setEditorOpen(false);
    } finally {
      setEditorLoading(false);
    }
  };

  const handleAddBlock = (type: BlogContentBlockType) => {
    setForm((prev) => ({
      ...prev,
      contentBlocks: [...prev.contentBlocks, cloneBlock(blockTemplates[type])]
    }));
  };

  const handleUpdateBlock = (index: number, patch: Partial<BlogContentBlock>) => {
    setForm((prev) => {
      const blocks = [...prev.contentBlocks];
      blocks[index] = { ...blocks[index], ...patch };
      return { ...prev, contentBlocks: blocks };
    });
  };

  const handleListChange = (index: number, value: string) => {
    const items = value
      .split(/\r?\n/)
      .map((item) => item.trim())
      .filter(Boolean);
    handleUpdateBlock(index, { items });
  };

  const handleRemoveBlock = (index: number) => {
    setForm((prev) => {
      const nextBlocks = prev.contentBlocks.filter((_, idx) => idx !== index);
      return {
        ...prev,
        contentBlocks: nextBlocks.length ? nextBlocks : defaultContentBlocks()
      };
    });
  };

  const handleDuplicateBlock = (index: number) => {
    setForm((prev) => {
      const blocks = [...prev.contentBlocks];
      const copy = cloneBlock(blocks[index]);
      blocks.splice(index + 1, 0, copy);
      return { ...prev, contentBlocks: blocks };
    });
  };

  const handleMoveBlock = (index: number, direction: -1 | 1) => {
    setForm((prev) => {
      const targetIndex = index + direction;
      if (targetIndex < 0 || targetIndex >= prev.contentBlocks.length) {
        return prev;
      }
      const blocks = [...prev.contentBlocks];
      const [block] = blocks.splice(index, 1);
      blocks.splice(targetIndex, 0, block);
      return { ...prev, contentBlocks: blocks };
    });
  };

  const handleCancelEdit = () => {
    setEditorOpen(false);
    setEditingPost(null);
    setForm(defaultEditorState());
    setEditorMode("create");
  };

  const handleSavePost = async () => {
    if (!form.title.trim()) {
      toast({
        title: "Title required",
        description: "Please provide a descriptive title before saving.",
        variant: "destructive"
      });
      return;
    }

    if (!form.contentBlocks.length) {
      toast({
        title: "Add content",
        description: "Your post needs at least one content block.",
        variant: "destructive"
      });
      return;
    }

    if (editorMode === "create" && !adminId) {
      toast({
        title: "Admin account missing",
        description: "Please log in again before creating a blog post.",
        variant: "destructive"
      });
      return;
    }

    const payload = {
      title: form.title.trim(),
      slug: form.slug.trim() || undefined,
      excerpt: form.excerpt.trim() || null,
      coverImageUrl: form.coverImageUrl.trim() || null,
      coverImageAlt: form.coverImageAlt.trim() || null,
      status: form.status,
      contentBlocks: form.contentBlocks,
      tags: csvToArray(form.tags),
      metaTitle: form.metaTitle.trim() || undefined,
      metaDescription: form.metaDescription.trim() || undefined,
      metaKeywords: csvToArray(form.metaKeywords),
      featureEmbedUrl: form.featureEmbedUrl.trim() || null
    };

    setSaving(true);
    try {
      if (editorMode === "create") {
        const created = await blogAPI.create({
          adminId: adminId as number,
          ...payload
        });
        setEditingPost(created);
        setForm(toEditorState(created));
        setEditorMode("edit");
        toast({
          title: "Blog draft saved",
          description: "Your new blog post is ready to review.",
          variant: "default"
        });
      } else if (editingPost) {
        const updated = await blogAPI.update(editingPost.id, payload);
        setEditingPost(updated);
        setForm(toEditorState(updated));
        toast({
          title: "Blog updated",
          description: "Changes have been saved successfully.",
          variant: "default"
        });
      }
      await fetchPosts(filters);
    } catch (error) {
      console.error("Failed to save blog", error);
      toast({
        title: "Save failed",
        description: "We could not save your changes. Please retry.",
        variant: "destructive"
      });
    } finally {
      setSaving(false);
    }
  };

  const handleDeletePost = async () => {
    if (!deleteTarget) return;
    try {
      await blogAPI.delete(deleteTarget.id);
      toast({
        title: "Blog removed",
        description: `"${deleteTarget.title}" has been deleted.`,
        variant: "default"
      });
      if (editingPost?.id === deleteTarget.id) {
        handleCancelEdit();
      }
      setDeleteTarget(null);
      await fetchPosts(filters);
    } catch (error) {
      console.error("Failed to delete blog", error);
      toast({
        title: "Delete failed",
        description: "We could not delete that entry. Try again shortly.",
        variant: "destructive"
      });
    }
  };

  const handleToggleStatus = async (post: BlogPostSummary) => {
    const nextStatus: BlogPostStatus = post.status === "published" ? "draft" : "published";
    const publishNow = nextStatus === "published";

    try {
      await blogAPI.update(post.id, {
        status: nextStatus,
        publishedAt: publishNow ? new Date().toISOString() : null
      });

      toast({
        title: publishNow ? "Post published" : "Post unpublished",
        description: publishNow
          ? `"${post.title}" is now live on the blog.`
          : `"${post.title}" has been moved back to drafts.`
      });

      await fetchPosts(filters);
    } catch (error) {
      console.error("Failed to toggle publish status", error);
      toast({
        title: "Update failed",
        description: "We couldn't update the publish status. Please try again.",
        variant: "destructive"
      });
    }
  };

  const renderBlockEditor = (block: BlogContentBlock, index: number) => (
    <Card key={`${block.type}-${index}`} className="border-dashed">
      <CardHeader className="flex flex-col gap-2 space-y-0 sm:flex-row sm:items-center sm:justify-between">
        <div className="flex items-center gap-2">
          <span className="inline-flex items-center gap-2 rounded-full border px-3 py-1 text-xs font-medium uppercase tracking-wide text-muted-foreground">
            {blockIcons[block.type]}
            {blockLabels[block.type]}
          </span>
          <span className="text-xs text-muted-foreground">Block #{index + 1}</span>
        </div>
        <div className="flex items-center gap-1">
          <Button
            type="button"
            variant="ghost"
            size="icon"
            className="h-8 w-8"
            onClick={() => handleMoveBlock(index, -1)}
            disabled={index === 0}
            title="Move block up"
          >
            <ArrowUp className="h-4 w-4" />
          </Button>
          <Button
            type="button"
            variant="ghost"
            size="icon"
            className="h-8 w-8"
            onClick={() => handleMoveBlock(index, 1)}
            disabled={index === form.contentBlocks.length - 1}
            title="Move block down"
          >
            <ArrowDown className="h-4 w-4" />
          </Button>
          <Button
            type="button"
            variant="ghost"
            size="icon"
            className="h-8 w-8"
            onClick={() => handleDuplicateBlock(index)}
            title="Duplicate block"
          >
            <Copy className="h-4 w-4" />
          </Button>
          <Button
            type="button"
            variant="ghost"
            size="icon"
            className="h-8 w-8 text-destructive hover:text-destructive"
            onClick={() => handleRemoveBlock(index)}
            title="Remove block"
          >
            <Trash2 className="h-4 w-4" />
          </Button>
        </div>
      </CardHeader>
      <CardContent className="space-y-4">
        {block.type === "heading" && (
          <div className="grid gap-4 md:grid-cols-[2fr,1fr]">
            <div className="space-y-2">
              <Label htmlFor={`block-${index}-heading`}>Heading text</Label>
              <Input
                id={`block-${index}-heading`}
                value={block.text ?? ""}
                onChange={(e) => handleUpdateBlock(index, { text: e.target.value })}
                placeholder="Introduce the section"
              />
            </div>
            <div className="space-y-2">
              <Label>Heading level</Label>
              <Select
                value={String(block.level ?? 2)}
                onValueChange={(value) =>
                  handleUpdateBlock(index, { level: Number(value) as 1 | 2 | 3 | 4 | 5 | 6 })
                }
              >
                <SelectTrigger>
                  <SelectValue />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="1">H1</SelectItem>
                  <SelectItem value="2">H2</SelectItem>
                  <SelectItem value="3">H3</SelectItem>
                  <SelectItem value="4">H4</SelectItem>
                  <SelectItem value="5">H5</SelectItem>
                  <SelectItem value="6">H6</SelectItem>
                </SelectContent>
              </Select>
            </div>
          </div>
        )}

        {block.type === "paragraph" && (
          <div className="space-y-2">
            <Label htmlFor={`block-${index}-paragraph`}>Body copy</Label>
            <Textarea
              id={`block-${index}-paragraph`}
              value={block.text ?? ""}
              onChange={(e) => handleUpdateBlock(index, { text: e.target.value })}
              rows={5}
              placeholder="Compose your paragraph content. Use line breaks to create spacing."
            />
          </div>
        )}

        {block.type === "quote" && (
          <div className="grid gap-4 md:grid-cols-[2fr,1fr]">
            <div className="space-y-2">
              <Label htmlFor={`block-${index}-quote`}>Highlight quote</Label>
              <Textarea
                id={`block-${index}-quote`}
                value={block.text ?? ""}
                onChange={(e) => handleUpdateBlock(index, { text: e.target.value })}
                rows={4}
                placeholder="Add a memorable quote or pull-out message."
              />
            </div>
            <div className="space-y-2">
              <Label htmlFor={`block-${index}-quote-caption`}>Attribution / caption</Label>
              <Input
                id={`block-${index}-quote-caption`}
                value={block.caption ?? ""}
                onChange={(e) => handleUpdateBlock(index, { caption: e.target.value })}
                placeholder="Optional author or context"
              />
            </div>
          </div>
        )}

        {block.type === "image" && (
          <div className="grid gap-4 md:grid-cols-2">
            <div className="space-y-2">
              <Label htmlFor={`block-${index}-image-url`}>Image URL</Label>
              <Input
                id={`block-${index}-image-url`}
                value={block.url ?? ""}
                onChange={(e) => handleUpdateBlock(index, { url: e.target.value })}
                placeholder="https://..."
              />
            </div>
            <div className="space-y-2">
              <Label htmlFor={`block-${index}-image-alt`}>Alt text</Label>
              <Input
                id={`block-${index}-image-alt`}
                value={block.alt ?? ""}
                onChange={(e) => handleUpdateBlock(index, { alt: e.target.value })}
                placeholder="Describe the image for accessibility"
              />
            </div>
            <div className="space-y-2 md:col-span-2">
              <Label htmlFor={`block-${index}-image-caption`}>Caption</Label>
              <Input
                id={`block-${index}-image-caption`}
                value={block.caption ?? ""}
                onChange={(e) => handleUpdateBlock(index, { caption: e.target.value })}
                placeholder="Optional caption shown below the image"
              />
            </div>
          </div>
        )}

        {block.type === "embed" && (
          <div className="space-y-2">
            <Label htmlFor={`block-${index}-embed-url`}>Embed URL</Label>
            <Input
              id={`block-${index}-embed-url`}
              value={block.url ?? ""}
              onChange={(e) => handleUpdateBlock(index, { url: e.target.value })}
              placeholder="YouTube or external link"
            />
            <p className="text-xs text-muted-foreground">
              Paste a YouTube URL for an embedded player, or any link to display it as a resource.
            </p>
            <div className="space-y-2">
              <Label htmlFor={`block-${index}-embed-caption`}>Caption</Label>
              <Input
                id={`block-${index}-embed-caption`}
                value={block.caption ?? ""}
                onChange={(e) => handleUpdateBlock(index, { caption: e.target.value })}
                placeholder="Optional caption for context"
              />
            </div>
          </div>
        )}

        {block.type === "list" && (
          <div className="space-y-2">
            <Label htmlFor={`block-${index}-list`}>List items</Label>
            <Textarea
              id={`block-${index}-list`}
              value={(block.items ?? []).join("\n")}
              onChange={(e) => handleListChange(index, e.target.value)}
              rows={4}
              placeholder="Add one list item per line"
            />
          </div>
        )}
      </CardContent>
    </Card>
  );

  return (
    <div className="space-y-6">
      <Card>
        <CardHeader className="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
          <div>
            <CardTitle>Blog Posts</CardTitle>
            <CardDescription>
              Create, publish, and maintain engaging stories for your audience.
            </CardDescription>
          </div>
          <div className="flex items-center gap-2">
            <Button
              type="button"
              variant="outline"
              size="icon"
              onClick={() => fetchPosts(filters)}
              disabled={loadingPosts}
              title="Refresh posts"
            >
              {loadingPosts ? <Loader2 className="h-4 w-4 animate-spin" /> : <RefreshCw className="h-4 w-4" />}
            </Button>
            <Button onClick={handleCreateNew} disabled={!adminId}>
              <FilePlus className="mr-2 h-4 w-4" />
              New Post
            </Button>
          </div>
        </CardHeader>
        <CardContent className="space-y-6">
          <div className="flex flex-wrap items-center gap-2 text-sm">
            <Badge variant="secondary">Total: {stats.total}</Badge>
            <Badge variant="default">Published: {stats.published}</Badge>
            <Badge variant="outline">Drafts: {stats.drafts}</Badge>
            <Badge variant="outline">Archived: {stats.archived}</Badge>
          </div>

          <div className="grid gap-4 md:grid-cols-3">
            <div className="space-y-2">
              <Label>Status</Label>
              <Select
                value={filters.status}
                onValueChange={(value) =>
                  setFilters((prev) => ({ ...prev, status: value as "all" | BlogPostStatus }))
                }
              >
                <SelectTrigger>
                  <SelectValue placeholder="Select status" />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="all">All statuses</SelectItem>
                  <SelectItem value="published">Published</SelectItem>
                  <SelectItem value="draft">Drafts</SelectItem>
                  <SelectItem value="archived">Archived</SelectItem>
                </SelectContent>
              </Select>
            </div>
            <div className="space-y-2">
              <Label htmlFor="blog-search">Search</Label>
              <Input
                id="blog-search"
                value={searchInput}
                onChange={(e) => setSearchInput(e.target.value)}
                placeholder="Search by title or excerpt"
              />
            </div>
            <div className="space-y-2">
              <Label htmlFor="blog-tag-filter">Tag filter</Label>
              <Input
                id="blog-tag-filter"
                value={tagInput}
                onChange={(e) => setTagInput(e.target.value)}
                placeholder="e.g. budgeting"
              />
            </div>
          </div>

          <div className="flex flex-wrap items-center gap-2">
            <Button type="button" size="sm" onClick={handleApplyFilters} disabled={loadingPosts}>
              Apply filters
            </Button>
            <Button type="button" variant="ghost" size="sm" onClick={handleResetFilters} disabled={loadingPosts}>
              <X className="mr-2 h-4 w-4" />
              Reset
            </Button>
            {uniqueTags.length > 0 && (
              <div className="flex flex-wrap items-center gap-2 text-xs text-muted-foreground">
                <span>Quick tags:</span>
                {uniqueTags.map((tag) => (
                  <Badge
                    key={tag}
                    variant={filters.tag === tag ? "default" : "outline"}
                    className="cursor-pointer"
                    onClick={() => {
                      setTagInput(tag);
                      setFilters((prev) => ({ ...prev, tag }));
                    }}
                  >
                    {tag}
                  </Badge>
                ))}
              </div>
            )}
          </div>

          <div className="rounded-lg border bg-card">
            {loadingPosts ? (
              <div className="flex items-center justify-center py-12">
                <Loader2 className="h-5 w-5 animate-spin text-muted-foreground" />
              </div>
            ) : posts.length === 0 ? (
              <div className="flex flex-col items-center justify-center gap-3 py-12 text-center">
                <FileText className="h-8 w-8 text-muted-foreground" />
                <div>
                  <p className="text-sm font-medium">No blog posts found</p>
                  <p className="text-sm text-muted-foreground">
                    Adjust your filters or create a new post to get started.
                  </p>
                </div>
              </div>
            ) : (
              <>
                <div className="grid gap-4 p-4 md:hidden">
                  {posts.map((post) => (
                    <div key={post.id} className="rounded-lg border bg-muted/40 p-4 space-y-3">
                      <div className="flex items-start justify-between gap-3">
                        <div className="space-y-1">
                          <p className="font-semibold text-foreground">{post.title}</p>
                          {post.excerpt && (
                            <p className="text-sm text-muted-foreground line-clamp-2">{post.excerpt}</p>
                          )}
                          <p className="text-xs text-muted-foreground">
                            ID: {post.id} • Author #{post.authorId}
                          </p>
                        </div>
                        <Badge variant={statusBadgeVariant[post.status]}>{post.status}</Badge>
                      </div>
                      <div className="grid gap-2 text-sm text-muted-foreground">
                        <div className="flex items-center gap-2">
                          <Badge variant="outline">{post.readingTime || 1} min read</Badge>
                          <span>{formatDate(post.updatedAt || post.publishedAt)}</span>
                        </div>
                        <div>
                          <p className="font-medium text-foreground">Tags</p>
                          <div className="mt-1 flex flex-wrap gap-1">
                            {post.tags.length === 0 ? (
                              <span className="text-xs text-muted-foreground">None</span>
                            ) : (
                              post.tags.map((tag) => (
                                <Badge key={tag} variant="outline" className="text-xs">
                                  {tag}
                                </Badge>
                              ))
                            )}
                          </div>
                        </div>
                      </div>
                      <div className="grid gap-2 sm:grid-cols-2">
                        <Button
                          className="flex w-full"
                          variant={post.status === "published" ? "outline" : "secondary"}
                          onClick={() => handleToggleStatus(post)}
                        >
                          {post.status === "published" ? "Unpublish" : "Publish"}
                        </Button>
                        <Button className="flex w-full" variant="outline" onClick={() => handleEditPost(post.id)}>
                          Edit
                        </Button>
                        {post.status === "published" && (
                          <Button
                            asChild
                            variant="ghost"
                            className="flex w-full sm:col-span-2"
                            title="View live post"
                          >
                            <Link to={`/blog/${post.slug}`} target="_blank" rel="noreferrer">
                              View live
                            </Link>
                          </Button>
                        )}
                        <div className="flex flex-col gap-2 sm:col-span-2 sm:flex-row">
                          <Button
                            className="flex-1"
                            variant="destructive"
                            onClick={() => setDeleteTarget(post)}
                          >
                            Delete
                          </Button>
                        </div>
                      </div>
                    </div>
                  ))}
                </div>
                <div className="hidden md:block">
                  <Table>
                    <TableHeader>
                      <TableRow>
                        <TableHead>Title</TableHead>
                        <TableHead>Status</TableHead>
                        <TableHead>Reading</TableHead>
                        <TableHead>Updated</TableHead>
                        <TableHead>Tags</TableHead>
                        <TableHead className="text-right">Actions</TableHead>
                      </TableRow>
                    </TableHeader>
                    <TableBody>
                      {posts.map((post) => (
                        <TableRow key={post.id}>
                          <TableCell>
                            <div className="flex flex-col">
                              <span className="font-medium">{post.title}</span>
                              {post.excerpt && (
                                <span className="text-sm text-muted-foreground line-clamp-1">
                                  {post.excerpt}
                                </span>
                              )}
                            </div>
                          </TableCell>
                          <TableCell>
                            <Badge variant={statusBadgeVariant[post.status]}>{post.status}</Badge>
                          </TableCell>
                          <TableCell>
                            <Badge variant="outline">{post.readingTime || 1} min</Badge>
                          </TableCell>
                          <TableCell>
                            <span className="text-sm text-muted-foreground">
                              {formatDate(post.updatedAt || post.publishedAt)}
                            </span>
                          </TableCell>
                          <TableCell>
                            <div className="flex flex-wrap gap-1">
                              {post.tags.length === 0 ? (
                                <span className="text-xs text-muted-foreground">-</span>
                              ) : (
                                post.tags.map((tag) => (
                                  <Badge key={tag} variant="outline" className="text-xs">
                                    {tag}
                                  </Badge>
                                ))
                              )}
                            </div>
                          </TableCell>
                          <TableCell className="text-right">
                            <div className="flex flex-wrap justify-end gap-2">
                              <Button
                                size="sm"
                                variant={post.status === "published" ? "outline" : "secondary"}
                                onClick={() => handleToggleStatus(post)}
                              >
                                {post.status === "published" ? "Unpublish" : "Publish"}
                              </Button>
                              {post.status === "published" && (
                                <Button
                                  asChild
                                  size="icon"
                                  variant="ghost"
                                  className="h-8 w-8"
                                  title="View live post"
                                >
                                  <Link to={`/blog/${post.slug}`} target="_blank" rel="noreferrer">
                                    <Eye className="h-4 w-4" />
                                  </Link>
                                </Button>
                              )}
                              <Button
                                size="icon"
                                variant="ghost"
                                className="h-8 w-8"
                                onClick={() => handleEditPost(post.id)}
                                title="Edit post"
                              >
                                <Edit className="h-4 w-4" />
                              </Button>
                              <Button
                                size="icon"
                                variant="ghost"
                                className="h-8 w-8 text-destructive hover:text-destructive"
                                onClick={() => setDeleteTarget(post)}
                                title="Delete post"
                              >
                                <Trash2 className="h-4 w-4" />
                              </Button>
                            </div>
                          </TableCell>
                        </TableRow>
                      ))}
                    </TableBody>
                  </Table>
                </div>
              </>
            )}
          </div>
        </CardContent>
      </Card>

      {editorOpen && (
        <Card className="border-primary/30">
          <CardHeader>
            <CardTitle>{editorMode === "create" ? "Create Blog Post" : "Edit Blog Post"}</CardTitle>
            <CardDescription>
              {editorMode === "create"
                ? "Draft a new story and publish when ready."
                : editingPost
                  ? `Created ${formatDate(editingPost.createdAt)} · Last updated ${formatDate(editingPost.updatedAt)}`
                  : "Update the selected story."}
            </CardDescription>
          </CardHeader>
          <CardContent className="space-y-6">
            {editorLoading ? (
              <div className="flex items-center justify-center py-20 text-muted-foreground">
                <Loader2 className="h-6 w-6 animate-spin" />
              </div>
            ) : (
              <>
                <div className="grid gap-6 lg:grid-cols-[2fr,1.1fr]">
                  <div className="space-y-6">
                    <div className="grid gap-4 md:grid-cols-2">
                      <div className="space-y-2">
                        <Label htmlFor="blog-title">Title</Label>
                        <Input
                          id="blog-title"
                          value={form.title}
                          onChange={(e) => setForm((prev) => ({ ...prev, title: e.target.value }))}
                          placeholder="Enter a compelling headline"
                        />
                      </div>
                      <div className="space-y-2">
                        <Label>Status</Label>
                        <Select
                          value={form.status}
                          onValueChange={(value: BlogPostStatus) =>
                            setForm((prev) => ({ ...prev, status: value }))
                          }
                        >
                          <SelectTrigger>
                            <SelectValue />
                          </SelectTrigger>
                          <SelectContent>
                            <SelectItem value="draft">Draft</SelectItem>
                            <SelectItem value="published">Published</SelectItem>
                            <SelectItem value="archived">Archived</SelectItem>
                          </SelectContent>
                        </Select>
                      </div>
                      <div className="space-y-2 md:col-span-2">
                        <Label htmlFor="blog-excerpt">Excerpt</Label>
                        <Textarea
                          id="blog-excerpt"
                          value={form.excerpt}
                          onChange={(e) => setForm((prev) => ({ ...prev, excerpt: e.target.value }))}
                          placeholder="Short summary used for listings and SEO description."
                          rows={3}
                        />
                      </div>
                      <div className="space-y-2">
                        <Label htmlFor="blog-cover">Cover image URL</Label>
                        <Input
                          id="blog-cover"
                          value={form.coverImageUrl}
                          onChange={(e) => setForm((prev) => ({ ...prev, coverImageUrl: e.target.value }))}
                          placeholder="https://images.unsplash.com/..."
                        />
                      </div>
                      <div className="space-y-2">
                        <Label htmlFor="blog-cover-alt">Cover image alt text</Label>
                        <Input
                          id="blog-cover-alt"
                          value={form.coverImageAlt}
                          onChange={(e) => setForm((prev) => ({ ...prev, coverImageAlt: e.target.value }))}
                          placeholder="Describe the cover image"
                        />
                      </div>
                      <div className="space-y-2 md:col-span-2">
                        <Label htmlFor="blog-feature-embed">Feature embed link</Label>
                        <Input
                          id="blog-feature-embed"
                          value={form.featureEmbedUrl}
                          onChange={(e) => setForm((prev) => ({ ...prev, featureEmbedUrl: e.target.value }))}
                          placeholder="YouTube or external resource link"
                        />
                        <p className="text-xs text-muted-foreground">
                          Supports YouTube URLs for embedded videos or any link to highlight an external resource.
                        </p>
                      </div>
                    </div>

                    <div className="space-y-4">
                      <div className="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                          <h3 className="text-sm font-semibold">Content Blocks</h3>
                          <p className="text-xs text-muted-foreground">
                            Blend headings, paragraphs, images, embeds, quotes, and lists to build the article body.
                          </p>
                        </div>
                        <DropdownMenu>
                          <DropdownMenuTrigger asChild>
                            <Button variant="outline" size="sm">
                              <Plus className="mr-2 h-4 w-4" />
                              Add block
                            </Button>
                          </DropdownMenuTrigger>
                          <DropdownMenuContent align="end">
                            {(Object.keys(blockTemplates) as BlogContentBlockType[]).map((type) => (
                              <DropdownMenuItem key={type} onSelect={() => handleAddBlock(type)}>
                                <div className="flex items-center gap-2">
                                  {blockIcons[type]}
                                  <span>{blockLabels[type]}</span>
                                </div>
                              </DropdownMenuItem>
                            ))}
                          </DropdownMenuContent>
                        </DropdownMenu>
                      </div>

                      <div className="space-y-4">
                        {form.contentBlocks.map((block, index) => renderBlockEditor(block, index))}
                      </div>
                    </div>
                  </div>

                  <div className="space-y-6">
                    <div className="space-y-2">
                      <Label htmlFor="blog-slug">Custom slug</Label>
                      <Input
                        id="blog-slug"
                        value={form.slug}
                        onChange={(e) => setForm((prev) => ({ ...prev, slug: e.target.value }))}
                        placeholder="Leave blank to auto-generate"
                      />
                      <p className="text-xs text-muted-foreground">
                        Final URL:{" "}
                        {slugPreviewValue ? (
                          <code className="rounded bg-muted px-2 py-1">/blog/{slugPreviewValue}</code>
                        ) : (
                          "Will be generated from the title"
                        )}
                      </p>
                      {previewPath && (
                        <Button asChild variant="link" size="sm" className="px-0 text-primary">
                          <Link to={previewPath} target="_blank" rel="noreferrer">
                            Open preview in new tab
                          </Link>
                        </Button>
                      )}
                    </div>

                    <div className="space-y-2">
                      <Label htmlFor="blog-tags">Tags</Label>
                      <Input
                        id="blog-tags"
                        value={form.tags}
                        onChange={(e) => setForm((prev) => ({ ...prev, tags: e.target.value }))}
                        placeholder="budgeting, savings, investing"
                      />
                      <p className="text-xs text-muted-foreground">
                        Separate tags with commas. They power related content suggestions.
                      </p>
                    </div>

                    <div className="space-y-2">
                      <Label htmlFor="blog-meta-title">Meta title</Label>
                      <Input
                        id="blog-meta-title"
                        value={form.metaTitle}
                        onChange={(e) => setForm((prev) => ({ ...prev, metaTitle: e.target.value }))}
                        placeholder="Optional: override SEO title"
                      />
                    </div>

                    <div className="space-y-2">
                      <Label htmlFor="blog-meta-description">Meta description</Label>
                      <Textarea
                        id="blog-meta-description"
                        value={form.metaDescription}
                        onChange={(e) => setForm((prev) => ({ ...prev, metaDescription: e.target.value }))}
                        rows={3}
                        placeholder="Concise description used for search and social sharing"
                      />
                    </div>

                    <div className="space-y-2">
                      <Label htmlFor="blog-meta-keywords">Meta keywords</Label>
                      <Input
                        id="blog-meta-keywords"
                        value={form.metaKeywords}
                        onChange={(e) => setForm((prev) => ({ ...prev, metaKeywords: e.target.value }))}
                        placeholder="finance, budgeting, money habits"
                      />
                      <p className="text-xs text-muted-foreground">Optional comma separated keywords.</p>
                    </div>

                    <div className="space-y-2">
                      <Label>Estimated reading time</Label>
                      <Badge variant="outline">{estimatedReadingTime} min read</Badge>
                    </div>

                    {form.coverImageUrl && (
                      <div className="space-y-2">
                        <Label>Cover preview</Label>
                        <AspectRatio ratio={16 / 9} className="overflow-hidden rounded-lg border bg-muted">
                          <img
                            src={form.coverImageUrl}
                            alt={form.coverImageAlt || "Cover preview"}
                            className="h-full w-full object-cover"
                          />
                        </AspectRatio>
                      </div>
                    )}

                    <div className="rounded-lg border bg-muted/40 p-4">
                      <div className="flex items-center justify-between">
                        <h4 className="text-sm font-semibold">Live preview</h4>
                        <Badge variant={statusBadgeVariant[form.status]} className="text-xs capitalize">
                          {form.status}
                        </Badge>
                      </div>
                      <div className="mt-3 max-h-[420px] overflow-y-auto pr-2">
                        <BlogContentRenderer blocks={form.contentBlocks} />
                      </div>
                    </div>
                  </div>
                </div>

                <div className="flex flex-wrap items-center justify-end gap-2">
                  <Button type="button" variant="outline" onClick={handleCancelEdit} disabled={saving}>
                    Cancel
                  </Button>
                  <Button
                    type="button"
                    onClick={handleSavePost}
                    disabled={saving || (editorMode === "create" && !adminId)}
                  >
                    {saving ? <Loader2 className="mr-2 h-4 w-4 animate-spin" /> : <Save className="mr-2 h-4 w-4" />}
                    {editorMode === "create" ? "Save draft" : "Save changes"}
                  </Button>
                </div>
              </>
            )}
          </CardContent>
        </Card>
      )}

      <AlertDialog open={!!deleteTarget} onOpenChange={(open) => !open && setDeleteTarget(null)}>
        <AlertDialogContent>
          <AlertDialogHeader>
            <AlertDialogTitle>Delete this blog post?</AlertDialogTitle>
            <AlertDialogDescription>
              This action cannot be undone. It will permanently delete "{deleteTarget?.title}" from the blog.
            </AlertDialogDescription>
          </AlertDialogHeader>
          <AlertDialogFooter>
            <AlertDialogCancel>Cancel</AlertDialogCancel>
            <AlertDialogAction
              onClick={handleDeletePost}
              className="bg-destructive hover:bg-destructive/90"
            >
              Delete
            </AlertDialogAction>
          </AlertDialogFooter>
        </AlertDialogContent>
      </AlertDialog>
    </div>
  );
};
