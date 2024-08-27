import apiRequest from "@wordpress/api-fetch";
import { useEffect, useState } from "react";
import { Link, Outlet, useLocation, useNavigate } from "react-router-dom";

import { Loader } from "@/components/loader";

const tabs = [
  { name: "main", title: "Main info" },
  { name: "details", title: "Additional details" },
];

export function EventSetup({ id = 0 }) {
  const navigate = useNavigate();
  const location = useLocation();

  const [loading, setLoading] = useState(true);
  const [event, setEvent] = useState(null);

  var parent = location.pathname?.split("/");
  var view = parent[3];

  const active =
    "font-medium px-3 py-3 rounded-lg text-foreground bg-foreground/5";

  const heading = event?.id ? "Edit event" : "Add event";

  const getEvent = async () => {
    await apiRequest({
      path: `${eventkoi_params.api}/event?id=${parseInt(id)}`,
      method: "get",
    })
      .then((response) => {
        console.log(response);
        setEvent(response);
        setLoading(false);
      })
      .catch((error) => {
        console.log(error);
        setLoading(false);
      });
  };

  useEffect(() => {
    if (!view) {
      navigate("main");
    }
  }, [location]);

  useEffect(() => {
    getEvent();
  }, []);

  return (
    <div className="w-full flex-1 mx-auto items-start gap-[80px] grid grid-cols-[200px_1fr]">
      <nav className="grid gap-1 text-sm text-muted-foreground">
        {tabs.map(function (item, i) {
          let activeTabClass = "font-medium px-3 py-3 rounded-lg";
          if (parent && view && view === item.name) {
            activeTabClass = active;
          }
          if (parent && !view && item.name === "main") {
            activeTabClass = active;
          }
          return (
            <Link
              key={`setting-tab-${i}`}
              to={item.name}
              className={activeTabClass}
            >
              {item.title}
            </Link>
          );
        })}
      </nav>
      {loading ? (
        <Loader />
      ) : (
        <div className="grid">
          <Outlet event={event} />
        </div>
      )}
    </div>
  );
}
