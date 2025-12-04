import React, { useState, useEffect } from "react"
import { Link } from "gatsby"
import Layout from "../components/layout"
import Seo from "../components/seo"

const API_URL = "https://finalswfeature.onrender.com"

const DeliveryPage = () => {
    const [orders, setOrders] = useState([])
    const [loading, setLoading] = useState(true)

    useEffect(() => {
        // For demo, get orders from localStorage
        const storedOrders = JSON.parse(localStorage.getItem("orders") || "[]")
        setOrders(storedOrders)
        setLoading(false)
    }, [])

    const renderImage = (gallery) => {
        if (!gallery) return "https://via.placeholder.com/100"
        if (gallery.startsWith('http')) return gallery
        return `${API_URL}/assets/images/${gallery}`
    }

    const cancelOrder = (orderId) => {
        if (window.confirm("Are you sure you want to cancel this order?")) {
            const updatedOrders = orders.map(order =>
                order.order_id === orderId
                    ? { ...order, delivery_status: "cancelled" }
                    : order
            )
            localStorage.setItem("orders", JSON.stringify(updatedOrders))
            setOrders(updatedOrders)
        }
    }

    const getStatusBadge = (status) => {
        switch (status) {
            case 'delivered':
                return <span className="badge bg-success px-3 py-2">Delivered</span>
            case 'cancelled':
                return <span className="badge bg-danger px-3 py-2">Cancelled</span>
            default:
                return <span className="badge bg-info px-3 py-2">In Progress</span>
        }
    }

    return (
        <Layout>
            <Seo title="Delivery" />

            <div className="container py-5">
                <div className="row mb-4">
                    <div className="col-md-12 text-center">
                        <h2 className="text-primary fw-bold">My Delivery Orders</h2>
                        <p className="text-muted">Track your orders and delivery status</p>
                    </div>
                </div>

                {loading ? (
                    <div className="text-center">
                        <div className="spinner-border text-primary" role="status">
                            <span className="visually-hidden">Loading...</span>
                        </div>
                    </div>
                ) : orders.length > 0 ? (
                    <div className="row">
                        {orders.map((item, index) => (
                            <div className="col-md-12 mb-4" key={index}>
                                <div className="card shadow-sm border-0 rounded-lg">
                                    <div className="card-body">
                                        <div className="row align-items-center">
                                            <div className="col-md-2 text-center">
                                                <img
                                                    src={renderImage(item.gallery)}
                                                    className="img-fluid rounded"
                                                    style={{ maxHeight: "100px", objectFit: "cover" }}
                                                    alt={item.name}
                                                />
                                            </div>
                                            <div className="col-md-4">
                                                <h5 className="card-title fw-bold mb-1">{item.name}</h5>
                                                <p className="text-muted mb-2">Order ID: #{item.order_id}</p>
                                                <h6 className="text-primary fw-bold">${item.price}</h6>
                                            </div>
                                            <div className="col-md-3">
                                                <div className="mb-2">
                                                    <small className="text-uppercase text-muted" style={{ fontSize: "0.7rem", letterSpacing: "1px" }}>Status</small>
                                                    <div>{getStatusBadge(item.delivery_status)}</div>
                                                </div>
                                                <div>
                                                    <small className="text-uppercase text-muted" style={{ fontSize: "0.7rem", letterSpacing: "1px" }}>Payment</small>
                                                    <div>
                                                        <span className="badge bg-light border px-2 py-1 text-dark">{item.payment_method}</span>
                                                        <span className={`badge ${item.payment_status === 'paid' ? 'bg-success' : 'bg-warning'} px-2 py-1 ms-1`}>
                                                            {item.payment_status}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div className="col-md-3 border-start">
                                                <div className="mb-2">
                                                    <small className="text-muted"><i className="fas fa-key me-1"></i>OTP Code</small>
                                                    <h4 className="text-danger fw-bold" style={{ letterSpacing: "2px" }}>{item.otp}</h4>
                                                </div>
                                                <div>
                                                    <small className="text-muted"><i className="fas fa-calendar-alt me-1"></i>Expected Delivery</small>
                                                    <h6 className="text-dark fw-bold">{item.delivery_date}</h6>
                                                </div>

                                                {item.delivery_status !== 'cancelled' && item.delivery_status !== 'delivered' && (
                                                    <div className="mt-3">
                                                        <button
                                                            className="btn btn-outline-danger btn-sm w-100"
                                                            onClick={() => cancelOrder(item.order_id)}
                                                        >
                                                            <i className="fas fa-times-circle me-1"></i>Cancel Order
                                                        </button>
                                                    </div>
                                                )}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        ))}
                    </div>
                ) : (
                    <div className="row justify-content-center">
                        <div className="col-md-6 text-center py-5">
                            <div className="mb-4">
                                <i className="fas fa-box-open text-muted" style={{ fontSize: "5rem" }}></i>
                            </div>
                            <h3 className="text-muted mb-3">No orders found</h3>
                            <p className="text-muted mb-4">Looks like you haven't placed any orders yet.</p>
                            <Link to="/" className="btn btn-primary btn-lg px-5 rounded-pill shadow-sm">Start Shopping</Link>
                        </div>
                    </div>
                )}
            </div>

            <style>{`
                .letter-spacing-2 {
                    letter-spacing: 2px;
                }
                .card {
                    transition: transform 0.2s;
                }
                .card:hover {
                    transform: translateY(-2px);
                }
            `}</style>
        </Layout>
    )
}

export default DeliveryPage
