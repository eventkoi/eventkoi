import { useOutletContext } from "react-router-dom";

import { Box } from "@/components/box";

export function EventEditMain() {
  const [event, setEvent] = useOutletContext();

  return <Box>test</Box>;
}
