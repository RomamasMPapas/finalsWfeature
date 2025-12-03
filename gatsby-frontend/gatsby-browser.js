/**
 * Implement Gatsby's Browser APIs in this file.
 *
 * See: https://www.gatsbyjs.com/docs/reference/config-files/gatsby-browser/
 */

// You can delete this file if you're not using it
// gatsby-browser.js
// This file runs on every client page load

// Import the Firebase app we created in src/firebase.js
import { firebaseApp, analytics } from "./src/firebase";

// You can now use `firebaseApp` or `analytics` anywhere in your components
// (e.g., for logging events, auth, etc.)