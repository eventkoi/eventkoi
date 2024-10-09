import { useOutletContext } from "react-router-dom";

import { Separator } from "@/components/ui/separator";

import { Box } from "@/components/box";
import { EventCalendar } from "@/components/event-calendar";
import { EventDescription } from "@/components/event-description";
import { EventImage } from "@/components/event-image";
import { EventTemplate } from "@/components/event-template";
import { Heading } from "@/components/heading";
import { Panel } from "@/components/panel";

export function EventEditDetails() {
  const [event, setEvent] = useOutletContext();

  return (
    <Box>
      <div className="grid w-full">
        <Panel>
          <Heading level={3}>Additional details</Heading>
        </Panel>
        <Separator />
        <EventDescription event={event} setEvent={setEvent} />
        <Separator />
        <EventTemplate event={event} setEvent={setEvent} />
        <Separator />
        <EventCalendar event={event} setEvent={setEvent} />
        <Separator />
        <EventImage event={event} setEvent={setEvent} />
      </div>
    </Box>
  );
}
