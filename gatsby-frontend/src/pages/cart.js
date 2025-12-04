import React, { useState, useEffect } from "react"
import { Link, navigate } from "gatsby"
import Layout from "../components/layout"
import Seo from "../components/seo"

const API_URL = "https://finalswfeature.onrender.com"

const CartPage = () => {
    const [cartItems, setCartItems] = useState([])
    const [user, setUser] = useState(null)

    useEffect(() => {
        const userData = localStorage.getItem("user")
        if (userData) {
            setUser(JSON.parse(userData))
        }

        const cart = JSON.parse(localStorage.getItem("cart") || "[]")
        setCartItems(cart)
    }, [])

    const renderImage = (gallery) => {
        if (!gallery) return "https://via.placeholder.com/300"
        if (gallery.startsWith('http')) return gallery
        return `${API_URL}/assets/images/${gallery}`
    }

    const removeFromCart = (productId) => {
        const updatedCart = cartItems.filter(item => item.id !== productId)
        localStorage.setItem("cart", JSON.stringify(updatedCart))
        setCartItems(updatedCart)
    }

    const getTotal = () => {
        return cartItems.reduce((sum, item) => sum + parseFloat(item.price || 0), 0)
    }

    return (
        <Layout>
            <Seo title="Cart" />

            <div className="container mt-5">
                <h3 className="mb-4">Cart List</h3>

                {cartItems.length === 0 ? (
                    <div className="text-center py-5">
                        <i className="fas fa-shopping-cart" style={{ fontSize: "5rem", color: "#ccc" }}></i>
                        <h4 className="mt-3 text-muted">Your cart is empty</h4>
                        <Link to="/products" className="btn btn-primary mt-3">Browse Products</Link>
                    </div>
                ) : (
                    <>
                        {cartItems.map(item => (
                            <div className="row my-3 shadow-lg rounded justify-content-center align-items-center p-3" key={item.id}>
                                <div className="col-md-4">
                                    <div className="card shadow">
                                        <Link to={`/product/${item.id}`}>
                                            <img
                                                className="card-img-top"
                                                src={renderImage(item.gallery)}
                                                alt={item.name}
                                                style={{ height: "200px", objectFit: "cover" }}
                                            />
                                        </Link>
                                    </div>
                                </div>
                                <div className="col-md-4">
                                    <div className="card-body">
                                        <h4 className="card-title">{item.name}</h4>
                                        <p className="card-text">{item.description}</p>
                                        <h5 className="text-primary">${item.price}</h5>
                                    </div>
                                </div>
                                <div className="col-md-4 text-center">
                                    <button
                                        className="btn btn-danger"
                                        onClick={() => removeFromCart(item.id)}
                                    >
                                        Remove Product
                                    </button>
                                </div>
                            </div>
                        ))}

                        <div className="row mt-4">
                            <div className="col-md-12">
                                <div className="card p-3">
                                    <h4>Total: <span className="text-primary">${getTotal().toFixed(2)}</span></h4>
                                </div>
                            </div>
                        </div>

                        <div className="mt-4">
                            <Link to="/checkout" className="btn btn-success btn-lg">Order Now</Link>
                        </div>
                    </>
                )}
            </div>
        </Layout>
    )
}

export default CartPage
