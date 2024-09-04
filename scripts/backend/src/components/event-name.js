import { cn } from "@/lib/utils";

import { Input } from "@/components/ui/input";

import { PencilLine } from "lucide-react";

export function EventName({ event, setEvent, isTyping, setIsTyping }) {
  return (
    <div>
      {isTyping ? (
        <Input
          type="text"
          value={event?.title}
          onChange={(e) => {
            setEvent((prevState) => ({
              ...prevState,
              title: e.target.value,
            }));
          }}
          autoFocus
          onBlur={() => setIsTyping(false)}
          onKeyDown={(e) => e.key === "Enter" && setIsTyping(false)}
        />
      ) : (
        <div
          className={cn(
            "inline-flex pr-6 relative cursor-pointer underline underline-offset-[7px] text-muted-foreground font-medium text-lg",
            event?.title && "text-foreground no-underline"
          )}
          onClick={() => {
            setIsTyping(true);
          }}
        >
          {event?.title ? event?.title : "Click to add event name"}

          {event?.title && (
            <PencilLine className="absolute top-1 right-0 w-5 h-5 text-ring" />
          )}
        </div>
      )}
    </div>
  );
}
