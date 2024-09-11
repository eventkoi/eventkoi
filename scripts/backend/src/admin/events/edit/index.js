import apiRequest from "@wordpress/api-fetch";

import { useEffect, useState } from "react";
import {
  Link,
  Outlet,
  useLocation,
  useNavigate,
  useParams,
} from "react-router-dom";

import { EventHeader } from "@/components/event-header";
import { EventTabs } from "@/components/event-tabs";
import { Loader } from "@/components/loader";
import { Button } from "@/components/ui/button";
import { Wrapper } from "@/components/wrapper";

import { toast } from "sonner";

import { ChevronLeft, TriangleAlert } from "lucide-react";

export function EventEdit() {
  const navigate = useNavigate();
  const location = useLocation();
  const { id } = useParams();

  const [loading, setLoading] = useState(true);
  const [event, setEvent] = useState(null);

  var parent = location.pathname?.split("/");
  var view = parent[3];
  let eventId = parseInt(id) || 0;

  const restoreEvent = async () => {
    setLoading(true);
    await apiRequest({
      path: `${eventkoi_params.api}/restore_event`,
      method: "post",
      data: {
        event_id: event?.id,
      },
      headers: {
        "EVENTKOI-API-KEY": eventkoi_params.api_key,
      },
    })
      .then((response) => {
        setEvent(response.event);
        setLoading(false);
        if (response.success) {
          const toastId = toast(
            <div
              className="flex items-center cursor-pointer active:ring-2 active:ring-ring active:ring-offset-2 bg-[#222222] rounded-sm border-0 font-medium justify-between p-4 gap-4 text-sm leading-5 text-primary-foreground w-60"
              onClick={() => toast.dismiss(toastId)}
            >
              {response.success}
            </div>,
            { duration: 4000 }
          );
        }
      })
      .catch((error) => {
        setLoading(false);
      });
  };

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
        setLoading(false);
      });
  };

  useEffect(() => {
    if (!view) {
      navigate("main");
    }
  }, [location]);

  useEffect(() => {
    if (!eventId) {
      setEvent(eventkoi_params.new_event);
      setLoading(false);
    } else {
      getEvent();
    }
  }, []);

  if (loading || (eventId && !event?.id)) {
    return (
      <div className="w-full flex-1 flex items-center justify-center text-sm flex-col gap-4 relative">
        <Loader />
      </div>
    );
  }

  if (event?.status === "trash") {
    return (
      <div className="w-full flex-1 flex items-center justify-center text-sm flex-col gap-4 relative">
        <div className="absolute top-4 left-4">
          <Button variant="link" asChild>
            <Link to="/events">
              <ChevronLeft className="mr-2 h-4 w-4" />
              Back
            </Link>
          </Button>
        </div>
        <TriangleAlert
          className="w-10 h-10 text-muted-foreground"
          strokeWidth={1}
        />
        <div className="text-base text-muted-foreground">
          Event has moved to Trash. Restore it before you can edit.
        </div>
        <div className="pt-4">
          <Button
            variant="default"
            onClick={() => {
              restoreEvent();
            }}
          >
            Restore event
          </Button>
        </div>
      </div>
    );
  }
  return (
    <>
      <EventHeader
        loading={loading}
        setLoading={setLoading}
        event={event}
        setEvent={setEvent}
      />
      <Wrapper className="max-w-[940px]">
        <div className="w-full flex-1 mx-auto items-start gap-[80px] grid grid-cols-[200px_1fr]">
          <EventTabs event={event} setEvent={setEvent} location={location} />
          <div className="grid">
            <Outlet context={[event, setEvent]} />
          </div>
        </div>
      </Wrapper>
    </>
  );
}
