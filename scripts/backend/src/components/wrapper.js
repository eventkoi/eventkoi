export function Wrapper({ children }) {
  return (
    <main className="flex flex-1 flex-col w-full max-w-[1280px] mx-auto gap-8 py-8 px-8">
      {children}
    </main>
  );
}
