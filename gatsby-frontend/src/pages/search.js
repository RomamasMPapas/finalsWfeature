import React, { useState, useEffect } from "react"
import { Link } from "gatsby"
import Layout from "../components/layout"
import Seo from "../components/seo"

const API_URL = "https://finalswfeature.onrender.com"

const SearchPage = ({ location }) => {
    const [products, setProducts] = useState([])
    const [loading, setLoading] = useState(true)
    const [searchQuery, setSearchQuery] = useState("")

    useEffect(() => {
        const params = new URLSearchParams(location.search)
        const query = params.get("q") || ""
        setSearchQuery(query)

        if (query) {
            searchProducts(query)
        } else {
            setLoading(false)
        }
    }, [location.search])

    const searchProducts = async (query) => {
        try {
            const response = await fetch(`${API_URL}/api/products`)
            const data = await response.json()
            const allProducts = data.products || []

            // Filter products based on search query
            const filtered = allProducts.filter(product =>
                product.name.toLowerCase().includes(query.toLowerCase()) ||
                product.description.toLowerCase().includes(query.toLowerCase()) ||
                product.category?.toLowerCase().includes(query.toLowerCase())
            )

            setProducts(filtered)
        } catch (error) {
            console.error("Error searching products:", error)
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
            <Seo title={`Search: ${searchQuery}`} />

            <div className="container-fluid py-5">
                <h4 className="text-center text-secondary mb-4">
                    Search Results for: <span className="text-primary">"{searchQuery}"</span>
                </h4>

                {loading ? (
                    <div className="text-center">
                        <div className="spinner-border text-primary" role="status">
                            <span className="visually-hidden">Loading...</span>
                        </div>
                    </div>
                ) : products.length > 0 ? (
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
                                            <p className="card-text text-muted">{product.description}</p>
                                        </div>
                                    </div>
                                </Link>
                            </div>
                        ))}
                    </div>
                ) : (
                    <div className="text-center py-5">
                        <i className="fas fa-search" style={{ fontSize: "5rem", color: "#ccc" }}></i>
                        <h4 className="mt-3 text-muted">No products found</h4>
                        <p className="text-muted">Try a different search term</p>
                        <Link to="/products" className="btn btn-primary mt-3">Browse All Products</Link>
                    </div>
                )}
            </div>
        </Layout>
    )
}

export default SearchPage
