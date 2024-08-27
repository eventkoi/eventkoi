import { Button } from "@/components/ui/button";
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";

import { ChevronDown } from "lucide-react";

export function EventNavBar() {
  return (
    <div className="flex gap-2">
      <Button variant="ghost">Save draft</Button>
      <Button variant="link" asChild>
        <a href="#">Preview</a>
      </Button>
      <div className="flex items-center gap-[1px]">
        <Button variant="default" className="rounded-r-none">
          Publish
        </Button>
        <DropdownMenu>
          <DropdownMenuTrigger asChild>
            <Button variant="default" size="icon" className="rounded-l-none">
              <ChevronDown className="w-4 h-4" />
            </Button>
          </DropdownMenuTrigger>
          <DropdownMenuContent className="w-56" align="end">
            <DropdownMenuItem>Schedule publish</DropdownMenuItem>
            <DropdownMenuItem disabled>Create duplicate event</DropdownMenuItem>
            <DropdownMenuSeparator />
            <DropdownMenuItem>Move to trash</DropdownMenuItem>
          </DropdownMenuContent>
        </DropdownMenu>
      </div>
    </div>
  );
}
