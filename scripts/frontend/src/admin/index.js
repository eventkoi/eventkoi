import { useEffect } from "react";
import { createRoot } from "react-dom/client";
import {
  Route,
  HashRouter as Router,
  Routes,
  useLocation,
} from "react-router-dom";

import { Dashboard } from "@/admin/dashboard";
import { DashboardOverview } from "@/admin/dashboard/overview";
import { Events } from "@/admin/events";
import { EventEdit } from "@/admin/events/edit";
import { EventEditDetails } from "@/admin/events/edit/details";
import { EventEditMain } from "@/admin/events/edit/main";
import { EventsOverview } from "@/admin/events/overview";
import { EventTemplates } from "@/admin/events/templates";
import { Home } from "@/admin/home";
import { Settings } from "@/admin/settings";
import { SettingsOverview } from "@/admin/settings/overview";

import { Nav } from "@/components/nav";
import { Toaster } from "@/components/ui/sonner";

import { useWindowDimensions } from "@/lib/use-window-dimensions";

export function Admin() {
  const location = useLocation();
  const { height, width } = useWindowDimensions();

  let offset = 0;
  if (width >= 960) {
    offset = 160;
  }
  if (width < 960 && width > 780) {
    offset = 32;
  }

  useEffect(() => {
    jQuery(".wp-toolbar").css({ backgroundColor: "inherit" });

    var parent = location.pathname?.split("/");
    var menu = parent[1];

    jQuery("#toplevel_page_eventkoi ul.wp-submenu-wrap li").removeClass(
      "current"
    );
    jQuery(
      '#toplevel_page_eventkoi ul.wp-submenu-wrap li a[href*="eventkoi#/' +
        menu +
        '"]'
    )
      .parent()
      .addClass("current");
  }, [location]);

  return (
    <div className="w-full flex flex-col min-h-[calc(100vh-46px)] md:min-h-[calc(100vh-32px)]">
      <Toaster
        expand
        position="bottom-left"
        visibleToasts={2}
        toastOptions={{
          unstyled: true,
          style: {
            left: offset,
          },
        }}
      />
      <Nav />
      <Routes>
        <Route index element={<Home />} />
        <Route path="dashboard" element={<Dashboard />}>
          <Route index element={<DashboardOverview />} />
          <Route path="overview" element={<DashboardOverview />} />
          <Route path="*" element={<Home />} />
        </Route>
        <Route path="events" element={<Events />}>
          <Route index element={<EventsOverview />} />
          <Route path="" element={<EventsOverview />} />
          <Route path="templates" element={<EventTemplates />} />
          <Route path=":id" element={<EventEdit />}>
            <Route path="main" element={<EventEditMain />} />
            <Route path="details" element={<EventEditDetails />} />
          </Route>
          <Route path="*" element={<Home />} />
        </Route>
        <Route path="settings" element={<Settings />}>
          <Route index element={<SettingsOverview />} />
          <Route path="overview" element={<SettingsOverview />} />
          <Route path="*" element={<Home />} />
        </Route>
        <Route path="*" element={<Home />} />
      </Routes>
    </div>
  );
}

var rootElement = document.getElementById("eventkoi-admin");

if (rootElement) {
  const root = createRoot(rootElement);
  root.render(
    <Router>
      <Admin />
    </Router>
  );
}
