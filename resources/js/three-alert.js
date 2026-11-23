import * as THREE from 'three';

/**
 * Three.js 3D Alert System
 * Menyediakan modal alert 3D interaktif dengan animasi muncul (entrance) dan keluar (exit),
 * serta mini 3D canvas interaktif untuk banner notifikasi flash.
 */

let activeAlertInstance = null;

/**
 * Tampilkan dialog alert 3D Three.js dengan animasi muncul & keluar
 *
 * @param {string} message
 * @param {string|object} optionsOrType 'success' | 'error' | 'warning' | 'info' atau { type, title, btnText }
 * @returns {Promise<void>}
 */
export function show3DAlert(message, optionsOrType = 'info') {
    return new Promise((resolve) => {
        let type = 'info';
        let title = '';
        let btnText = 'Mengerti';

        if (typeof optionsOrType === 'string') {
            type = optionsOrType;
        } else if (typeof optionsOrType === 'object' && optionsOrType !== null) {
            type = optionsOrType.type || 'info';
            title = optionsOrType.title || '';
            btnText = optionsOrType.btnText || 'Mengerti';
        }

        // Deteksi tipe otomatis dari teks jika masih default info
        const lowerMsg = String(message).toLowerCase();
        if (type === 'info') {
            if (lowerMsg.includes('berhasil') || lowerMsg.includes('alhamdulillah') || lowerMsg.includes('sukses') || lowerMsg.includes('selesai')) {
                type = 'success';
            } else if (lowerMsg.includes('gagal') || lowerMsg.includes('error') || lowerMsg.includes('salah')) {
                type = 'error';
            } else if (lowerMsg.includes('perhatian') || lowerMsg.includes('peringatan') || lowerMsg.includes('terkunci') || lowerMsg.includes('mohon lengkapi') || lowerMsg.includes('belum')) {
                type = 'warning';
            }
        }

        if (!title) {
            if (type === 'success') title = 'Berhasil';
            else if (type === 'error') title = 'Terjadi Kesalahan';
            else if (type === 'warning') title = 'Perhatian';
            else title = 'Pemberitahuan';
        }

        // Tutup alert sebelumnya jika masih terbuka
        if (activeAlertInstance) {
            activeAlertInstance.close();
        }

        // 1. Buat Container Modal (state awal opacity-0 untuk animasi entrance)
        const modal = document.createElement('div');
        modal.id = 'three-alert-modal';
        modal.className = 'fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-black/75 backdrop-blur-md opacity-0 transition-opacity duration-250 ease-out select-none';

        const colorMap = {
            success: {
                primary: 0x10b981, // emerald-500
                glow: 0x34d399,
                badgeBg: 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                btnBg: 'bg-emerald-600 hover:bg-emerald-500 text-white shadow-emerald-950/50',
                border: 'border-emerald-800/60',
            },
            error: {
                primary: 0xef4444, // rose-500
                glow: 0xf87171,
                badgeBg: 'bg-rose-500/10 text-rose-400 border-rose-500/20',
                btnBg: 'bg-rose-600 hover:bg-rose-500 text-white shadow-rose-950/50',
                border: 'border-rose-800/60',
            },
            warning: {
                primary: 0xf59e0b, // amber-500
                glow: 0xfbbf24,
                badgeBg: 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                btnBg: 'bg-amber-600 hover:bg-amber-500 text-white shadow-amber-950/50',
                border: 'border-amber-800/60',
            },
            info: {
                primary: 0x6366f1, // indigo-500
                glow: 0x818cf8,
                badgeBg: 'bg-indigo-500/10 text-indigo-400 border-indigo-500/20',
                btnBg: 'bg-indigo-600 hover:bg-indigo-500 text-white shadow-indigo-950/50',
                border: 'border-indigo-800/60',
            },
        };

        const config = colorMap[type] || colorMap.info;

        // Card dengan state awal transform scale-80 translate-y-5 opacity-0 untuk animasi spring masuk
        modal.innerHTML = `
            <div id="three-alert-card" class="relative w-full max-w-sm rounded-2xl border ${config.border} bg-card text-foreground shadow-2xl p-6 flex flex-col items-center text-center overflow-hidden transition-all duration-300 ease-[cubic-bezier(0.34,1.56,0.64,1)] transform scale-75 translate-y-6 opacity-0">
                <!-- 3D Canvas Box -->
                <div id="three-alert-canvas-box" class="w-36 h-36 relative flex items-center justify-center cursor-grab active:cursor-grabbing"></div>
                
                <!-- Type Badge -->
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold border ${config.badgeBg} mb-2 uppercase tracking-wider font-mono">
                    ${type}
                </span>

                <!-- Title & Message -->
                <h3 class="text-base font-bold text-foreground tracking-tight">${title}</h3>
                <p class="text-xs text-muted-foreground mt-2 leading-relaxed max-h-48 overflow-y-auto px-1">${message}</p>

                <!-- Action Button -->
                <div class="mt-6 w-full flex items-center justify-center">
                    <button id="three-alert-btn" type="button" class="inline-flex h-9 w-full items-center justify-center rounded-xl ${config.btnBg} px-4 text-xs font-semibold shadow-md hover:scale-[1.02] active:scale-[0.98] transition-all cursor-pointer">
                        <span>${btnText}</span>
                    </button>
                </div>
            </div>
        `;

        document.body.appendChild(modal);

        // Memicu animasi CSS muncul (entrance transition)
        requestAnimationFrame(() => {
            modal.classList.remove('opacity-0');
            modal.classList.add('opacity-100');
            const card = modal.querySelector('#three-alert-card');
            if (card) {
                card.classList.remove('scale-75', 'translate-y-6', 'opacity-0');
                card.classList.add('scale-100', 'translate-y-0', 'opacity-100');
            }
        });

        // 2. Setup Three.js Scene di dalam canvas box
        const canvasContainer = modal.querySelector('#three-alert-canvas-box');
        const width = 144;
        const height = 144;

        const scene = new THREE.Scene();
        const camera = new THREE.PerspectiveCamera(45, width / height, 0.1, 100);
        camera.position.set(0, 0, 4.5);

        const renderer = new THREE.WebGLRenderer({
            antialias: true,
            alpha: true,
            powerPreference: 'high-performance',
        });
        renderer.setSize(width, height);
        renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
        canvasContainer.appendChild(renderer.domElement);

        // Lighting
        const ambientLight = new THREE.AmbientLight(0xffffff, 1.2);
        scene.add(ambientLight);

        const dirLight = new THREE.DirectionalLight(0xffffff, 2.5);
        dirLight.position.set(3, 4, 5);
        scene.add(dirLight);

        const pointLight = new THREE.PointLight(config.primary, 3, 10);
        pointLight.position.set(0, 0, 2);
        scene.add(pointLight);

        // Master 3D Group
        const group = new THREE.Group();
        // Mulai dari skala 0 untuk animasi 3D pop-in
        group.scale.set(0.001, 0.001, 0.001);
        group.rotation.y = -Math.PI * 1.5;
        scene.add(group);

        const geometries = [];
        const materials = [];

        // Geometri utama berdasarkan tipe alert
        let mainGeom;
        if (type === 'success') {
            mainGeom = new THREE.DodecahedronGeometry(1.0, 0); // Gem dodecahedron
        } else if (type === 'error') {
            mainGeom = new THREE.OctahedronGeometry(1.0, 0); // Ruby octahedron
        } else if (type === 'warning') {
            mainGeom = new THREE.TetrahedronGeometry(1.1, 0); // Warning pyramid
        } else {
            mainGeom = new THREE.IcosahedronGeometry(1.0, 0); // Futuristic icosahedron
        }
        geometries.push(mainGeom);

        // Material utama solid dengan kilauan
        const mainMat = new THREE.MeshStandardMaterial({
            color: config.primary,
            roughness: 0.15,
            metalness: 0.85,
            wireframe: false,
        });
        materials.push(mainMat);
        const mainMesh = new THREE.Mesh(mainGeom, mainMat);
        group.add(mainMesh);

        // Wireframe cage di luar untuk estetika modern
        const wireMat = new THREE.MeshBasicMaterial({
            color: config.glow,
            wireframe: true,
            transparent: true,
            opacity: 0.45,
        });
        materials.push(wireMat);
        const wireMesh = new THREE.Mesh(mainGeom, wireMat);
        wireMesh.scale.set(1.15, 1.15, 1.15);
        group.add(wireMesh);

        // Orbital Ring
        const ringGeom = new THREE.TorusGeometry(1.4, 0.02, 16, 64);
        geometries.push(ringGeom);
        const ringMat = new THREE.MeshStandardMaterial({
            color: config.glow,
            emissive: config.primary,
            emissiveIntensity: 0.5,
            roughness: 0.3,
            metalness: 0.8,
        });
        materials.push(ringMat);
        const ring = new THREE.Mesh(ringGeom, ringMat);
        ring.rotation.x = Math.PI / 3;
        group.add(ring);

        // Floating particles
        const particleCount = 28;
        const particleGeom = new THREE.BufferGeometry();
        const positions = new Float32Array(particleCount * 3);
        for (let i = 0; i < particleCount * 3; i += 3) {
            const radius = 1.3 + Math.random() * 0.7;
            const theta = Math.random() * Math.PI * 2;
            const phi = (Math.random() - 0.5) * Math.PI;
            positions[i] = radius * Math.cos(theta) * Math.cos(phi);
            positions[i + 1] = radius * Math.sin(phi);
            positions[i + 2] = radius * Math.sin(theta) * Math.cos(phi);
        }
        particleGeom.setAttribute('position', new THREE.BufferAttribute(positions, 3));
        geometries.push(particleGeom);

        const particleMat = new THREE.PointsMaterial({
            color: config.glow,
            size: 0.06,
            transparent: true,
            opacity: 0.8,
        });
        materials.push(particleMat);
        const particles = new THREE.Points(particleGeom, particleMat);
        group.add(particles);

        // Parallax cursor effect
        let mouseX = 0;
        let mouseY = 0;
        const onMouseMove = (e) => {
            const rect = canvasContainer.getBoundingClientRect();
            mouseX = ((e.clientX - rect.left) / width - 0.5) * 2;
            mouseY = ((e.clientY - rect.top) / height - 0.5) * 2;
        };
        window.addEventListener('mousemove', onMouseMove);

        // Animation Loop with Entrance Spring & Exit Shrink
        let animId;
        let clock = new THREE.Clock();
        let entranceProgress = 0;
        let isEntering = true;
        let isClosing = false;

        const animate = () => {
            animId = requestAnimationFrame(animate);
            const delta = clock.getDelta();
            const elapsed = clock.getElapsedTime();

            if (isEntering) {
                // Animasi 3D muncul dengan efek elastis / spring burst
                entranceProgress += delta * 3.2; // selesai dalam ~0.3 detik
                if (entranceProgress >= 1) {
                    entranceProgress = 1;
                    isEntering = false;
                    group.scale.set(1, 1, 1);
                } else {
                    const t = entranceProgress;
                    // Ease out overshoot: melampaui ukuran 1.0 sedikit lalu menstabilkan
                    const overshoot = 1 + Math.sin(t * Math.PI) * 0.22;
                    const s = Math.sin((t * Math.PI) / 2) * overshoot;
                    group.scale.set(s, s, s);
                    group.rotation.y += delta * 6 * (1 - t);
                }
            } else if (isClosing) {
                // Animasi 3D keluar: mengecil cepat sambil berputar kencang
                group.scale.multiplyScalar(0.78);
                group.rotation.y += delta * 12;
            } else {
                // Animasi idle berputar lembut
                group.rotation.y += delta * 0.9;
                group.rotation.x = Math.sin(elapsed * 1.5) * 0.2 + (mouseY * 0.3);
                group.rotation.z = Math.cos(elapsed * 1.2) * 0.15;

                // Parallax shift
                group.position.x += (mouseX * 0.2 - group.position.x) * 0.1;
                group.position.y += (-mouseY * 0.2 - group.position.y) * 0.1;

                // Efek denyut halus
                const pulse = 1 + Math.sin(elapsed * 3) * 0.04;
                mainMesh.scale.set(pulse, pulse, pulse);
            }

            ring.rotation.z += delta * 1.4;
            particles.rotation.y -= delta * 0.5;

            renderer.render(scene, camera);
        };
        animate();

        // Cleanup & Close Handler dengan Animasi Keluar
        let isClosed = false;
        const close = () => {
            if (isClosed) return;
            isClosed = true;
            isClosing = true;

            window.removeEventListener('mousemove', onMouseMove);
            window.removeEventListener('keydown', onKeyDown);

            // Memicu animasi CSS keluar
            modal.classList.remove('opacity-100');
            modal.classList.add('opacity-0');
            const card = modal.querySelector('#three-alert-card');
            if (card) {
                card.classList.remove('scale-100', 'translate-y-0', 'opacity-100');
                card.classList.add('scale-75', 'translate-y-4', 'opacity-0');
            }

            // Tunggu transisi selesai sebelum menghapus DOM
            setTimeout(() => {
                cancelAnimationFrame(animId);

                geometries.forEach((g) => g.dispose());
                materials.forEach((m) => m.dispose());
                renderer.dispose();

                modal.remove();
                activeAlertInstance = null;
                resolve();
            }, 240);
        };

        const onKeyDown = (e) => {
            if (e.key === 'Escape' || e.key === 'Enter') {
                close();
            }
        };
        window.addEventListener('keydown', onKeyDown);

        const btn = modal.querySelector('#three-alert-btn');
        btn.addEventListener('click', close);

        // Klik backdrop untuk menutup
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                close();
            }
        });

        activeAlertInstance = { close };
    });
}

