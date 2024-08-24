import { Button } from "@/components/ui/button";
import {
  DropdownMenu,
  DropdownMenuCheckboxItem,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuPortal,
  DropdownMenuSub,
  DropdownMenuSubContent,
  DropdownMenuSubTrigger,
  DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";

import { EllipsisVertical } from "lucide-react";

export function BulkActions(props) {
  const { table, base, fetchResults, addTo } = props;

  const runAction = async (action) => {
    let selectedRows = table.getFilteredSelectedRowModel().rows;

    let ids = [];

    selectedRows.forEach((item, index) => {
      ids.push(item.original.id);
    });

    let data = {
      ids: ids,
      action: action,
      base: base,
    };

    let method = "post";
    if (action === "delete") {
      method = "delete";
    }

    let endpoint = base;

    if (action === "duplicate") {
      endpoint = "duplicate";
    }

    if (base === "events") {
      data.trash = "yes";
    }

    let response = await wp
      .apiRequest({
        path: `eventkoi/v1/${endpoint}`,
        method: method,
        data: JSON.stringify(data),
      })
      .then((response) => {
        table.setRowSelection({});
        fetchResults(response.success);
      })
      .catch((error) => {});
  };

  return (
    <DropdownMenu>
      <DropdownMenuTrigger asChild>
        <Button variant="outline" className="ml-auto flex font-normal">
          <EllipsisVertical className="mr-2 h-4 w-4" />
          Bulk actions
        </Button>
      </DropdownMenuTrigger>
      <DropdownMenuContent align="start" className="w-[180px]">
        <DropdownMenuItem
          disabled={table.getFilteredSelectedRowModel().rows.length == 0}
          onClick={() => {
            runAction("duplicate");
          }}
        >
          <span>Duplicate</span>
        </DropdownMenuItem>
        <DropdownMenuItem
          disabled={table.getFilteredSelectedRowModel().rows.length == 0}
          onClick={() => {
            runAction("delete");
          }}
        >
          <span>
            {["categories"].includes(base) ? "Delete" : "Move to trash"}
          </span>
        </DropdownMenuItem>

        {addTo && table.getFilteredSelectedRowModel().rows.length > 0 && (
          <DropdownMenuSub>
            <DropdownMenuSubTrigger>
              <span>{addTo}</span>
            </DropdownMenuSubTrigger>
            <DropdownMenuPortal>
              <DropdownMenuSubContent>
                <DropdownMenuCheckboxItem>Panel</DropdownMenuCheckboxItem>
              </DropdownMenuSubContent>
            </DropdownMenuPortal>
          </DropdownMenuSub>
        )}

        {addTo && table.getFilteredSelectedRowModel().rows.length == 0 && (
          <DropdownMenuItem
            disabled={table.getFilteredSelectedRowModel().rows.length == 0}
          >
            <span>{addTo}</span>
          </DropdownMenuItem>
        )}
      </DropdownMenuContent>
    </DropdownMenu>
  );
}
