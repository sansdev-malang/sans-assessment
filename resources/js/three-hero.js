import * as THREE from 'three';

export function initThreeHero() {
    const container = document.getElementById('three-hero-container') || document.getElementById('three-login-container');
    if (!container) return;

    let width = container.clientWidth;
    let height = container.clientHeight || 450;

    const scene = new THREE.Scene();

    const camera = new THREE.PerspectiveCamera(45, width / height, 0.1, 100);
    camera.position.set(0, 0, 9);

    const renderer = new THREE.WebGLRenderer({
        antialias: true,
        alpha: true,
        powerPreference: 'high-performance',
    });
    renderer.setSize(width, height);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    container.innerHTML = '';
    container.appendChild(renderer.domElement);

    const masterGroup = new THREE.Group();
    scene.add(masterGroup);

    // 1. Minimalist Wireframe Polyhedron (Shadcn Monochrome Style)
    const geometry = new THREE.IcosahedronGeometry(2.4, 1);

    const wireMat = new THREE.MeshBasicMaterial({
        color: 0x71717a, // zinc-500
        wireframe: true,
        transparent: true,
        opacity: 0.35,
    });
    const wireMesh = new THREE.Mesh(geometry, wireMat);
    masterGroup.add(wireMesh);

    // Vertex points (subtle dots)
    const pointMat = new THREE.PointsMaterial({
        color: 0xe4e4e7, // zinc-200
        size: 0.08,
        transparent: true,
        opacity: 0.7,
    });
    const pointMesh = new THREE.Points(geometry, pointMat);
    masterGroup.add(pointMesh);

    // 2. Concentric delicate rings
    const ringMat = new THREE.MeshBasicMaterial({
        color: 0x3f3f46, // zinc-700
        transparent: true,
        opacity: 0.4,
    });
    const ring1 = new THREE.Mesh(new THREE.TorusGeometry(3.4, 0.012, 16, 100), ringMat);
    ring1.rotation.x = Math.PI / 3;
    masterGroup.add(ring1);

    const ring2 = new THREE.Mesh(new THREE.TorusGeometry(3.9, 0.012, 16, 100), ringMat);
    ring2.rotation.y = Math.PI / 4;
    masterGroup.add(ring2);

    // 3. Subtle background particles
    const particleCount = 180;
    const posArray = new Float32Array(particleCount * 3);
    for (let i = 0; i < particleCount * 3; i++) {
        posArray[i] = (Math.random() - 0.5) * 14;
    }
    const particleGeo = new THREE.BufferGeometry();
    particleGeo.setAttribute('position', new THREE.BufferAttribute(posArray, 3));
    const particleMat = new THREE.PointsMaterial({
        color: 0x52525b,
        size: 0.03,
        transparent: true,
        opacity: 0.4,
    });
    const particles = new THREE.Points(particleGeo, particleMat);
    scene.add(particles);

    // Mouse movement damping
    let mouseX = 0;
    let mouseY = 0;
    let targetX = 0;
    let targetY = 0;

    function onMouseMove(e) {
        const rect = container.getBoundingClientRect();
        mouseX = ((e.clientX - rect.left) / width) * 2 - 1;
        mouseY = -((e.clientY - rect.top) / height) * 2 + 1;
        targetX = mouseX * 0.3;
        targetY = -mouseY * 0.2;
    }

    window.addEventListener('mousemove', onMouseMove, { passive: true });

    // Resize
    function onResize() {
        if (!container) return;
        width = container.clientWidth;
        height = container.clientHeight || 450;
        camera.aspect = width / height;
        camera.updateProjectionMatrix();
        renderer.setSize(width, height);
    }
    window.addEventListener('resize', onResize);

    // Animation Loop
    let clock = new THREE.Clock();
    function animate() {
        requestAnimationFrame(animate);
        const t = clock.getElapsedTime();

        wireMesh.rotation.y = t * 0.08;
        pointMesh.rotation.y = t * 0.08;
        ring1.rotation.z = t * 0.1;
        ring2.rotation.x = t * 0.07;
        particles.rotation.y = t * 0.01;

        masterGroup.rotation.y += (targetX - masterGroup.rotation.y) * 0.05;
        masterGroup.rotation.x += (targetY - masterGroup.rotation.x) * 0.05;

        renderer.render(scene, camera);
    }
    animate();
}
