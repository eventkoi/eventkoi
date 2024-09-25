export function ShareLink({ icon, title }) {
  return (
    <a
      href="#"
      className="flex flex-col gap-1 items-center justify-center no-underline text-sm text-foreground/90 hover:text-foreground group"
    >
      <span className="rounded-full bg-accent group-hover:bg-input flex items-center justify-center w-16 h-16">
        {icon}
      </span>
      <span>{title}</span>
    </a>
  );
}
