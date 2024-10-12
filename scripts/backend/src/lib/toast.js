import { toast } from "sonner";

export function showToast(response) {
  if (!response.message) {
    return;
  }

  const toastId = toast(
    <div
      className="flex items-center cursor-pointer active:ring-2 active:ring-ring active:ring-offset-2 bg-[#222222] rounded-sm border-0 font-medium justify-between p-4 gap-4 text-sm leading-5 text-primary-foreground w-60"
      onClick={() => toast.dismiss(toastId)}
    >
      {response.message}{" "}
      {response.url && (
        <div
          onClick={() => {
            window.open(response.url, "_blank");
          }}
          className="underline underline-offset-2 hover:no-underline"
        >
          View
        </div>
      )}
    </div>,
    { duration: 4000 }
  );
}

export function showStaticToast(message) {
  if (!message) {
    return;
  }

  const toastId = toast(
    <div
      className="flex items-center cursor-pointer active:ring-2 active:ring-ring active:ring-offset-2 bg-[#222222] rounded-sm border-0 font-medium justify-between p-4 gap-4 text-sm leading-5 text-primary-foreground w-72"
      onClick={() => toast.dismiss(toastId)}
    >
      {message}
    </div>,
    { duration: 4000 }
  );
}

export function showToastError(error) {
  if (!error) {
    return;
  }

  const toastId = toast(
    <div
      className="flex items-center cursor-pointer active:ring-2 active:ring-ring active:ring-offset-2 bg-destructive/10 rounded-sm border-0 font-medium justify-between p-4 gap-4 text-sm leading-5 text-destructive w-60"
      onClick={() => toast.dismiss(toastId)}
    >
      {error}
    </div>,
    { duration: 4000 }
  );
}
