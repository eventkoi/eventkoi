import { useState } from "react";
import { useOutletContext } from "react-router-dom";

import { Box } from "@/components/box";
import { EventDate } from "@/components/event-date";
import { EventLocation } from "@/components/event-location";
import { EventName } from "@/components/event-name";
import { EventTemplate } from "@/components/event-template";

export function EventEditMain() {
  const [event, setEvent] = useOutletContext();

  const [isTyping, setIsTyping] = useState(false);

  return (
    <Box>
      <div className="w-full space-y-10 pb-6">
        <EventName
          event={event}
          setEvent={setEvent}
          isTyping={isTyping}
          setIsTyping={setIsTyping}
        />
        <EventDate event={event} setEvent={setEvent} />
        <EventLocation event={event} setEvent={setEvent} />
        <EventTemplate event={event} setEvent={setEvent} />
      </div>
    </Box>
  );
}
