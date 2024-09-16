import { Outlet } from "react-router-dom";

import { Subnav } from "@/components/sub-nav";
import { Wrapper } from "@/components/wrapper";

export function Settings() {
  return (
    <>
      <Subnav root="settings" />
      <Wrapper>
        <Outlet />
      </Wrapper>
    </>
  );
}
