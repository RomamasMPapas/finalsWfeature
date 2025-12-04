import React, { useState, useEffect } from "react"
import { Link } from "gatsby"
import Layout from "../components/layout"
import Seo from "../components/seo"

const ProfilePage = () => {
    const [user, setUser] = useState(null)
    const [editMode, setEditMode] = useState(false)
    const [formData, setFormData] = useState({})
    const [orders, setOrders] = useState([])

    useEffect(() => {
        const userData = localStorage.getItem("user")
        if (userData) {
            const parsed = JSON.parse(userData)
            setUser(parsed)
            setFormData(parsed)
        }

        const storedOrders = JSON.parse(localStorage.getItem("orders") || "[]")
        setOrders(storedOrders)
    }, [])

    const handleChange = (e) => {
        const { name, value } = e.target
        if (name === "phone") {
            setFormData({ ...formData, [name]: value.replace(/[^0-9]/g, "").slice(0, 11) })
        } else {
            setFormData({ ...formData, [name]: value })
        }
    }

    const handleSave = (e) => {
        e.preventDefault()
        localStorage.setItem("user", JSON.stringify(formData))
        setUser(formData)
        setEditMode(false)
        alert("Profile updated successfully!")
    }

    const handleLogout = () => {
        localStorage.removeItem("user")
        window.location.href = "/"
    }

    if (!user) {
        return (
            <Layout>
                <div className="container py-5 text-center">
                    <h3>Please login to view your profile</h3>
                    <Link to="/login" className="btn btn-primary mt-3">Login</Link>
                </div>
            </Layout>
        )
    }

    const getInitials = () => {
        const first = user.firstName?.charAt(0) || user.email?.charAt(0) || "U"
        const last = user.lastName?.charAt(0) || ""
        return (first + last).toUpperCase()
    }

    return (
        <Layout>
            <Seo title="Profile" />

            <div className="profile-page" style={{ background: "linear-gradient(135deg, #667eea 0%, #764ba2 100%)", minHeight: "calc(100vh - 120px)", padding: "40px 0" }}>
                <div className="container">
                    {/* Profile Header */}
                    <div className="bg-white rounded-4 p-4 mb-4 shadow d-flex align-items-center gap-4 flex-wrap">
                        <div
                            className="d-flex align-items-center justify-content-center rounded-circle text-white fw-bold"
                            style={{
                                width: "120px",
                                height: "120px",
                                background: "linear-gradient(135deg, #667eea 0%, #764ba2 100%)",
                                fontSize: "48px"
                            }}
                        >
                            {getInitials()}
                        </div>
                        <div className="flex-grow-1">
                            <h1 className="fw-bold mb-1">{user.firstName} {user.lastName}</h1>
                            <p className="text-muted mb-3"><i className="fas fa-envelope me-2"></i>{user.email}</p>
                            <div className="d-flex gap-4">
                                <div className="text-center">
                                    <span className="d-block fw-bold text-primary fs-4">{orders.length}</span>
                                    <span className="text-muted text-uppercase small">Orders</span>
                                </div>
                                <div className="text-center">
                                    <span className="d-block fw-bold text-primary fs-4">2024</span>
                                    <span className="text-muted text-uppercase small">Member Since</span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <button
                                className="btn text-white px-4 py-2"
                                style={{ background: "linear-gradient(135deg, #667eea 0%, #764ba2 100%)" }}
                                onClick={() => setEditMode(!editMode)}
                            >
                                <i className="fas fa-edit me-2"></i>{editMode ? "Cancel" : "Edit Profile"}
                            </button>
                        </div>
                    </div>

                    <div className="row">
                        {/* Profile Information */}
                        <div className="col-md-6 mb-4">
                            <div className="bg-white rounded-4 p-4 shadow">
                                <h5 className="fw-bold border-bottom pb-3 mb-3">
                                    <i className="fas fa-user me-2"></i>Profile Information
                                </h5>

                                {!editMode ? (
                                    <>
                                        <div className="d-flex justify-content-between py-3 border-bottom">
                                            <span className="fw-semibold">First Name</span>
                                            <span className="text-muted">{user.firstName}</span>
                                        </div>
                                        <div className="d-flex justify-content-between py-3 border-bottom">
                                            <span className="fw-semibold">Last Name</span>
                                            <span className="text-muted">{user.lastName}</span>
                                        </div>
                                        <div className="d-flex justify-content-between py-3 border-bottom">
                                            <span className="fw-semibold">Email</span>
                                            <span className="text-muted">{user.email}</span>
                                        </div>
                                        <div className="d-flex justify-content-between py-3">
                                            <span className="fw-semibold">Phone</span>
                                            <span className="text-muted">{user.phone}</span>
                                        </div>
                                    </>
                                ) : (
                                    <form onSubmit={handleSave}>
                                        <div className="mb-3">
                                            <label className="form-label fw-semibold">First Name</label>
                                            <input
                                                type="text"
                                                className="form-control"
                                                name="firstName"
                                                value={formData.firstName || ""}
                                                onChange={handleChange}
                                                required
                                            />
                                        </div>
                                        <div className="mb-3">
                                            <label className="form-label fw-semibold">Last Name</label>
                                            <input
                                                type="text"
                                                className="form-control"
                                                name="lastName"
                                                value={formData.lastName || ""}
                                                onChange={handleChange}
                                                required
                                            />
                                        </div>
                                        <div className="mb-3">
                                            <label className="form-label fw-semibold">Phone (11 digits)</label>
                                            <input
                                                type="tel"
                                                className="form-control"
                                                name="phone"
                                                value={formData.phone || ""}
                                                onChange={handleChange}
                                                pattern="[0-9]{11}"
                                                maxLength="11"
                                                required
                                            />
                                        </div>
                                        <button type="submit" className="btn btn-success me-2">
                                            <i className="fas fa-save me-1"></i>Save Changes
                                        </button>
                                    </form>
                                )}
                            </div>
                        </div>

                        {/* Address & Actions */}
                        <div className="col-md-6">
                            <div className="bg-white rounded-4 p-4 shadow mb-4">
                                <h5 className="fw-bold border-bottom pb-3 mb-3">
                                    <i className="fas fa-map-marker-alt me-2"></i>Shipping Address
                                </h5>
                                {!editMode ? (
                                    <div className="d-flex justify-content-between py-3">
                                        <span className="fw-semibold">Address</span>
                                        <span className="text-muted">{user.address || "Not set"}</span>
                                    </div>
                                ) : (
                                    <div className="mb-3">
                                        <textarea
                                            className="form-control"
                                            name="address"
                                            value={formData.address || ""}
                                            onChange={handleChange}
                                            rows="3"
                                        ></textarea>
                                    </div>
                                )}
                            </div>

                            <div className="bg-white rounded-4 p-4 shadow">
                                <h5 className="fw-bold border-bottom pb-3 mb-3">
                                    <i className="fas fa-cog me-2"></i>Account Actions
                                </h5>
                                <Link to="/cart" className="btn btn-outline-primary w-100 mb-2">
                                    <i className="fas fa-shopping-cart me-2"></i>View Cart
                                </Link>
                                <Link to="/products" className="btn btn-outline-success w-100 mb-2">
                                    <i className="fas fa-store me-2"></i>Browse Products
                                </Link>
                                <Link to="/delivery" className="btn btn-outline-info w-100 mb-2">
                                    <i className="fas fa-truck me-2"></i>My Orders
                                </Link>
                                <button onClick={handleLogout} className="btn btn-outline-danger w-100">
                                    <i className="fas fa-sign-out-alt me-2"></i>Logout
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </Layout>
    )
}

export default ProfilePage
