import { cn } from "@/lib/utils";

import { Button } from "@/components/ui/button";
import { Skeleton } from "@/components/ui/skeleton";

import { Box } from "@/components/box";

import { FilePlus, Globe, PenLine } from "lucide-react";

export function EventEditActions({ loading, event }) {
  const buttonClass = cn(
    "font-normal justify-start border border-input rounded-xl hover:border-foreground/40 min-h-14 px-4",
    !event?.id && "opacity-70 pointer-events-none [&>a]:pointer-events:none"
  );

  if (loading) {
    return (
      <Box>
        <div className="flex flex-col gap-4 w-full">
          <Skeleton className="h-3 w-full" />
          <Skeleton className="h-3 w-3/4" />
          <Skeleton className="h-3 w-2/3" />
        </div>
      </Box>
    );
  }

  return (
    <Box>
      <Button variant="ghost" asChild className={buttonClass}>
        <a href={event?.url}>
          <Globe className="mr-4 w-5 h-5" />
          View event page
        </a>
      </Button>

      <Button variant="ghost" asChild className={buttonClass}>
        <a href="">
          <PenLine className="mr-4 w-5 h-5" />
          Edit event page
        </a>
      </Button>

      <Button variant="ghost" asChild className={buttonClass}>
        <a href="">
          <FilePlus className="mr-4 w-5 h-5" />
          Edit event template
        </a>
      </Button>
    </Box>
  );
}
