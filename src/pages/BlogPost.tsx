import { useCallback, useEffect, useMemo, useState } from "react";
import { useParams, Link } from "react-router-dom";
import { BlogContentRenderer } from "@/components/blog/BlogContentRenderer";
import { BlogCard } from "@/components/blog/BlogCard";
import { BlogArticleHero } from "@/components/blog/BlogArticleHero";
import { Seo } from "@/components/Seo";
import { AspectRatio } from "@/components/ui/aspect-ratio";
import { Button } from "@/components/ui/button";
import { Loader2, ArrowLeft } from "lucide-react";
import { Header } from "@/components/Header";
import { Footer } from "@/components/Footer";
import { BackToTop } from "@/components/BackToTop";
import { blogAPI, type BlogPostDetail, type BlogPostSummary } from "@/lib/api";
import { useToast } from "@/hooks/use-toast";

const getCanonical = (slug: string) => {
  if (typeof window === "undefined") return undefined;
  return `${window.location.origin}/blog/${slug}`;
};

const extractYouTubeId = (url: string) => {
  try {
    const parsed = new URL(url);
    if (parsed.hostname.includes("youtube.com")) {
      return parsed.searchParams.get("v");
    }
    if (parsed.hostname === "youtu.be") {
      return parsed.pathname.slice(1);
    }
  } catch (error) {
    console.warn("Failed to parse embed URL", error);
  }
  return null;
};

const FeatureEmbed = ({ url, caption }: { url: string; caption?: string }) => {
  const youtubeId = extractYouTubeId(url);

  if (youtubeId) {
    return (
      <figure className="space-y-3">
        <AspectRatio ratio={16 / 9} className="overflow-hidden rounded-xl border bg-muted">
          <iframe
            src={`https://www.youtube.com/embed/${youtubeId}`}
            title={caption || "Embedded video"}
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            allowFullScreen
            className="h-full w-full"
          />
        </AspectRatio>
        {caption && <figcaption className="text-sm text-muted-foreground">{caption}</figcaption>}
      </figure>
    );
  }

  return (
    <div className="rounded-lg border bg-muted/40 p-4">
      <p className="text-sm font-medium text-muted-foreground">Featured resource</p>
      <a
        href={url}
        target="_blank"
        rel="noreferrer noopener"
        className="mt-2 inline-flex items-center gap-2 text-primary hover:underline"
      >
        {url}
      </a>
      {caption && <p className="mt-2 text-xs text-muted-foreground">{caption}</p>}
    </div>
  );
};

const BlogPost = () => {
  const { slug } = useParams<{ slug: string }>();
  const { toast } = useToast();
  const [post, setPost] = useState<BlogPostDetail | null>(null);
  const [related, setRelated] = useState<BlogPostSummary[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  const loadPost = useCallback(
    async (articleSlug: string) => {
      try {
        setLoading(true);
        setError(null);
        const detail = await blogAPI.getBySlug(articleSlug);
        if (!detail) {
          setError("We couldn't find the article you were looking for.");
          setPost(null);
          setRelated([]);
          return;
        }

        setPost(detail);
        const relatedPosts = await blogAPI.getRelated(detail.id, { limit: 4 });
        setRelated(relatedPosts);
      } catch (err) {
        console.error("Failed to load blog post", err);
        setError("We ran into an issue loading this blog post. Please try again later.");
        toast({
          title: "Unable to load article",
          description: "Refresh the page or check back soon.",
          variant: "destructive"
        });
      } finally {
        setLoading(false);
      }
    },
    [toast]
  );

  useEffect(() => {
    if (slug) {
      void loadPost(slug);
    }
  }, [slug, loadPost]);

  const keywords = useMemo(() => {
    if (!post) return undefined;
    const combined = new Set<string>();
    post.tags.forEach((tag) => combined.add(tag));
    post.metaKeywords.forEach((keyword) => combined.add(keyword));
    return Array.from(combined);
  }, [post]);

  const seoTitle = post?.metaTitle || post?.title || "BudgetBuddy Blog";
  const seoDescription =
    post?.metaDescription ||
    post?.excerpt ||
    "Read the latest money insights and product updates from the BudgetBuddy team.";

  return (
    <>
      <Seo
        title={seoTitle}
        description={seoDescription}
        canonical={slug ? getCanonical(slug) : undefined}
        keywords={keywords}
        image={post?.coverImageUrl ?? undefined}
        type="article"
      />

      <div className="min-h-screen bg-background">
        <Header />
        <main>
          <section className="bg-muted/40 py-10">
            <div className="container mx-auto max-w-4xl px-6">
              <Button asChild variant="ghost" className="mb-6 w-fit pl-0 text-primary">
                <Link to="/blog">
                  <ArrowLeft className="mr-2 h-4 w-4" />
                  Back to all articles
                </Link>
              </Button>

              {loading ? (
                <div className="flex min-h-[300px] items-center justify-center">
                  <Loader2 className="h-6 w-6 animate-spin text-muted-foreground" />
                </div>
              ) : error ? (
                <div className="rounded-lg border border-destructive/40 bg-destructive/10 p-6 text-destructive">
                  {error}
                </div>
              ) : post ? (
                <BlogArticleHero post={post} />
              ) : null}
            </div>
          </section>

          {!loading && post && (
            <section className="container mx-auto px-6 py-12">
              <div className="grid gap-8 lg:grid-cols-[2fr,1fr] lg:gap-12">
                <article className="space-y-10">
                  {post.featureEmbedUrl && (
                    <FeatureEmbed url={post.featureEmbedUrl} caption={post.metaDescription ?? undefined} />
                  )}
                  <BlogContentRenderer blocks={post.contentBlocks} />
                </article>

                <aside className="space-y-6">
                  <div className="rounded-xl border bg-muted/40 p-6">
                    <h2 className="text-lg font-semibold">Related reads</h2>
                    <p className="mt-1 text-sm text-muted-foreground">
                      Explore more articles that align with this topic.
                    </p>
                    <div className="mt-4 space-y-4">
                      {related.length === 0 ? (
                        <p className="text-sm text-muted-foreground">More stories are on the way.</p>
                      ) : (
                        related.map((item) => <BlogCard key={item.id} post={item} variant="compact" />)
                      )}
                    </div>
                  </div>
                  <div className="rounded-xl border bg-primary/5 p-6">
                    <h3 className="text-base font-semibold">Bring BudgetBuddy along</h3>
                    <p className="mt-2 text-sm text-muted-foreground">
                      Track every goal, automate savings, and collaborate with the people who matter.
                    </p>
                    <Button asChild className="mt-4 w-full">
                      <Link to="/register">Create free account</Link>
                    </Button>
                  </div>
                </aside>
              </div>
            </section>
          )}
        </main>
        <Footer />
        <BackToTop />
      </div>
    </>
  );
};

export default BlogPost;
