import { useState, useEffect } from "react";
import { Button } from "@/components/ui/button";
import { ArrowUp } from "lucide-react";

export function BackToTop() {
  const [isVisible, setIsVisible] = useState(false);

  useEffect(() => {
    const handleScroll = () => {
      setIsVisible(window.scrollY > 200);
    };

    window.addEventListener("scroll", handleScroll);
    return () => window.removeEventListener("scroll", handleScroll);
  }, []);

  const scrollToTop = () => window.scrollTo({ top: 0, behavior: "smooth" });

  return (
    isVisible && (
      <Button
        onClick={scrollToTop}
        className="fixed bottom-6 right-6 z-50 h-12 w-12 rounded-full bg-primary p-0 shadow-lg transition-all hover:bg-primary/90 hover:shadow-xl"
        size="icon"
        aria-label="Back to top"
      >
        <ArrowUp className="h-6 w-6" />
      </Button>
    )
  );
}
