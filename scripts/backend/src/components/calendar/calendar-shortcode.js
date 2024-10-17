import { useState } from "react";

import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";

import { Panel } from "@/components/panel";

import { CheckCheck, Files } from "lucide-react";

export function CalendarShortcode({ calendar, setCalendar }) {
  const [copying, setCopying] = useState(false);

  return (
    <Panel>
      <Label>Shortcode</Label>
      <div className="relative max-w-[260px]">
        <Input
          type="text"
          value={calendar?.shortcode}
          className="w-full"
          readOnly
          disabled={!calendar.url}
        />
        <Button
          variant="secondary"
          type="submit"
          className="absolute h-8 px-2 right-[5px] top-[4px] border-none cursor-pointer hover:bg-input"
          disabled={!calendar.url}
          onClick={() => {
            setCopying(true);
            navigator.clipboard.writeText(calendar?.shortcode);
            setTimeout(() => {
              setCopying(false);
            }, 1200);
          }}
        >
          {copying ? (
            <CheckCheck className="mr-2 h-5 w-5" />
          ) : (
            <Files className="mr-2 h-5 w-5" />
          )}
          {copying ? "Copied!" : "Copy"}
        </Button>
      </div>
    </Panel>
  );
}
