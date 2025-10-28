import { useCallback, useEffect, useMemo, useState } from "react";
import { BlogCard } from "@/components/blog/BlogCard";
import { Seo } from "@/components/Seo";
import { Badge } from "@/components/ui/badge";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Loader2, Search } from "lucide-react";
import { Header } from "@/components/Header";
import { Footer } from "@/components/Footer";
import { BackToTop } from "@/components/BackToTop";
import { blogAPI, type BlogPostSummary } from "@/lib/api";
import { ROUTE_PATHS } from "@/config/site";
import { useToast } from "@/hooks/use-toast";

const BLOG_PAGE_TITLE = "BudgetBuddy Blog | Money Moves That Matter";
const BLOG_PAGE_DESCRIPTION =
  "Actionable budgeting tactics, savings playbooks, and product tips written by the BudgetBuddy team to help you master your money.";

const extractTags = (posts: BlogPostSummary[]) => {
  const tagSet = new Set<string>();
  posts.forEach((post) => {
    post.tags.forEach((tag) => tagSet.add(tag));
  });
  return Array.from(tagSet).sort((a, b) => a.localeCompare(b));
};

const Blog = () => {
  const { toast } = useToast();
  const [posts, setPosts] = useState<BlogPostSummary[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [searchInput, setSearchInput] = useState("");
  const [tagInput, setTagInput] = useState("");
  const [filters, setFilters] = useState<{ search: string; tag: string }>({
    search: "",
    tag: ""
  });

  const fetchPosts = useCallback(
    async (activeFilters: { search: string; tag: string }) => {
      try {
        setLoading(true);
        setError(null);
        const data = await blogAPI.listPublished({
          search: activeFilters.search,
          tag: activeFilters.tag
        });
        setPosts(data);
      } catch (err) {
        console.error("Failed to load blog posts", err);
        setError("We couldn't load the blog posts right now. Please try again later.");
        toast({
          title: "Unable to load articles",
          description: "Please refresh the page or try again shortly.",
          variant: "destructive"
        });
      } finally {
        setLoading(false);
      }
    },
    [toast]
  );

  useEffect(() => {
    void fetchPosts(filters);
  }, [filters, fetchPosts]);

  const featuredPost = posts[0] ?? null;
  const otherPosts = featuredPost ? posts.slice(1) : posts;

  const availableTags = useMemo(() => extractTags(posts), [posts]);

  const handleApplyFilters = () => {
    setFilters({
      search: searchInput.trim(),
      tag: tagInput.trim()
    });
  };

  const handleResetFilters = () => {
    setSearchInput("");
    setTagInput("");
    setFilters({ search: "", tag: "" });
  };

  return (
    <>
      <Seo
        title={BLOG_PAGE_TITLE}
        description={BLOG_PAGE_DESCRIPTION}
        path={ROUTE_PATHS.blog}
        keywords={[
          "budgeting tips",
          "money management",
          "personal finance blog",
          "savings strategies",
          "BudgetBuddy updates"
        ]}
      />

      <div className="min-h-screen bg-background">
        <Header />
        <main>
          <section className="bg-muted/40 py-16">
            <div className="container mx-auto max-w-4xl px-6 text-center">
              <Badge variant="outline" className="mb-4 text-sm uppercase tracking-widest">
                BudgetBuddy Blog
              </Badge>
              <h1 className="text-3xl font-bold tracking-tight md:text-4xl lg:text-5xl">
                Money wisdom for every milestone
              </h1>
              <p className="mt-4 text-muted-foreground md:text-lg">
                From foundational budgeting to next-level wealth moves, explore guides written by our
                financial strategy team and power users.
              </p>
              <div className="mt-8 flex flex-col gap-3 md:flex-row md:items-center md:justify-center">
                <div className="flex w-full max-w-sm items-center gap-3">
                  <div className="relative w-full">
                    <Search className="pointer-events-none absolute left-3 top-3 h-4 w-4 text-muted-foreground" />
                    <Input
                      value={searchInput}
                      onChange={(e) => setSearchInput(e.target.value)}
                      placeholder="Search by title or keyword"
                      className="pl-9"
                    />
                  </div>
                  <Button onClick={handleApplyFilters} disabled={loading}>
                    Filter
                  </Button>
                </div>
                <div className="flex w-full max-w-sm items-center gap-3">
                  <Input
                    value={tagInput}
                    onChange={(e) => setTagInput(e.target.value)}
                    placeholder="Filter by tag e.g. budgeting"
                  />
                  <Button variant="ghost" onClick={handleResetFilters} disabled={loading && posts.length === 0}>
                    Clear
                  </Button>
                </div>
              </div>

              {availableTags.length > 0 && (
                <div className="mt-6 flex flex-wrap items-center justify-center gap-2 text-sm text-muted-foreground">
                  <span>Popular tags:</span>
                  {availableTags.slice(0, 8).map((tag) => (
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
          </section>

          <section className="container mx-auto px-6 py-12">
            {loading ? (
              <div className="flex min-h-[300px] items-center justify-center">
                <Loader2 className="h-6 w-6 animate-spin text-muted-foreground" />
              </div>
            ) : error ? (
              <div className="mx-auto max-w-2xl rounded-lg border border-destructive/40 bg-destructive/10 p-6 text-center text-destructive">
                {error}
              </div>
            ) : posts.length === 0 ? (
              <div className="mx-auto max-w-2xl text-center">
                <h2 className="text-2xl font-semibold">No posts yet</h2>
                <p className="mt-2 text-muted-foreground">
                  We&apos;re preparing fresh insights. Check back soon or adjust your filters.
                </p>
              </div>
            ) : (
              <div className="space-y-12">
                {featuredPost && (
                  <div>
                    <h2 className="text-xl font-semibold text-muted-foreground">Featured insight</h2>
                    <div className="mt-4">
                      <BlogCard post={featuredPost} highlight />
                    </div>
                  </div>
                )}

                {otherPosts.length > 0 && (
                  <div>
                    <h2 className="text-xl font-semibold text-muted-foreground">Latest stories</h2>
                    <div className="mt-6 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                      {otherPosts.map((post) => (
                        <BlogCard key={post.id} post={post} />
                      ))}
                    </div>
                  </div>
                )}
              </div>
            )}
          </section>
        </main>
        <Footer />
        <BackToTop />
      </div>
    </>
  );
};

export default Blog;
