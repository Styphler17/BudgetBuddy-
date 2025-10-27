import { type ReactNode } from "react";

interface AdminLayoutProps {
  sidebar: ReactNode;
  topbar: ReactNode;
  children: ReactNode;
}

export const AdminLayout = ({ sidebar, topbar, children }: AdminLayoutProps) => (
  <div className="flex min-h-screen bg-muted/10">
    <aside className="hidden w-64 flex-shrink-0 flex-col border-r bg-background/80 p-6 backdrop-blur supports-[backdrop-filter]:bg-background/60 md:flex">
      {sidebar}
    </aside>
    <div className="flex flex-1 flex-col">
      <header className="sticky top-0 z-20 border-b bg-background/80 backdrop-blur supports-[backdrop-filter]:bg-background/60">
        {topbar}
      </header>
      <main className="flex-1 overflow-y-auto">
        {children}
      </main>
    </div>
  </div>
);
