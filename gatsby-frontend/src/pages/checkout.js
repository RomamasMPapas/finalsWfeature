import React, { useState } from "react"
import { Link, navigate } from "gatsby"
import Layout from "../components/layout"
import Seo from "../components/seo"

const CheckoutPage = () => {
    const [step, setStep] = useState(1) // 1: Order Summary, 2: Email Entry, 3: OTP Verification, 4: Payment
    const [email, setEmail] = useState("")
    const [otp, setOtp] = useState("")
    const [error, setError] = useState("")
    const [success, setSuccess] = useState("")
    const [loading, setLoading] = useState(false)
    const [paymentMethod, setPaymentMethod] = useState("cod")

    // Use local API for testing OTP
    const API_URL = "http://127.0.0.1:8000/api"

    // Sample cart items
    const cartItems = [
        { id: 1, name: "Smartphone X", price: 999, quantity: 1 },
        { id: 2, name: "Laptop Pro", price: 1299, quantity: 1 },
    ]

    const total = cartItems.reduce((sum, item) => sum + (item.price * item.quantity), 0)

    const handleProceedToEmail = () => {
        setStep(2)
        setError("")
    }

    const handleSendOTP = async (e) => {
        e.preventDefault()
        setLoading(true)
        setError("")

        try {
            const response = await fetch(`${API_URL}/send-otp`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ email })
            })

            const data = await response.json()

            if (data.success) {
                setSuccess("OTP sent to your email! Check your inbox.")
                setStep(3)
            } else {
                setError(data.message || "Failed to send OTP")
            }
        } catch (err) {
            setError("Network error. Please try again.")
            console.error(err)
        } finally {
            setLoading(false)
        }
    }

    const handleVerifyOTP = async (e) => {
        e.preventDefault()
        setLoading(true)
        setError("")

        try {
            const response = await fetch(`${API_URL}/verify-otp`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ email, otp })
            })

            const data = await response.json()

            if (data.success) {
                setSuccess("OTP Verified!")
                setStep(4)
            } else {
                setError(data.message || "Invalid OTP")
            }
        } catch (err) {
            setError("Network error. Please try again.")
            console.error(err)
        } finally {
            setLoading(false)
        }
    }

    const handlePlaceOrder = () => {
        alert("Order placed successfully! Thank you for your purchase.")
        navigate("/delivery")
    }

    return (
        <Layout>
            <Seo title="Checkout" />
            <div className="container mt-5 mb-5">
                <h2 className="text-center mb-4">Checkout</h2>

                {/* Progress Steps */}
                <div className="row mb-4">
                    <div className="col-12">
                        <div className="d-flex justify-content-center">
                            <div className={`px-3 py-2 rounded-circle ${step >= 1 ? 'bg-primary text-white' : 'bg-secondary text-white'}`}>1</div>
                            <div className="align-self-center mx-2">—</div>
                            <div className={`px-3 py-2 rounded-circle ${step >= 2 ? 'bg-primary text-white' : 'bg-secondary text-white'}`}>2</div>
                            <div className="align-self-center mx-2">—</div>
                            <div className={`px-3 py-2 rounded-circle ${step >= 3 ? 'bg-primary text-white' : 'bg-secondary text-white'}`}>3</div>
                            <div className="align-self-center mx-2">—</div>
                            <div className={`px-3 py-2 rounded-circle ${step >= 4 ? 'bg-primary text-white' : 'bg-secondary text-white'}`}>4</div>
                        </div>
                        <div className="d-flex justify-content-center mt-2">
                            <small className="mx-2">Order</small>
                            <small className="mx-3">Email</small>
                            <small className="mx-3">OTP</small>
                            <small className="mx-2">Payment</small>
                        </div>
                    </div>
                </div>

                {error && (
                    <div className="alert alert-danger text-center">{error}</div>
                )}
                {success && (
                    <div className="alert alert-success text-center">{success}</div>
                )}

                {/* Step 1: Order Summary */}
                {step === 1 && (
                    <div className="row justify-content-center">
                        <div className="col-md-8">
                            <div className="card shadow">
                                <div className="card-header bg-primary text-white">
                                    <h5 className="mb-0">Order Summary</h5>
                                </div>
                                <div className="card-body">
                                    <table className="table">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Quantity</th>
                                                <th>Price</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {cartItems.map(item => (
                                                <tr key={item.id}>
                                                    <td>{item.name}</td>
                                                    <td>{item.quantity}</td>
                                                    <td>${item.price}</td>
                                                </tr>
                                            ))}
                                        </tbody>
                                        <tfoot>
                                            <tr className="table-dark">
                                                <td colSpan="2"><strong>Total</strong></td>
                                                <td><strong>${total}</strong></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <button
                                        className="btn btn-primary w-100"
                                        onClick={handleProceedToEmail}
                                    >
                                        Proceed to Verification
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                )}

                {/* Step 2: Email Entry */}
                {step === 2 && (
                    <div className="row justify-content-center">
                        <div className="col-md-5">
                            <div className="card shadow">
                                <div className="card-header bg-info text-white text-center">
                                    <h5 className="mb-0">📧 Enter Your Email</h5>
                                </div>
                                <div className="card-body p-4">
                                    <p className="text-center text-muted">
                                        We'll send an OTP to your email for payment verification.
                                    </p>
                                    <form onSubmit={handleSendOTP}>
                                        <div className="mb-3">
                                            <label className="form-label">Email Address</label>
                                            <input
                                                type="email"
                                                className="form-control form-control-lg"
                                                value={email}
                                                onChange={(e) => setEmail(e.target.value)}
                                                placeholder="your-email@gmail.com"
                                                required
                                            />
                                        </div>
                                        <button
                                            type="submit"
                                            className="btn btn-primary w-100"
                                            disabled={loading}
                                        >
                                            {loading ? "Sending..." : "Send OTP to Email"}
                                        </button>
                                    </form>
                                    <button
                                        className="btn btn-link w-100 mt-2"
                                        onClick={() => { setStep(1); setError(""); setSuccess(""); }}
                                    >
                                        ← Back to Order Summary
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                )}

                {/* Step 3: OTP Verification */}
                {step === 3 && (
                    <div className="row justify-content-center">
                        <div className="col-md-5">
                            <div className="card shadow">
                                <div className="card-header bg-warning text-dark text-center">
                                    <h5 className="mb-0">🔒 Verify OTP</h5>
                                </div>
                                <div className="card-body p-4">
                                    <p className="text-center text-muted">
                                        Enter the 5-digit code sent to <strong>{email}</strong>
                                    </p>
                                    <form onSubmit={handleVerifyOTP}>
                                        <div className="mb-3">
                                            <input
                                                type="text"
                                                className="form-control form-control-lg text-center"
                                                value={otp}
                                                onChange={(e) => setOtp(e.target.value)}
                                                placeholder="XXXXX"
                                                maxLength="5"
                                                required
                                                style={{ letterSpacing: "5px", fontSize: "24px" }}
                                            />
                                        </div>
                                        <button
                                            type="submit"
                                            className="btn btn-success w-100"
                                            disabled={loading}
                                        >
                                            {loading ? "Verifying..." : "Verify OTP"}
                                        </button>
                                    </form>
                                    <button
                                        className="btn btn-link w-100 mt-2"
                                        onClick={() => { setStep(2); setOtp(""); setError(""); setSuccess(""); }}
                                    >
                                        ← Change Email
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                )}

                {/* Step 4: Payment */}
                {step === 4 && (
                    <div className="row justify-content-center">
                        <div className="col-md-6">
                            <div className="card shadow">
                                <div className="card-header bg-success text-white text-center">
                                    <h5 className="mb-0">Payment Method</h5>
                                </div>
                                <div className="card-body p-4">
                                    <div className="alert alert-success">
                                        <i className="fas fa-check-circle me-2"></i> Email Verified Successfully!
                                    </div>

                                    <h6 className="mb-3">Select Payment Method:</h6>

                                    <div className="form-check mb-3 p-3 border rounded">
                                        <input
                                            className="form-check-input"
                                            type="radio"
                                            name="paymentMethod"
                                            id="cod"
                                            value="cod"
                                            checked={paymentMethod === "cod"}
                                            onChange={(e) => setPaymentMethod(e.target.value)}
                                        />
                                        <label className="form-check-label ms-2" htmlFor="cod">
                                            <strong>Cash on Delivery (COD)</strong>
                                            <p className="text-muted mb-0 small">Pay when you receive your order.</p>
                                        </label>
                                    </div>

                                    <div className="form-check mb-3 p-3 border rounded bg-light">
                                        <input
                                            className="form-check-input"
                                            type="radio"
                                            name="paymentMethod"
                                            id="card"
                                            value="card"
                                            disabled
                                        />
                                        <label className="form-check-label ms-2 text-muted" htmlFor="card">
                                            <strong>Credit Card (Coming Soon)</strong>
                                        </label>
                                    </div>

                                    <button
                                        className="btn btn-primary w-100 btn-lg mt-3"
                                        onClick={handlePlaceOrder}
                                    >
                                        Place Order
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                )}

                <div className="text-center mt-4">
                    <Link to="/products">← Continue Shopping</Link>
                </div>
            </div>
        </Layout>
    )
}

export default CheckoutPage
