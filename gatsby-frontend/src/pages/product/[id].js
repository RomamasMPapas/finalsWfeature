import React, { useState, useEffect } from "react"
import { Link, navigate } from "gatsby"
import Layout from "../../components/layout"
import Seo from "../../components/seo"

const API_URL = "http://127.0.0.1:8000"

const ProductPage = ({ params }) => {
    const [product, setProduct] = useState(null)
    const [relatedProducts, setRelatedProducts] = useState([])
    const [loading, setLoading] = useState(true)
    const [user, setUser] = useState(null)

    const productId = params?.id || (typeof window !== 'undefined' ? window.location.pathname.split('/').pop() : null)

    useEffect(() => {
        const userData = localStorage.getItem("user")
        if (userData) {
            setUser(JSON.parse(userData))
        }

        if (productId) {
            fetchProduct()
        }
    }, [productId])

    const fetchProduct = async () => {
        try {
            const response = await fetch(`${API_URL}/api/products`)
            const data = await response.json()
            const allProducts = data.products || []

            const foundProduct = allProducts.find(p => p.id.toString() === productId.toString())
            setProduct(foundProduct)

            // Get related products (same category or random)
            const related = allProducts
                .filter(p => p.id !== foundProduct?.id)
                .slice(0, 4)
            setRelatedProducts(related)
        } catch (error) {
            console.error("Error fetching product:", error)
        } finally {
            setLoading(false)
        }
    }

    const renderImage = (gallery) => {
        if (!gallery) return "https://via.placeholder.com/400"
        if (gallery.startsWith('http')) return gallery
        return `${API_URL}/assets/images/${gallery}`
    }

    const addToCart = () => {
        if (!user) {
            navigate("/login")
            return
        }

        const cart = JSON.parse(localStorage.getItem("cart") || "[]")

        // Check if already in cart
        if (!cart.find(item => item.id === product.id)) {
            cart.push(product)
            localStorage.setItem("cart", JSON.stringify(cart))
            alert("Product added to cart!")
            window.location.reload() // Refresh to update cart count
        } else {
            alert("Product is already in your cart")
        }
    }

    if (loading) {
        return (
            <Layout>
                <div className="container py-5 text-center">
                    <div className="spinner-border text-primary" role="status">
                        <span className="visually-hidden">Loading...</span>
                    </div>
                </div>
            </Layout>
        )
    }

    if (!product) {
        return (
            <Layout>
                <div className="container py-5 text-center">
                    <h3>Product not found</h3>
                    <Link to="/products" className="btn btn-primary mt-3">Browse Products</Link>
                </div>
            </Layout>
        )
    }

    return (
        <Layout>
            <Seo title={product.name} />

            <div className="container mt-4 mb-5">
                <div className="row">
                    <div className="col-md-5 my-4">
                        <div className="shadow-lg rounded bg-secondary p-3">
                            <img
                                className="card-img-top shadow rounded"
                                src={renderImage(product.gallery)}
                                alt={product.name}
                                style={{ objectFit: "cover", height: "355px", minHeight: "280px" }}
                            />
                        </div>
                    </div>
                    <div className="col-md-6 my-4">
                        <div className="card-body shadow-lg rounded p-4">
                            <Link to="/" className="text-primary">
                                Continue Shopping <i className="fa fa-arrow-right"></i>
                            </Link>
                            <h4 className="card-title text-secondary mt-3">{product.name}</h4>
                            <h5 className="text-secondary">
                                Price: <span className="badge bg-danger">${product.price}</span>
                            </h5>
                            <h5 className="text-secondary">Category: {product.category}</h5>
                            <p className="card-text text-secondary">Description: {product.description}</p>

                            <br /><br />

                            <button
                                className="btn btn-warning my-3"
                                onClick={addToCart}
                            >
                                Add to Cart
                            </button>
                            <br />
                            <Link to="/checkout" className="btn btn-success">
                                Proceed to Checkout
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            {/* Related Products */}
            <div className="container mt-5">
                <div className="row">
                    <div className="col-12">
                        <h3 className="text-secondary">Related Products</h3>
                    </div>
                    {relatedProducts.map(relProduct => (
                        <div className="col-md-3 my-4" key={relProduct.id}>
                            <Link to={`/product/${relProduct.id}`} style={{ textDecoration: "none" }}>
                                <div className="card shadow rounded" style={{ backgroundColor: "transparent", borderColor: "rgb(230, 230, 248)" }}>
                                    <div className="image-hover">
                                        <img
                                            className="card-img-top rounded"
                                            src={renderImage(relProduct.gallery)}
                                            alt={relProduct.name}
                                            style={{ height: "250px", objectFit: "cover" }}
                                        />
                                    </div>
                                    <div className="card-body">
                                        <h5 className="card-title text-secondary">{relProduct.name}</h5>
                                        <p className="card-text text-muted">
                                            {relProduct.description?.length > 50
                                                ? relProduct.description.substring(0, 50) + "..."
                                                : relProduct.description}
                                        </p>
                                    </div>
                                </div>
                            </Link>
                        </div>
                    ))}
                </div>
            </div>
        </Layout>
    )
}

export default ProductPage
