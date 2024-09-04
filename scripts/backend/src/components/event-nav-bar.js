import apiRequest from "@wordpress/api-fetch";

import { useState } from "react";
import { useNavigate } from "react-router-dom";

import { Button } from "@/components/ui/button";
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";

import { toast } from "sonner";

import { ChevronDown } from "lucide-react";

export function EventNavBar({ loading, setLoading, event, setEvent }) {
  const navigate = useNavigate();

  const [saving, setSaving] = useState(false);

  let disabled = (!event?.id && !event?.title) || saving;

  const trashEvent = async () => {
    setLoading(true);
    await apiRequest({
      path: `${eventkoi_params.api}/delete_event`,
      method: "post",
      data: {
        event_id: event?.id,
      },
    })
      .then((response) => {
        setLoading(false);
        navigate("/events");
        if (response.success) {
          const toastId = toast(
            <div
              className="flex items-center cursor-pointer active:ring-2 active:ring-ring active:ring-offset-2 bg-[#222222] rounded-sm border-0 font-medium justify-between p-4 gap-4 text-sm leading-5 text-primary-foreground w-60"
              onClick={() => toast.dismiss(toastId)}
            >
              {response.success}
            </div>,
            { duration: 4000 }
          );
        }
      })
      .catch(() => {
        setLoading(false);
      });
  };

  const saveEvent = async (status) => {
    setSaving(true);

    await apiRequest({
      path: `${eventkoi_params.api}/update_event`,
      method: "post",
      data: {
        event: event,
        status: status,
      },
    })
      .then((response) => {
        console.log(response);
        setSaving(false);
        setEvent(response);
        if (response.message) {
          const toastId = toast(
            <div
              className="flex items-center cursor-pointer active:ring-2 active:ring-ring active:ring-offset-2 bg-[#222222] rounded-sm border-0 font-medium justify-between p-4 gap-4 text-sm leading-5 text-primary-foreground w-60"
              onClick={() => toast.dismiss(toastId)}
            >
              {response.message}{" "}
              <div
                onClick={() => {
                  window.open(response.url, "_blank");
                }}
                className="underline underline-offset-2 hover:no-underline"
              >
                View event
              </div>
            </div>,
            { duration: 4000 }
          );
        }
        if (response.update_endpoint) {
          window.location.hash = window.location.hash.replace(
            "add",
            response.id
          );
        }
      })
      .catch((error) => {
        setSaving(false);
      });
  };

  return (
    <div className="flex gap-2">
      <Button
        variant="ghost"
        disabled={disabled}
        onClick={() => {
          saveEvent("draft");
        }}
      >
        Save draft
      </Button>
      <Button
        variant="link"
        disabled={disabled}
        onClick={() => window.open(event?.url, "_blank")}
      >
        Preview
      </Button>
      <div className="flex items-center gap-[1px]">
        <Button
          variant="default"
          className="rounded-r-none"
          disabled={disabled}
          onClick={() => {
            saveEvent("publish");
          }}
        >
          Publish
        </Button>
        <DropdownMenu modal={false}>
          <DropdownMenuTrigger asChild>
            <Button
              variant="default"
              size="icon"
              className="rounded-l-none"
              disabled={disabled}
            >
              <ChevronDown className="w-4 h-4" />
            </Button>
          </DropdownMenuTrigger>
          <DropdownMenuContent className="w-56 z-[510]" align="end">
            <DropdownMenuItem>Schedule publish</DropdownMenuItem>
            <DropdownMenuItem disabled>Create duplicate event</DropdownMenuItem>
            <DropdownMenuSeparator />
            <DropdownMenuItem
              className="text-destructive focus:text-destructive"
              onClick={() => {
                trashEvent();
              }}
            >
              Move to trash
            </DropdownMenuItem>
          </DropdownMenuContent>
        </DropdownMenu>
      </div>
    </div>
  );
}
