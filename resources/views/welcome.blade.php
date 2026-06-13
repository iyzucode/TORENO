<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TORENO</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --espresso: #1A1209;
            --gold: #C9A96E;
            --gold-light: #E8D5B0;
            --cream: #FAF8F5;
            --warm-gray: #8C8278;
            --mid-gray: #4A4540;
        }

        html { font-size: 16px; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--cream);
            color: var(--espresso);
            -webkit-font-smoothing: antialiased;
        }

        /* ── NAV ── */
        nav {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 100;
            padding: 0 2rem;
            height: 72px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(250, 248, 245, 0.92);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(201,169,110,0.15);
            transition: box-shadow 0.3s;
        }
        nav.scrolled { box-shadow: 0 1px 24px rgba(26,18,9,0.06); }

        .nav-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        .nav-logo img { height: 32px; width: auto; }
        .nav-logo span {
            font-family: 'Playfair Display', serif;
            font-size: 1.2rem;
            font-weight: 700;
            letter-spacing: 0.2em;
            color: var(--espresso);
        }

        .nav-links {
            display: flex;
            gap: 2.5rem;
            list-style: none;
        }
        .nav-links a {
            font-size: 0.8rem;
            font-weight: 500;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            text-decoration: none;
            color: var(--warm-gray);
            transition: color 0.2s;
        }
        .nav-links a:hover { color: var(--espresso); }

        /* ── HERO ── */
        #hero {
            min-height: 100svh;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0;
            padding-top: 72px;
        }

        .hero-left {
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 6rem 4rem 6rem 8vw;
        }

        .hero-eyebrow {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 2rem;
        }
        .hero-eyebrow::before {
            content: '';
            display: block;
            width: 32px; height: 1px;
            background: var(--gold);
        }
        .hero-eyebrow span {
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--gold);
        }

        h1 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2.8rem, 4vw, 4.5rem);
            font-weight: 700;
            line-height: 1.1;
            color: var(--espresso);
            margin-bottom: 1.5rem;
        }
        h1 em {
            font-style: italic;
            color: var(--gold);
        }

        .hero-desc {
            font-size: 1rem;
            font-weight: 300;
            line-height: 1.8;
            color: var(--warm-gray);
            max-width: 380px;
            margin-bottom: 2.5rem;
        }

        .hero-cta {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .btn-primary {
            display: inline-block;
            padding: 0.85rem 2rem;
            background: var(--espresso);
            color: var(--cream);
            font-size: 0.8rem;
            font-weight: 500;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            text-decoration: none;
            transition: background 0.2s, transform 0.2s;
        }
        .btn-primary:hover { background: var(--mid-gray); transform: translateY(-2px); }

        .btn-ghost {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 0.85rem 2rem;
            border: 1px solid var(--gold-light);
            color: var(--mid-gray);
            font-size: 0.8rem;
            font-weight: 500;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            text-decoration: none;
            transition: border-color 0.2s, color 0.2s;
        }
        .btn-ghost:hover { border-color: var(--gold); color: var(--espresso); }
        .btn-ghost svg { width: 14px; height: 14px; transition: transform 0.2s; }
        .btn-ghost:hover svg { transform: translateX(3px); }

        .hero-right {
            position: relative;
            overflow: hidden;
        }
        .hero-right img {
            width: 100%; height: 100%;
            object-fit: cover;
            display: block;
        }
        .hero-right::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to right, var(--cream) 0%, transparent 15%);
        }

        .hero-badge {
            position: absolute;
            bottom: 2.5rem;
            left: -1px;
            z-index: 10;
            background: var(--cream);
            padding: 1rem 1.5rem;
            border-top: 2px solid var(--gold);
            display: flex;
            flex-direction: column;
            gap: 2px;
            box-shadow: 4px 4px 32px rgba(26,18,9,0.1);
        }
        .hero-badge .rating {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--espresso);
        }
        .hero-badge .stars { color: var(--gold); font-size: 0.75rem; letter-spacing: 2px; }
        .hero-badge .label {
            font-size: 0.65rem;
            font-weight: 600;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--warm-gray);
        }

        /* ── SECTION COMMON ── */
        section { position: relative; }

        .section-eyebrow {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 1.25rem;
        }
        .section-eyebrow::before {
            content: '';
            display: block;
            width: 24px; height: 1px;
            background: var(--gold);
        }
        .section-eyebrow span {
            font-size: 0.68rem;
            font-weight: 600;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--gold);
        }

        h2 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            color: var(--espresso);
            line-height: 1.15;
        }

        /* ── TENTANG ── */
        #tentang {
            padding: 8rem 8vw;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 6rem;
            align-items: center;
            background: white;
        }

        #tentang h2 { font-size: clamp(2rem, 3vw, 3rem); margin-bottom: 1.5rem; }

        .tentang-body {
            font-size: 0.95rem;
            font-weight: 300;
            line-height: 1.9;
            color: var(--warm-gray);
            margin-bottom: 2.5rem;
        }

        .stats-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            border-top: 1px solid var(--gold-light);
            padding-top: 2rem;
        }
        .stat-item {}
        .stat-num {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 700;
            color: var(--espresso);
            line-height: 1;
            margin-bottom: 0.25rem;
        }
        .stat-label {
            font-size: 0.7rem;
            font-weight: 500;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--warm-gray);
        }

        .tentang-img {
            position: relative;
        }
        .tentang-img img {
            width: 100%;
            aspect-ratio: 4/5;
            object-fit: cover;
            display: block;
        }
        .tentang-img::before {
            content: '';
            position: absolute;
            top: -16px; right: -16px;
            width: 100%; height: 100%;
            border: 1px solid var(--gold-light);
            z-index: 0;
        }
        .tentang-img img { position: relative; z-index: 1; }

        /* ── LAYANAN ── */
        #layanan {
            background: var(--espresso);
            padding: 8rem 8vw;
            color: white;
        }

        .layanan-header {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: end;
            margin-bottom: 5rem;
        }

        .layanan-header h2 {
            color: white;
            font-size: clamp(2rem, 3vw, 3rem);
        }
        .layanan-header h2 em { color: var(--gold); }

        .layanan-desc {
            font-size: 0.95rem;
            font-weight: 300;
            line-height: 1.8;
            color: rgba(255,255,255,0.45);
            align-self: end;
            padding-bottom: 0.25rem;
        }

        .layanan-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1px;
            background: rgba(201,169,110,0.15);
        }

        .layanan-item {
            background: var(--espresso);
            padding: 2.5rem 2rem;
            transition: background 0.3s;
        }
        .layanan-item:hover { background: rgba(201,169,110,0.05); }

        .layanan-num {
            font-family: 'Playfair Display', serif;
            font-size: 3rem;
            font-weight: 700;
            color: rgba(201,169,110,0.2);
            line-height: 1;
            margin-bottom: 1.5rem;
        }

        .layanan-title {
            font-size: 1rem;
            font-weight: 600;
            color: white;
            margin-bottom: 0.75rem;
            letter-spacing: 0.02em;
        }

        .layanan-body {
            font-size: 0.85rem;
            font-weight: 300;
            line-height: 1.8;
            color: rgba(255,255,255,0.4);
        }

        /* ── MENU ── */
        #menu {
            padding: 8rem 8vw;
            background: var(--cream);
        }

        .menu-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 4rem;
        }
        .menu-header h2 { font-size: clamp(2rem, 3vw, 3rem); }

        .menu-link {
            font-size: 0.78rem;
            font-weight: 500;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--gold);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
            margin-bottom: 0.25rem;
            transition: color 0.2s;
        }
        .menu-link:hover { color: var(--espresso); }
        .menu-link svg { width: 14px; height: 14px; }

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
        }

        .menu-card { cursor: pointer; }
        .menu-img {
            width: 100%;
            aspect-ratio: 3/4;
            object-fit: cover;
            display: block;
            margin-bottom: 1rem;
            transition: filter 0.4s;
            filter: saturate(0.85);
        }
        .menu-card:hover .menu-img { filter: saturate(1); }

        .menu-name {
            font-family: 'Playfair Display', serif;
            font-size: 1rem;
            font-weight: 400;
            color: var(--espresso);
            margin-bottom: 0.25rem;
        }
        .menu-price {
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--warm-gray);
            letter-spacing: 0.05em;
        }

        /* ── FOOTER ── */
        footer {
            background: var(--espresso);
            color: white;
            padding: 5rem 8vw 2.5rem;
        }

        .footer-top {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 4rem;
            padding-bottom: 4rem;
            border-bottom: 1px solid rgba(201,169,110,0.15);
            margin-bottom: 2rem;
        }

        .footer-brand-name {
            font-family: 'Playfair Display', serif;
            font-size: 1.4rem;
            font-weight: 700;
            letter-spacing: 0.2em;
            color: white;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 1rem;
        }
        .footer-brand-name img { height: 28px; filter: brightness(0) invert(1) opacity(0.8); }

        .footer-tagline {
            font-size: 0.85rem;
            font-weight: 300;
            line-height: 1.8;
            color: rgba(255,255,255,0.35);
            max-width: 280px;
            margin-bottom: 2rem;
        }

        .social-row { display: flex; gap: 1rem; }
        .social-btn {
            width: 36px; height: 36px;
            border: 1px solid rgba(201,169,110,0.2);
            display: flex; align-items: center; justify-content: center;
            color: rgba(255,255,255,0.4);
            text-decoration: none;
            transition: border-color 0.2s, color 0.2s;
        }
        .social-btn:hover { border-color: var(--gold); color: var(--gold); }
        .social-btn svg { width: 16px; height: 16px; }

        .footer-col h4 {
            font-size: 0.68rem;
            font-weight: 600;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 1.25rem;
        }

        .footer-col p, .footer-col li {
            font-size: 0.85rem;
            font-weight: 300;
            color: rgba(255,255,255,0.4);
            line-height: 2;
            list-style: none;
        }

        .footer-col a {
            color: rgba(255,255,255,0.4);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 300;
            transition: color 0.2s;
        }
        .footer-col a:hover { color: var(--gold); }

        .jam-row {
            display: flex;
            justify-content: space-between;
            font-size: 0.82rem;
            font-weight: 300;
            color: rgba(255,255,255,0.4);
            padding: 0.3rem 0;
        }

        .footer-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .footer-bottom p {
            font-size: 0.75rem;
            color: rgba(255,255,255,0.2);
        }
        .footer-bottom a {
            font-size: 0.75rem;
            color: rgba(255,255,255,0.2);
            text-decoration: none;
            transition: color 0.2s;
        }
        .footer-bottom a:hover { color: rgba(255,255,255,0.5); }

        /* ── REVEAL ── */
        .reveal {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }
        .reveal.active { opacity: 1; transform: none; }

        /* ── RESPONSIVE ── */
        @media (max-width: 1024px) {
            #hero { grid-template-columns: 1fr; }
            .hero-left { padding: 4rem 2rem 3rem; }
            .hero-right { height: 50vh; }
            .hero-right::after { background: linear-gradient(to top, var(--cream) 0%, transparent 30%); }
            #tentang { grid-template-columns: 1fr; padding: 5rem 2rem; gap: 3rem; }
            .tentang-img { display: none; }
            .layanan-header { grid-template-columns: 1fr; gap: 1rem; }
            .layanan-grid { grid-template-columns: 1fr; }
            .menu-grid { grid-template-columns: repeat(2, 1fr); }
            .footer-top { grid-template-columns: 1fr; gap: 2rem; }
            nav { padding: 0 1.25rem; }
            #layanan, #menu { padding: 5rem 2rem; }
            .menu-header { flex-direction: column; align-items: flex-start; gap: 1rem; }
        }

        @media (max-width: 640px) {
            .nav-links { display: none; }
            .menu-grid { grid-template-columns: repeat(2, 1fr); gap: 1rem; }
            .stats-row { grid-template-columns: repeat(3, 1fr); gap: 1rem; }
        }
    </style>
