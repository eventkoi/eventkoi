import { cn } from "@/lib/utils";
import { Link, useSearchParams } from "react-router-dom";

export function StatusFilters(props) {
  const { statusFilters, base } = props;

  const [searchParams, setSearchParams] = useSearchParams();
  const queryStatus = searchParams.get("status");

  return (
    <>
      {statusFilters?.map(function (status, i) {
        let selected =
          (!queryStatus && status.key === "all") || queryStatus === status.key;
        return (
          <Link
            key={`status-${i}`}
            to={`/${base}?status=${status.key}`}
            className={cn(
              "flex items-center hover:underline hover:decoration-dotted text-foreground",
              selected && "underline decoration-dotted font-medium"
            )}
          >
            {status.title}
            {!status.hideCount && <> (0)</>}
          </Link>
        );
      })}
    </>
  );
}
