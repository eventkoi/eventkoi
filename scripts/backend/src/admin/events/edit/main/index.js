import { useState } from "react";
import { useOutletContext } from "react-router-dom";

import { Box } from "@/components/box";
import { EventDate } from "@/components/event-date";
import { EventName } from "@/components/event-name";

export function EventEditMain() {
  const [event, setEvent] = useOutletContext();

  const [isTyping, setIsTyping] = useState(false);

  return (
    <Box>
      <div className="max-w-[480px] space-y-8">
        <EventName
          event={event}
          setEvent={setEvent}
          isTyping={isTyping}
          setIsTyping={setIsTyping}
        />
        <EventDate event={event} setEvent={setEvent} />
      </div>
    </Box>
  );
}
