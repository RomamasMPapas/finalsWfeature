import React, { useState } from "react"
import { Link, navigate } from "gatsby"
import Layout from "../components/layout"
import Seo from "../components/seo"

const RegisterPage = () => {
    const [formData, setFormData] = useState({
        firstName: "",
        lastName: "",
        email: "",
        phone: "",
        address: "",
        password: ""
    })
    const [error, setError] = useState("")
    const [loading, setLoading] = useState(false)

    const handleChange = (e) => {
        const { name, value } = e.target

        // Phone validation - only digits, max 11
        if (name === "phone") {
            const cleaned = value.replace(/[^0-9]/g, "").slice(0, 11)
            setFormData({ ...formData, [name]: cleaned })
        } else {
            setFormData({ ...formData, [name]: value })
        }
    }

    const API_URL = "https://finalswfeature.onrender.com"

    const handleSubmit = async (e) => {
        e.preventDefault()
        setLoading(true)
        setError("")

        // Basic validation
        if (formData.phone.length !== 11) {
            setError("Phone number must be exactly 11 digits")
            setLoading(false)
            return
        }

        if (formData.password.length < 5) {
            setError("Password must be at least 5 characters")
            setLoading(false)
            return
        }

        try {
            const response = await fetch(`${API_URL}/api/register`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify(formData),
            })

            const data = await response.json()

            if (data.success) {
                localStorage.setItem("user", JSON.stringify(data.user))
                alert("Registration successful!")
                navigate("/")
            } else {
                setError(data.message || "Registration failed")
            }
        } catch (err) {
            console.error("Registration error:", err)
            setError("An error occurred. Please try again.")
        } finally {
            setLoading(false)
        }
    }

    return (
        <Layout>
            <Seo title="Register" />

            <div className="container mt-3">
                <div className="row justify-content-center align-items-center">
                    <div className="col-md-6 bg-white shadow p-4 mt-5" style={{ borderRadius: "10px" }}>
                        <h5 className="login-header my-2 p-2 text-center shadow bg-light text-secondary">
                            User | <span className="text-danger">Register</span>
                        </h5>

                        {error && (
                            <div className="alert alert-danger">{error}</div>
                        )}

                        <form onSubmit={handleSubmit}>
                            <div className="row">
                                <div className="col-md-6 mb-3">
                                    <label htmlFor="firstName">First Name</label>
                                    <input
                                        type="text"
                                        name="firstName"
                                        className="form-control"
                                        value={formData.firstName}
                                        onChange={handleChange}
                                        required
                                    />
                                </div>
                                <div className="col-md-6 mb-3">
                                    <label htmlFor="lastName">Last Name</label>
                                    <input
                                        type="text"
                                        name="lastName"
                                        className="form-control"
                                        value={formData.lastName}
                                        onChange={handleChange}
                                        required
                                    />
                                </div>
                            </div>

                            <div className="mb-3">
                                <label htmlFor="email">Email</label>
                                <input
                                    type="email"
                                    name="email"
                                    className="form-control"
                                    value={formData.email}
                                    onChange={handleChange}
                                    required
                                />
                            </div>

                            <div className="mb-3">
                                <label htmlFor="phone">Phone Number (11 digits)</label>
                                <input
                                    type="tel"
                                    name="phone"
                                    className="form-control"
                                    value={formData.phone}
                                    onChange={handleChange}
                                    placeholder="e.g., 09123456789"
                                    required
                                />
                            </div>

                            <div className="mb-3">
                                <label htmlFor="address">Address</label>
                                <textarea
                                    name="address"
                                    className="form-control"
                                    rows="2"
                                    value={formData.address}
                                    onChange={handleChange}
                                    required
                                ></textarea>
                            </div>

                            <div className="mb-3">
                                <label htmlFor="password">Password</label>
                                <input
                                    type="password"
                                    name="password"
                                    className="form-control"
                                    value={formData.password}
                                    onChange={handleChange}
                                    minLength="5"
                                    required
                                />
                            </div>

                            <div className="d-flex">
                                <button type="submit" className="btn btn-danger me-auto" disabled={loading}>
                                    {loading ? "Registering..." : "Register"}
                                </button>
                                <Link className="btn btn-primary" to="/login">Already have an account?</Link>
                            </div>
                        </form>

                        <div className="text-center my-3">
                            <span className="text-muted">OR</span>
                        </div>

                        <div className="social-login">
                            <button className="btn btn-outline-primary w-100 mb-2 d-flex align-items-center justify-content-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#1877F2" viewBox="0 0 24 24" style={{ marginRight: "10px" }}>
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                </svg>
                                Facebook
                            </button>
                            <button className="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" style={{ marginRight: "10px" }}>
                                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                                </svg>
                                Google
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </Layout>
    )
}

export default RegisterPage
