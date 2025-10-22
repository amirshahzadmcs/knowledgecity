import React from "react";
import { Link } from "react-router-dom";

function Home() {
    const user = JSON.parse(localStorage.getItem("user"));

    return (
        <div style={{ padding: "20px" }}>

            {user ? (
                <div>
                    <h1>Welcome to KnowledgeCity</h1>
                    <p>Hello, {user.name}</p>
                    <p>Email {user.email}</p>
                    <p>Region {user.region}</p>
                    <p>Role {user.role}</p>
                </div>
            ) : (
                <div style={{ padding: "20px" }}>
                    <h1>Welcome to KnowledgeCity</h1>
                    <p>Your global educational platform.</p>
                    <p>Use the navigation above to Login or Register.</p>
                </div>
            )}
        </div>
    );
}

export default Home;
