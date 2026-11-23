import { createIcons, icons } from 'lucide';
import { initThreeHero } from './three-hero';
import { overrideNativeAlert, initThreeAlertBanners, show3DAlert } from './three-alert';

// Override window.alert default browser dengan Three.js 3D alert
overrideNativeAlert();

document.addEventListener('DOMContentLoaded', () => {
    // 1. Initialize Lucide Icons
    try {
        createIcons({ icons });
    } catch (e) {
        console.warn('Lucide icons initialization error:', e);
    }

    // 2. Initialize Three.js Minimalist Wireframe
    try {
        initThreeHero();
    } catch (e) {
        console.warn('Three.js could not initialize:', e);
    }

    // 3. Initialize Three.js interactive 3D on flash alert banners
    try {
        initThreeAlertBanners();
    } catch (e) {
        console.warn('Three.js alert banners error:', e);
    }
});

// Expose helper and window.lucide to refresh icons on any dynamic tab/dom change
window.lucide = {
    createIcons: function (opts) {
        try {
            createIcons(opts || { icons });
        } catch (e) {
            console.warn(e);
        }
    }
};

window.refreshLucideIcons = function () {
    try {
        createIcons({ icons });
    } catch (e) {
        console.warn(e);
    }
};
