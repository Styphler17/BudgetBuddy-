import { Link } from "react-router-dom";
import { Card, CardContent, CardFooter, CardHeader, CardTitle } from "@/components/ui/card";
import { Badge } from "@/components/ui/badge";
import { Button } from "@/components/ui/button";
import { AspectRatio } from "@/components/ui/aspect-ratio";
import type { BlogPostSummary } from "@/lib/api";

interface BlogCardProps {
  post: BlogPostSummary;
  variant?: "default" | "compact";
  highlight?: boolean;
}

export const BlogCard = ({ post, variant = "default", highlight = false }: BlogCardProps) => {
  const isCompact = variant === "compact";
  const showImage = !isCompact && Boolean(post.coverImageUrl);
  const Container = highlight ? "article" : "div";

  return (
    <Container>
      <Card className={highlight ? "border-primary/40 shadow-lg" : undefined}>
        {showImage && (
          <div className="p-4 pb-0">
            <Link to={`/blog/${post.slug}`} className="group block overflow-hidden rounded-xl">
              <AspectRatio ratio={16 / 9} className="bg-muted">
                <img
                  src={post.coverImageUrl ?? ""}
                  alt={post.coverImageAlt ?? post.title}
                  className="h-full w-full object-cover transition-transform duration-500 group-hover:scale-[1.02]"
                  loading="lazy"
                />
              </AspectRatio>
            </Link>
          </div>
        )}
        <CardHeader className={highlight && !showImage ? "pt-6" : isCompact ? "pb-3" : undefined}>
          <div className="flex flex-wrap items-center gap-2 text-xs text-muted-foreground">
            <Badge variant="outline" className="capitalize">
              {post.status}
            </Badge>
            <span>{post.readingTime || 1} min read</span>
            {post.publishedAt && <span>· {new Date(post.publishedAt).toLocaleDateString()}</span>}
          </div>
          <CardTitle className="text-xl md:text-2xl">
            <Link to={`/blog/${post.slug}`} className="hover:text-primary">
              {post.title}
            </Link>
          </CardTitle>
        </CardHeader>
        <CardContent className="space-y-3">
            {post.excerpt && (
            <p className={`text-sm text-muted-foreground ${isCompact ? "" : "md:text-base"}`}>
              {post.excerpt}
            </p>
          )}
          {post.tags.length > 0 && !isCompact && (
            <div className="flex flex-wrap gap-2">
              {post.tags.slice(0, 5).map((tag) => (
                <Badge key={tag} variant="secondary" className="text-xs">
                  {tag}
                </Badge>
              ))}
            </div>
          )}
        </CardContent>
        <CardFooter className={isCompact ? "justify-between py-3" : "flex items-center justify-between"}>
          <Link to={`/blog/${post.slug}`} className="text-sm font-medium text-primary hover:underline">
            Read more
          </Link>
          {!isCompact && (
            <Button variant="ghost" size="sm" asChild>
              <Link to={`/blog/${post.slug}`}>Open article</Link>
            </Button>
          )}
        </CardFooter>
      </Card>
    </Container>
  );
};
