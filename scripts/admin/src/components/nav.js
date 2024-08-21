import { Logo } from "@/components/logo";
import { Navbar } from "@/components/nav-bar";

import { tabs } from "@/data/tabs";

export function Nav(props) {
  return (
    <header className="flex h-12 items-center border-b gap-6 px-6">
      <Logo />
      <Navbar tabs={tabs["main"]} />
      <div className="flex w-full justify-end">
        <Navbar tabs={tabs["side"]} asDiv />
      </div>
    </header>
  );
}
