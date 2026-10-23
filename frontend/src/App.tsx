import { useEffect, useState } from 'react';
import { motion, AnimatePresence } from 'motion/react';

const API_BASE = 'http://localhost:8000';

export interface Origin {
  id: number;
  name: string;
  region?: string;
}

export interface CoffeeProfile {
  process?: string;
  roast_level?: string;
  variety?: string;
  altitude_masl?: number;
  origin?: Origin;
  acidity?: number;
  body?: number;
  sweetness?: number;
}

export interface FlavorNote {
  id: number;
  name: string;
}

export interface ProductImage {
  id: number;
  path: string;
  url?: string;
  alt?: string;
  is_primary?: boolean;
}

export interface Variant {
  id: number;
  sku: string;
  name?: string;
  price: number;
  stock: number;
  weight_grams?: number;
}

export interface Category {
  id: number;
  name: string;
  slug: string;
  product_type?: string;
}

export interface Product {
  id: number;
  name: string;
  slug: string;
  type: string;
  short_description?: string;
  description?: string;
  price_from?: number;
  category?: Category;
  coffee_profile?: CoffeeProfile;
  flavor_notes?: FlavorNote[];
  variants?: Variant[];
  images?: ProductImage[];
}

export interface Banner {
  id: number;
  title: string;
  subtitle?: string;
  image?: string;
  image_url?: string;
  cta_label?: string;
  link_url?: string;
  placement?: string;
}

export interface Post {
  id: number;
  title: string;
  slug: string;
  type?: string;
  excerpt?: string;
  content?: string;
  cover_image?: string;
  cover_image_url?: string;
  published_at?: string;
}

// Fallback jika API belum mengembalikan data
const fallbackProducts: Product[] = [
  {
    id: 101,
    name: 'Aceh Gayo Wine Process Anaerobic',
    slug: 'aceh-gayo-wine-process-anaerobic',
    type: 'coffee',
    short_description: 'Aroma buah fermentasi manis yang intens dengan sentuhan red wine, strawberry jam, dan brown sugar aftertaste.',
    description: 'Biji kopi pilihan dari dataran tinggi Takengon, Aceh Tengah. Diproses fermentasi wine anaerobic selama 30 hari untuk memaksimalkan profil rasa fruity yang kompleks dan sweetness yang pekat.',
    price_from: 125000,
    coffee_profile: {
      origin: { id: 1, name: 'Aceh Gayo', region: 'Takengon' },
      process: 'Wine Anaerobic',
      roast_level: 'light',
      variety: 'Ateng Super, Timtim',
      altitude_masl: 1600,
      acidity: 4,
      body: 3,
      sweetness: 5,
    },
    flavor_notes: [
      { id: 1, name: 'Strawberry' },
      { id: 2, name: 'Peach' },
      { id: 3, name: 'Brown Sugar' },
    ],
    variants: [
      { id: 1, sku: 'GAYO-WINE-200G', name: '200 Gram Biji Utuh', price: 125000, stock: 50, weight_grams: 220 },
      { id: 2, sku: 'GAYO-WINE-500G', name: '500 Gram Biji Utuh', price: 285000, stock: 25, weight_grams: 530 },
      { id: 3, sku: 'GAYO-WINE-1KG', name: '1 Kilogram Biji Utuh', price: 490000, stock: 15, weight_grams: 1050 },
    ],
  },
  {
    id: 102,
    name: 'Flores Bajawa Kartika Washed',
    slug: 'flores-bajawa-kartika-washed',
    type: 'coffee',
    short_description: 'Karakter clean cup khas dataran tinggi Inerie dengan notes citrus, milk chocolate, dan caramel finish.',
    description: 'Ditanam di lereng Gunung Inerie, Bajawa, Flores. Proses fully washed menghasilkan kebersihan rasa yang elegan dengan keasaman segar dan aroma cokelat susu yang manis.',
    price_from: 95000,
    coffee_profile: {
      origin: { id: 2, name: 'Flores Bajawa', region: 'Ngada' },
      process: 'Fully Washed',
      roast_level: 'medium',
      variety: 'Kartika, S-795',
      altitude_masl: 1450,
      acidity: 3,
      body: 4,
      sweetness: 4,
    },
    flavor_notes: [
      { id: 4, name: 'Dark Chocolate' },
      { id: 5, name: 'Caramel' },
      { id: 6, name: 'Citrus' },
    ],
    variants: [
      { id: 4, sku: 'FLORES-200G', name: '200 Gram', price: 95000, stock: 40, weight_grams: 220 },
      { id: 5, sku: 'FLORES-500G', name: '500 Gram', price: 220000, stock: 20, weight_grams: 530 },
    ],
  },
  {
    id: 103,
    name: 'Ceremonial Grade Uji Matcha',
    slug: 'ceremonial-grade-uji-matcha',
    type: 'matcha',
    short_description: 'Matcha organik kualitas ceremonial grade dari Kyoto, rasa umami lembut, creamy, dan kaya antioksidan alami.',
    description: 'Dipanen pada first harvest di perkebunan naungan Uji, Kyoto. Daun teh tencha digiling batu secara perlahan menjadi bubuk halus selembut sutra dengan profil umami tebal.',
    price_from: 165000,
    flavor_notes: [
      { id: 7, name: 'Umami' },
      { id: 8, name: 'Sweet Grass' },
      { id: 9, name: 'Creamy' },
    ],
    variants: [
      { id: 6, sku: 'MATCHA-TIN-30G', name: 'Kaleng Kedap Udara 30 Gram', price: 165000, stock: 30, weight_grams: 120 },
      { id: 7, sku: 'MATCHA-POUCH-100G', name: 'Refill Pouch 100 Gram', price: 420000, stock: 15, weight_grams: 140 },
    ],
  },
  {
    id: 104,
    name: 'Crystal Glass V60 Dripper 02',
    slug: 'crystal-glass-v60-dripper-02',
    type: 'tool',
    short_description: 'Dripper kaca tahan panas dengan ulir spiral presisi untuk ekstraksi seduhan manual brew yang konsisten.',
    description: 'Dripper manual brew berbahan borosilicate glass tahan panas hingga 120°C. Struktur spiral spiral rib mengarahkan aliran air dengan presisi untuk ekstraksi optimal.',
    price_from: 210000,
    variants: [
      { id: 8, sku: 'TOOL-DRIP-02', name: 'Ukuran 02 (1-4 Cups)', price: 210000, stock: 18, weight_grams: 450 },
    ],
  },
];

