import { AspectRatio } from "@/components/ui/aspect-ratio";
import type { BlogContentBlock } from "@/lib/blogApi";
import { cn } from "@/lib/utils";

interface BlogContentRendererProps {
  blocks: BlogContentBlock[];
  className?: string;
  showCaptions?: boolean;
}

const normaliseText = (text?: string) =>
  text ? text.trim() : "";

const renderParagraphText = (text?: string) => {
  if (!text) return null;
  const segments = text.split(/\n+/).filter(Boolean);
  return segments.map((segment, index) => (
    <span key={index} className="block">
      {segment}
    </span>
  ));
};

const clampHeadingLevel = (level?: number) => {
  if (!level) return 2;
  return Math.min(Math.max(Math.round(level), 1), 6);
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

const renderEmbed = (url?: string, caption?: string) => {
  if (!url) return null;
  const youtubeId = extractYouTubeId(url);

  if (youtubeId) {
    const embedUrl = `https://www.youtube.com/embed/${youtubeId}`;
    return (
      <figure className="not-prose space-y-3">
        <AspectRatio ratio={16 / 9} className="overflow-hidden rounded-lg border bg-muted">
          <iframe
            src={embedUrl}
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
    <div className="not-prose space-y-2">
      <a
        href={url}
        target="_blank"
        rel="noreferrer noopener"
        className="inline-flex items-center gap-2 text-primary hover:underline"
      >
        {url}
      </a>
      {caption && <p className="text-sm text-muted-foreground">{caption}</p>}
    </div>
  );
};

export const BlogContentRenderer = ({
  blocks,
  className,
  showCaptions = true
}: BlogContentRendererProps) => {
  const safeBlocks = Array.isArray(blocks) ? blocks : [];

  if (!safeBlocks.length) {
    return (
      <div className={cn("text-sm text-muted-foreground", className)}>
        Content will appear here once blocks are added.
      </div>
    );
  }

  return (
    <article className={cn("prose prose-slate max-w-none dark:prose-invert", className)}>
      {safeBlocks.map((block, index) => {
        switch (block.type) {
          case "heading": {
            const level = clampHeadingLevel(block.level);
            const HeadingTag = `h${level}` as keyof JSX.IntrinsicElements;
            return (
              <HeadingTag key={index} className="scroll-m-20 font-semibold tracking-tight">
                {normaliseText(block.text)}
              </HeadingTag>
            );
          }
          case "paragraph":
            return (
              <p key={index} className="leading-7 text-muted-foreground">
                {renderParagraphText(block.text)}
              </p>
            );
          case "quote":
            return (
              <blockquote key={index} className="border-l-4 border-primary pl-4 italic text-muted-foreground">
                {renderParagraphText(block.text)}
                {showCaptions && block.caption && (
                  <span className="mt-2 block text-sm font-medium text-primary">{block.caption}</span>
                )}
              </blockquote>
            );
          case "image":
            if (!block.url) return null;
            return (
              <figure key={index} className="not-prose space-y-3">
                <AspectRatio ratio={16 / 9} className="overflow-hidden rounded-lg border bg-muted">
                  <img
                    src={block.url}
                    alt={block.alt || "Blog illustration"}
                    loading="lazy"
                    className="h-full w-full object-cover transition-transform duration-500 hover:scale-[1.01]"
                  />
                </AspectRatio>
                {showCaptions && (block.caption || block.alt) && (
                  <figcaption className="text-sm text-muted-foreground">
                    {block.caption || block.alt}
                  </figcaption>
                )}
              </figure>
            );
          case "embed":
            return (
              <div key={index}>
                {renderEmbed(block.url, showCaptions ? block.caption : undefined)}
              </div>
            );
          case "list":
            if (!block.items || !block.items.length) return null;
            return (
              <ul key={index} className="list-disc pl-6 text-muted-foreground">
                {block.items.map((item, itemIndex) => (
                  <li key={itemIndex}>{normaliseText(item)}</li>
                ))}
              </ul>
            );
          default:
            return null;
        }
      })}
    </article>
  );
};
