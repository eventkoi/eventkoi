import apiRequest from "@wordpress/api-fetch";

import { useEffect, useState } from "react";
import { Outlet, useLocation, useNavigate, useParams } from "react-router-dom";

import { EventHeader } from "@/components/event-header";
import { EventTabs } from "@/components/event-tabs";
import { Wrapper } from "@/components/wrapper";

export function EventEdit() {
  const navigate = useNavigate();
  const location = useLocation();
  const { id } = useParams();

  const [loading, setLoading] = useState(true);
  const [event, setEvent] = useState(null);

  var parent = location.pathname?.split("/");
  var view = parent[3];
  let eventId = parseInt(id) || 0;

  const getEvent = async () => {
    await apiRequest({
      path: `${eventkoi_params.api}/event?id=${eventId}`,
      method: "get",
    })
      .then((response) => {
        console.log(response);
        setEvent(response);
        setLoading(false);
      })
      .catch((error) => {
        console.log(error);
        setLoading(false);
      });
  };

  useEffect(() => {
    if (!view) {
      navigate("main");
    }
  }, [location]);

  useEffect(() => {
    getEvent();
  }, []);

  return (
    <>
      <EventHeader eventId={eventId} event={event} setEvent={setEvent} />
      <Wrapper>
        <div className="w-full flex-1 mx-auto items-start gap-[80px] grid grid-cols-[200px_1fr] min-h-[2000px]">
          <EventTabs
            eventId={eventId}
            event={event}
            setEvent={setEvent}
            location={location}
          />
          <div className="grid">
            <Outlet context={[eventId, event, setEvent]} />
          </div>
        </div>
      </Wrapper>
    </>
  );
}
