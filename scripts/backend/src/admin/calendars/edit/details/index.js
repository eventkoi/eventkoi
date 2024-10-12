import { useOutletContext } from "react-router-dom";

import { Box } from "@/components/box";

export function CalendarEditDetails() {
  const [event, setEvent] = useOutletContext();

  return (
    <Box>
      <div className="grid w-full">test</div>
    </Box>
  );
}
