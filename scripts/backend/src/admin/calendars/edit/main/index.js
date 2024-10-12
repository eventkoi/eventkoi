import { useOutletContext } from "react-router-dom";

import { Separator } from "@/components/ui/separator";

import { Box } from "@/components/box";
import { CalendarName } from "@/components/calendar-name";

export function CalendarEditMain() {
  const [calendar, setCalendar] = useOutletContext();

  return (
    <Box>
      <div className="grid w-full">
        <CalendarName calendar={calendar} setCalendar={setCalendar} />
        <Separator />
      </div>
    </Box>
  );
}
