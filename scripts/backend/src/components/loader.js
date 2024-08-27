import { Spinner } from "@/components/ui/spinner";

export function Loader({ size }) {
  return (
    <div className="flex w-full min-h-72 flex-1 items-center justify-center">
      <Spinner size={size} />
    </div>
  );
}
