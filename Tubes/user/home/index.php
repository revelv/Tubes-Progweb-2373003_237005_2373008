<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>TonyCKGaming</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Font Awesome icons (free version)-->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="css/styles.css" rel="stylesheet" />
</head>

<body id="page-top">
    <!-- Navigation-->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav">
        <div class="container">
            <a class="navbar-brand" href="#page-top" id="logo">
                <img src="https://i.postimg.cc/855ZSty7/no-bg.png" alt="logo" style="height: 60px;"> </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                Menu
                <i class="fas fa-bars ms-1"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav text-uppercase ms-auto py-4 py-lg-0">
                    <li class="nav-item"><a class="nav-link" href="#services">Services</a></li>
                    <li class="nav-item"><a class="nav-link" href="#portfolio">Recommendation</a></li>
                    <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="#team">Team</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Masthead-->
    <header class="masthead">
        <div class="container">
            <div class="masthead-subheading">Welcome To Styrk Industries!</div>
            <div class="masthead-heading text-uppercase">We don’t just build keyboards. We craft legends!</div>
            <a class="btn btn-primary btn-xl text-uppercase" href="../produk.php">Shop Now</a>
        </div>
    </header>
    <!-- Services-->
    <section class="page-section" id="services">
        <div class="container">
            <div class="text-center">
                <h2 class="section-heading text-uppercase">Our Services</h2>
                <h3 class="section-subheading text-muted">Checkout our Product!</h3>
            </div>
            <div class="row text-center">
                <div class="col-md-4">
                    <span class="fa-stack fa-4x">
                        <i class="fas fa-circle fa-stack-2x text-primary"></i>
                        <i class="fas fa-shopping-cart fa-stack-1x fa-inverse"></i>
                    </span>
                    <h4 class="my-3">Custom Keyboard</h4>
                    <p class="text-muted">Custom Keyboard anda di Styrk Industries sesuai dengan yang anda inginkan!</p>
                </div>
                <div class="col-md-4">
                    <span class="fa-stack fa-4x">
                        <i class="fas fa-circle fa-stack-2x text-primary"></i>
                        <i class="fas fa-laptop fa-stack-1x fa-inverse"></i>
                    </span>
                    <h4 class="my-3">Bundling Keyboard</h4>
                    <p class="text-muted">Dapatkan bundling custom keyboard eksklusif kami! Sudah termasuk keycaps premium, switch pilihan, dan stabilizer—semua dalam satu paket siap rakit dengan harga terjangkau!</p>
                </div>
                <div class="col-md-4">
                    <span class="fa-stack fa-4x">
                        <i class="fas fa-circle fa-stack-2x text-primary"></i>
                        <i class="fas fa-lock fa-stack-1x fa-inverse"></i>
                    </span>
                    <h4 class="my-3">Komponen Keyboard</h4>
                    <p class="text-muted">Kami menyediakan berbagai komponen keyboard mekanis berkualitas, mulai dari switch, keycaps, PCB, casing, hingga stabilizer. Semua part tersedia secara terpisah, memungkinkan Anda merakit custom keyboard sesuai preferensi.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Portfolio Grid-->
<section class="page-section bg-light" id="portfolio">
    <div class="container">
        <div class="text-center">
            <h2 class="section-heading text-uppercase">Recommendations</h2>
            <h3 class="section-subheading text-muted">Check out our featured products!</h3>
        </div>
        <div class="row">
            <?php
            // Database connection
            require_once '../koneksi.php';

            // Get 3 products with highest stock AND oldest in database
            $query = "SELECT * FROM products 
                     ORDER BY stok DESC, product_id ASC 
                     LIMIT 3";
            $result = mysqli_query($conn, $query);

            $counter = 1;
            while ($row = mysqli_fetch_assoc($result)) {
                echo '
            <div class="col-lg-4 col-sm-6 mb-4">
                <div class="portfolio-item">
                    <a class="portfolio-link" href="../produk.php">
                        <div class="portfolio-hover">
                            <div class="portfolio-hover-content"><i class="fas fa-plus fa-3x"></i></div>
                        </div>
                        <img class="img-fluid" src="' . $row['link_gambar'] . '" alt="' . $row['nama_produk'] . '" />
                    </a>
                    <div class="portfolio-caption">
                        <div class="portfolio-caption-heading">' . $row['nama_produk'] . '</div>
                        <div class="portfolio-caption-subheading text-muted">
                            $' . number_format($row['harga'], 2) . '
                        </div>
                    </div>
                </div>
            </div>
            ';
                $counter++;
            }
            ?>
        </div>
    </div>
