import { useEffect, useState } from "react";

import apiRequest from "@wordpress/api-fetch";

import { Label } from "@/components/ui/label";
import { MultiSelect } from "@/components/ui/multiselect";

import { Panel } from "@/components/panel";

export function EventCalendar({ event, setEvent }) {
  const [items, setItems] = useState([]);

  const setCalendar = (selection) => {
    setEvent((prevState) => ({
      ...prevState,
      selectedCalendars: selection,
    }));
  };

  const getCalendars = async () => {
    let response = await apiRequest({
      path: `${eventkoi_params.api}/calendars`,
      method: "get",
    })
      .then((response) => {
        if (response) {
          let calendars = [];
          response.map((calendar, index) => {
            calendars.push({ label: calendar.name, value: calendar.id });
          });
          setItems(calendars);
        }
      })
      .catch((error) => {});
  };

  useEffect(() => {
    getCalendars();
  }, []);

  let selected = [];
  event?.calendar.map((calendar, index) => {
    selected.push({ label: calendar.name, value: calendar.id });
  });

  return (
    <Panel>
      <Label htmlFor="calendar">Event calendar</Label>
      {items && (
        <MultiSelect
          options={items}
          placeholder={"Select calendar(s)"}
          noItems={"No calendars are found."}
          searchPlaceholder={"Search calendars"}
          value={selected}
          onSelectionChange={setCalendar}
        />
      )}
    </Panel>
  );
}
