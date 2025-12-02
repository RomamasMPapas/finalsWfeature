// Firebase initialization for Gatsby front‑end (optional analytics / auth)
import { initializeApp } from "firebase/app";
import { getAnalytics } from "firebase/analytics";

// Your Firebase configuration – replace with your own values if needed
const firebaseConfig = {
    apiKey: "AIzaSyARF41HRTvpLufwXg_Vv2VLUVW_zTjT-1g",
    authDomain: "finalswfeature.firebaseapp.com",
    projectId: "finalswfeature",
    storageBucket: "finalswfeature.firebasestorage.app",
    messagingSenderId: "43570287430",
    appId: "1:43570287430:web:eeebebbf060140c1e34b47",
    measurementId: "G-4HKCX8Z8YX"
};

// Initialize Firebase
export const firebaseApp = initializeApp(firebaseConfig);
export const analytics = getAnalytics(firebaseApp);
