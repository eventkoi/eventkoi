import { Label } from "@/components/ui/label";
import { Textarea } from "@/components/ui/textarea";

export function EventDescription({ event, setEvent }) {
  return (
    <div className="flex flex-col gap-2">
      <Label htmlFor="description">Event description</Label>
      <Textarea
        id="description"
        placeholder="Share details of this event with your guests."
        className="min-h-[110px]"
        value={event?.description}
        onChange={(e) => {
          setEvent((prevState) => ({
            ...prevState,
            description: e.target.value,
          }));
        }}
      />
    </div>
  );
}
