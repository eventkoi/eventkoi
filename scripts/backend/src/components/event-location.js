import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs";

export function EventLocation({ event, setEvent }) {
  const onTabChange = (value) => {
    setEvent((prevState) => ({
      ...prevState,
      type: value,
    }));
  };

  return (
    <Tabs
      defaultValue={event?.type}
      className="w-auto"
      onValueChange={onTabChange}
    >
      <TabsList className="border border-input rounded-lg">
        <TabsTrigger value="inperson" className="rounded-lg">
          In person event
        </TabsTrigger>
        <TabsTrigger value="virtual" className="rounded-lg">
          Virtual event
        </TabsTrigger>
      </TabsList>
      <TabsContent value="inperson" className="mt-4">
        <div className="flex flex-col gap-2">
          <Label htmlFor="location">Location</Label>
          <Input
            type="text"
            id="location"
            value={event?.location}
            placeholder="Venue name and address"
            onChange={(e) => {
              setEvent((prevState) => ({
                ...prevState,
                location: e.target.value,
              }));
            }}
          />
        </div>
      </TabsContent>
      <TabsContent value="virtual" className="mt-4">
        <div className="flex flex-col gap-2">
          <Label htmlFor="virtual_url">URL</Label>
          <Input
            type="text"
            id="virtual_url"
            value={event?.virtual_url}
            placeholder="Web address of your event"
            onChange={(e) => {
              setEvent((prevState) => ({
                ...prevState,
                virtual_url: e.target.value,
              }));
            }}
          />
        </div>
      </TabsContent>
    </Tabs>
  );
}
