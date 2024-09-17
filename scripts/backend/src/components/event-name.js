import { useRef } from "react";

import { PencilLine } from "lucide-react";

import { Panel } from "@/components/panel";

export function EventName({ event, setEvent }) {
  const focusRef = useRef(null);

  const updateEventName = (e) => {
    var value = e.currentTarget.textContent;
    setEvent((prevState) => ({
      ...prevState,
      title: value,
    }));

    if (value) {
      e.currentTarget.classList.remove("eventkoi-error");
    }
  };

  const setEndOfContenteditable = (contentEditableElement) => {
    var range, selection;
    if (document.createRange) {
      //Firefox, Chrome, Opera, Safari, IE 9+
      range = document.createRange(); //Create a range (a range is a like the selection but invisible)
      range.selectNodeContents(contentEditableElement); //Select the entire contents of the element with the range
      range.collapse(false); //collapse the range to the end point. false means collapse to end rather than the start
      selection = window.getSelection(); //get the selection object (allows you to change selection)
      selection.removeAllRanges(); //remove any selections already made
      selection.addRange(range); //make the range you have just created the visible selection
    } else if (document.selection) {
      //IE 8 and lower
      range = document.body.createTextRange(); //Create a range (a range is a like the selection but invisible)
      range.moveToElementText(contentEditableElement); //Select the entire contents of the element with the range
      range.collapse(false); //collapse the range to the end point. false means collapse to end rather than the start
      range.select(); //Select the range (make it the visible selection
    }
  };

  return (
    <Panel className="flex-row items-center">
      <div
        ref={focusRef}
        id="event-name"
        className="inline-flex rounded-md items-center px-2 py-1 cursor-pointer font-medium text-lg border border-2	border-transparent hover:border-input focus:border-input active:border-input"
        contentEditable
        spellCheck={false}
        placeholder="Click to add event name"
        dangerouslySetInnerHTML={{
          __html: event?.title,
        }}
        onBlur={(e) => updateEventName(e)}
        onKeyDown={(e) => e.key === "Enter" && updateEventName(e)}
      />
      <div
        onClick={() => {
          var elem = document.getElementById("event-name");
          setEndOfContenteditable(elem);
          focusRef.current.focus();
        }}
        className="cursor-pointer"
      >
        <PencilLine className="w-3.5 h-3.5 text-ring" />
      </div>
    </Panel>
  );
}
