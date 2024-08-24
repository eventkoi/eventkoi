import { cn } from "@/lib/utils";
import { useState } from "react";

import {
  flexRender,
  getCoreRowModel,
  getFilteredRowModel,
  getPaginationRowModel,
  getSortedRowModel,
  useReactTable,
} from "@tanstack/react-table";

import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table";

import { BulkActions } from "@/components/bulk-actions";
import { Filters } from "@/components/filters";
import { Pagination } from "@/components/pagination";
import { RowsPerPage } from "@/components/rows-per-page";
import { StatusFilters } from "@/components/status-filters";
import { TablePage } from "@/components/table-page";
import { TableSelectedRows } from "@/components/table-selected-rows";

export function DataTable({
  data,
  columns,
  empty,
  hideStatusFilters,
  hideDateRange,
  hideCategories,
  hideBottomBar,
  hideBottomSelected,
  gap,
  base,
  compact,
  isLoading,
  fetchResults,
  activeId,
  statusFilters,
  filterName,
  addTo,
}) {
  const [sorting, setSorting] = useState([]);
  const [columnFilters, setColumnFilters] = useState([]);
  const [columnVisibility, setColumnVisibility] = useState({});
  const [rowSelection, setRowSelection] = React.useState({});

  const table = useReactTable({
    data,
    columns,
    onSortingChange: setSorting,
    onColumnFiltersChange: setColumnFilters,
    getCoreRowModel: getCoreRowModel(),
    getPaginationRowModel: getPaginationRowModel(),
    getSortedRowModel: getSortedRowModel(),
    getFilteredRowModel: getFilteredRowModel(),
    onColumnVisibilityChange: setColumnVisibility,
    onRowSelectionChange: setRowSelection,
    state: {
      sorting,
      columnFilters,
      columnVisibility,
      rowSelection,
    },
  });

  return (
    <div className={cn("w-full grid self-start", gap ? `gap-${gap}` : "gap-6")}>
      <div className="flex items-center justify-between">
        <div className="flex items-center">
          <BulkActions
            table={table}
            base={base}
            fetchResults={fetchResults}
            addTo={addTo}
          />
        </div>
        <div className="flex items-center space-x-4">
          <Filters
            table={table}
            hideDateRange={hideDateRange}
            hideCategories={hideCategories}
            filterName={filterName}
          />
        </div>
      </div>

      <div className="flex items-center justify-between">
        {!hideStatusFilters && (
          <div className="flex items-center text-sm space-x-6">
            <StatusFilters statusFilters={statusFilters} base={base} />
          </div>
        )}
        <div className="text-sm text-muted-foreground">
          <TableSelectedRows table={table} compact={compact} />
        </div>
      </div>

      <div className="rounded-lg border bg-card text-sm text-card-foreground shadow-sm w-full overflow-x-auto">
        <Table>
          <TableHeader>
            {table.getHeaderGroups().map((headerGroup) => (
              <TableRow key={headerGroup.id}>
                {headerGroup.headers.map((header) => {
                  return (
                    <TableHead key={header.id} className="h-10">
                      {header.isPlaceholder
                        ? null
                        : flexRender(
                            header.column.columnDef.header,
                            header.getContext()
                          )}
                    </TableHead>
                  );
                })}
              </TableRow>
            ))}
          </TableHeader>
          <TableBody>
            {table.getRowModel().rows?.length ? (
              table.getRowModel().rows.map((row) => (
                <TableRow
                  key={row.id}
                  data-state={row.getIsSelected() && "selected"}
                >
                  {row.getVisibleCells().map((cell) => (
                    <TableCell
                      key={cell.id}
                      className={cn(
                        activeId == row.original.id &&
                          cell.id.indexOf("name") >= 1 &&
                          "font-medium underline decoration-dotted"
                      )}
                    >
                      {flexRender(
                        cell.column.columnDef.cell,
                        cell.getContext()
                      )}
                    </TableCell>
                  ))}
                </TableRow>
              ))
            ) : (
              <TableRow>
                <TableCell
                  colSpan={columns.length}
                  className="h-40 text-center text-muted-foreground text-sm"
                >
                  {empty
                    ? isLoading
                      ? "Loading data…"
                      : empty
                    : "No data was found."}
                </TableCell>
              </TableRow>
            )}
          </TableBody>
        </Table>
      </div>

      {!hideBottomBar && table.getRowModel().rows?.length > 0 && (
        <div className="flex items-center justify-between">
          <div className="text-sm text-muted-foreground">
            {!hideBottomSelected && (
              <TableSelectedRows table={table} compact={compact} />
            )}
          </div>
          <div className="flex items-center space-x-12 text-foreground">
            <RowsPerPage table={table} />
            <div className="flex items-center space-x-4">
              <TablePage table={table} />
              <Pagination table={table} />
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