/**
 * Pasang mini 3D canvas interaktif pada flash alert banners yang ada di halaman.
 */
export function initThreeAlertBanners() {
    const banners = document.querySelectorAll('[data-three-alert]');
    banners.forEach((banner) => {
        if (banner.dataset.threeInitialized) return;
        banner.dataset.threeInitialized = 'true';

        // Berikan animasi entrance halus pada container banner
        banner.classList.add('transition-all', 'duration-300', 'ease-out');

        const type = banner.getAttribute('data-three-alert') || 'success';
        const color = type === 'error' ? 0xef4444 : (type === 'warning' ? 0xf59e0b : 0x10b981);

        const box = document.createElement('div');
        box.className = 'w-9 h-9 shrink-0 flex items-center justify-center relative cursor-pointer';
        banner.prepend(box);

        const scene = new THREE.Scene();
        const camera = new THREE.PerspectiveCamera(45, 1, 0.1, 50);
        camera.position.z = 3.2;

        const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
        renderer.setSize(36, 36);
        renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
        box.appendChild(renderer.domElement);

        const light = new THREE.DirectionalLight(0xffffff, 2.5);
        light.position.set(2, 2, 3);
        scene.add(light);
        scene.add(new THREE.AmbientLight(0xffffff, 1.2));

        const geom = type === 'error'
            ? new THREE.OctahedronGeometry(0.8)
            : (type === 'warning' ? new THREE.TetrahedronGeometry(0.8) : new THREE.DodecahedronGeometry(0.8));
        const mat = new THREE.MeshStandardMaterial({
            color: color,
            metalness: 0.8,
            roughness: 0.2,
        });
        const mesh = new THREE.Mesh(geom, mat);
        scene.add(mesh);

        const wireMat = new THREE.MeshBasicMaterial({ color: 0xffffff, wireframe: true, transparent: true, opacity: 0.4 });
        const wire = new THREE.Mesh(geom, wireMat);
        wire.scale.set(1.1, 1.1, 1.1);
        scene.add(wire);

        // Mulai dari skala 0 untuk animasi masuk
        mesh.scale.set(0.001, 0.001, 0.001);
        wire.scale.set(0.001, 0.001, 0.001);

        let animId;
        let bannerScale = 0;
        let isHovered = false;

        box.addEventListener('mouseenter', () => { isHovered = true; });
        box.addEventListener('mouseleave', () => { isHovered = false; });

        const animate = () => {
            animId = requestAnimationFrame(animate);

            if (bannerScale < 1) {
                bannerScale += 0.08;
                const s = Math.min(1, bannerScale);
                mesh.scale.set(s, s, s);
                wire.scale.set(s * 1.1, s * 1.1, s * 1.1);
            }

            const speed = isHovered ? 0.08 : 0.025;
            mesh.rotation.x += speed * 0.8;
            mesh.rotation.y += speed;
            wire.rotation.x += speed * 0.8;
            wire.rotation.y += speed;

            renderer.render(scene, camera);
        };
        animate();
    });
}

/**
 * Override window.alert default browser dengan Three.js 3D Alert.
 */
export function overrideNativeAlert() {
    window.threeAlert = show3DAlert;
    window.show3DAlert = show3DAlert;

    // Simpan alert native asli jika sewaktu-waktu dibutuhkan
    window._nativeAlert = window.alert;

    // Gantikan alert bawaan browser
    window.alert = function (message) {
        show3DAlert(message);
    };
}
