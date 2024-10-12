import { Label } from "@/components/ui/label";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";

import { Panel } from "@/components/panel";

const days = {
  monday: "Monday",
  tuesday: "Tuesday",
  wednesday: "Wednesday",
  thursday: "Thursday",
  friday: "Friday",
  saturday: "Saturday",
  sunday: "Sunday",
};

export function CalendarStartDay({ calendar, setCalendar }) {
  return (
    <Panel>
      <Label htmlFor="startday">Week starts on</Label>
      <Select
        value={calendar?.startday}
        onValueChange={(value) => {
          setCalendar((prevState) => ({
            ...prevState,
            startday: value,
          }));
        }}
      >
        <SelectTrigger id="startday" className="w-[250px]">
          <SelectValue placeholder="Select a day" />
        </SelectTrigger>
        <SelectContent>
          {Object.keys(days).map(function (key, index) {
            return (
              <SelectItem key={`option-${key}`} value={key}>
                {days[key]}
              </SelectItem>
            );
          })}
        </SelectContent>
      </Select>
    </Panel>
  );
}
