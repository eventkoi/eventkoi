import { MediaUpload } from "@wordpress/media-utils";

import { Button } from "@/components/ui/button";
import { Label } from "@/components/ui/label";

import { Image, Repeat2, Trash2 } from "lucide-react";

import { cn } from "@/lib/utils";

const ALLOWED_MEDIA_TYPES = ["image"];

export function EventImage({ event, setEvent }) {
  const deleteImage = () => {
    setEvent((prevState) => ({
      ...prevState,
      image: "",
      media: [],
    }));
  };

  return (
    <div className="flex flex-col gap-2">
      <Label htmlFor="image">Header banner image</Label>
      <div className="text-muted-foreground">Ideal size: 1800px x 900px</div>
      <MediaUpload
        title={"Select event image"}
        onSelect={(media) => {
          setEvent((prevState) => ({
            ...prevState,
            image: media.url,
            media: media,
          }));
        }}
        allowedTypes={ALLOWED_MEDIA_TYPES}
        value={event?.media?.id}
        render={({ open }) => (
          <div
            className={cn(event.image && "relative p-0 cursor-pointer group")}
          >
            {event?.image && (
              <div
                className="absolute top-0 left-0 flex opacity-0 group-hover:opacity-100 transition-opacity duration-300 ease-out w-full h-full bg-white/70 rounded-lg items-center justify-center gap-4 border border-dashed border-muted-foreground/80"
                onClick={(e) => e.preventDefault}
              >
                <Button variant="default" onClick={open}>
                  <Repeat2 className="mr-2 h-4 w-4" />
                  Replace
                </Button>
                <Button variant="default" onClick={(e) => deleteImage()}>
                  <Trash2 className="mr-2 h-4 w-4" />
                  Delete
                </Button>
              </div>
            )}
            {event?.image && (
              <img
                src={event?.image}
                alt=""
                className="rounded-lg w-full h-auto"
              />
            )}
            {!event.image && (
              <div
                className="flex items-center justify-center flex-col gap-1 p-10 cursor-pointer border border-dashed border-muted-foreground/40 bg-secondary rounded-lg cursor-default"
                onClick={open}
              >
                <Image className="w-6 h-6" strokeWidth={1} />
                <div className="pt-1 text-lg font-medium">
                  Drag and drop your image here.
                </div>
                <div className="text-sm">
                  Or click to select from media gallery.
                </div>
              </div>
            )}
          </div>
        )}
      />
    </div>
  );
}
