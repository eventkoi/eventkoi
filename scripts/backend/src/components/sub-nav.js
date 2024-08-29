import { useLocation } from "react-router-dom";

import { Logo } from "@/components/logo";
import { Navbar } from "@/components/nav-bar";

import { tabs } from "@/data/tabs";

export function Subnav({ root }) {
  const location = useLocation();

  return (
    <div className="flex text-sm h-12 items-center border-b gap-6 px-8">
      <Logo invisible />
      <Navbar tabs={tabs[root]} isSub />
    </div>
  );
}
