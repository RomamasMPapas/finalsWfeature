import React from "react"
import Layout from "../components/layout"
import Seo from "../components/seo"

const ShopPage = () => {
    // Placeholder products - in a real app, you'd fetch these from your Laravel API
    const products = [
        { id: 1, name: "Smartphone X", price: "$699", image: "📱" },
        { id: 2, name: "Laptop Pro", price: "$1299", image: "💻" },
        { id: 3, name: "Wireless Headphones", price: "$199", image: "🎧" },
        { id: 4, name: "Smart Watch", price: "$399", image: "⌚" },
        { id: 5, name: "Tablet Ultra", price: "$599", image: "📱" },
        { id: 6, name: "Gaming Console", price: "$499", image: "🎮" },
    ]

    return (
        <Layout>
            <Seo title="Shop" />
            <div style={{ padding: "2rem", maxWidth: "1200px", margin: "0 auto" }}>
                <h1 style={{ textAlign: "center", marginBottom: "2rem" }}>Shop Our Products</h1>

                <div style={{
                    display: "grid",
                    gridTemplateColumns: "repeat(auto-fill, minmax(250px, 1fr))",
                    gap: "2rem"
                }}>
                    {products.map(product => (
                        <div key={product.id} style={{
                            border: "1px solid #ddd",
                            borderRadius: "8px",
                            padding: "1.5rem",
                            textAlign: "center",
                            boxShadow: "0 2px 4px rgba(0,0,0,0.1)",
                            transition: "transform 0.2s",
                            cursor: "pointer"
                        }}
                            onMouseEnter={(e) => e.currentTarget.style.transform = "translateY(-5px)"}
                            onMouseLeave={(e) => e.currentTarget.style.transform = "translateY(0)"}
                        >
                            <div style={{ fontSize: "4rem", marginBottom: "1rem" }}>
                                {product.image}
                            </div>
                            <h3 style={{ marginBottom: "0.5rem" }}>{product.name}</h3>
                            <p style={{ color: "#663399", fontWeight: "bold", fontSize: "1.2rem" }}>
                                {product.price}
                            </p>
                            <button style={{
                                marginTop: "1rem",
                                padding: "0.5rem 1.5rem",
                                backgroundColor: "#663399",
                                color: "white",
                                border: "none",
                                borderRadius: "4px",
                                cursor: "pointer",
                                fontWeight: "bold"
                            }}>
                                Add to Cart
                            </button>
                        </div>
                    ))}
                </div>
            </div>
        </Layout>
    )
}

export default ShopPage
