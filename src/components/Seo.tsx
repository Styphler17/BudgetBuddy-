import { Helmet } from "react-helmet-async";
import { SITE_URL, SITE_NAME } from "@/config/site";

interface SeoProps {
  title: string;
  description?: string;
  keywords?: string[];
  canonical?: string;
  path?: string;
  image?: string;
  type?: "article" | "website";
  noIndex?: boolean;
}

const absoluteUrl = (value?: string) => {
  if (!value) return undefined;

  const trimmed = value.trim();
  if (!trimmed) return undefined;

  if (/^https?:\/\//i.test(trimmed)) {
    return trimmed;
  }

  const sanitized = trimmed.startsWith("/") ? trimmed : `/${trimmed}`;
  return `${SITE_URL.replace(/\/$/, "")}${sanitized}`;
};

export const Seo = ({
  title,
  description,
  keywords,
  canonical,
  path,
  image,
  type = "website",
  noIndex = false
}: SeoProps) => {
  const canonicalUrl = canonical ? absoluteUrl(canonical) : absoluteUrl(path);
  const imageUrl = absoluteUrl(image);

  return (
    <Helmet>
      <title>{title}</title>
      {description && <meta name="description" content={description} />}
      {keywords && keywords.length > 0 && (
        <meta name="keywords" content={keywords.filter(Boolean).join(", ")} />
      )}
      {noIndex && <meta name="robots" content="noindex, nofollow" />}
      {canonicalUrl && <link rel="canonical" href={canonicalUrl} />}

      <meta property="og:site_name" content={SITE_NAME} />
      <meta property="og:title" content={title} />
      {description && <meta property="og:description" content={description} />}
      {canonicalUrl && <meta property="og:url" content={canonicalUrl} />}
      <meta property="og:type" content={type} />
      {imageUrl && <meta property="og:image" content={imageUrl} />}

      <meta name="twitter:card" content={imageUrl ? "summary_large_image" : "summary"} />
      <meta name="twitter:title" content={title} />
      {description && <meta name="twitter:description" content={description} />}
      {imageUrl && <meta name="twitter:image" content={imageUrl} />}
    </Helmet>
  );
};
