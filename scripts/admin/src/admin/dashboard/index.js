import { Outlet } from "react-router-dom";

import { Heading } from "@/components/heading";
import { Wrapper } from "@/components/wrapper";

export function Dashboard(props) {
  return (
    <Wrapper>
      <div className="mx-auto flex w-full gap-2 justify-between">
        <Heading>Dashboard</Heading>
      </div>
      <Outlet />
      <div className="h-10"></div>
    </Wrapper>
  );
}
