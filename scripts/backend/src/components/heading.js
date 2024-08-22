import { cn } from "@/lib/utils";

const sizes = {
  1: "text-2xl",
  3: "text-xl",
  4: "text-base",
};

export function Heading({ level = 1, tagline = null, children }) {
  const Tag = `h${level}`;

  return (
    <div className="grid gap-1">
      <Tag
        className={cn(
          sizes.level,
          "font-medium text-foreground tracking-tight scroll-m-20"
        )}
      >
        {children}
      </Tag>
      {tagline && (
        <span className="block text-sm text-muted-foreground font-normal">
          {tagline}
        </span>
      )}
    </div>
  );
}
