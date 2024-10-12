import { useOutletContext } from "react-router-dom";

import { Separator } from "@/components/ui/separator";

import { Box } from "@/components/box";
import { EventDate } from "@/components/event/event-date";
import { EventLocation } from "@/components/event/event-location";
import { EventName } from "@/components/event/event-name";

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
      </div>
    </Box>
  );
}
