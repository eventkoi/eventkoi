import { useOutletContext } from "react-router-dom";

import { Box } from "@/components/box";

export function CalendarEditEmbed() {
  const [calendar, setCalendar] = useOutletContext();

  return (
    <Box>
      <div className="grid w-full">test</div>
    </Box>
  );
}
