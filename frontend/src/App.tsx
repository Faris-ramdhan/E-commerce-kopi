import { useState } from 'react';
import { motion, AnimatePresence } from 'motion/react';

interface CoffeeProfile {
  process?: string;
  roast_level?: string;
  variety?: string;
  altitude_masl?: number;
  origin?: {
    name: string;
    region?: string;
  };
}

interface FlavorNote {
  id: number;
  name: string;
}

interface Variant {
  id: number;
  sku: string;
  name?: string;
  price: number;
  stock: number;
  weight_grams: number;
}

interface Product {
  id: number;
  name: string;
  slug: string;
  type: string;
  short_description?: string;
  price_from?: number;
  coffee_profile?: CoffeeProfile;
  flavor_notes?: FlavorNote[];
  variants?: Variant[];
}

const mockProducts: Product[] = [
  {
    id: 1,
    name: 'Aceh Gayo Wine Process Anaerobic',
    slug: 'aceh-gayo-wine-process-anaerobic',
    type: 'coffee',
    short_description: 'Aroma buah fermentasi manis yang intens dengan sentuhan red wine, strawberry jam, dan brown sugar aftertaste.',
    price_from: 125000,
    coffee_profile: {
      origin: { name: 'Aceh Gayo', region: 'Takengon' },
      process: 'Wine Anaerobic',
      roast_level: 'light',
      altitude_masl: 1600,
    },
    flavor_notes: [
      { id: 1, name: 'Strawberry' },
      { id: 2, name: 'Peach' },
      { id: 3, name: 'Brown Sugar' },
    ],
    variants: [
      { id: 1, sku: 'GAYO-WINE-200G', name: '200 Gram', price: 125000, stock: 50, weight_grams: 220 },
      { id: 2, sku: 'GAYO-WINE-500G', name: '500 Gram', price: 285000, stock: 25, weight_grams: 530 },
    ],
  },
  {
    id: 2,
    name: 'Flores Bajawa Kartika Washed',
    slug: 'flores-bajawa-kartika-washed',
    type: 'coffee',
    short_description: 'Karakter clean cup khas dataran tinggi Inerie dengan notes citrus, milk chocolate, dan caramel finish.',
    price_from: 95000,
    coffee_profile: {
      origin: { name: 'Flores Bajawa', region: 'Ngada' },
      process: 'Fully Washed',
      roast_level: 'medium',
      altitude_masl: 1450,
    },
    flavor_notes: [
      { id: 4, name: 'Dark Chocolate' },
      { id: 5, name: 'Caramel' },
      { id: 6, name: 'Citrus' },
    ],
    variants: [
      { id: 3, sku: 'FLORES-200G', name: '200 Gram', price: 95000, stock: 40, weight_grams: 220 },
    ],
  },
  {
    id: 3,
    name: 'Ceremonial Grade Uji Matcha',
    slug: 'ceremonial-grade-uji-matcha',
    type: 'matcha',
    short_description: 'Matcha organik kualitas ceremonial grade dari Kyoto, rasa umami lembut, creamy, dan kaya antioksidan alami.',
    price_from: 165000,
    flavor_notes: [
      { id: 7, name: 'Umami' },
      { id: 8, name: 'Sweet Grass' },
      { id: 9, name: 'Creamy' },
    ],
    variants: [
      { id: 5, sku: 'MATCHA-TIN-30G', name: 'Kaleng 30 Gram', price: 165000, stock: 30, weight_grams: 120 },
    ],
  },
  {
    id: 4,
    name: 'Crystal Glass V60 Dripper 02',
    slug: 'crystal-glass-v60-dripper-02',
    type: 'tool',
    short_description: 'Dripper kaca tahan panas dengan ulir spiral presisi untuk ekstraksi seduhan manual brew yang konsisten.',
    price_from: 210000,
    variants: [
      { id: 4, sku: 'TOOL-DRIP-02', name: 'Ukuran 02', price: 210000, stock: 18, weight_grams: 450 },
    ],
  },
];

