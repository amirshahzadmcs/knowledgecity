import React, { useEffect, useState } from "react";
import { useParams } from "react-router-dom";
import apiController from "../api/apiController";

const BASE_URL = "http://localhost:8000/api";

function CourseDetail() {
    const { id } = useParams();
    const [course, setCourse] = useState(null);
    const [loading, setLoading] = useState(true);
    const [user, setUser] = useState(null);
    const [enrollment, setEnrollment] = useState(null);
    const [payment, setPayment] = useState(null);
    const [paying, setPaying] = useState(false);
    const [completedLessons, setCompletedLessons] = useState([]);
    const [updatingProgress, setUpdatingProgress] = useState({});

    useEffect(() => {
        const fetchData = async () => {
            try {
                // 1. Get course details
                const courseRes = await apiController.get(`/courses/${id}`);
                setCourse(courseRes.data);

                // 2. Get user info
                const userRes = await apiController.get(`/me`);
                setUser(userRes.data);

                // 3. Only for students: check enrollment, payment, and completed lessons
                if (userRes.data.role === "student") {
                    const enrollRes = await apiController.get(`/enrollments`);
                    const userEnrollment = enrollRes.data.find(e => e.course_id === courseRes.data.id);
                    setEnrollment(userEnrollment || null);

                    if (userEnrollment) {
                        const payRes = await apiController.get(`/payments`);
                        const userPayment = payRes.data.find(p => p.course_id === courseRes.data.id);
                        setPayment(userPayment || null);

                        // Initialize completed lessons
                        const completed = userEnrollment.completed_lessons || [];
                        setCompletedLessons(completed);
                    }
                }
            } catch (err) {
                console.log("Error fetching data:", err);
            } finally {
                setLoading(false);
            }
        };

        fetchData();
    }, [id]);

    const handlePay = async () => {
        if (!enrollment) {
            alert("You need to enroll first!");
            return;
        }

        setPaying(true);
        try {
            const res = await apiController.post(`/pay/${enrollment.id}`);
            setPayment(res.data);
            alert(res.data?.message || "Payment successful!");
        } catch (err) {
            console.log(err);
            alert(err.response?.data?.message || "Payment failed!");
        } finally {
            setPaying(false);
        }
    };

    const handleLessonToggle = async (lessonId) => {
        if (!enrollment || !payment) {
            alert("You need to enroll and pay to track lessons!");
            return;
        }

        setUpdatingProgress((prev) => ({ ...prev, [lessonId]: true }));

        const updatedCompleted = completedLessons.includes(lessonId)
            ? completedLessons.filter((id) => id !== lessonId)
            : [...completedLessons, lessonId];

        const progress = Math.round((updatedCompleted.length / course.lessons.length) * 100);

        try {
            await apiController.put(`/enrollment/${enrollment.id}/progress`, {
                progress,
                completed: updatedCompleted.length === course.lessons.length,
            });

            setCompletedLessons(updatedCompleted);
            setEnrollment({ ...enrollment, progress, completed: updatedCompleted.length === course.lessons.length, completed_lessons: updatedCompleted });
        } catch (err) {
            alert(err.response?.data?.message || "Failed to update progress!");
        } finally {
            setUpdatingProgress((prev) => ({ ...prev, [lessonId]: false }));
        }
    };

    if (loading) return <p>Loading course...</p>;
    if (!course) return <p>Course not found</p>;

    return (
        <div style={{ padding: "20px" }}>
            <h1>{course.title}</h1>
            <p>{course.description}</p>

            <h2>Lessons</h2>
            {course.lessons.length === 0 ? (
                <p>No lessons available.</p>
            ) : (
                <ul>
                    {course.lessons.map((lesson) => {
                        const isCompleted = completedLessons.includes(lesson.id);
                        return (
                            <li key={lesson.id}>
                                <input
                                    type="checkbox"
                                    checked={isCompleted}
                                    disabled={!payment || updatingProgress[lesson.id]}
                                    onChange={() => handleLessonToggle(lesson.id)}
                                    style={{ marginRight: "10px" }}
                                />
                                <strong>{lesson.title}</strong>
                                {lesson.video ? (
                                    lesson.video.status === 'processed' && lesson.video.processed_file ? (
                                        <div>
                                            <video
                                                width="400"
                                                controls
                                                src={`${BASE_URL}/video/${lesson.video.id}/stream`}
                                            ></video>
                                        </div>
                                    ) : (
                                        <p>Video is processing... Please check back later.</p>
                                    )
                                ) : (
                                    <p>No video uploaded for this lesson.</p>
                                )}
                            </li>
                        );
                    })}
                </ul>
            )}

            {/* Payment / Enrollment Section */}
            {user?.role === "student" && (
                <div style={{ marginTop: "20px" }}>
                    {!enrollment ? (
                        <button
                            onClick={async () => {
                                try {
                                    const res = await apiController.post(`/enroll/${course.id}`);
                                    setEnrollment(res.data);
                                    alert("Enrolled successfully!");
                                } catch (err) {
                                    alert(err.response?.data?.message || "Enrollment failed!");
                                }
                            }}
                        >
                            Enroll Now
                        </button>
                    ) : payment ? (
                        <button disabled>Paid</button>
                    ) : (
                        <button onClick={handlePay} disabled={paying}>
                            {paying ? "Processing..." : "Buy Now"}
                        </button>
                    )}

                    {enrollment && (
                        <p style={{ marginTop: "10px" }}>
                            Progress: {enrollment.progress || 0}% {enrollment.completed ? "(Completed)" : ""}
                        </p>
                    )}
                </div>
            )}
        </div>
    );
}

export default CourseDetail;