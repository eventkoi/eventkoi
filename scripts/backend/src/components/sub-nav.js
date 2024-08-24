import { Logo } from "@/components/logo";
import { Navbar } from "@/components/nav-bar";

import { tabs } from "@/data/tabs";

export function Subnav(props) {
  const { root } = props;

  if (!tabs[root]) {
    return null;
  }

  return (
    <div className="flex h-12 items-center border-b gap-6 px-8 text-sm">
      <Logo invisible />
      <Navbar tabs={tabs[root]} isSub />
    </div>
  );
}