export function App() {
  const [activeCategory, setActiveCategory] = useState<string>('all');
  const [selectedRoast, setSelectedRoast] = useState<string>('all');

  const filteredProducts = mockProducts.filter((product) => {
    if (activeCategory !== 'all' && product.type !== activeCategory) {
      return false;
    }
    if (
      selectedRoast !== 'all' &&
      product.coffee_profile?.roast_level !== selectedRoast
    ) {
      return false;
    }
    return true;
  });

  const formatRupiah = (val?: number) => {
    if (!val) return 'Rp 0';
    return new Intl.NumberFormat('id-ID', {
      style: 'currency',
      currency: 'IDR',
      maximumFractionDigits: 0,
    }).format(val);
  };

  return (
    <div className="storefront-app">
      {/* Top Navigation */}
      <motion.header
        className="header-top"
        initial={{ y: -60, opacity: 0 }}
        animate={{ y: 0, opacity: 1 }}
        transition={{ duration: 0.5, ease: 'easeOut' }}
      >
        <div className="nav-container">
          <div className="brand-logo">
            <span>ORGANIC COFFEE & CO.</span>
            <span className="brand-badge">Zen Roast</span>
          </div>

          <nav>
            <ul className="nav-links">
              <li><a href="#hero" className="active">HOME</a></li>
              <li><a href="#shop">SHOP ALL</a></li>
              <li><a href="#story">OUR PHILOSOPHY</a></li>
              <li><a href="#wholesale">WHOLESALE</a></li>
              <li><a href="#contact">CONTACT</a></li>
            </ul>
          </nav>

          <div className="nav-actions">
            <motion.a
              href="http://localhost:8000/admin"
              target="_blank"
              rel="noreferrer"
              className="btn-admin"
              whileHover={{ scale: 1.04 }}
              whileTap={{ scale: 0.96 }}
            >
              Panel Admin
            </motion.a>
          </div>
        </div>
      </motion.header>

      {/* Hero Section */}
      <section id="hero" className="hero-section">
        <motion.div
          className="hero-content"
          initial={{ opacity: 0, y: 30 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.7, ease: [0.22, 1, 0.36, 1] }}
        >
          <motion.div
            className="hero-pill"
            initial={{ opacity: 0, scale: 0.9 }}
            animate={{ opacity: 1, scale: 1 }}
            transition={{ delay: 0.2, duration: 0.5 }}
          >
            <span>✦</span> Purest Beans, Cleanest Energy
          </motion.div>
          <motion.h1
            className="hero-title"
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ delay: 0.3, duration: 0.6 }}
          >
            Harmoni Rasa Alami dalam <span>Setiap Seduhan</span>.
          </motion.h1>
          <motion.p
            className="hero-subtitle"
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ delay: 0.4, duration: 0.6 }}
          >
            Koleksi kurasi biji kopi organik single origin dan ceremonial grade matcha.
            Diproses secara etis dan disangrai dengan presisi untuk kejernihan rasa murni.
          </motion.p>
          <motion.div
            className="hero-cta"
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ delay: 0.5, duration: 0.6 }}
          >
            <motion.a
              href="#shop"
              className="btn-primary"
              whileHover={{ scale: 1.03, y: -2 }}
              whileTap={{ scale: 0.97 }}
            >
              Jelajahi Koleksi
            </motion.a>
            <motion.a
              href="#wholesale"
              className="btn-secondary"
              whileHover={{ scale: 1.03, y: -2 }}
              whileTap={{ scale: 0.97 }}
            >
              Kemitraan Wholesale
            </motion.a>
          </motion.div>
        </motion.div>
      </section>

      {/* Main Catalog Section */}
      <main id="shop" className="container">
        <div className="section-head">
          <div>
            <h2 className="section-title">Koleksi Produk Pilihan</h2>
            <p className="section-desc">Eksplorasi kopi organik, teh matcha artisan, dan perlengkapan seduh zen.</p>
          </div>

          {/* Roast Level filter */}
          <div className="category-pills" style={{ marginBottom: 0 }}>
            <button
              className={`pill-btn ${selectedRoast === 'all' ? 'active' : ''}`}
              onClick={() => setSelectedRoast('all')}
            >
              Semua Roast
            </button>
            <button
              className={`pill-btn ${selectedRoast === 'light' ? 'active' : ''}`}
              onClick={() => setSelectedRoast('light')}
            >
              Light Roast
            </button>
            <button
              className={`pill-btn ${selectedRoast === 'medium' ? 'active' : ''}`}
              onClick={() => setSelectedRoast('medium')}
            >
              Medium Roast
            </button>
          </div>
        </div>

        {/* Category Pills */}
        <div className="category-pills">
          <button
            className={`pill-btn ${activeCategory === 'all' ? 'active' : ''}`}
            onClick={() => setActiveCategory('all')}
          >
            Semua Produk
          </button>
          <button
            className={`pill-btn ${activeCategory === 'coffee' ? 'active' : ''}`}
            onClick={() => setActiveCategory('coffee')}
          >
            Kopi Organik
          </button>
          <button
            className={`pill-btn ${activeCategory === 'matcha' ? 'active' : ''}`}
            onClick={() => setActiveCategory('matcha')}
          >
            Matcha & Tea
          </button>
          <button
            className={`pill-btn ${activeCategory === 'tool' ? 'active' : ''}`}
            onClick={() => setActiveCategory('tool')}
          >
            Alat Seduh
          </button>
        </div>

        {/* Product Grid with Motion Animation */}
        <motion.div layout className="product-grid">
          <AnimatePresence>
            {filteredProducts.map((p) => (
              <motion.div
                key={p.id}
                layout
                initial={{ opacity: 0, scale: 0.95 }}
                animate={{ opacity: 1, scale: 1 }}
                exit={{ opacity: 0, scale: 0.95 }}
                transition={{ duration: 0.35 }}
                whileHover={{ y: -6 }}
                className="product-card"
              >
                <div className="card-img-wrapper">
                  <span className="card-img-placeholder">
                    {p.type === 'coffee' ? '☕' : p.type === 'matcha' ? '🍵' : '🧪'}
                  </span>
                  <span className="card-badge">{p.type}</span>
                  {p.coffee_profile?.roast_level && (
                    <span className="card-roast-badge">
                      {p.coffee_profile.roast_level}
                    </span>
                  )}
                </div>

                <div className="card-body">
                  {p.coffee_profile?.origin && (
                    <div className="card-meta">
                      {p.coffee_profile.origin.name} • {p.coffee_profile.process}
                    </div>
                  )}
                  {p.type === 'matcha' && (
                    <div className="card-meta">Uji, Kyoto • Ceremonial</div>
                  )}
                  <h3 className="card-title">{p.name}</h3>
                  <p className="card-desc">{p.short_description}</p>

                  {p.flavor_notes && p.flavor_notes.length > 0 && (
                    <div className="card-tags">
                      {p.flavor_notes.map((fn) => (
                        <span key={fn.id} className="card-tag">
                          #{fn.name}
                        </span>
                      ))}
                    </div>
                  )}

                  <div className="card-footer">
                    <div>
                      <div className="card-price-label">Mulai Dari</div>
                      <div className="card-price">{formatRupiah(p.price_from)}</div>
                    </div>
                    <motion.button
                      className="btn-card"
                      whileHover={{ scale: 1.05 }}
                      whileTap={{ scale: 0.95 }}
                    >
                      Pilih Varian
                    </motion.button>
                  </div>
                </div>
              </motion.div>
            ))}
          </AnimatePresence>
        </motion.div>

        {/* Our Story / Zen Philosophy */}
        <motion.section
          id="story"
          className="story-box"
          initial={{ opacity: 0, y: 40 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true, margin: '-100px' }}
          transition={{ duration: 0.7 }}
        >
          <div>
            <div className="story-tag">OUR PHILOSOPHY</div>
            <h2 className="story-heading">
              Ketenangan & Kejujuran Rasa Dari Kebun Ke Cangkir
            </h2>
            <p className="story-text">
              Kami percaya secangkir kopi atau matcha terbaik hadir dari tanah yang dirawat
              secara organik tanpa pestisida, petikan tangan para petani lokal yang dihargai adil,
              dan profil sangrai lambat yang menjaga keaslian terroir nusantara.
            </p>
            <motion.button
              className="btn-secondary"
              whileHover={{ scale: 1.03 }}
              whileTap={{ scale: 0.97 }}
            >
              Baca Jurnal Zen Kami
            </motion.button>
          </div>

          <div style={{ textAlign: 'center', background: '#FAF9F5', padding: '2.5rem', borderRadius: '16px', border: '1px solid #E3D5CA' }}>
            <div style={{ fontSize: '1.2rem', color: '#2C302E', fontFamily: 'Playfair Display, serif', fontWeight: 700, marginBottom: '0.6rem' }}>
              Wholesale & Kemitraan Kafe
            </div>
            <p style={{ fontSize: '0.875rem', color: '#5A605C', marginBottom: '1.5rem', lineHeight: 1.6 }}>
              Pasokan biji sangrai stabil dan ceremonial matcha asli untuk kafe & bisnis F&B Anda.
            </p>
            <motion.a
              href="http://localhost:8000/admin"
              className="btn-primary"
              style={{ display: 'inline-block', fontSize: '0.85rem' }}
              whileHover={{ scale: 1.04 }}
              whileTap={{ scale: 0.96 }}
            >
              Ajukan Penawaran B2B
            </motion.a>
          </div>
        </motion.section>
      </main>

      {/* Footer */}
      <footer className="store-footer">
        <div className="footer-content">
          <div className="footer-brand">
            <h4>ORGANIC COFFEE & CO.</h4>
            <p>
              Boutique organic coffee roastery & ceremonial matcha purveyor.
              Kejernihan rasa dan ketenangan seduh setiap hari.
            </p>
          </div>

          <div className="footer-col">
            <h5>Navigasi</h5>
            <ul>
              <li><a href="#hero">Home</a></li>
              <li><a href="#shop">Shop All</a></li>
              <li><a href="#story">Our Philosophy</a></li>
              <li><a href="#wholesale">Wholesale B2B</a></li>
            </ul>
          </div>

          <div className="footer-col">
            <h5>Layanan</h5>
            <ul>
              <li><a href="#">Tracking Pengiriman</a></li>
              <li><a href="#">Panduan Seduh Zen</a></li>
              <li><a href="#">Ketentuan Pembelian</a></li>
              <li><a href="#">Privasi</a></li>
            </ul>
          </div>

          <div className="footer-col">
            <h5>Panel Admin</h5>
            <ul>
              <li><a href="http://localhost:8000/admin/login">Login Admin</a></li>
              <li><a href="http://localhost:8000/admin/products">Kelola Produk</a></li>
            </ul>
          </div>
        </div>

        <div className="footer-bottom">
          <div>© {new Date().getFullYear()} Organic Coffee & Co. All rights reserved.</div>
          <div>Mata Uang: IDR (Rupiah)</div>
        </div>
      </footer>
    </div>
  );
}

export default App;