export function App() {
  const [currentPage, setCurrentPage] = useState<'home' | 'philosophy' | 'detail'>('home');
  const [products, setProducts] = useState<Product[]>(fallbackProducts);
  const [banners, setBanners] = useState<Banner[]>([]);
  const [posts, setPosts] = useState<Post[]>([]);
  const [categories, setCategories] = useState<Category[]>([]);

  // Filters
  const [activeCategory, setActiveCategory] = useState<string>('all');
  const [selectedRoast, setSelectedRoast] = useState<string>('all');

  // Modal / Detail state
  const [selectedProduct, setSelectedProduct] = useState<Product | null>(null);
  const [selectedVariant, setSelectedVariant] = useState<Variant | null>(null);
  const [selectedImageIndex, setSelectedImageIndex] = useState<number>(0);
  const [cartCount, setCartCount] = useState<number>(0);
  const [notification, setNotification] = useState<string | null>(null);

  // Fetch data dari Backend API
  useEffect(() => {
    async function loadStorefrontData() {
      try {
        const [prodRes, bannerRes, postRes, catRes] = await Promise.allSettled([
          fetch(`${API_BASE}/api/storefront/products`).then((r) => r.json()),
          fetch(`${API_BASE}/api/storefront/banners`).then((r) => r.json()),
          fetch(`${API_BASE}/api/storefront/posts`).then((r) => r.json()),
          fetch(`${API_BASE}/api/storefront/categories`).then((r) => r.json()),
        ]);

        if (prodRes.status === 'fulfilled' && Array.isArray(prodRes.value) && prodRes.value.length > 0) {
          // Normalize price_from
          const loadedProducts = prodRes.value.map((p: Product) => {
            const minPrice = p.variants && p.variants.length > 0
              ? Math.min(...p.variants.map((v) => Number(v.price)))
              : p.price_from || 0;
            return { ...p, price_from: minPrice };
          });
          setProducts(loadedProducts);
        }

        if (bannerRes.status === 'fulfilled' && Array.isArray(bannerRes.value)) {
          setBanners(bannerRes.value);
        }

        if (postRes.status === 'fulfilled' && Array.isArray(postRes.value)) {
          setPosts(postRes.value);
        }

        if (catRes.status === 'fulfilled' && Array.isArray(catRes.value)) {
          setCategories(catRes.value);
        }
      } catch (e) {
        console.warn('Gagal memuat API storefront, menggunakan fallback lokal', e);
      }
    }

    loadStorefrontData();
  }, []);

  const getProductImage = (p: Product): string => {
    if (p.images && p.images.length > 0) {
      const primary = p.images.find((i) => i.is_primary) || p.images[0];
      if (primary.url) return primary.url;
      if (primary.path) return `${API_BASE}/storage/${primary.path}`;
    }
    return '';
  };

  const getBannerImage = (b: Banner): string => {
    if (b.image_url) return b.image_url;
    if (b.image) return `${API_BASE}/storage/${b.image}`;
    return '';
  };

  const getPostImage = (post: Post): string => {
    if (post.cover_image_url) return post.cover_image_url;
    if (post.cover_image) return `${API_BASE}/storage/${post.cover_image}`;
    return '';
  };

  const formatRupiah = (val?: number) => {
    if (!val) return 'Rp 0';
    return new Intl.NumberFormat('id-ID', {
      style: 'currency',
      currency: 'IDR',
      maximumFractionDigits: 0,
    }).format(val);
  };

  // Filter Produk
  const filteredProducts = products.filter((product) => {
    if (activeCategory !== 'all') {
      const matchType = product.type === activeCategory;
      const matchCat = product.category?.slug === activeCategory;
      if (!matchType && !matchCat) return false;
    }
    if (selectedRoast !== 'all') {
      if (product.coffee_profile?.roast_level !== selectedRoast) {
        return false;
      }
    }
    return true;
  });

  const openProductDetail = (p: Product) => {
    setSelectedProduct(p);
    setSelectedVariant(p.variants && p.variants.length > 0 ? p.variants[0] : null);
    setSelectedImageIndex(0);
    setCurrentPage('detail');
    window.scrollTo({ top: 0, behavior: 'smooth' });
  };

  const openQuickVariantModal = (p: Product) => {
    setSelectedProduct(p);
    setSelectedVariant(p.variants && p.variants.length > 0 ? p.variants[0] : null);
    setSelectedImageIndex(0);
  };

  const closeDetail = () => {
    setSelectedProduct(null);
    setSelectedVariant(null);
    if (currentPage === 'detail') {
      setCurrentPage('home');
    }
  };

  const addToCart = () => {
    setCartCount((c) => c + 1);
    setNotification(`Berhasil menambahkan "${selectedProduct?.name} (${selectedVariant?.name || 'Standard'})" ke keranjang`);
    setTimeout(() => setNotification(null), 3500);
    if (currentPage !== 'detail') {
      closeDetail();
    }
  };

  const scrollToSection = (id: string) => {
    if (currentPage !== 'home') {
      setCurrentPage('home');
      setTimeout(() => {
        const el = document.getElementById(id);
        if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }, 100);
    } else {
      const el = document.getElementById(id);
      if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  };

  const heroBanner = banners.find((b) => b.placement === 'home_hero') || banners[0];

  return (
    <div className="storefront-app">
      {/* Toast Notification */}
      <AnimatePresence>
        {notification && (
          <motion.div
            className="toast-notification"
            initial={{ opacity: 0, y: -40, x: '-50%' }}
            animate={{ opacity: 1, y: 0, x: '-50%' }}
            exit={{ opacity: 0, y: -30, x: '-50%' }}
            transition={{ duration: 0.3 }}
          >
            <span>✓</span> {notification}
          </motion.div>
        )}
      </AnimatePresence>

      {/* Top Sticky Header */}
      <motion.header
        className="header-top"
        initial={{ y: -60, opacity: 0 }}
        animate={{ y: 0, opacity: 1 }}
        transition={{ duration: 0.5, ease: 'easeOut' }}
      >
        <div className="nav-container">
          <div
            className="brand-logo"
            onClick={() => {
              setCurrentPage('home');
              window.scrollTo({ top: 0, behavior: 'smooth' });
            }}
            style={{ cursor: 'pointer' }}
          >
            <span>ORGANIC COFFEE & CO.</span>
            <span className="brand-badge">Zen Roast</span>
          </div>

          <nav>
            <ul className="nav-links">
              <li>
                <button
                  type="button"
                  className={`nav-link-btn ${currentPage === 'home' ? 'active' : ''}`}
                  onClick={() => {
                    setCurrentPage('home');
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                  }}
                >
                  HOME
                </button>
              </li>
              <li>
                <button
                  type="button"
                  className="nav-link-btn"
                  onClick={() => scrollToSection('shop')}
                >
                  SHOP ALL
                </button>
              </li>
              <li>
                <button
                  type="button"
                  className={`nav-link-btn ${currentPage === 'philosophy' ? 'active' : ''}`}
                  onClick={() => {
                    setCurrentPage('philosophy');
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                  }}
                >
                  OUR PHILOSOPHY
                </button>
              </li>
              <li>
                <button
                  type="button"
                  className="nav-link-btn"
                  onClick={() => scrollToSection('journal')}
                >
                  JOURNAL
                </button>
              </li>
              <li>
                <button
                  type="button"
                  className="nav-link-btn"
                  onClick={() => scrollToSection('wholesale')}
                >
                  WHOLESALE
                </button>
              </li>
            </ul>
          </nav>

          <div className="nav-actions">
            <div className="cart-badge-btn" title="Keranjang Belanja">
              <span>🛒</span>
              {cartCount > 0 && <span className="cart-count">{cartCount}</span>}
            </div>

            <motion.a
              href="/admin"
              className="btn-admin"
              whileHover={{ scale: 1.04 }}
              whileTap={{ scale: 0.96 }}
            >
              Panel Admin
            </motion.a>
          </div>
        </div>
      </motion.header>

      {/* VIEW: OUR PHILOSOPHY DEDICATED PAGE */}
      {currentPage === 'philosophy' && (
        <motion.div
          key="philosophy-page"
          className="philosophy-page container"
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          exit={{ opacity: 0, y: -20 }}
          transition={{ duration: 0.5 }}
        >
          <div className="breadcrumb-nav">
            <button
              type="button"
              className="breadcrumb-back"
              onClick={() => setCurrentPage('home')}
            >
              ← Kembali ke Beranda
            </button>
          </div>

          <div className="philosophy-header text-center">
            <span className="hero-pill">✦ Our Way of Coffee</span>
            <h1 className="philosophy-title">
              Kejujuran Tanah, Kemurnian Sangrai, <i>Kedamaian Jiwa</i>.
            </h1>
            <p className="philosophy-lead">
              Bagi kami, secangkir kopi atau matcha bukan sekadar asupan kafein harian. Ini adalah jeda hening di tengah riuhnya dunia—perjalanan meditatif yang menghubungkan bumi, tangan petani, dan cangkir Anda.
            </p>
          </div>

          <div className="philosophy-grid">
            <div className="philosophy-card">
              <div className="philosophy-icon">🌱</div>
              <h3>100% Organik & Regeneratif</h3>
              <p>
                Kami hanya bermitra dengan perkebunan agroforestri kopi naungan (shade-grown) yang menolak penggunaan pestisida sintetis dan pupuk kimiawi. Tanah yang dirawat dengan cinta melahirkan mikro-nutrisi terkaya bagi biji kopi.
              </p>
            </div>

            <div className="philosophy-card">
              <div className="philosophy-icon">🔥</div>
              <h3>Artisan Slow Micro-Roast</h3>
              <p>
                Tidak ada pemanggangan massal industri. Setiap batch disangrai manual dalam volume kecil (micro-lot) menggunakan profil sangrai presisi yang mengekspresikan karakter terroir asli daerah asalnya secara jujur.
              </p>
            </div>

            <div className="philosophy-card">
              <div className="philosophy-icon">🤝</div>
              <h3>Perdagangan Berkeadilan (Fair Trade)</h3>
              <p>
                Kualitas terbaik berhak atas penghargaan tertinggi. Kami membeli ceri kopi langsung dari kelompok tani dengan harga di atas standar pasar reguler untuk menjamin keberlanjutan hidup keluarga petani.
              </p>
            </div>

            <div className="philosophy-card">
              <div className="philosophy-icon">🍵</div>
              <h3>Ceremonial Matcha Kyoto</h3>
              <p>
                Untuk pecinta teh, dedikasi kami serupa: daun tencha first-harvest yang dinaungi dari sinar matahari selama 30 hari di perbukitan Uji, Kyoto. Menghasilkan rasa umami murni yang menenangkan tanpa rasa sepat.
              </p>
            </div>
          </div>

          <div className="philosophy-quote-box">
            <blockquote>
              "Ketenangan sejati bermula saat kita menikmati setiap tetes rasa yang diberikan alam dengan sepenuh kesadaran."
            </blockquote>
            <cite>— Head Roaster & Co-Founder Organic Coffee & Co.</cite>
          </div>

          <div className="text-center" style={{ marginTop: '3rem' }}>
            <motion.button
              type="button"
              className="btn-primary"
              onClick={() => {
                setCurrentPage('home');
                setTimeout(() => scrollToSection('shop'), 150);
              }}
              whileHover={{ scale: 1.03 }}
              whileTap={{ scale: 0.97 }}
            >
              Cicipi Seduhan Pilihan Kami →
            </motion.button>
          </div>
        </motion.div>
      )}

      {/* VIEW: PRODUCT DETAIL PAGE */}
      {currentPage === 'detail' && selectedProduct && (
        <motion.div
          key="detail-page"
          className="product-detail-page container"
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          exit={{ opacity: 0, y: -20 }}
          transition={{ duration: 0.4 }}
        >
          <div className="breadcrumb-nav">
            <button
              type="button"
              className="breadcrumb-back"
              onClick={() => setCurrentPage('home')}
            >
              ← Kembali ke Katalog
            </button>
            <span className="breadcrumb-divider">/</span>
            <span className="breadcrumb-current">{selectedProduct.name}</span>
          </div>

          <div className="detail-layout">
            {/* Gallery Left */}
            <div className="detail-gallery">
              <div className="detail-main-img-box">
                {selectedProduct.images && selectedProduct.images.length > 0 ? (
                  <img
                    src={
                      selectedProduct.images[selectedImageIndex]?.url ||
                      `${API_BASE}/storage/${selectedProduct.images[selectedImageIndex]?.path}`
                    }
                    alt={selectedProduct.name}
                    className="detail-main-img"
                  />
                ) : getProductImage(selectedProduct) ? (
                  <img
                    src={getProductImage(selectedProduct)}
                    alt={selectedProduct.name}
                    className="detail-main-img"
                  />
                ) : (
                  <div className="detail-img-fallback">
                    {selectedProduct.type === 'coffee' ? '☕' : selectedProduct.type === 'matcha' ? '🍵' : '🧪'}
                  </div>
                )}
              </div>

              {selectedProduct.images && selectedProduct.images.length > 1 && (
                <div className="detail-thumbs">
                  {selectedProduct.images.map((img, idx) => (
                    <button
                      key={img.id || idx}
                      type="button"
                      className={`thumb-btn ${selectedImageIndex === idx ? 'active' : ''}`}
                      onClick={() => setSelectedImageIndex(idx)}
                    >
                      <img
                        src={img.url || `${API_BASE}/storage/${img.path}`}
                        alt={img.alt || selectedProduct.name}
                      />
                    </button>
                  ))}
                </div>
              )}
            </div>

            {/* Info Right */}
            <div className="detail-info">
              <div className="detail-badges">
                <span className="card-badge">{selectedProduct.type}</span>
                {selectedProduct.coffee_profile?.roast_level && (
                  <span className="card-roast-badge">
                    {selectedProduct.coffee_profile.roast_level} roast
                  </span>
                )}
                {selectedProduct.coffee_profile?.origin && (
                  <span className="badge-origin">
                    📍 {selectedProduct.coffee_profile.origin.name}
                  </span>
                )}
              </div>

              <h1 className="detail-title">{selectedProduct.name}</h1>

              <div className="detail-price-row">
                <div className="detail-price">
                  {formatRupiah(selectedVariant ? selectedVariant.price : selectedProduct.price_from)}
                </div>
                {selectedVariant && (
                  <div className={`stock-status ${selectedVariant.stock > 0 ? 'in-stock' : 'out-of-stock'}`}>
                    {selectedVariant.stock > 0 ? `Tersedia: ${selectedVariant.stock} pcs` : 'Stok Habis'}
                  </div>
                )}
              </div>

              <p className="detail-short-desc">
                {selectedProduct.short_description || selectedProduct.description?.replace(/<[^>]+>/g, '')}
              </p>

              {/* Flavor Notes */}
              {selectedProduct.flavor_notes && selectedProduct.flavor_notes.length > 0 && (
                <div className="flavor-notes-section">
                  <div className="detail-subhead">Karakter Rasa (Tasting Notes):</div>
                  <div className="card-tags">
                    {selectedProduct.flavor_notes.map((fn) => (
                      <span key={fn.id} className="card-tag">
                        #{fn.name}
                      </span>
                    ))}
                  </div>
                </div>
              )}

              {/* Coffee Specifications */}
              {selectedProduct.coffee_profile && (
                <div className="spec-table-box">
                  <div className="detail-subhead">Spesifikasi Biji & Terroir:</div>
                  <div className="spec-grid">
                    <div>
                      <span className="spec-label">Proses Pascapanen</span>
                      <span className="spec-val">{selectedProduct.coffee_profile.process || '-'}</span>
                    </div>
                    <div>
                      <span className="spec-label">Varietas</span>
                      <span className="spec-val">{selectedProduct.coffee_profile.variety || '-'}</span>
                    </div>
                    <div>
                      <span className="spec-label">Ketinggian</span>
                      <span className="spec-val">
                        {selectedProduct.coffee_profile.altitude_masl ? `${selectedProduct.coffee_profile.altitude_masl} mdpl` : '-'}
                      </span>
                    </div>
                    <div>
                      <span className="spec-label">Profil Sangrai</span>
                      <span className="spec-val capitalize">
                        {selectedProduct.coffee_profile.roast_level || '-'}
                      </span>
                    </div>
                  </div>
                </div>
              )}

              {/* Variant Selector */}
              {selectedProduct.variants && selectedProduct.variants.length > 0 && (
                <div className="variant-selector-box">
                  <div className="detail-subhead">Pilih Varian / Ukuran:</div>
                  <div className="variant-buttons">
                    {selectedProduct.variants.map((v) => (
                      <button
                        key={v.id}
                        type="button"
                        className={`variant-choice-btn ${selectedVariant?.id === v.id ? 'active' : ''}`}
                        onClick={() => setSelectedVariant(v)}
                      >
                        <span className="variant-name">{v.name || v.sku}</span>
                        <span className="variant-price">{formatRupiah(v.price)}</span>
                      </button>
                    ))}
                  </div>
                </div>
              )}

              {/* Action Buttons */}
              <div className="detail-actions">
                <motion.button
                  type="button"
                  className="btn-primary btn-add-cart"
                  onClick={addToCart}
                  whileHover={{ scale: 1.02 }}
                  whileTap={{ scale: 0.98 }}
                >
                  Tambah Ke Keranjang • {formatRupiah(selectedVariant ? selectedVariant.price : selectedProduct.price_from)}
                </motion.button>
              </div>
            </div>
          </div>
        </motion.div>
      )}

      {/* VIEW: HOME PAGE (DEFAULT) */}
      {currentPage === 'home' && (
        <motion.div
          key="home-page"
          initial={{ opacity: 0 }}
          animate={{ opacity: 1 }}
          exit={{ opacity: 0 }}
          transition={{ duration: 0.4 }}
        >
          {/* Hero Section dengan Dynamic Banner */}
          <section id="hero" className="hero-section">
            {heroBanner && getBannerImage(heroBanner) && (
              <div
                className="hero-bg-cover"
                style={{ backgroundImage: `url(${getBannerImage(heroBanner)})` }}
              />
            )}
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
                {heroBanner?.title ? (
                  heroBanner.title
                ) : (
                  <>Harmoni Rasa Alami dalam <span>Setiap Seduhan</span>.</>
                )}
              </motion.h1>
              <motion.p
                className="hero-subtitle"
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ delay: 0.4, duration: 0.6 }}
              >
                {heroBanner?.subtitle ||
                  'Koleksi kurasi biji kopi organik single origin dan ceremonial grade matcha. Diproses secara etis dan disangrai dengan presisi untuk kejernihan rasa murni.'}
              </motion.p>
              <motion.div
                className="hero-cta"
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ delay: 0.5, duration: 0.6 }}
              >
                <motion.button
                  type="button"
                  className="btn-primary"
                  onClick={() => scrollToSection('shop')}
                  whileHover={{ scale: 1.03, y: -2 }}
                  whileTap={{ scale: 0.97 }}
                >
                  {heroBanner?.cta_label || 'Jelajahi Koleksi'}
                </motion.button>
                <motion.button
                  type="button"
                  className="btn-secondary"
                  onClick={() => {
                    setCurrentPage('philosophy');
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                  }}
                  whileHover={{ scale: 1.03, y: -2 }}
                  whileTap={{ scale: 0.97 }}
                >
                  Filosofi Kami
                </motion.button>
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
                  type="button"
                  className={`pill-btn ${selectedRoast === 'all' ? 'active' : ''}`}
                  onClick={() => setSelectedRoast('all')}
                >
                  Semua Roast
                </button>
                <button
                  type="button"
                  className={`pill-btn ${selectedRoast === 'light' ? 'active' : ''}`}
                  onClick={() => setSelectedRoast('light')}
                >
                  Light Roast
                </button>
                <button
                  type="button"
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
                type="button"
                className={`pill-btn ${activeCategory === 'all' ? 'active' : ''}`}
                onClick={() => setActiveCategory('all')}
              >
                Semua Produk ({products.length})
              </button>
              <button
                type="button"
                className={`pill-btn ${activeCategory === 'coffee' ? 'active' : ''}`}
                onClick={() => setActiveCategory('coffee')}
              >
                Kopi Organik
              </button>
              <button
                type="button"
                className={`pill-btn ${activeCategory === 'matcha' ? 'active' : ''}`}
                onClick={() => setActiveCategory('matcha')}
              >
                Matcha & Tea
              </button>
              <button
                type="button"
                className={`pill-btn ${activeCategory === 'tool' ? 'active' : ''}`}
                onClick={() => setActiveCategory('tool')}
              >
                Alat Seduh
              </button>
              {categories.map((c) => (
                <button
                  key={c.id}
                  type="button"
                  className={`pill-btn ${activeCategory === c.slug ? 'active' : ''}`}
                  onClick={() => setActiveCategory(c.slug)}
                >
                  {c.name}
                </button>
              ))}
            </div>

            {/* Product Grid */}
            <motion.div layout className="product-grid">
              <AnimatePresence>
                {filteredProducts.map((p) => {
                  const coverImg = getProductImage(p);
                  return (
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
                      <div
                        className="card-img-wrapper"
                        onClick={() => openProductDetail(p)}
                        style={{ cursor: 'pointer' }}
                      >
                        {coverImg ? (
                          <img src={coverImg} alt={p.name} className="card-img" />
                        ) : (
                          <span className="card-img-placeholder">
                            {p.type === 'coffee' ? '☕' : p.type === 'matcha' ? '🍵' : '🧪'}
                          </span>
                        )}
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

                        <h3
                          className="card-title"
                          onClick={() => openProductDetail(p)}
                          style={{ cursor: 'pointer' }}
                        >
                          {p.name}
                        </h3>

                        <p className="card-desc">
                          {p.short_description || p.description?.replace(/<[^>]+>/g, '')}
                        </p>

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
                            type="button"
                            className="btn-card"
                            onClick={() => openQuickVariantModal(p)}
                            whileHover={{ scale: 1.05 }}
                            whileTap={{ scale: 0.95 }}
                          >
                            Pilih Varian
                          </motion.button>
                        </div>
                      </div>
                    </motion.div>
                  );
                })}
              </AnimatePresence>
            </motion.div>

            {/* Our Story / Zen Philosophy Preview */}
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
                  type="button"
                  className="btn-secondary"
                  onClick={() => {
                    setCurrentPage('philosophy');
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                  }}
                  whileHover={{ scale: 1.03 }}
                  whileTap={{ scale: 0.97 }}
                >
                  Baca Selengkapnya Tentang Filosofi Kami →
                </motion.button>
              </div>

              <div className="wholesale-box" id="wholesale">
                <h3>Wholesale & Kemitraan Kafe</h3>
                <p>Pasokan biji sangrai stabil dan ceremonial matcha asli untuk kafe & bisnis F&B Anda.</p>
                <motion.button
                  type="button"
                  className="btn-primary"
                  whileHover={{ scale: 1.04 }}
                  whileTap={{ scale: 0.96 }}
                  onClick={() => {
                    alert('Silakan ajukan kemitraan via kontak atau login ke Panel Admin untuk mengelola penawaran wholesale.');
                  }}
                >
                  Ajukan Penawaran B2B
                </motion.button>
              </div>
            </motion.section>

            {/* Journal & Articles Section (Live from Posts API) */}
            {posts.length > 0 && (
              <section id="journal" className="journal-section" style={{ marginTop: '5rem' }}>
                <div className="section-head">
                  <div>
                    <h2 className="section-title">Journal & Edukasi Seduh</h2>
                    <p className="section-desc">Catatan perjalanan kebun, panduan seduh manual, dan wawasan artisan kopi.</p>
                  </div>
                </div>

                <div className="journal-grid">
                  {posts.map((post) => {
                    const postImg = getPostImage(post);
                    return (
                      <div key={post.id} className="journal-card">
                        <div className="journal-img-wrapper">
                          {postImg ? (
                            <img src={postImg} alt={post.title} className="journal-img" />
                          ) : (
                            <div className="journal-placeholder">📖</div>
                          )}
                        </div>
                        <div className="journal-body">
                          <span className="journal-tag">{post.type || 'Journal'}</span>
                          <h3 className="journal-title">{post.title}</h3>
                          <p className="journal-excerpt">{post.excerpt}</p>
                        </div>
                      </div>
                    );
                  })}
                </div>
              </section>
            )}
          </main>
        </motion.div>
      )}

      {/* QUICK VARIANT MODAL */}
      <AnimatePresence>
        {selectedProduct && currentPage !== 'detail' && (
          <motion.div
            className="modal-overlay"
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
            onClick={closeDetail}
          >
            <motion.div
              className="modal-card"
              initial={{ scale: 0.9, opacity: 0, y: 30 }}
              animate={{ scale: 1, opacity: 1, y: 0 }}
              exit={{ scale: 0.9, opacity: 0, y: 20 }}
              transition={{ type: 'spring', damping: 25, stiffness: 300 }}
              onClick={(e) => e.stopPropagation()}
            >
              <button
                type="button"
                className="modal-close"
                onClick={closeDetail}
              >
                ✕
              </button>

              <div className="modal-content">
                <div className="modal-img-wrapper">
                  {getProductImage(selectedProduct) ? (
                    <img
                      src={getProductImage(selectedProduct)}
                      alt={selectedProduct.name}
                      className="modal-img"
                    />
                  ) : (
                    <span className="modal-img-placeholder">
                      {selectedProduct.type === 'coffee' ? '☕' : selectedProduct.type === 'matcha' ? '🍵' : '🧪'}
                    </span>
                  )}
                </div>

                <div className="modal-info">
                  <div className="card-badge" style={{ display: 'inline-block', marginBottom: '0.5rem' }}>
                    {selectedProduct.type}
                  </div>
                  <h2 className="modal-title">{selectedProduct.name}</h2>
                  <p className="modal-desc">{selectedProduct.short_description}</p>

                  {/* Varian Selection */}
                  <div className="variant-block">
                    <div className="detail-subhead">Pilih Varian Produk:</div>
                    <div className="variant-buttons">
                      {selectedProduct.variants && selectedProduct.variants.length > 0 ? (
                        selectedProduct.variants.map((v) => (
                          <button
                            key={v.id}
                            type="button"
                            className={`variant-choice-btn ${selectedVariant?.id === v.id ? 'active' : ''}`}
                            onClick={() => setSelectedVariant(v)}
                          >
                            <span className="variant-name">{v.name || v.sku}</span>
                            <span className="variant-price">{formatRupiah(v.price)}</span>
                          </button>
                        ))
                      ) : (
                        <div className="variant-choice-btn active">
                          <span className="variant-name">Standard</span>
                          <span className="variant-price">{formatRupiah(selectedProduct.price_from)}</span>
                        </div>
                      )}
                    </div>
                  </div>

                  <div className="modal-footer">
                    <div>
                      <div className="card-price-label">Harga Pilihan:</div>
                      <div className="modal-price">
                        {formatRupiah(selectedVariant ? selectedVariant.price : selectedProduct.price_from)}
                      </div>
                    </div>

                    <div style={{ display: 'flex', gap: '0.6rem' }}>
                      <button
                        type="button"
                        className="btn-secondary"
                        style={{ padding: '0.65rem 1.25rem', fontSize: '0.85rem' }}
                        onClick={() => openProductDetail(selectedProduct)}
                      >
                        Detail Lengkap
                      </button>
                      <motion.button
                        type="button"
                        className="btn-primary"
                        onClick={addToCart}
                        whileHover={{ scale: 1.03 }}
                        whileTap={{ scale: 0.97 }}
                      >
                        + Keranjang
                      </motion.button>
                    </div>
                  </div>
                </div>
              </div>
            </motion.div>
          </motion.div>
        )}
      </AnimatePresence>

      {/* Store Footer */}
      <footer className="store-footer">
        <div className="footer-content">
          <div className="footer-brand">
            <h4>ORGANIC COFFEE & CO.</h4>
            <p>Boutique organic coffee roastery & ceremonial matcha purveyor. Kejernihan rasa dan ketenangan seduh setiap hari.</p>
          </div>

          <div className="footer-col">
            <h5>Navigasi</h5>
            <ul>
              <li>
                <button
                  type="button"
                  className="footer-link-btn"
                  onClick={() => {
                    setCurrentPage('home');
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                  }}
                >
                  Home
                </button>
              </li>
              <li>
                <button
                  type="button"
                  className="footer-link-btn"
                  onClick={() => scrollToSection('shop')}
                >
                  Shop All
                </button>
              </li>
              <li>
                <button
                  type="button"
                  className="footer-link-btn"
                  onClick={() => {
                    setCurrentPage('philosophy');
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                  }}
                >
                  Our Philosophy
                </button>
              </li>
              <li>
                <button
                  type="button"
                  className="footer-link-btn"
                  onClick={() => scrollToSection('wholesale')}
                >
                  Wholesale B2B
                </button>
              </li>
            </ul>
          </div>

          <div className="footer-col">
            <h5>Layanan</h5>
            <ul>
              <li><a href="#tracking">Tracking Pengiriman</a></li>
              <li><a href="#guide">Panduan Seduh Zen</a></li>
              <li><a href="#terms">Ketentuan Pembelian</a></li>
              <li><a href="#privacy">Privasi</a></li>
            </ul>
          </div>

          <div className="footer-col">
            <h5>Panel Admin</h5>
            <ul>
              <li><a href="/admin">Login Admin</a></li>
              <li><a href="/admin/products">Kelola Produk</a></li>
            </ul>
          </div>
        </div>

        <div className="footer-bottom">
          <div>&copy; {new Date().getFullYear()} Organic Coffee & Co. All rights reserved.</div>
          <div>Mata Uang: IDR (Rupiah)</div>
        </div>
      </footer>
    </div>
  );
}

export default App;
