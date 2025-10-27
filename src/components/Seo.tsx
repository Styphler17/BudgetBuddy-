import { Helmet } from "react-helmet-async";

interface SeoProps {
  title: string;
  description?: string;
  keywords?: string[];
  canonical?: string;
  image?: string;
  type?: "article" | "website";
  noIndex?: boolean;
}

export const Seo = ({
  title,
  description,
  keywords,
  canonical,
  image,
  type = "website",
  noIndex = false
}: SeoProps) => (
  <Helmet>
    <title>{title}</title>
    {description && <meta name="description" content={description} />}
    {keywords && keywords.length > 0 && (
      <meta name="keywords" content={keywords.filter(Boolean).join(", ")} />
    )}
    {noIndex && <meta name="robots" content="noindex, nofollow" />}
    {canonical && <link rel="canonical" href={canonical} />}

    <meta property="og:title" content={title} />
    {description && <meta property="og:description" content={description} />}
    {canonical && <meta property="og:url" content={canonical} />}
    <meta property="og:type" content={type} />
    {image && <meta property="og:image" content={image} />}

    <meta name="twitter:card" content={image ? "summary_large_image" : "summary"} />
    <meta name="twitter:title" content={title} />
    {description && <meta name="twitter:description" content={description} />}
    {image && <meta name="twitter:image" content={image} />}
  </Helmet>
);
