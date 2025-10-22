import React, { useState } from "react";
import { loginUser } from "../api/auth";
import { useNavigate } from "react-router-dom";

function Login() {
    const [email, setEmail] = useState("");
    const [password, setPassword] = useState("");
    const [message, setMessage] = useState("");
    const navigate = useNavigate();

    const inputStyle = {
        padding: "8px",
        margin: "6px 0",
        width: "100%",
        boxSizing: "border-box",
    };

    const buttonStyle = {
        padding: "8px",
        width: "100%",
        backgroundColor: "#007BFF",
        color: "white",
        border: "none",
        cursor: "pointer",
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        const res = await loginUser({ email, password });
        if (res.token) {
            localStorage.setItem("token", res.token);
            localStorage.setItem("user", JSON.stringify(res.user));
            navigate("/");
            setMessage("Login successful!");
        } else {
            setMessage("Login failed.");
        }
    };

    return (
        <div style={{ maxWidth: "300px", margin: "auto" }}>
            <h2>Please Login first </h2>
            <form onSubmit={handleSubmit}>
                <input
                    style={inputStyle}
                    name="email"
                    placeholder="Email"
                    value={email}
                    onChange={(e) => setEmail(e.target.value)}
                    required
                />
                <input
                    style={inputStyle}
                    name="password"
                    type="password"
                    placeholder="Password"
                    value={password}
                    onChange={(e) => setPassword(e.target.value)}
                    required
                />
                <button style={buttonStyle} type="submit">
                    Login
                </button>
            </form>
            <p>{message}</p>
        </div>
    );
}

export default Login;
