import { useEffect, useState } from "react";

import { cn } from "@/lib/utils";
import { add, format } from "date-fns";

import { Button } from "@/components/ui/button";
import { Calendar } from "@/components/ui/calendar";
import { Checkbox } from "@/components/ui/checkbox";
import { Label } from "@/components/ui/label";
import {
  Popover,
  PopoverContent,
  PopoverTrigger,
} from "@/components/ui/popover";

import { TimePicker } from "@/components/time-picker";

import { MoveRight } from "lucide-react";

export function EventDate({ event, setEvent }) {
  const { date_now, date_24h, time_now } = eventkoi_params;

  const [period, setPeriod] = useState("AM");

  let startDate = event.date.start ? new Date(event.date.start) : undefined;
  let endDate = event.date.end ? new Date(event.date.end) : undefined;

  const handleStartSelect = (newDay) => {
    if (!newDay) {
      updateStartDate();
      return;
    }
    if (!startDate) {
      updateStartDate(newDay);
      return;
    }
    startDate = new Date(startDate);
    const diff = newDay.getTime() - startDate.getTime();
    const diffInDays = diff / (1000 * 60 * 60 * 24);
    const newDateFull = add(startDate, { days: Math.ceil(diffInDays) });
    updateStartDate(newDateFull);
  };

  const handleEndSelect = (newDay) => {
    if (!newDay) {
      updateEndDate();
      return;
    }
    if (!endDate) {
      updateEndDate(newDay);
      return;
    }
    endDate = new Date(endDate);
    const diff = newDay.getTime() - endDate.getTime();
    const diffInDays = diff / (1000 * 60 * 60 * 24);
    const newDateFull = add(endDate, { days: Math.ceil(diffInDays) });
    updateEndDate(newDateFull);
  };

  const updateStartDate = (date) => {
    setEvent((prevState) => ({
      ...prevState,
      date: {
        ...prevState.date,
        start: date ? format(date, "yyyy-MM-dd hh:mm a") : "",
      },
    }));
  };

  const updateEndDate = (date) => {
    setEvent((prevState) => ({
      ...prevState,
      date: {
        ...prevState.date,
        end: date ? format(date, "yyyy-MM-dd hh:mm a") : "",
      },
    }));
  };

  useEffect(() => {
    console.log(event);
  }, [event]);

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
                    "w-auto justify-start text-left font-normal",
                    !startDate &&
                      "text-muted-foreground/60 hover:text-muted-foreground/60",
                    event?.tbc &&
                      "disabled:cursor-not-allowed disabled:bg-secondary disabled:text-muted-foreground/60 disabled:opacity-100"
                  )}
                  disabled={event?.tbc}
                >
                  {startDate ? (
                    format(startDate, "d MMM yyyy h:mm a")
                  ) : (
                    <span>
                      {date_now} {time_now}
                    </span>
                  )}
                </Button>
              </PopoverTrigger>
              <PopoverContent align="start" className="w-auto p-0">
                <Calendar
                  mode="single"
                  selected={startDate}
                  onSelect={(d) => handleStartSelect(d)}
                  initialFocus
                />
                <div className="p-3 border-t border-border">
                  <TimePicker
                    setDate={updateStartDate}
                    date={startDate}
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
          <div className="flex">
            <Popover>
              <PopoverTrigger asChild>
                <Button
                  variant={"outline"}
                  className={cn(
                    "w-auto justify-start text-left font-normal",
                    !endDate &&
                      "text-muted-foreground/60 hover:text-muted-foreground/60",
                    event?.tbc &&
                      "disabled:cursor-not-allowed disabled:bg-secondary disabled:text-muted-foreground/60 disabled:opacity-100"
                  )}
                  disabled={event?.tbc}
                >
                  {endDate ? (
                    format(endDate, "d MMM yyyy h:mm a")
                  ) : (
                    <span>
                      {date_now} {time_now}
                    </span>
                  )}
                </Button>
              </PopoverTrigger>
              <PopoverContent align="start" className="w-auto p-0">
                <Calendar
                  mode="single"
                  selected={endDate}
                  onSelect={(d) => handleEndSelect(d)}
                  initialFocus
                />
                <div className="p-3 border-t border-border">
                  <TimePicker
                    setDate={updateEndDate}
                    date={endDate}
                    period={period}
                    setPeriod={setPeriod}
                  />
                </div>
              </PopoverContent>
            </Popover>
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
