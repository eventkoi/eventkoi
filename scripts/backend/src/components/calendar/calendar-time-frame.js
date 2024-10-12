import { Label } from "@/components/ui/label";
import { Tabs, TabsList, TabsTrigger } from "@/components/ui/tabs";

import { Panel } from "@/components/panel";

export function CalendarTimeFrame({ calendar, setCalendar }) {
  const onTabChange = (value) => {
    setCalendar((prevState) => ({
      ...prevState,
      timeframe: value,
    }));
  };

  return (
    <Panel>
      <Label>Default time frame to display</Label>
      <Tabs
        defaultValue={calendar?.timeframe}
        onValueChange={onTabChange}
        className="pt-1"
      >
        <TabsList className="border border-input rounded-lg">
          <TabsTrigger value="month" className="rounded-lg">
            Month
          </TabsTrigger>
          <TabsTrigger value="week" className="rounded-lg">
            Week
          </TabsTrigger>
        </TabsList>
      </Tabs>
    </Panel>
  );
}