</head>
<body>

    <nav id="main-nav">
        <a href="#hero" class="nav-logo">
            <img src="https://res.cloudinary.com/dtbut0lkj/image/upload/images_crg2rm.png" alt="Toreno">
            <span>TORENO</span>
        </a>
        <ul class="nav-links">
            <li><a href="#tentang">Tentang</a></li>
            <li><a href="#layanan">Layanan</a></li>
            <li><a href="#menu">Menu</a></li>
            <li><a href="#kontak">Kontak</a></li>
        </ul>
    </nav>

    <!-- HERO -->
    <section id="hero">
        <div class="hero-left reveal active">
            <div class="hero-eyebrow">
                <span>Kopi Pilihan, Suasana Tenang</span>
            </div>
            <h1>Menyajikan <em>Rasa</em>,<br>Menginspirasi<br>Cerita.</h1>
            <p class="hero-desc">
                Setiap tegukan membawa Anda ke sumber — biji kopi pilihan nusantara, diracik oleh tangan yang penuh dedikasi.
            </p>
            <div class="hero-cta">
                <a href="#tentang" class="btn-primary">Jelajahi TORENO</a>
                <a href="#layanan" class="btn-ghost">
                    Cara Pesan
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4-4 4M3 12h18"/></svg>
                </a>
            </div>
        </div>
        <div class="hero-right">
            <img src="https://res.cloudinary.com/dtbut0lkj/image/upload/v1781360725/We_re_Brewing_Something_Better.Toreno_Coffee_sedang_melakukan_maintenance_sementara.Terima_kasih_ktbsre.jpg" alt="Suasana Coffee TORENO">
            <div class="hero-badge">
                <span class="label">Rating Pelanggan</span>
                <span class="rating">5 / 5</span>
                <span class="stars">★★★★★</span>
            </div>
        </div>
    </section>

    <!-- TENTANG -->
    <section id="tentang">
        <div class="reveal">
            <div class="section-eyebrow"><span>Tentang Kami</span></div>
            <h2>Lebih dari sekadar<br>secangkir kopi.</h2>
            <p class="tentang-body">
                Berawal dari kecintaan terhadap biji kopi nusantara, Coffee TORENO hadir sebagai ruang di mana ide-ide lahir, persahabatan terjalin, dan momen berharga dirayakan. Kami meracik setiap cangkir dengan ketelitian dan dedikasi penuh.
            </p>
            <div class="stats-row">
                <div class="stat-item">
                    <div class="stat-num">50+</div>
                    <div class="stat-label">Menu Pilihan</div>
                </div>
                <div class="stat-item">
                    <div class="stat-num">1th</div>
                    <div class="stat-label">Melayani Yogyakarta</div>
                </div>
                <div class="stat-item">
                    <div class="stat-num">10K+</div>
                    <div class="stat-label">Pelanggan Setia</div>
                </div>
            </div>
        </div>
        <div class="tentang-img reveal">
            <img src="https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Barista TORENO">
        </div>
    </section>

    <!-- LAYANAN -->
    <section id="layanan">
        <div class="layanan-header reveal">
            <div>
                <div class="section-eyebrow"><span>Inovasi Kami</span></div>
                <h2>Pengalaman memesan<br>yang <em>modern</em>.</h2>
            </div>
            <p class="layanan-desc">Teknologi terintegrasi tanpa menghilangkan kehangatan layanan kafe yang sesungguhnya.</p>
        </div>
        <div class="layanan-grid reveal">
            <div class="layanan-item">
                <div class="layanan-num">01</div>
                <div class="layanan-title">Pemesanan via QR</div>
                <p class="layanan-body">Scan QR Code di meja, pilih dari katalog digital, dan checkout — tanpa antre di kasir.</p>
            </div>
            <div class="layanan-item">
                <div class="layanan-num">02</div>
                <div class="layanan-title">Pemantauan Real-time</div>
                <p class="layanan-body">Pesanan langsung masuk ke dapur. Lacak status dari HP Anda sedetik setelah konfirmasi.</p>
            </div>
            <div class="layanan-item">
                <div class="layanan-num">03</div>
                <div class="layanan-title">Manajemen Terintegrasi</div>
                <p class="layanan-body">Sinergi barista, dapur, dan kasir untuk pelayanan tercepat dengan minim kesalahan pesanan.</p>
            </div>
        </div>
    </section>

    <!-- MENU -->
    <section id="menu">
        <div class="menu-header reveal">
            <div>
                <div class="section-eyebrow"><span>Galeri Rasa</span></div>
                <h2>Menu andalan kami.</h2>
            </div>
            <a href="#layanan" class="menu-link">
                Lihat semua menu
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4-4 4M3 12h18"/></svg>
            </a>
        </div>
        <div class="menu-grid reveal">
            <div class="menu-card">
                <img class="menu-img" src="https://images.unsplash.com/photo-1461023058943-07fcbe16d735?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Signature Espresso">
                <div class="menu-name">Signature Espresso</div>
                <div class="menu-price">Rp 25.000</div>
            </div>
            <div class="menu-card">
                <img class="menu-img" src="https://images.unsplash.com/photo-1541167760496-1628856ab772?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Vanilla Latte">
                <div class="menu-name">Vanilla Latte</div>
                <div class="menu-price">Rp 32.000</div>
            </div>
            <div class="menu-card">
                <img class="menu-img" src="https://images.unsplash.com/photo-1579954115545-a95591f28bfc?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Premium Matcha">
                <div class="menu-name">Premium Matcha</div>
                <div class="menu-price">Rp 35.000</div>
            </div>
            <div class="menu-card">
                <img class="menu-img" src="https://images.unsplash.com/photo-1550617931-e17a7b70dce2?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Butter Croissant">
                <div class="menu-name">Butter Croissant</div>
                <div class="menu-price">Rp 22.000</div>
            </div>
        </div>
    </section>

    <!-- FOOTER / KONTAK -->
    <footer id="kontak">
        <div class="footer-top">
            <div>
                <div class="footer-brand-name">
                    <img src="https://res.cloudinary.com/dtbut0lkj/image/upload/images_crg2rm.png" alt="Toreno">
                    TORENO
                </div>
                <p class="footer-tagline">Destinasi kopi pilihan dengan sistem pelayanan modern. Menyajikan rasa, menginspirasi cerita di setiap tegukannya.</p>
                <div class="social-row">
                    <a href="#" class="social-btn" aria-label="Twitter">
                        <svg fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                    </a>
                    <a href="#" class="social-btn" aria-label="Instagram">
                        <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.162 6.162 6.162 6.162-2.759 6.162-6.162-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                </div>
            </div>
            <div class="footer-col">
                <h4>Lokasi</h4>
                <p>Wates, Kulonprogo, DIY</p>
                <br>
                <a href="#">Lihat di Maps →</a>
            </div>
            <div class="footer-col">
                <h4>Jam Buka</h4>
                <div class="jam-row"><span>Senin – Jumat</span><span>08:00–22:00</span></div>
                <div class="jam-row"><span>Sabtu – Minggu</span><span>07:00–23:00</span></div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© 2025 Coffee TORENO. All rights reserved.</p>
            <div style="display:flex;gap:1.5rem;">
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
            </div>
        </div>
    </footer>

    <script>
        // Scroll reveal
        const reveals = document.querySelectorAll('.reveal');
        const observer = new IntersectionObserver(entries => {
            entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('active'); });
        }, { threshold: 0.12 });
        reveals.forEach(r => observer.observe(r));

        // Nav shadow on scroll
        const nav = document.getElementById('main-nav');
        window.addEventListener('scroll', () => {
            nav.classList.toggle('scrolled', window.scrollY > 40);
        });
    </script>
</body>
</html>
