import apiRequest from "@wordpress/api-fetch";

import { useEffect, useState } from "react";
import { Outlet, useLocation, useNavigate, useParams } from "react-router-dom";

import { CalendarHeader } from "@/components/calendar/calendar-header";
import { CalendarTabs } from "@/components/calendar/calendar-tabs";
import { Loader } from "@/components/loader";
import { Wrapper } from "@/components/wrapper";

import { toast } from "sonner";

export function CalendarEdit() {
  const navigate = useNavigate();
  const location = useLocation();
  const { id } = useParams();

  const [loading, setLoading] = useState(true);
  const [calendar, setCalendar] = useState(null);

  var parent = location.pathname?.split("/");
  var view = parent[3];
  let calendarId = parseInt(id) || 0;

  const showToast = (response) => {
    if (!response.message) {
      return;
    }

    const toastId = toast(
      <div
        className="flex items-center cursor-pointer active:ring-2 active:ring-ring active:ring-offset-2 bg-[#222222] rounded-sm border-0 font-medium justify-between p-4 gap-4 text-sm leading-5 text-primary-foreground w-60"
        onClick={() => toast.dismiss(toastId)}
      >
        {response.message}{" "}
        {response.url && (
          <div
            onClick={() => {
              window.open(response.url, "_blank");
            }}
            className="underline underline-offset-2 hover:no-underline"
          >
            View event
          </div>
        )}
      </div>,
      { duration: 4000 }
    );
  };

  const getCalendar = async () => {
    await apiRequest({
      path: `${eventkoi_params.api}/calendar?id=${calendarId}`,
      method: "get",
    })
      .then((response) => {
        console.log(response);
        setCalendar(response);
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
    if (!calendarId) {
      console.log(eventkoi_params.new_calendar);
      setCalendar(eventkoi_params.new_calendar);
      setLoading(false);
    } else {
      getCalendar();
    }
  }, []);

  if (loading || (calendarId && !calendar?.id)) {
    return (
      <div className="w-full flex-1 flex items-center justify-center text-sm flex-col gap-4 relative">
        <Loader />
      </div>
    );
  }

  return (
    <>
      <CalendarHeader
        loading={loading}
        setLoading={setLoading}
        calendar={calendar}
        setCalendar={setCalendar}
      />
      <Wrapper className="max-w-[940px]">
        <div className="w-full flex-1 mx-auto items-start gap-[80px] grid grid-cols-[200px_1fr]">
          <CalendarTabs
            calendar={calendar}
            setCalendar={setCalendar}
            location={location}
          />
          <div className="grid">
            <Outlet context={[calendar, setCalendar]} />
          </div>
        </div>
        <div className="h-10" />
      </Wrapper>
    </>
  );
}
