import { useState } from "react";

import { cn } from "@/lib/utils";
import { add, format } from "date-fns";

import { Button } from "@/components/ui/button";
import { Calendar } from "@/components/ui/calendar";
import { Checkbox } from "@/components/ui/checkbox";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import {
  Popover,
  PopoverContent,
  PopoverTrigger,
} from "@/components/ui/popover";

import { TimePicker } from "@/components/time-picker";

import { Calendar as CalendarIcon, MoveRight } from "lucide-react";

export function EventDate({ event, setEvent }) {
  const { date_now, date_24h, time_now } = eventkoi_params;

  const [date, setDate] = useState();
  const [period, setPeriod] = useState("PM");

  /**
   * carry over the current time when a user clicks a new day
   * instead of resetting to 00:00
   */
  const handleSelect = (newDay) => {
    console.log(newDay);
    if (!newDay) {
      setDate();
      return;
    }
    if (!date) {
      setDate(newDay);
      return;
    }
    const diff = newDay.getTime() - date.getTime();
    const diffInDays = diff / (1000 * 60 * 60 * 24);
    const newDateFull = add(date, { days: Math.ceil(diffInDays) });
    setDate(newDateFull);
  };

  return (
    <div className="flex flex-col gap-3">
      <div className="flex gap-4 items-start">
        <div className="flex flex-col gap-2">
          <Label className={cn(event?.tbc && "text-muted-foreground")}>
            Start
          </Label>
          <div className="flex">
            <Popover>
              <PopoverTrigger asChild>
                <Button
                  variant={"outline"}
                  className={cn(
                    "w-[280px] justify-start text-left font-normal",
                    !date && "text-muted-foreground"
                  )}
                >
                  <CalendarIcon className="mr-2 h-4 w-4" />
                  {date ? (
                    format(date, "PPP HH:mm:ss")
                  ) : (
                    <span>Pick a date</span>
                  )}
                </Button>
              </PopoverTrigger>
              <PopoverContent className="w-auto p-0">
                <Calendar
                  mode="single"
                  selected={date}
                  onSelect={(d) => handleSelect(d)}
                  initialFocus
                />
                <div className="p-3 border-t border-border">
                  <TimePicker
                    setDate={setDate}
                    date={date}
                    period={period}
                    setPeriod={setPeriod}
                  />
                </div>
              </PopoverContent>
            </Popover>
          </div>
        </div>
        <div className="mt-8">
          <MoveRight
            className="text-muted-foreground w-6 h-6"
            strokeWidth={1.5}
          />
        </div>
        <div className="flex flex-col gap-2">
          <Label className={cn(event?.tbc && "text-muted-foreground")}>
            End
          </Label>
          <div className="flex gap-2">
            <Input
              placeholder={date_24h}
              className="w-3/5"
              disabled={event?.tbc}
            />
            <Input
              placeholder={time_now}
              className="w-2/5"
              disabled={event?.tbc}
            />
          </div>
        </div>
      </div>

      <div className="flex items-center space-x-2">
        <Checkbox
          id="tbc"
          checked={event?.tbc}
          onCheckedChange={(bool) => {
            setEvent((prevState) => ({
              ...prevState,
              tbc: bool,
            }));
          }}
        />
        <label
          htmlFor="tbc"
          className="peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
        >
          Date and time not confirmed.
        </label>
      </div>

      <div>
        Current timezone is: {event?.timezone}.{" "}
        <a href="#" className="underline">
          Change timezone.
        </a>
      </div>
    </div>
  );
}
