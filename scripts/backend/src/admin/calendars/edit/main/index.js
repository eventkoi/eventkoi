import { useOutletContext } from "react-router-dom";

import { Separator } from "@/components/ui/separator";

import { Box } from "@/components/box";
import { CalendarName } from "@/components/calendar-name";
import { CalendarSlug } from "@/components/calendar-slug";

export function CalendarEditMain() {
  const [calendar, setCalendar] = useOutletContext();

  return (
    <Box>
      <div className="grid w-full">
        <CalendarName calendar={calendar} setCalendar={setCalendar} />
        <Separator />
        <CalendarSlug calendar={calendar} setCalendar={setCalendar} />
      </div>
    </Box>
  );
}
