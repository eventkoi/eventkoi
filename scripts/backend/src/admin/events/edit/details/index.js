import { useOutletContext } from "react-router-dom";

import { Box } from "@/components/box";
import { EventDescription } from "@/components/event-description";
import { EventImage } from "@/components/event-image";
import { Heading } from "@/components/heading";

export function EventEditDetails() {
  const [event, setEvent] = useOutletContext();

  return (
    <Box>
      <div className="w-full space-y-10 pb-6">
        <Heading level={3}>Additional details</Heading>
        <EventDescription event={event} setEvent={setEvent} />
        <EventImage event={event} setEvent={setEvent} />
      </div>
    </Box>
  );
}
