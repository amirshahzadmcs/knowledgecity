import React, { useState } from "react";
import { registerUser } from "../api/auth";
import { useNavigate } from "react-router-dom";

function Register() {
    const [formData, setFormData] = useState({
        name: "",
        email: "",
        password: "",
        role: "",
        region: "",
    });
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
        backgroundColor: "#28a745",
        color: "white",
        border: "none",
        cursor: "pointer",
    };

    const handleChange = (e) => {
        setFormData({ ...formData, [e.target.name]: e.target.value });
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        const res = await registerUser(formData);
        setMessage(res?.message || "Registration complete.");
        navigate("/login");
    };
    return (
        <div style={{ maxWidth: "300px", margin: "auto" }}>
            <h2>Register</h2>
            <form onSubmit={handleSubmit}>
                <input style={inputStyle} name="name" placeholder="Name" onChange={handleChange} required />
                <input style={inputStyle} name="email" placeholder="Email" value={formData.email} onChange={handleChange} required />
                <input style={inputStyle} name="password" placeholder="Password" type="password" value={formData.password} onChange={handleChange} required />
                <select style={inputStyle} name="role" value={formData.role} onChange={handleChange} required>
                    <option value="">Select Role</option>
                    <option value="student">Student</option>
                    <option value="instructor">Instructor</option>
                </select>
                <select style={inputStyle} name="region" value={formData.region} onChange={handleChange} required>
                    <option value="">Select Region</option>
                    <option value="UAE">UAE</option>
                    <option value="KSA">KSA</option>
                </select>

                <button style={buttonStyle} type="submit">Register</button>
            </form>
            <p>{message}</p>
        </div>
    );
}

export default Register;
