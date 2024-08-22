import { useEffect, useState } from "react";
import { Outlet } from "react-router-dom";

import { Heading } from "@/components/heading";
import { Loader } from "@/components/loader";
import { Wrapper } from "@/components/wrapper";

export function Dashboard() {
  const [isLoading, setIsLoading] = useState(false);

  useEffect(() => {}, []);

  if (isLoading) {
    return <Loader />;
  }

  return (
    <Wrapper>
      <div className="mx-auto flex w-full gap-2 justify-between">
        <Heading>Dashboard</Heading>
      </div>
      <Outlet />
    </Wrapper>
  );
}
