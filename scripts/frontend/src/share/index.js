import { Button } from "@/components/ui/button";
import { Path, SVG } from "@wordpress/primitives";
import { createRoot } from "react-dom/client";

export function ShareButton() {
  return (
    <div>
      <Button
        variant="outline"
        className="bg-transparent hover:bg-transparent border border-foreground border-solid cursor-pointer rounded-[20px] h-[56px] min-w-[138px] text-[1rem] font-medium"
      >
        Share
        <SVG
          xmlns="http://www.w3.org/2000/svg"
          width="16.739"
          height="18.004"
          viewBox="0 0 16.739 18.004"
          className="ml-3"
        >
          <Path
            d="M6.541,9.518a1.889,1.889,0,1,0,0,1.835m0-1.835a1.891,1.891,0,0,1,0,1.835m0-1.835,8.031-4.461m-8.031,6.3,8.031,4.461m0,0a1.89,1.89,0,1,0,2.57-.734,1.89,1.89,0,0,0-2.57.734Zm0-10.758a1.889,1.889,0,1,0,.734-2.569,1.889,1.889,0,0,0-.734,2.569Z"
            transform="translate(-2.25 -1.493)"
            fill="none"
            stroke="#6f6f6f"
            strokeLinecap="round"
            strokeLinejoin="round"
            strokeWidth="1.5"
          />
        </SVG>
      </Button>
    </div>
  );
}

var rootElement = document.getElementsByClassName("eventkoi-share")[0];

if (rootElement) {
  const root = createRoot(rootElement);
  root.render(<ShareButton />);
}
