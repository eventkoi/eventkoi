import { useEffect, useState } from "react";
import { useSearchParams } from "react-router-dom";

import apiRequest from "@wordpress/api-fetch";

import { Badge } from "@/components/ui/badge";
import { Checkbox } from "@/components/ui/checkbox";

import { AddButton } from "@/components/add-button";
import { DataTable } from "@/components/data-table";
import { Heading } from "@/components/heading";
import { SortButton } from "@/components/sort-button";

import { showStaticToast } from "@/lib/toast";
import { cn } from "@/lib/utils";

import {
  Ban,
  CircleAlert,
  CircleCheck,
  CircleDotDashed,
  Clock3,
  Link2,
} from "lucide-react";

const statuses = {
  live: "Live",
  completed: "Completed",
  tbc: "Unconfirmed",
  upcoming: "Upcoming",
  publish: "Upcoming",
  draft: "Draft",
  trash: "Trash",
};

/**
 * Support multi-column search.
 */
const multiColumnSearch = (row, columnId, filterValue) => {
  const searchableRowContent = `${row.original.title} ${row.original.status}`;

  return searchableRowContent.toLowerCase().includes(filterValue.toLowerCase());
};

/**
 * Sort by status.
 */
const sortStatusFn = (rowA, rowB, _columnId) => {
  const statusA = rowA.original.status;
  const statusB = rowB.original.status;
  const statusOrder = [
    "live",
    "upcoming",
    "publish",
    "tbc",
    "draft",
    "completed",
    "trash",
  ];
  return statusOrder.indexOf(statusA) - statusOrder.indexOf(statusB);
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
      const wp_status = row.original.wp_status;
      return (
        <div className="grid space-y-1">
          <div className="flex gap-2 items-center text-foreground">
            <a
              href={url}
              className={cn(
                "hover:underline hover:decoration-dotted underline-offset-4"
              )}
            >
              {row.getValue("title")}
            </a>
            {wp_status === "draft" && <Badge variant="outline">Draft</Badge>}
            {wp_status === "trash" && <Badge variant="outline">Trash</Badge>}
            <a
              href={row.original.url}
              className="hidden group-hover:flex w-5 h-5 items-center justify-center"
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
          {status == "tbc" && (
            <CircleDotDashed className="w-4 h-4 text-primary/60" />
          )}
          {status == "upcoming" && (
            <Clock3 className="w-4 h-4 text-[#48BEFA]" />
          )}
          {status == "live" && (
            <CircleAlert className="w-4 h-4 text-destructive" />
          )}
          {status == "publish" && <Clock3 className="w-4 h-4 text-[#48BEFA]" />}
          {status == "trash" && <Ban className="w-4 h-4 text-primary/40" />}
          <div className="capitalize text-foreground">{statuses[status]}</div>
        </div>
      );
    },
    filterFn: multiColumnSearch,
    sortingFn: sortStatusFn,
  },
  {
    accessorKey: "start_date",
    header: ({ column }) => <SortButton title="Starts" column={column} />,
    cell: ({ row }) => {
      return (
        <div
          className={cn(
            "text-foreground whitespace-pre-line",
            row.original.tbc && "text-muted-foreground/80"
          )}
        >
          {row.getValue("start_date").replace(/ /, "\n")}
        </div>
      );
    },
    filterFn: multiColumnSearch,
    sortingFn: "alphanumeric",
    sortUndefined: "last",
    invertSorting: true,
  },
  {
    accessorKey: "end_date",
    header: ({ column }) => <SortButton title="Ends" column={column} />,
    cell: ({ row }) => {
      return (
        <div
          className={cn(
            "text-foreground whitespace-pre-line",
            row.original.tbc && "text-muted-foreground/80"
          )}
        >
          {row.getValue("end_date").replace(/ /, "\n")}
        </div>
      );
    },
    filterFn: multiColumnSearch,
    sortingFn: "alphanumeric",
    sortUndefined: "last",
    invertSorting: true,
  },
  {
    accessorKey: "calendar",
    header: ({ column }) => <>Calendar</>,
    cell: ({ row }) => {
      const calendar = row.original.calendar;
      return (
        <div className="text-foreground">
          {calendar &&
            calendar.map((item, i) => {
              return (
                <div key={`calendar-${i}`}>
                  {item.name}
                  {i > 0 && ", "}
                </div>
              );
            })}
        </div>
      );
    },
    filterFn: multiColumnSearch,
  },
  {
    accessorKey: "modified_date",
    header: ({ column }) => (
      <SortButton title="Last modified" column={column} />
    ),
    cell: ({ row }) => {
      const event = row.original;
      return (
        <div className="text-foreground whitespace-pre-line">
          {row.getValue("modified_date").replace(/ /, "\n")}
        </div>
      );
    },
    filterFn: multiColumnSearch,
    sortingFn: "alphanumeric",
    sortUndefined: "last",
    invertSorting: true,
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
        <Heading>Events</Heading>
        <AddButton title={"Create event"} url={"/events/add"} />
      </div>
      <DataTable
        data={data}
        columns={columns}
        empty={"No events are found."}
        base="events"
        statusFilters={statusFilters}
        isLoading={isLoading}
        fetchResults={fetchResults}
        queryStatus={queryStatus}
        hideCategories
        defaultSort={[
          {
            id: "status",
            desc: false,
          },
        ]}
      />
    </div>
  );
}
