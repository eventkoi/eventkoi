import apiRequest from "@wordpress/api-fetch";

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

export function BulkActions({ table, base, fetchResults, addTo }) {
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

    if (base === "events") {
      data.trash = "yes";
    }

    await apiRequest({
      path: `${eventkoi_params.api}/${action}_${base}`,
      method: "post",
      data: data,
    })
      .then((response) => {
        table.setRowSelection({});
        fetchResults(response.success);
      })
      .catch((error) => {
        console.log(error);
      });
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
