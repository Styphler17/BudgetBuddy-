import { ToggleTheme } from "@/components/ui/toggle-theme";
import { cn } from '@/lib/utils';

export default function DemoOne() {
  return (
    <div className="relative flex min-h-screen w-full items-center justify-center">
      <div
        aria-hidden="true"
        className={cn(
          'absolute inset-0 -z-10 size-full',
          'bg-[radial-gradient(hsla(var(--foreground),0.1)_2px,transparent_2px)]',
          'bg-[size:12px_12px]',
        )}
      />
      <ToggleTheme />
    </div>
  );
}
