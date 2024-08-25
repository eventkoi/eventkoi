import { useParams } from "react-router-dom";

import { EventSetup } from "@/components/event-setup";

export function EventEdit() {
  const { id } = useParams();

  return <EventSetup id={id} />;
}
