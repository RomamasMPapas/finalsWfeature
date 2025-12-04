import * as React from "react"
import Header from "./header"
import Footer from "./footer"

const Layout = ({ children }) => {
  return (
    <>
      <Header />
      <main style={{ minHeight: "calc(100vh - 200px)" }}>
        {children}
      </main>
      <Footer />
    </>
  )
}

export default Layout
