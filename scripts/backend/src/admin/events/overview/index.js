import { useEffect, useState } from "react";
import { useSearchParams } from "react-router-dom";

import apiRequest from "@wordpress/api-fetch";

import { AddButton } from "@/components/add-button";
import { DataTable } from "@/components/data-table";
import { Heading } from "@/components/heading";
import { SortButton } from "@/components/sort-button";
import { Checkbox } from "@/components/ui/checkbox";

import { toast } from "sonner";

import { cn } from "@/lib/utils";
import { Ban, CircleCheck, CircleDotDashed } from "lucide-react";

/**
 * Support multi-column search.
 */
const multiColumnSearch = (row, columnId, filterValue) => {
  const searchableRowContent = `${row.original.title}`;

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
    accessorKey: "title",
    header: ({ column }) => <SortButton title="Event name" column={column} />,
    cell: ({ row }) => {
      const url = "#/events/" + parseInt(row.original.id) + "/main";
      const status = row.original.status;
      return (
        <div className="grid space-y-1">
          <div className="text-foreground">
            <a
              href={url}
              className={cn(
                "hover:underline hover:decoration-dotted underline-offset-4",
                status === "trash" &&
                  "text-muted-foreground pointer-events-none"
              )}
            >
              {row.getValue("title")}
            </a>
          </div>
        </div>
      );
    },
    filterFn: multiColumnSearch,
  },
  {
    accessorKey: "status",
    header: ({ column }) => <SortButton title="Status" column={column} />,
    cell: ({ row }) => {
      const status = row.getValue("status");

      return (
        <div className="flex items-center space-x-2">
          {status == "completed" && (
            <CircleCheck className="w-4 h-4 text-success" />
          )}
          {status == "draft" && (
            <CircleDotDashed className="w-4 h-4 text-primary/60" />
          )}
          {status == "trash" && <Ban className="w-4 h-4 text-primary/40" />}
          <div className="capitalize text-foreground">{status}</div>
        </div>
      );
    },
    filterFn: multiColumnSearch,
  },
  {
    accessorKey: "date",
    header: ({ column }) => <SortButton title="Date" column={column} />,
    cell: ({ row }) => {
      const date = row.original.date;
      return <div className="text-foreground">{date.start}</div>;
    },
    filterFn: multiColumnSearch,
  },
  {
    accessorKey: "category",
    header: ({ column }) => (
      <div className="text-right">
        <SortButton title="Category" column={column} />
      </div>
    ),
    cell: ({ row }) => {
      return (
        <div className="text-right text-foreground">
          <a
            href="#"
            className="text-foreground hover:underline hover:decoration-dotted"
          >
            {row.getValue("category")}
          </a>
        </div>
      );
    },
    filterFn: multiColumnSearch,
  },
];

const statusFilters = [
  { key: "all", title: "All", hideCount: true, isSelected: true },
  { key: "live", title: "Live" },
  { key: "upcoming", title: "Upcoming" },
  { key: "draft", title: "Draft" },
  { key: "completed", title: "Completed" },
  { key: "trash", title: "Trash" },
];

export function EventsOverview() {
  const [isLoading, setIsLoading] = useState([]);
  const [data, setData] = useState([]);

  const [searchParams, setSearchParams] = useSearchParams();
  const queryStatus = searchParams.get("status");

  const fetchResults = async (toastMessage = null) => {
    await apiRequest({
      path: `${eventkoi_params.api}/events?status=${queryStatus}`,
      method: "get",
    })
      .then((response) => {
        setIsLoading(false);
        setData(response);

        if (toastMessage) {
          const toastId = toast(
            <div
              className="flex items-center cursor-pointer active:ring-2 active:ring-ring active:ring-offset-2 bg-[#222222] rounded-sm border-0 font-medium justify-between p-4 gap-4 text-sm leading-5 text-primary-foreground w-60"
              onClick={() => toast.dismiss(toastId)}
            >
              {toastMessage}
            </div>,
            { duration: 4000 }
          );
        }
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
        <Heading>Events</Heading>
        <AddButton title={"Create event"} url={"/events/add"} />
      </div>
      <DataTable
        data={data}
        columns={columns}
        empty={"No events are found."}
        base="events"
        statusFilters={statusFilters}
        filterName="Category"
        addTo={`Add to category`}
        isLoading={isLoading}
        fetchResults={fetchResults}
        queryStatus={queryStatus}
      />
    </div>
  );
}
