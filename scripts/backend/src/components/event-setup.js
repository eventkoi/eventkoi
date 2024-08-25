import apiRequest from "@wordpress/api-fetch";
import { useEffect, useState } from "react";

import { Skeleton } from "@/components/ui/skeleton";

import { Heading } from "@/components/heading";
import { UserBreadcrumb } from "@/components/user-breadcrumb";

import { EventEditActions } from "@/components/event-edit-actions";
import { EventEditDetails } from "@/components/event-edit-details";
import { EventEditMain } from "@/components/event-edit-main";

export function EventSetup({ id = 0 }) {
  const [loading, setLoading] = useState(true);
  const [event, setEvent] = useState(null);

  const getEvent = async () => {
    await apiRequest({
      path: `eventkoi/v1/event?id=${id}`,
      method: "get",
    })
      .then((response) => {
        console.log(response);
        setTimeout(() => {
          setEvent(response);
          setLoading(false);
        }, 700);
      })
      .catch((error) => {
        console.log(error);
      });
  };

  useEffect(() => {
    getEvent();
  }, []);

  const heading = event?.id ? "Edit event" : "Add event";

  return (
    <div className="flex flex-col gap-8">
      <div className="w-full flex flex-col gap-2">
        <UserBreadcrumb
          path="events"
          parent="Events"
          active={heading}
          loading={loading}
        />
        {loading ? (
          <div className="flex items-center h-8">
            <Skeleton className="bg-primary/10 h-3 w-[150px]" />
          </div>
        ) : (
          <Heading>{heading}</Heading>
        )}
      </div>
      <div className="grid gap-8 grid-cols-12 text-card-foreground items-start">
        <div className="flex flex-col gap-8 col-span-8">
          <EventEditMain loading={loading} event={event} />
          <EventEditDetails loading={loading} event={event} />
        </div>
        <div className="flex flex-col gap-8 col-span-4">
          <EventEditActions loading={loading} event={event} />
        </div>
      </div>
    </div>
  );
}
