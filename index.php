<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <script src="https://cdn.jsdelivr.net/npm/three@0.136.0/build/three.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="events-login.css">
    <style>
        body {
            margin: 0;
            overflow: hidden;
        }
        #background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }
    </style>
</head>
<body>
    <!-- Three.js Canvas -->
    <div id="background"></div>

    <script>
        // Three.js setup
        let scene, camera, renderer, sphere, moon, particles, particleGeo;

        function init() {
            scene = new THREE.Scene();
            camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
            renderer = new THREE.WebGLRenderer();
            renderer.setSize(window.innerWidth, window.innerHeight);
            document.getElementById('background').appendChild(renderer.domElement);

            // Add rotating Earth
            const textureLoader = new THREE.TextureLoader();
            textureLoader.load('earth_atmos_4096.jpg', function (texture) {
                const sphereGeometry = new THREE.SphereGeometry(100, 64, 64); // Increase segments for smoother sphere
                const sphereMaterial = new THREE.MeshStandardMaterial({ map: texture });
                sphere = new THREE.Mesh(sphereGeometry, sphereMaterial);
                sphere.position.set(0, 0, 0);
                scene.add(sphere);
            });

            // Add moon orbiting Earth
            textureLoader.load('moon_1024.jpg', function (texture) {
                const moonGeometry = new THREE.SphereGeometry(30, 64, 64); // Increase segments for smoother sphere
                const moonMaterial = new THREE.MeshStandardMaterial({ map: texture });
                moon = new THREE.Mesh(moonGeometry, moonMaterial);
                moon.position.set(250, 0, 0); // Initial position
                scene.add(moon);
            });

            // Particle setup
            particleGeo = new THREE.BufferGeometry();
            const particlesCount = 2000;
            const positions = new Float32Array(particlesCount * 3);

            for (let i = 0; i < particlesCount * 3; i++) {
                positions[i] = (Math.random() - 0.5) * 2000;
            }

            particleGeo.setAttribute('position', new THREE.BufferAttribute(positions, 3));
            const particleMaterial = new THREE.PointsMaterial({
                color: 0xffffff,
                size: 2,
                map: new THREE.TextureLoader().load('disc.png'),
                transparent: true,
                blending: THREE.AdditiveBlending
            });

            particles = new THREE.Points(particleGeo, particleMaterial);
            scene.add(particles);

            // Lighting
            const ambientLight = new THREE.AmbientLight(0xffffff, 0.5);
            scene.add(ambientLight);

            const pointLight = new THREE.PointLight(0xffffff, 1);
            pointLight.position.set(0, 0, 100);
            scene.add(pointLight);

            camera.position.z = 500;

            function render() {
                requestAnimationFrame(render);
                if (sphere) {
                    sphere.rotation.y -= 0.01;
                }
                if (moon) {
                    const time = Date.now() * 0.001;
                    moon.position.x = sphere.position.x + 150 * Math.cos(time);
                    moon.position.z = sphere.position.z + 150 * Math.sin(time);
                    moon.position.y = 0; // Ensure moon stays on the same plane
                }
                particles.rotation.y += 0.001; // Move particles
                renderer.render(scene, camera);
            }
            render();
        }
        init();
    </script>
</body>
</html>
