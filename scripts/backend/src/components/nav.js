import { cn } from "@/lib/utils";
import { Link, useLocation } from "react-router-dom";

import { EventNavBar } from "@/components/event-nav-bar";
import { Logo } from "@/components/logo";
import { Navbar } from "@/components/nav-bar";
import { Button } from "@/components/ui/button";
import { ChevronLeft } from "lucide-react";

import { tabs } from "@/data/tabs";

export function Nav({ isEvent = false }) {
  const location = useLocation();

  const split = location.pathname.split("events/");
  if (split[1] && (parseInt(split[1]) > 0 || split[1].includes("add"))) {
    isEvent = true;
  }

  return (
    <header
      className={cn(
        "flex text-sm h-12 items-center border-b gap-6 px-8",
        isEvent &&
          "sticky top-0 z-[100000] bg-muted h-20 gap-2 shadow-sm border-none"
      )}
    >
      <Logo />
      {!isEvent ? (
        <Navbar tabs={tabs["main"]} />
      ) : (
        <Button variant="link" asChild>
          <Link to="/events">
            <ChevronLeft className="mr-2 h-4 w-4" />
            Back
          </Link>
        </Button>
      )}
      <div className="flex w-full justify-end">
        {!isEvent ? <Navbar tabs={tabs["side"]} asDiv /> : <EventNavBar />}
      </div>
    </header>
  );
}
