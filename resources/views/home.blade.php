<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>

    <!-- Bootstrap & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <style>
        /* Navbar */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background: transparent !important;
            z-index: 10;
            transition: background-color 0.3s, box-shadow 0.3s;
        }

        .navbar.scrolled {
            background-color: rgba(255, 255, 255, 0.9) !important;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .navbar.scrolled .nav-link {
            color: black !important;
        }

        .navbar.scrolled .navbar-brand {
            color: black !important;
        }

        /* Hero Section */
        .hero-section {
            position: relative;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-image: url('{{ asset('images/back.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        .hero-section .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.8));
            z-index: 1;
        }

        .hero-section .hero-text {
            position: relative;
            z-index: 2;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.8);
        }

        /* Section Destinasi */
        .destinasi-section {
            padding: 4rem 1rem;
            opacity: 0;
            transform: translateY(50px);
            transition: opacity 0.8s ease, transform 0.8s ease;
        }

        .destinasi-section.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .destinasi-card {
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .destinasi-card:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .destinasi-card img {
            transition: transform 0.3s ease-in-out;
        }

        .destinasi-card:hover img {
            transform: scale(1.1);
        }

        /* Footer */
        footer {
            background: linear-gradient(to right, #f9c13d, #ffa000);
            color: white;
        }

        footer p {
            margin: 0;
        }

        footer a {
            color: white;
            margin: 0 10px;
            font-size: 1.5rem;
        }

        footer a:hover {
            color: #f3c230;
        }
    </style>
</head>

<body>
    <!-- Navbar -->

    <nav class="navbar navbar-expand-lg shadow-sm">
        <div class="container">

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link text-warning active" href="/">Home</a></li>
                    <li class="nav-item"><a class="nav-link text-warning" href="/map">Maps</a></li>
                    <li class="nav-item"><a class="nav-link text-warning" href="/login">Login</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <main class="hero-section text-white text-center">
        <div class="overlay"></div>
        <div class="hero-text container">
            <h1 class="display-4 fw-bold text-warning">ARCADĪPA</h1>
            <h2 class="display-7 fw-bold text-warning">"Pelita Spasial: Menyinari Keindahan Sejarah dan Ruang Semarang"</h2>
            <p class="mt-3 fst-italic text-light">
                Dibuat oleh: Elkar Patria Akbar – 25/569213/SV/27612
            </p>
        </div>
    </main>

    <section id="tujuan-latar-belakang" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center fw-bold mb-4 text-dark">Tujuan dan Latar Belakang</h2>
            <div class="row justify-content-center g-4">
                <!-- Tujuan -->
                <div class="col-lg-5 col-md-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body">
                            <h5 class="card-title fw-bold text-warning mb-3">Tujuan Dibuatnya Website</h5>
                            <p class="card-text text-secondary">
                                Website ini bertujuan untuk mempromosikan keindahan dan keberagaman destinasi wisata
                                di Semarang kepada masyarakat luas, sekaligus memudahkan pengunjung untuk mengetahui
                                lokasi
                                dan informasi terkait tempat-tempat menarik di Semarang.
                            </p>
                        </div>
                    </div>
                </div>
                <!-- Latar Belakang -->
                <div class="col-lg-5 col-md-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body">
                            <h5 class="card-title fw-bold text-warning mb-3">Latar Belakang Pembuatan</h5>
                            <p class="card-text text-secondary">
                                Website ini dibuat untuk memetakan dan memberikan informasi tentang berbagai lokasi
                                wisata di wilayah Semarang, Jawa Tengah. Selain itu, website ini juga menampilkan detail
                                lokasi wisata, termasuk gambar, deskripsi, dan keterangan lain yang berkaitan, sehingga
                                pengguna dapat dengan mudah menavigasi dan mengetahui lokasi serta informasi penting
                                tentang destinasi wisata tersebut.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <!-- Destinasi Section -->
    <section id="destinasi" class="destinasi-section container" data-aos="fade-up" data-aos-duration="1000">
        <h2 class="text-center fw-bold mb-5">Destinasi Favorit</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card destinasi-card">
                    <img src="images/marinaaa.jpg" class="card-img-top" alt="Pantai Marina">
                    <div class="card-body">
                        <h5 class="card-title">Pantai Marina</h5>
                        <p class="card-text">Keindahan pantai dengan pemandangan sunset terbaik di Semarang.</p>
                        <a href="/map">Lihat di Peta</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card destinasi-card">
                    <img src="images/lawangsewu.jpg" class="card-img-top" alt="Destinasi 2">
                    <div class="card-body">
                        <h5 class="card-title">Lawang Sewu</h5>
                        <p class="card-text">Bangunan bersejarah yang menjadi ikon wisata di Semarang.</p>
                        <a href="/map">Lihat di Peta</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card destinasi-card">
                    <img src="images/kotalamaaa.jpg" class="card-img-top" alt="Destinasi 3">
                    <div class="card-body">
                        <h5 class="card-title">Kota Lama</h5>
                        <p class="card-text">Jelajahi arsitektur kuno yang membawa Anda ke masa lalu.</p>
                        <a href="/map">Lihat di Peta</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Kontak -->
        <div class="contact-info mt-5">
            <h2 class="text-center fw-bold mb-4">Info Kontak</h2>
            <div class="row justify-content-center g-3">
                <div class="col-md-3">
                    <div class="card text-center shadow-sm border-0 h-100">
                        <div class="card-body">
                            <i class="fab fa-whatsapp fa-3x text-success mb-3"></i>
                            <h5 class="card-title">WhatsApp</h5>
                            <p class="card-text">Hubungi kami melalui WhatsApp.</p>
                            <a href="https://wa.me/6289688465454" class="btn btn-outline-success">+62 896-8846-5454</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center shadow-sm border-0 h-100">
                        <div class="card-body">
                            <i class="fab fa-instagram fa-3x text-danger mb-3"></i>
                            <h5 class="card-title">Instagram</h5>
                            <p class="card-text">Ikuti kami di Instagram.</p>
                            <a href="https://www.instagram.com/belindaasyl"
                                class="btn btn-outline-danger">@belindaasyl</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center shadow-sm border-0 h-100">
                        <div class="card-body">
                            <i class="fab fa-linkedin fa-3x text-primary mb-3"></i>
                            <h5 class="card-title">LinkedIn</h5>
                            <p class="card-text">Terhubung dengan kami di LinkedIn.</p>
                            <a href="https://www.linkedin.com/in/belinda-sandyawela"
                                class="btn btn-outline-primary">/in/belinda-sandyawela</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="text-center py-4">
        <p class="mb-3">&copy; 2025 Samāraṅga. All rights reserved.</p>

    </footer>

    <script>
        document.addEventListener("scroll", function() {
            const navbar = document.querySelector(".navbar");
            const destinasiSection = document.querySelector(".destinasi-section");

            // Navbar scroll effect
            if (window.scrollY > 50) {
                navbar.classList.add("scrolled");
            } else {
                navbar.classList.remove("scrolled");
            }

            // Destinasi section animation
            const sectionTop = destinasiSection.offsetTop;
            const sectionHeight = destinasiSection.offsetHeight;
            const windowBottom = window.scrollY + window.innerHeight;

            if (windowBottom > sectionTop + sectionHeight / 4) {
                destinasiSection.classList.add("visible");
            }
        });

        AOS.init();
    </script>
</body>

</html>
