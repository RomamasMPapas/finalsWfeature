import React, { useState } from "react"
import { Link, navigate } from "gatsby"
import Layout from "../components/layout"
import Seo from "../components/seo"

const LoginPage = () => {
    const [step, setStep] = useState(1) // 1: Phone/Email, 2: OTP
    const [identifier, setIdentifier] = useState("")
    const [otp, setOtp] = useState("")
    const [error, setError] = useState("")

    // valid OTPs as requested
    const validOtps = ["81506", "13579", "15208", "20342", "52533"]

    const handleSendOtp = (e) => {
        e.preventDefault()
        if (!identifier) {
            setError("Please enter your email or phone number")
            return
        }
        // Simulate sending OTP
        setError("")
        setStep(2)
    }

    const handleVerifyOtp = (e) => {
        e.preventDefault()
        if (validOtps.includes(otp)) {
            // Success!
            // In a real app, you'd save a token here.
            // For now, we'll just redirect.
            alert("Login Successful!")
            navigate("/shop")
        } else {
            setError("Invalid OTP. Please try again.")
        }
    }

    return (
        <Layout>
            <Seo title="Login" />
            <div style={{
                maxWidth: "400px",
                margin: "0 auto",
                padding: "2rem",
                boxShadow: "0 4px 6px rgba(0,0,0,0.1)",
                borderRadius: "8px",
                marginTop: "2rem"
            }}>
                <h1 style={{ textAlign: "center", marginBottom: "2rem" }}>Login</h1>

                {error && (
                    <div style={{
                        backgroundColor: "#ffebee",
                        color: "#c62828",
                        padding: "0.5rem",
                        borderRadius: "4px",
                        marginBottom: "1rem",
                        textAlign: "center"
                    }}>
                        {error}
                    </div>
                )}

                {step === 1 ? (
                    <form onSubmit={handleSendOtp}>
                        <div style={{ marginBottom: "1rem" }}>
                            <label style={{ display: "block", marginBottom: "0.5rem" }}>
                                Email or Phone Number
                            </label>
                            <input
                                type="text"
                                value={identifier}
                                onChange={(e) => setIdentifier(e.target.value)}
                                style={{
                                    width: "100%",
                                    padding: "0.5rem",
                                    borderRadius: "4px",
                                    border: "1px solid #ccc"
                                }}
                                placeholder="Enter email or phone"
                            />
                        </div>
                        <button
                            type="submit"
                            style={{
                                width: "100%",
                                padding: "0.75rem",
                                backgroundColor: "#663399",
                                color: "white",
                                border: "none",
                                borderRadius: "4px",
                                cursor: "pointer",
                                fontWeight: "bold"
                            }}
                        >
                            Send OTP
                        </button>
                    </form>
                ) : (
                    <form onSubmit={handleVerifyOtp}>
                        <div style={{ marginBottom: "1rem" }}>
                            <label style={{ display: "block", marginBottom: "0.5rem" }}>
                                Enter OTP
                            </label>
                            <input
                                type="text"
                                value={otp}
                                onChange={(e) => setOtp(e.target.value)}
                                style={{
                                    width: "100%",
                                    padding: "0.5rem",
                                    borderRadius: "4px",
                                    border: "1px solid #ccc"
                                }}
                                placeholder="Enter OTP"
                            />
                            <small style={{ display: "block", marginTop: "0.5rem", color: "#666" }}>
                                (Check the list of valid OTPs)
                            </small>
                        </div>
                        <button
                            type="submit"
                            style={{
                                width: "100%",
                                padding: "0.75rem",
                                backgroundColor: "#663399",
                                color: "white",
                                border: "none",
                                borderRadius: "4px",
                                cursor: "pointer",
                                fontWeight: "bold"
                            }}
                        >
                            Verify & Login
                        </button>
                        <button
                            type="button"
                            onClick={() => { setStep(1); setError(""); setOtp(""); }}
                            style={{
                                width: "100%",
                                padding: "0.5rem",
                                marginTop: "0.5rem",
                                backgroundColor: "transparent",
                                color: "#666",
                                border: "none",
                                cursor: "pointer",
                                textDecoration: "underline"
                            }}
                        >
                            Back
                        </button>
                    </form>
                )}

                <div style={{ marginTop: "1.5rem", textAlign: "center" }}>
                    <Link to="/">Back to Home</Link>
                </div>
            </div>
        </Layout>
    )
}

export default LoginPage
