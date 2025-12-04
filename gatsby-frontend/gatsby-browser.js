/**
 * Implement Gatsby's Browser APIs in this file.
 *
 * See: https://www.gatsbyjs.com/docs/reference/config-files/gatsby-browser/
 */

import "bootstrap/dist/css/bootstrap.min.css"
import "./src/styles/custom.css"

// Load Bootstrap JS for dropdowns, modals, etc.
export const onClientEntry = () => {
    if (typeof window !== "undefined") {
        require("bootstrap/dist/js/bootstrap.bundle.min.js")
    }
}
