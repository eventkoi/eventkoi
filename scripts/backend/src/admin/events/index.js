import { Outlet, useLocation } from "react-router-dom";

import { Subnav } from "@/components/sub-nav";
import { Wrapper } from "@/components/wrapper";

export function Events() {
  const location = useLocation();

  const path = location.pathname.split("events");

  if (path[1]) {
    return <Outlet />;
  }

  return (
    <>
      <Subnav root="events" />
      <Wrapper>
        <Outlet />
      </Wrapper>
    </>
  );
}
