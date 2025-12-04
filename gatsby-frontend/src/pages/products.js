import React, { useState, useEffect } from "react"
import { Link } from "gatsby"
import Layout from "../components/layout"
import Seo from "../components/seo"

const API_URL = "http://127.0.0.1:8000"

const ProductsPage = () => {
    const [products, setProducts] = useState([])
    const [loading, setLoading] = useState(true)

    useEffect(() => {
        fetchProducts()
    }, [])

    const fetchProducts = async () => {
        try {
            const response = await fetch(`${API_URL}/api/products`)
            const data = await response.json()
            setProducts(data.products || [])
        } catch (error) {
            console.error("Error fetching products:", error)
        } finally {
            setLoading(false)
        }
    }

    const renderImage = (gallery) => {
        if (!gallery) return "https://via.placeholder.com/300"
        if (gallery.startsWith('http')) return gallery
        return `${API_URL}/assets/images/${gallery}`
    }

    return (
        <Layout>
            <Seo title="All Products" />

            <div className="container-fluid py-5">
                <h2 className="text-center text-secondary mb-4">All Products</h2>

                {loading ? (
                    <div className="text-center">
                        <div className="spinner-border text-primary" role="status">
                            <span className="visually-hidden">Loading...</span>
                        </div>
                    </div>
                ) : (
                    <div className="row justify-content-center px-4">
                        {products.map(product => (
                            <div className="col-md-3 my-4" key={product.id}>
                                <Link to={`/product/${product.id}`} style={{ textDecoration: "none" }}>
                                    <div className="card shadow rounded h-100" style={{ backgroundColor: "transparent", borderColor: "rgb(230, 230, 248)" }}>
                                        <div className="image-hover">
                                            <img
                                                className="card-img-top rounded"
                                                src={renderImage(product.gallery)}
                                                alt={product.name}
                                                style={{ height: "250px", objectFit: "cover" }}
                                            />
                                        </div>
                                        <div className="card-body">
                                            <h5 className="card-title text-secondary">{product.name}</h5>
                                            <h6 className="text-danger">${product.price}</h6>
                                            <p className="card-text text-muted">{product.description}</p>
                                        </div>
                                    </div>
                                </Link>
                            </div>
                        ))}
                    </div>
                )}
            </div>
        </Layout>
    )
}

export default ProductsPage
