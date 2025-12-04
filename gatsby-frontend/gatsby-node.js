/**
 * Implement Gatsby's Node APIs in this file.
 *
 * See: https://www.gatsbyjs.com/docs/reference/config-files/gatsby-node/
 */

// No build-time data fetching - all data is fetched client-side
exports.createPages = async ({ actions }) => {
  // Empty - we use client-side routing with file-based pages
}

// Prevent build failures from missing images
exports.onCreateWebpackConfig = ({ actions }) => {
  actions.setWebpackConfig({
    resolve: {
      fallback: {
        fs: false,
        path: false,
      },
    },
  })
}
