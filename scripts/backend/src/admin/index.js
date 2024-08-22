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
import { Home } from "@/admin/home";

import { Nav } from "@/components/nav";
import { Toaster } from "@/components/ui/sonner";

export function Admin() {
  const location = useLocation();

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
      <Toaster richColors expand />
      <Nav />
      <Routes>
        <Route index element={<Home />} />
        <Route path="dashboard" element={<Dashboard />}>
          <Route index element={<DashboardOverview />} />
          <Route path="overview" element={<DashboardOverview />} />
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
