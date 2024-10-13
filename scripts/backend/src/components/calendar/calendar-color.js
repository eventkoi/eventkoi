import { Label } from "@/components/ui/label";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";

import { Panel } from "@/components/panel";

const colors = {
  accent: "Accent color",
};

const values = {
  accent: "#578CA7",
};

export function CalendarColor({ calendar, setCalendar }) {
  return (
    <Panel>
      <Label htmlFor="color">Color</Label>
      <Select
        value={calendar?.color}
        onValueChange={(value) => {
          setCalendar((prevState) => ({
            ...prevState,
            startday: value,
          }));
        }}
      >
        <SelectTrigger id="color" className="w-[250px]">
          <div
            className={`flex rounded-full w-5 h-5 bg-[${
              values[calendar?.color]
            }]`}
          />
          <SelectValue placeholder="Select a color" />
        </SelectTrigger>
        <SelectContent>
          {Object.keys(colors).map(function (key, index) {
            return (
              <SelectItem key={`option-${key}`} value={key}>
                {colors[key]}
              </SelectItem>
            );
          })}
        </SelectContent>
      </Select>
    </Panel>
  );
}
