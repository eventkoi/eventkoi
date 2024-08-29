import { useEffect, useState } from "react";

import apiRequest from "@wordpress/api-fetch";

import { AddButton } from "@/components/add-button";
import { DataTable } from "@/components/data-table";
import { Heading } from "@/components/heading";
import { SortButton } from "@/components/sort-button";
import { Checkbox } from "@/components/ui/checkbox";

import { toast } from "sonner";

import { CircleCheck, CircleDotDashed } from "lucide-react";

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
      const url = "#/events/" + parseInt(row.original.id);
      return (
        <div className="grid space-y-1">
          <div className="text-foreground">
            <a
              href={url}
              className="hover:underline hover:decoration-dotted underline-offset-4"
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
      return <div className="text-foreground">{row.getValue("date")}</div>;
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
  const [isLoading, setIsLoading] = useState(true);
  const [data, setData] = useState([]);

  const fetchResults = async (toastMessage = null) => {
    await apiRequest({
      path: `${eventkoi_params.api}/events`,
      method: "get",
    })
      .then((response) => {
        setData(response);
        setIsLoading(false);
        if (toastMessage) {
          toast.success(toastMessage);
        }
      })
      .catch((error) => {
        console.log(error);
        setIsLoading(false);
      });
  };

  useEffect(() => {
    fetchResults();
  }, []);

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
      />
    </div>
  );
}
