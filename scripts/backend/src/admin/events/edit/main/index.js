import { useState } from "react";
import { useOutletContext } from "react-router-dom";

import { cn } from "@/lib/utils";

import { Input } from "@/components/ui/input";

import { Box } from "@/components/box";

import { PencilLine } from "lucide-react";

export function EventEditMain() {
  const [event, setEvent] = useOutletContext();

  const [isTyping, setIsTyping] = useState(false);

  return (
    <Box>
      <div className="max-w-[450px]">
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
    </Box>
  );
}
