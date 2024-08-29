import { cn } from "@/lib/utils";

import { EventNavBack } from "@/components/event-nav-back";
import { EventNavBar } from "@/components/event-nav-bar";
import { Logo } from "@/components/logo";

export function EventHeader({ event, setEvent }) {
  return (
    <header
      className={cn(
        "flex text-sm h-12 items-center border-b gap-6 px-8",
        "sticky top-8 z-[500] bg-muted h-20 shadow-sm border-none"
      )}
    >
      <Logo />
      <EventNavBack event={event} setEvent={setEvent} />
      <div className="flex w-full justify-end">
        <EventNavBar event={event} setEvent={setEvent} />
      </div>
    </header>
  );
}
