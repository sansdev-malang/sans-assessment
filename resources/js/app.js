import { createIcons, icons } from 'lucide';
import { initThreeHero } from './three-hero';

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
});

// Expose helper to refresh icons on any dynamic tab/dom change
window.refreshLucideIcons = function () {
    try {
        createIcons({ icons });
    } catch (e) {
        console.warn(e);
    }
};