</section>

    <!-- About-->
    <section class="page-section" id="about">
        <div class="container">
            <div class="text-center">
                <h2 class="section-heading text-uppercase">About</h2>
                <h3 class="section-subheading text-muted">Our Journey.</h3>
            </div>
            <ul class="timeline">
                <li>
                    <div class="timeline-image"><img class="rounded-circle img-fluid" src="assets/img/about/1.jpg" alt="..." /></div>
                    <div class="timeline-panel">
                        <div class="timeline-heading">
                            <h4>2023</h4>
                            <h4 class="subheading">The Beginnings</h4>
                        </div>
                        <div class="timeline-body">
                            <p class="text-muted">Sebuah ide lahir di garasi kecil. Tiga pecinta mekanikal keyboard, frustasi dengan keyboard biasa dan mulai merakit switch sendiri.
                            </p>
                        </div>
                    </div>
                </li>
                <li class="timeline-inverted">
                    <div class="timeline-image"><img class="rounded-circle img-fluid" src="assets/img/about/2.jpg" alt="..." /></div>
                    <div class="timeline-panel">
                        <div class="timeline-heading">
                            <h4>2024</h4>
                            <h4 class="subheading">The Born of Styrk Industries</h4>
                        </div>
                        <div class="timeline-body">
                            <p class="text-muted">Stryk Industries resmi berdiri dengan workshop pertama di Bandung.
                                Kolaborasi dengan seniman lokal untuk limited edition keycap "Jawa Heritage", langka dan jadi barang kolektor.

                            </p>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="timeline-image"><img class="rounded-circle img-fluid" src="assets/img/about/3.jpg" alt="..." /></div>
                    <div class="timeline-panel">
                        <div class="timeline-heading">
                            <h4>2025</h4>
                            <h4 class="subheading">Our First Ever Custom Keyboard</h4>
                        </div>
                        <div class="timeline-body">
                            <p class="text-muted">Luncurkan Mark-Alims, Sebuah Custom Keyboard dengan Chery MX Switch dan casing aluminum CNC. Harga premium, tapi pasar profesional menyukainya.
                            </p>
                        </div>
                    </div>
                </li>
                <li class="timeline-inverted">
                    <div class="timeline-image"><img class="rounded-circle img-fluid" src="assets/img/about/4.jpg" alt="..." /></div>
                    <div class="timeline-panel">
                        <div class="timeline-heading">
                            <h4>Now</h4>
                            <h4 class="subheading">Business Expansion</h4>
                        </div>
                        <div class="timeline-body">
                            <p class="text-muted">Kini Styrk Industries berfokus menjadi penyedia Keyboard Bundle dan Custom Keyboard serta menjadi inspirasi bagi dunia.</p>
                        </div>
                    </div>
                </li>
                <li class="timeline-inverted">
                    <div class="timeline-image">
                        <h4>
                            Be part <br>
                            of every
                            <br>
                            Keystrokes
                        </h4>
                    </div>
                </li>
            </ul>
        </div>
    </section>
    <!-- Team-->
    <section class="page-section bg-light" id="team">
        <div class="container">
            <div class="text-center">
                <h2 class="section-heading text-uppercase">Our Amazing Team</h2>
                <h3 class="section-subheading text-muted">Meet our Legends</h3>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <div class="team-member">
                        <img class="mx-auto rounded-circle" src="assets/img/team/1.jpg" alt="..." />
                        <h4>Igris</h4>
                        <p class="text-muted">Lead Designer</p>
                        <a class="btn btn-dark btn-social mx-2" href="#!" aria-label="Parveen Anand Twitter Profile"><i class="fab fa-twitter"></i></a>
                        <a class="btn btn-dark btn-social mx-2" href="#!" aria-label="Parveen Anand Facebook Profile"><i class="fab fa-facebook-f"></i></a>
                        <a class="btn btn-dark btn-social mx-2" href="#!" aria-label="Parveen Anand LinkedIn Profile"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="team-member">
                        <img class="mx-auto rounded-circle" src="assets/img/team/2.jpg" alt="..." />
                        <h4>Thomas</h4>
                        <p class="text-muted">The Mechanic</p>
                        <a class="btn btn-dark btn-social mx-2" href="#!" aria-label="Diana Petersen Twitter Profile"><i class="fab fa-twitter"></i></a>
                        <a class="btn btn-dark btn-social mx-2" href="#!" aria-label="Diana Petersen Facebook Profile"><i class="fab fa-facebook-f"></i></a>
                        <a class="btn btn-dark btn-social mx-2" href="#!" aria-label="Diana Petersen LinkedIn Profile"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="team-member">
                        <img class="mx-auto rounded-circle" src="assets/img/team/3.jpg" alt="..." />
                        <h4>Sung Andre</h4>
                        <p class="text-muted">Lead Developer</p>
                        <a class="btn btn-dark btn-social mx-2" href="#!" aria-label="Larry Parker Twitter Profile"><i class="fab fa-twitter"></i></a>
                        <a class="btn btn-dark btn-social mx-2" href="#!" aria-label="Larry Parker Facebook Profile"><i class="fab fa-facebook-f"></i></a>
                        <a class="btn btn-dark btn-social mx-2" href="#!" aria-label="Larry Parker LinkedIn Profile"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <p class="large text-muted">Passion di Setiap Switch, Dedikasi di Setiap Layout – Inilah Wajah di Balik Keyboard Custom Anda.</p>
                </div>
            </div>
        </div>
    </section>
    <!-- Clients-->
    <div class="py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-3 col-sm-6 my-3">
                    <a href="https://www.instagram.com/tonyck_gaming"><img class="img-fluid img-brand d-block mx-auto" src="https://i.postimg.cc/DyBd2qXs/insta.png" alt="..." aria-label="Insta Logo" /></a>

                </div>
                <div class="col-md-3 col-sm-6 my-3">
                    <a href="https://x.com/TonyCK169"><img class="img-fluid img-brand d-block mx-auto" src="https://i.postimg.cc/SK8Mz8Jg/eks.png" alt="..." aria-label="eks x Logo" /></a>
                </div>
                <div class="col-md-3 col-sm-6 my-3">
                    <a href="https://www.youtube.com/@JessNoLimit"><img class="img-fluid img-brand d-block mx-auto" src="https://i.postimg.cc/wMc2319D/free-youtube-icon-123-thumb.png" alt="..." aria-label="Youtube logo" /></a>
                </div>
                <div class="col-md-3 col-sm-6 my-3">
                    <a href="https://www.tiktok.com/@tonyckgaming"><img class="img-fluid img-brand d-block mx-auto" src="https://i.postimg.cc/Qd2MZSbF/1000-F-576083591-j-O2u-WDr-W843l-L8e-FMe9a-DZlo-Iri7ghc4.jpg" alt="..." aria-label="Tiktok logo" /></a>
                </div>
            </div>
        </div>
    </div>
    <!-- Contact US-->


    </section>
    <!-- Footer-->
    <footer class="footer py-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-4 text-lg-start">Copyright &copy; Styrk Industries 2025</div>
                <div class="col-lg-4 my-3 my-lg-0">

                </div>
                <div class="col-lg-4 text-lg-end">
                    <a class="link-dark text-decoration-none me-3" href="#!">Privacy Policy</a>
                    <a class="link-dark text-decoration-none" href="#!">Terms of Use</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="js/scripts.js"></script>
    <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
    <!-- * *                               SB Forms JS                               * *-->
    <!-- * * Activate your form at https://startbootstrap.com/solution/contact-forms * *-->
    <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
    <script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>
</body>

</html>