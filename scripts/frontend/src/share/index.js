import { Button } from "@/components/ui/button";
import { createRoot } from "react-dom/client";

import {
  EmailIcon,
  FacebookIcon,
  InstagramIcon,
  LinkedinIcon,
  ShareIcon,
  WhatsappIcon,
  XIcon,
} from "@/icons";

import { Files } from "lucide-react";

import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from "@/components/ui/dialog";

import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";

import { ShareLink } from "@/components/share-link";

export function ShareButton() {
  const { event } = eventkoi_params;

  return (
    <div>
      <Dialog>
        <DialogTrigger asChild>
          <Button
            variant="outline"
            className="bg-transparent hover:bg-transparent border border-foreground border-solid cursor-pointer rounded-[20px] h-[56px] min-w-[138px] text-[1rem] font-medium"
          >
            Share
            <ShareIcon />
          </Button>
        </DialogTrigger>
        <DialogContent className="sm:max-w-[685px] p-0">
          <DialogHeader className="flex items-center justify-center p-4 border-0 border-solid border-b-2 border-input">
            <DialogTitle className="font-sans	text-xl m-0 text-foreground">
              Share this event
            </DialogTitle>
          </DialogHeader>
          <div className="flex flex-col pt-[30px] pb-[60px] px-[60px]">
            <div className="flex gap-4 items-center justify-between pb-[60px]">
              <ShareLink title="Whatsapp" icon={<WhatsappIcon />} />
              <ShareLink title="Instagram" icon={<InstagramIcon />} />
              <ShareLink title="Email" icon={<EmailIcon />} />
              <ShareLink title="Facebook" icon={<FacebookIcon />} />
              <ShareLink title="X" icon={<XIcon />} />
              <ShareLink title="Linkedin" icon={<LinkedinIcon />} />
            </div>
            <div className="flex flex-col gap-3 pb-[10px]">
              <Label className="text-base">Event link</Label>
              <div className="relative">
                <Input
                  id="link"
                  defaultValue={event?.url}
                  readOnly
                  className="min-h-[66px] border border-input border-solid border-primary/30 box-border text-lg text-foreground"
                />
                <Button
                  variant="secondary"
                  type="submit"
                  className="absolute h-12 right-[9px] top-[9px] border-none cursor-pointer"
                >
                  <Files className="mr-2 h-5 w-5" />
                  Copy
                </Button>
              </div>
            </div>
          </div>
        </DialogContent>
      </Dialog>
    </div>
  );
}

var rootElement = document.getElementsByClassName("eventkoi-share")[0];

if (rootElement) {
  const root = createRoot(rootElement);
  root.render(<ShareButton />);
}
