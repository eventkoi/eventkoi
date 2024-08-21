import { useEffect } from "react";
import { useNavigate } from "react-router-dom";

export function NoMatch(props) {
  const navigate = useNavigate();

  useEffect(() => {
    navigate("/dashboard");
  });

  return null;
}
