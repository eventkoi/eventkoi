export function LogoIcon(props) {
  const fill = props.color ? props.color : "#CB3CA5";
  const width = props.width ? props.width : "21.138";
  const height = props.height ? props.height : "16.085";

  return (
    <svg
      xmlns="http://www.w3.org/2000/svg"
      width={width}
      height={height}
      viewBox="0 0 21.138 16.085"
    ></svg>
  );
}
