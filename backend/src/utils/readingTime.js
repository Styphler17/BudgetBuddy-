const WORDS_PER_MINUTE = Number(process.env.READING_WORDS_PER_MINUTE || 200);

function collectText(block) {
  if (!block) return "";
  switch (block.type) {
    case "heading":
    case "paragraph":
    case "quote":
      return block.text || "";
    case "list":
      return Array.isArray(block.items) ? block.items.join(" ") : "";
    default:
      return "";
  }
}

function estimateReadingTime(blocks = []) {
  const joined = blocks
    .map(collectText)
    .join(" ")
    .trim();

  if (!joined) return 1;

  const words = joined.split(/\s+/).filter(Boolean).length;
  return Math.max(1, Math.ceil(words / WORDS_PER_MINUTE));
}

module.exports = { estimateReadingTime };
