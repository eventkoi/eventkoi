import { useOutletContext } from "react-router-dom";

import { Separator } from "@/components/ui/separator";

import { Box } from "@/components/box";
import { EventDate } from "@/components/event-date";
import { EventLocation } from "@/components/event-location";
import { EventName } from "@/components/event-name";
import { EventTemplate } from "@/components/event-template";

export function EventEditMain() {
  const [event, setEvent] = useOutletContext();

  return (
    <Box>
      <div className="grid w-full">
        <EventName event={event} setEvent={setEvent} />
        <Separator />
        <EventDate event={event} setEvent={setEvent} />
        <Separator />
        <EventLocation event={event} setEvent={setEvent} />
        <Separator />
        <EventTemplate event={event} setEvent={setEvent} />
      </div>
    </Box>
  );
}
