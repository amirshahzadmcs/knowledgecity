// src/components/NavBar.js
import React from "react";
import { Link, useNavigate } from "react-router-dom";
import apiController from "../api/apiController";

function NavBar() {
    const navigate = useNavigate();
    const user = JSON.parse(localStorage.getItem("user"));

    const handleLogout = async (e) => {
        e.preventDefault();
        try {
            await apiController.post("/logout");
        } catch (err) {
            console.log("Logout API error", err);
        }

        localStorage.removeItem("token");
        localStorage.removeItem("user");
        navigate("/login");
    };

    return (
        <nav style={{ padding: "10px", borderBottom: "1px solid #ccc" }}>
            <Link to="/" style={{ marginRight: "10px" }}>Home</Link>

            {user ? (
                <>
                    <span style={{ marginRight: "10px" }}>Hello, {user.name}</span>
                    <Link to="/courses" style={{ marginRight: "10px" }}>Courses</Link>
                    <Link to="/login" onClick={handleLogout} style={{ marginLeft: "10px" }}>Logout</Link>
                </>
            ) : (
                <>
                    <Link to="/login" style={{ marginRight: "10px" }}>Login</Link>
                    <Link to="/register">Register</Link>
                </>
            )}
        </nav>
    );
}

export default NavBar;
