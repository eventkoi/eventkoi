import { Outlet } from "react-router-dom";

import { Subnav } from "@/components/sub-nav";
import { Wrapper } from "@/components/wrapper";

export function Events() {
  return (
    <>
      <Subnav root="events" />
      <Wrapper>
        <Outlet />
      </Wrapper>
    </>
  );
}
