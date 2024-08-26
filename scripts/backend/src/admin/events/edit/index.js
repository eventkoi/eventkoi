import { EventSetup } from "@/components/event-setup";
import { useParams } from "react-router-dom";

export function EventEdit() {
  const { id } = useParams();

  return <EventSetup id={id} />;
}
