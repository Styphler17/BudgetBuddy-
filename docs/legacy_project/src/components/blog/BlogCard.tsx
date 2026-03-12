import { Link } from "react-router-dom";
import { Card, CardContent, CardFooter, CardHeader, CardTitle } from "@/components/ui/card";
import { cn } from "@/lib/utils";
import { Badge } from "@/components/ui/badge";
import { Button } from "@/components/ui/button";
import { AspectRatio } from "@/components/ui/aspect-ratio";
import { ArrowRight } from "lucide-react";
import type { BlogPostSummary } from "@/lib/blogApi";

interface BlogCardProps {
  post: BlogPostSummary;
  variant?: "default" | "compact";
  highlight?: boolean;
  className?: string;
}

export const BlogCard = ({ post, variant = "default", highlight = false, className }: BlogCardProps) => {
  const isCompact = variant === "compact";
  const showImage = !isCompact && Boolean(post.coverImageUrl);
  const Container = highlight ? "article" : "div";

  return (
    <Container className={cn("h-full", className)}>
      <Card className={cn("flex h-full flex-col", highlight ? "border-primary/40 shadow-lg" : undefined)}>
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
        <CardContent className="flex-1 space-y-3">
          {post.excerpt && (
            <div
              className={`text-sm text-muted-foreground line-clamp-3 ${isCompact ? "" : "md:text-base"}`}
              dangerouslySetInnerHTML={{ __html: post.excerpt || "" }}
            />
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
        <CardFooter className={isCompact ? "justify-end py-3" : "flex items-center justify-end"}>
          <Button variant="ghost" size="sm" asChild className="group/btn text-primary hover:text-primary hover:bg-primary/10">
            <Link to={`/blog/${post.slug}`} className="flex items-center gap-2">
              Read Article
              <ArrowRight className="h-4 w-4 transition-transform group-hover/btn:translate-x-1" />
            </Link>
          </Button>
        </CardFooter>
      </Card>
    </Container>
  );
};
