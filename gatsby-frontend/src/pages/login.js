import React, { useState } from "react"
import { Link, navigate } from "gatsby"
import Layout from "../components/layout"
import Seo from "../components/seo"

const LoginPage = () => {
    const [email, setEmail] = useState("")
    const [password, setPassword] = useState("")
    const [error, setError] = useState("")
    const [loading, setLoading] = useState(false)
    const API_URL = "https://finalswfeature.onrender.com"

    const handleLogin = async (e) => {
        e.preventDefault()
        setError("")
        setLoading(true)

        if (!email || !password) {
            setError("Please enter both email and password")
            setLoading(false)
            return
        }

        try {
            const response = await fetch(`${API_URL}/api/login`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ email, password }),
            })

            const data = await response.json()

            if (data.success) {
                localStorage.setItem("user", JSON.stringify(data.user))
                navigate("/")
            } else {
                setError(data.message || "Invalid credentials")
            }
        } catch (err) {
            console.error("Login error:", err)
            setError("An error occurred. Please try again.")
        } finally {
            setLoading(false)
        }
    }

    return (
        <Layout>
            <Seo title="Login" />
            <div className="container mt-5">
                <div className="row justify-content-center">
                    <div className="col-md-5">
                        <div className="card shadow">
                            <div className="card-header bg-primary text-white text-center">
                                <h3>Login</h3>
                            </div>
                            <div className="card-body p-4">
                                {error && (
                                    <div className="alert alert-danger">{error}</div>
                                )}

                                <form onSubmit={handleLogin}>
                                    <div className="mb-3">
                                        <label className="form-label">Email Address</label>
                                        <input
                                            type="email"
                                            className="form-control"
                                            value={email}
                                            onChange={(e) => setEmail(e.target.value)}
                                            placeholder="Enter your email"
                                        />
                                    </div>
                                    <div className="mb-3">
                                        <label className="form-label">Password</label>
                                        <input
                                            type="password"
                                            className="form-control"
                                            value={password}
                                            onChange={(e) => setPassword(e.target.value)}
                                            placeholder="Enter your password"
                                        />
                                    </div>
                                    <button type="submit" className="btn btn-primary w-100" disabled={loading}>
                                        {loading ? "Logging in..." : "Login"}
                                    </button>
                                </form>

                                <hr />
                                <p className="text-center mb-0">
                                    Don't have an account? <Link to="/register">Register</Link>
                                </p>
                            </div>
                        </div>
                        <div className="text-center mt-3">
                            <Link to="/">Back to Home</Link>
                        </div>
                    </div>
                </div>
            </div>
        </Layout>
    )
}

export default LoginPage
