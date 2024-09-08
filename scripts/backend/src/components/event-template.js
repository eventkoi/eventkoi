import { Label } from "@/components/ui/label";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";

export function EventTemplate({ event, setEvent }) {
  return (
    <div className="flex flex-col gap-2">
      <Label htmlFor="template">Select event template</Label>
      <Select
        value={event?.template}
        onValueChange={(value) => {
          setEvent((prevState) => ({
            ...prevState,
            template: value,
          }));
        }}
      >
        <SelectTrigger id="template" className="w-[250px]">
          <SelectValue placeholder="Select a template" />
        </SelectTrigger>
        <SelectContent>
          <SelectItem value="default">Default template</SelectItem>
        </SelectContent>
      </Select>
    </div>
  );
}
