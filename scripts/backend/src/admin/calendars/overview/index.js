import { useEffect, useState } from "react";
import { useSearchParams } from "react-router-dom";

import apiRequest from "@wordpress/api-fetch";

import { Checkbox } from "@/components/ui/checkbox";

import { AddButton } from "@/components/add-button";
import { DataTable } from "@/components/data-table";
import { Heading } from "@/components/heading";
import { SortButton } from "@/components/sort-button";

import { showStaticToast } from "@/lib/toast";
import { cn } from "@/lib/utils";

import { Link2 } from "lucide-react";

/**
 * Support multi-column search.
 */
const multiColumnSearch = (row, columnId, filterValue) => {
  const searchableRowContent = `${row.original.name} ${row.original.slug}`;

  return searchableRowContent.toLowerCase().includes(filterValue.toLowerCase());
};

const columns = [
  {
    id: "select",
    header: ({ table }) => (
      <div className="flex items-center justify-center min-h-6">
        <Checkbox
          checked={
            table.getIsAllPageRowsSelected() ||
            (table.getIsSomePageRowsSelected() && "indeterminate")
          }
          onCheckedChange={(value) => table.toggleAllPageRowsSelected(!!value)}
          aria-label="Select all"
        />
      </div>
    ),
    cell: ({ row }) => (
      <div className="flex items-center justify-center min-h-6">
        <Checkbox
          checked={row.getIsSelected()}
          onCheckedChange={(value) => row.toggleSelected(!!value)}
          aria-label="Select row"
        />
      </div>
    ),
    enableSorting: false,
    enableHiding: false,
  },
  {
    accessorKey: "name",
    header: ({ column }) => (
      <SortButton title="Calendar name" column={column} />
    ),
    cell: ({ row }) => {
      const url = "#/calendars/" + parseInt(row.original.id) + "/main";
      return (
        <div className="grid space-y-1">
          <div className="flex gap-2 items-center text-foreground">
            <a
              href={url}
              className={cn(
                "hover:underline hover:decoration-dotted underline-offset-4"
              )}
            >
              {row.getValue("name")}
            </a>
            <a
              href={row.original.url}
              className="flex w-5 h-5 items-center justify-center"
            >
              <Link2 className="w-full h-full" />
            </a>
          </div>
        </div>
      );
    },
    filterFn: multiColumnSearch,
    sortingFn: "alphanumeric",
  },
  {
    accessorKey: "slug",
    header: ({ column }) => <SortButton title="Slug" column={column} />,
    cell: ({ row }) => {
      return (
        <div className={cn("text-foreground")}>{row.getValue("slug")}</div>
      );
    },
    filterFn: multiColumnSearch,
    sortingFn: "alphanumeric",
  },
  {
    accessorKey: "count",
    header: ({ column }) => <SortButton title="Events count" column={column} />,
    cell: ({ row }) => {
      const event = row.original;
      return (
        <div className="text-foreground text-right">
          {row.getValue("count")}
        </div>
      );
    },
    filterFn: multiColumnSearch,
    sortingFn: "alphanumeric",
  },
];

export function CalendarsOverview() {
  const [isLoading, setIsLoading] = useState([]);
  const [data, setData] = useState([]);

  const [searchParams, setSearchParams] = useSearchParams();

  const fetchResults = async (toastMessage = null) => {
    await apiRequest({
      path: `${eventkoi_params.api}/calendars`,
      method: "get",
    })
      .then((response) => {
        console.log(response);
        setIsLoading(false);
        setData(response);
        showStaticToast(toastMessage);
      })
      .catch((error) => {
        setIsLoading(false);
      });
  };

  useEffect(() => {
    setData([]);
    setIsLoading(true);
    fetchResults();
  }, [searchParams]);

  return (
    <div className="flex flex-col gap-8">
      <div className="mx-auto flex w-full gap-2 justify-between">
        <Heading>Calendars</Heading>
        <AddButton title={"Create calendar"} url={"/calendars/add"} />
      </div>
      <DataTable
        data={data}
        columns={columns}
        empty={"No calendars are found."}
        base="calendars"
        hideStatusFilters
        isLoading={isLoading}
        fetchResults={fetchResults}
        hideCategories
        hideDateRange
      />
    </div>
  );
}
