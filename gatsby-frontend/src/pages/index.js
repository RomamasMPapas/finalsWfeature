import React, { useState, useEffect } from "react"
import { Link } from "gatsby"
import Layout from "../components/layout"
import Seo from "../components/seo"
import { Carousel } from "react-bootstrap"

const API_URL = "https://finalswfeature.onrender.com"

const IndexPage = () => {
  const [products, setProducts] = useState([])
  const [popularProducts, setPopularProducts] = useState([])
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    fetchProducts()
  }, [])

  const fetchProducts = async () => {
    try {
      const response = await fetch(`${API_URL}/api/products`)
      const data = await response.json()
      setProducts(data.products || [])
      setPopularProducts(data.popular_products || [])
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
      <Seo title="Home" />

      <div className="container mt-3 mb-5">
        <div className="jumbotron bg-light p-5 rounded mb-4">
          <h1 className="display-4">Welcome to Our E-Commerce Store!</h1>
          <p className="lead">Discover the best products at unbeatable prices. Shop now and enjoy exclusive deals.</p>
          <hr className="my-4" />
          <p>Explore our wide range of categories and find exactly what you're looking for.</p>
        </div>

        {/* Carousel */}
        {products.length > 0 && (
          <Carousel>
            {products.slice(0, 4).map((product, index) => (
              <Carousel.Item key={product.id}>
                <Link to={`/product/${product.id}`}>
                  <img
                    className="d-block w-100"
                    src={renderImage(product.gallery)}
                    alt={product.name}
                    style={{ height: "320px", objectFit: "contain", backgroundColor: "#333" }}
                  />
                  <Carousel.Caption>
                    <h3 className="text-primary">{product.name}</h3>
                    <p>{product.description}</p>
                  </Carousel.Caption>
                </Link>
              </Carousel.Item>
            ))}
          </Carousel>
        )}
      </div>

      {/* Trending Products */}
      <div className="container-fluid mt-5 pt-5">
        <h4 className="text-center text-secondary my-4">Trending Products</h4>
        <div className="row justify-content-center px-4">
          {loading ? (
            <p>Loading products...</p>
          ) : (
            products.map(product => (
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
            ))
          )}
        </div>
      </div>

      {/* Popular Products */}
      <div className="container-fluid mt-5">
        <h4 className="text-center text-secondary my-4">Popular Products</h4>
        <div className="row justify-content-center px-4">
          {popularProducts.map(product => (
            <div className="col-md-4 my-4" key={product.id}>
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
                    <p className="card-text text-muted">
                      {product.description?.length > 50
                        ? product.description.substring(0, 50) + "..."
                        : product.description}
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

export default IndexPage
