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
    <div className="flex flex-1 flex-col min-w-0 relative">
      <header className="sticky top-0 z-30 border-b bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/60">
        {topbar}
      </header>
      <main className="flex-1 overflow-y-auto overflow-x-hidden">
        <div className="min-h-full w-full px-4 py-6 md:px-8">
          {children}
        </div>
      </main>
    </div>
  </div>
);
