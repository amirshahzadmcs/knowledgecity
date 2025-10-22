import React, { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import apiController from "../api/apiController";

function Courses() {
    const [courses, setCourses] = useState([]);
    const [loading, setLoading] = useState(true);
    const [user, setUser] = useState(null);
    const [enrollments, setEnrollments] = useState([]);
    const [payments, setPayments] = useState([]);
    const [processing, setProcessing] = useState({}); // track individual button loading

    useEffect(() => {
        const fetchData = async () => {
            try {
                // Fetch all courses
                const courseRes = await apiController.get("/courses");
                setCourses(courseRes.data);

                // Fetch logged-in user
                const userRes = await apiController.get("/me");
                setUser(userRes.data);

                if (userRes.data.role === "student") {
                    // Fetch enrollments
                    const enrollRes = await apiController.get("/enrollments");
                    setEnrollments(enrollRes.data);

                    // Fetch payments
                    const payRes = await apiController.get("/payments");
                    setPayments(payRes.data);
                }
            } catch (err) {
                console.log("Error fetching data:", err);
            } finally {
                setLoading(false);
            }
        };

        fetchData();
    }, []);

    const handleEnroll = async (courseId) => {
        setProcessing((prev) => ({ ...prev, [courseId]: true }));
        try {
            const res = await apiController.post(`/enroll/${courseId}`);
            setEnrollments([...enrollments, res.data]);
            alert("Enrolled successfully!");
        } catch (err) {
            const msg = err.response?.data?.message || "Enrollment failed!";
            alert(msg);
        } finally {
            setProcessing((prev) => ({ ...prev, [courseId]: false }));
        }
    };

    const handlePay = async (enrollmentId, courseId) => {
        setProcessing((prev) => ({ ...prev, [courseId]: true }));
        try {
            const res = await apiController.post(`/pay/${enrollmentId}`);
            setPayments([...payments, res.data]);
            alert("Payment successful!");
        } catch (err) {
            const msg = err.response?.data?.message || "Payment failed!";
            alert(msg);
        } finally {
            setProcessing((prev) => ({ ...prev, [courseId]: false }));
        }
    };

    if (loading) return <p>Loading courses...</p>;

    return (
        <div style={{ padding: "20px" }}>
            <h1>Courses</h1>
            {courses.length === 0 ? (
                <p>No courses available.</p>
            ) : (
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Language</th>
                            <th>Level</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        {courses.map((course) => {
                            // Find enrollment/payment for this course
                            const enrollment = enrollments.find((e) => e.course_id === course.id);
                            const payment = payments.find((p) => p.course_id === course.id);

                            return (
                                <tr key={course.id}>
                                    <td>{course.title}</td>
                                    <td>{course.category}</td>
                                    <td>{course.language}</td>
                                    <td>{course.level}</td>
                                    <td>{course.price}</td>
                                    <td>
                                        <div style={{ display: "flex", gap: "10px", alignItems: "center" }}>
                                            <Link to={`/courses/${course.id}`}>View Details</Link>

                                            {user?.role === "student" && (
                                                <>
                                                    {!enrollment ? (
                                                        <button
                                                            onClick={() => handleEnroll(course.id)}
                                                            disabled={processing[course.id]}
                                                        >
                                                            {processing[course.id] ? "Processing..." : "Enroll"}
                                                        </button>
                                                    ) : payment ? (
                                                        <button disabled>Paid</button>
                                                    ) : (
                                                        <button
                                                            onClick={() => handlePay(enrollment.id, course.id)}
                                                            disabled={processing[course.id]}
                                                        >
                                                            {processing[course.id] ? "Processing..." : "Buy Now"}
                                                        </button>
                                                    )}
                                                </>
                                            )}
                                        </div>
                                    </td>
                                </tr>
                            );
                        })}
                    </tbody>
                </table>
            )}
        </div>
    );
}

export default Courses;