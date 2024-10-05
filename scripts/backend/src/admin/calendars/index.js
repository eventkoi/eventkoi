import { Outlet, useLocation } from "react-router-dom";

import { Wrapper } from "@/components/wrapper";

export function Calendars() {
  const location = useLocation();

  const path = location.pathname.split("calendars");

  if (path[1]) {
    let id = path[1].split("/");
    if (path[1].includes("add") || (id[1] && parseInt(id[1]) > 0)) {
      return <Outlet />;
    }
  }

  return (
    <Wrapper>
      <Outlet />
    </Wrapper>
  );
}
