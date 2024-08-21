export function Wrapper(props) {
  return (
    <div className="flex flex-col flex-1 px-1">
      <main className="flex flex-1 flex-col gap-8 py-8 px-16 w-full max-w-[1280px] mx-auto">
        {props.children}
      </main>
    </div>
  );
}
