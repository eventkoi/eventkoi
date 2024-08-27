import { Box } from "@/components/box";
import { Heading } from "@/components/heading";

export function EventEditDetails({ event }) {
  console.log(event);
  console.log("test");

  return (
    <Box>
      <Heading level={3}>Event details</Heading>
    </Box>
  );
}
