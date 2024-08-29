import { useOutletContext } from "react-router-dom";

import { Box } from "@/components/box";
import { Heading } from "@/components/heading";

export function EventEditDetails() {
  const [eventId, event, setEvent] = useOutletContext();

  return (
    <Box>
      <Heading level={3}>Additional details {eventId}</Heading>
    </Box>
  );
}
