import React, { useState, useEffect } from "react"
import Layout from "../components/layout"
import Seo from "../components/seo"
import { Link } from "gatsby"
import { Carousel } from "react-bootstrap"

const ShopPage = () => {
    const [products, setProducts] = useState([])
    const [popularProducts, setPopularProducts] = useState([])
    const [loading, setLoading] = useState(true)

    useEffect(() => {
        const fetchProducts = async () => {
            try {
                // Fetch from your Render Backend
                const response = await fetch("https://finalswfeature.onrender.com/api/products")
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                const data = await response.json()

                // Assuming the API returns { products: [...], popular_products: [...] }
                setProducts(data.products || [])
                setPopularProducts(data.popular_products || [])
                setLoading(false)
            } catch (error) {
                console.error("Error fetching products:", error)
                setLoading(false)
            }
        }

        fetchProducts()
    }, [])

    // Fallback if API fails or is empty (for testing UI)
    const displayProducts = products.length > 0 ? products : [
        { id: 1, name: "Smartphone X", description: "Latest model with high res camera", gallery: "mobile.jpg" },
        { id: 2, name: "Laptop Pro", description: "High performance for professionals", gallery: "laptop.jpg" },
        { id: 3, name: "Smart TV", description: "4K Ultra HD Smart TV", gallery: "tv.jpg" },
        { id: 4, name: "Headphones", description: "Noise cancelling wireless headphones", gallery: "headphone.jpg" },
    ]

    const displayPopular = popularProducts.length > 0 ? popularProducts : displayProducts.slice(0, 3)

    const renderImage = (gallery) => {
        // If it's a full URL, use it. Otherwise, assume it's on the Render server.
        if (gallery.startsWith('http')) return gallery;
        return `https://finalswfeature.onrender.com/assets/images/${gallery}`;
    }

    return (
        <Layout>
            <Seo title="Home" />

            <div className="container mt-3 mb-5">
                <div className="jumbotron bg-light p-5 rounded mb-4">
                    <h1 className="display-4">Welcome to Our E-Commerce Store!</h1>
                    <p className="lead">Discover the best products at unbeatable prices. Shop now and enjoy exclusive deals.</p>
                    <hr className="my-4" />
                    <p>Explore our wide range of categories and find exactly what you're looking for.</p>
                </div>

                {/* Carousel */}
                <Carousel id="carouselId">
                    {displayProducts.slice(0, 4).map((product, index) => (
                        <Carousel.Item key={product.id} className={index === 0 ? "active" : ""}>
                            <Link to={`/product/${product.id}`}>
                                <img
                                    className="d-block w-100 product_images"
                                    src={renderImage(product.gallery)}
                                    alt={product.name}
                                    style={{ height: "320px", objectFit: "contain", backgroundColor: "#333" }}
                                />
                                <Carousel.Caption className="d-none d-md-block">
                                    <h3 className="text-primary">{product.name}</h3>
                                    <p className="text-white">{product.description}</p>
                                </Carousel.Caption>
                            </Link>
                        </Carousel.Item>
                    ))}
                </Carousel>
            </div>

            {/* Trending Products */}
            <div className="container-fluid mt-5 pt-5 trending">
                <h4 className="text-center text-secondary my-4">Trending Product</h4>
                <div className="row justify-content-center">
                    {displayProducts.map(product => (
                        <div className="col-md-3 my-4" key={product.id}>
                            <Link to={`/product/${product.id}`}>
                                <div className="card shadow rounded" style={{ backgroundColor: "transparent", borderColor: "rgb(230, 230, 248)" }}>
                                    <div className="image-hover">
                                        <img
                                            className="card-img-top rounded trending-image"
                                            src={renderImage(product.gallery)}
                                            alt={product.name}
                                        />
                                    </div>
                                    <div className="card-body">
                                        <h4 className="card-title text-secondary">{product.name}</h4>
                                        <p className="card-text">{product.description}</p>
                                    </div>
                                </div>
                            </Link>
                        </div>
                    ))}
                </div>
            </div>

            {/* Popular Products */}
            <div className="container-fluid mt-5 popular">
                <h4 className="text-center text-secondary my-4">Popular Products</h4>
                <div className="row justify-content-center">
                    {displayPopular.map(product => (
                        <div className="col-md-4 my-4" key={product.id}>
                            <Link to={`/product/${product.id}`}>
                                <div className="card shadow rounded" style={{ backgroundColor: "transparent", borderColor: "rgb(230, 230, 248)" }}>
                                    <div className="image-hover">
                                        <img
                                            className="card-img-top rounded trending-image"
                                            src={renderImage(product.gallery)}
                                            alt={product.name}
                                        />
                                    </div>
                                    <div className="card-body">
                                        <h4 className="card-title text-secondary">{product.name}</h4>
                                        <p className="card-text">
                                            {product.description.length > 50 ? product.description.substring(0, 50) + "..." : product.description}
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

export default ShopPage
