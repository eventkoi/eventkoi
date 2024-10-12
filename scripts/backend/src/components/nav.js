import { cn } from "@/lib/utils";
import { useLocation } from "react-router-dom";

import { Logo } from "@/components/logo";
import { Navbar } from "@/components/nav-bar";

import { tabs } from "@/data/tabs";

export function Nav({ isEvent = false, isCalendar = false }) {
  const location = useLocation();

  const event = location.pathname.split("events/");
  if (event[1] && (parseInt(event[1]) > 0 || event[1].includes("add"))) {
    isEvent = true;
  }

  if (isEvent) {
    return null;
  }

  const calendar = location.pathname.split("calendars/");
  if (
    calendar[1] &&
    (parseInt(calendar[1]) > 0 || calendar[1].includes("add"))
  ) {
    isCalendar = true;
  }

  if (isCalendar) {
    return null;
  }

  return (
    <header
      className={cn(
        "flex text-sm h-12 items-center border-b gap-6 px-8",
        isEvent && "sticky top-8 z-[500] bg-muted h-20 shadow-sm border-none"
      )}
    >
      <Logo />
      <Navbar tabs={tabs["main"]} />
      <div className="flex w-full justify-end">
        <Navbar tabs={tabs["side"]} asDiv />
      </div>
    </header>
  );
}
