import { Outlet, useLocation } from "react-router-dom";

import { Subnav } from "@/components/sub-nav";
import { Wrapper } from "@/components/wrapper";

export function Events() {
  const location = useLocation();

  const path = location.pathname.split("events");

  if (path[1]) {
    let id = path[1].split("/");
    if (path[1].includes("add") || (id[1] && parseInt(id[1]) > 0)) {
      return <Outlet />;
    }
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
