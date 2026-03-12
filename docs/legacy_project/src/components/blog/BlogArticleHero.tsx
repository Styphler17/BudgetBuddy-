import { Badge } from "@/components/ui/badge";
import { AspectRatio } from "@/components/ui/aspect-ratio";
import type { BlogPostDetail } from "@/lib/blogApi";

interface BlogArticleHeroProps {
  post: BlogPostDetail;
  badgeLabel?: string;
}

export const BlogArticleHero = ({
  post,
  badgeLabel = "BudgetBuddy Blog"
}: BlogArticleHeroProps) => {
  return (
    <div className="space-y-10">
      <div className="space-y-6 text-center">
        <Badge variant="outline" className="text-xs uppercase tracking-widest">
          {badgeLabel}
        </Badge>
        <h1 className="text-3xl font-bold tracking-tight md:text-4xl lg:text-5xl">
          {post.title}
        </h1>
        <div className="flex flex-wrap items-center justify-center gap-3 text-sm text-muted-foreground">
          {post.publishedAt && (
            <span>Published {new Date(post.publishedAt).toLocaleDateString()}</span>
          )}
          <span>· {post.readingTime || 1} min read</span>
          {post.tags.length > 0 && (
            <span className="flex flex-wrap items-center gap-2">
              {post.tags.map((tag) => (
                <Badge key={tag} variant="secondary" className="text-xs">
                  {tag}
                </Badge>
              ))}
            </span>
          )}
        </div>
      </div>

      {post.coverImageUrl && (
        <AspectRatio ratio={16 / 9} className="overflow-hidden rounded-2xl border bg-muted">
          <img
            src={post.coverImageUrl}
            alt={post.coverImageAlt || post.title}
            className="h-full w-full object-cover"
          />
        </AspectRatio>
      )}
    </div>
  );
};
