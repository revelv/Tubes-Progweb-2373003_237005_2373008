-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 17, 2025 at 04:48 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `styrk_industries`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int NOT NULL,
  `username` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `password` text COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `username`, `password`) VALUES
(101, 'admin', '$2y$10$fbdJi7jdC0xfeKCuZSDaG.Fv6TM7Hiuway3HYMDNnwqKziU9TsOUy');

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `cart_id` int NOT NULL,
  `customer_id` int NOT NULL,
  `product_id` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `jumlah_barang` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`cart_id`, `customer_id`, `product_id`, `jumlah_barang`) VALUES
(1, 6, 'KB002', 2),
(2, 6, 'KB001', 1);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int NOT NULL,
  `category` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi_category` text COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `category`, `deskripsi_category`) VALUES
(1, 'Case', 'Casing dari Keyboard'),
(2, 'Case', 'Casing dari Keyboard');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_id` int NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password` text COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `no_telepon` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customer_id`, `nama`, `password`, `email`, `no_telepon`, `alamat`) VALUES
(5, 'Jeremia', '$2y$10$fwucJezb3RQTmc5Gz2a56uyAjvXYyMdS/V38v913qqxpJtpMF5sRS', 'jeremiadylan80@gmail.com', '081312663058', 'Jalan kebenara 169'),
(6, 'Jeremia', '$2y$10$JK.Mo5XVtj0TCs2ikcXCBevbN5By6w9KYArlaRgMFBXFlpJK5gmFO', 'jeremiaethan05@gmail.com', '081312663058', 'Jalan asmi no 123'),
(7, 'Aldy Taher', '$2y$10$EiyoeSPMZt2kKDkP5bB0h.7ae7fA5dvhz4uJpgwSFup5viqMXjIlK', 'guaganteng@123.com', '123456', 'jalan tuhan kebenaran no. 100');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int NOT NULL,
  `customer_id` int NOT NULL,
  `tgl_order` datetime NOT NULL,
  `total_harga` decimal(12,2) NOT NULL,
  `status` varchar(20) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `customer_id`, `tgl_order`, `total_harga`, `status`) VALUES
(2, 5, '2025-06-10 15:33:00', 0.00, 'proses');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `detail_id` int NOT NULL,
  `order_id` int NOT NULL,
  `product_id` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `jumlah` int NOT NULL,
  `harga_satuan` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_tracking`
--

CREATE TABLE `order_tracking` (
  `tracking_id` int NOT NULL,
  `order_id` int NOT NULL,
  `status` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `timestamp` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_tracking`
--

INSERT INTO `order_tracking` (`tracking_id`, `order_id`, `status`, `description`, `timestamp`) VALUES
(14, 1000, 'Pesanan diterima', 'Pesanan masuk via website', '2025-06-11 17:32:57'),
(15, 1000, 'Pesanan diproses', 'Stok tersedia, menunggu packaging', '2025-06-11 19:32:57'),
(16, 1000, 'Pesanan dikemas', 'No. paket: PKG-1000-001', '2025-06-11 21:32:57'),
(17, 1000, 'Pesanan dikirim', 'No. resi: JNE-1234567890', '2025-06-12 00:32:57'),
(18, 1000, 'Pesanan sampai di gerai terdekat', 'Telah sampai di cabang Jakarta Selatan', '2025-06-12 16:32:57'),
(19, 1000, 'Pesanan diterima customer', 'Diterima oleh Budi Santoso', '2025-06-13 16:32:57'),
(20, 1001, 'Pesanan diterima', 'Pesanan masuk via mobile app', '2025-06-13 18:32:57'),
(21, 1001, 'Pesanan diproses', 'Menunggu konfirmasi stok', '2025-06-13 20:32:57'),
(22, 1001, 'Pesanan dikemas', 'No. paket: PKG-1001-001', '2025-06-14 16:32:57'),
(23, 1003, 'Pesanan diterima', 'Pesanan masuk via website', '2025-06-14 17:32:57'),
(24, 1003, 'Pesanan diproses', 'Sedang quality check', '2025-06-14 19:32:57'),
(25, 1002, 'Pesanan diterima', 'Pesanan masuk via website', '2025-06-15 17:02:57'),
(26, 1003, 'Pesanan dikemas', '', '2025-06-16 16:51:25'),
(27, 1003, 'Pesanan dikirim', 'jne', '2025-06-16 16:51:33'),
(28, 1003, 'Pesanan sampai di gerai terdekat', 'test1', '2025-06-16 16:51:37'),
(29, 1003, 'Pesanan sampai di gerai', '2', '2025-06-16 16:56:13'),
(30, 1003, 'Pesanan keluar dari gerai', '', '2025-06-16 16:58:39'),
(31, 1003, 'Pesanan dikirim ke customer', '', '2025-06-16 16:58:47'),
(32, 1003, 'Pesanan diterima customer', '', '2025-06-16 16:58:51'),
(33, 1001, 'Pesanan dikirim ke gerai', '', '2025-06-16 16:58:58'),
(34, 1001, 'Pesanan sampai di gerai', '', '2025-06-16 16:59:01'),
(35, 1001, 'Pesanan keluar dari gerai', '', '2025-06-16 16:59:07'),
(36, 1001, 'Pesanan dikirim ke gerai', '', '2025-06-16 17:07:37'),
(37, 1001, 'Pesanan sampai di gerai', '', '2025-06-16 17:07:40'),
(38, 1001, 'Pesanan keluar dari gerai', '', '2025-06-16 17:07:44'),
(39, 1001, 'Pesanan dikirim ke customer', '', '2025-06-16 17:07:47'),
(40, 1001, 'Pesanan diterima customer', '', '2025-06-16 17:07:50');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int NOT NULL,
  `order_id` int NOT NULL,
  `metode` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `jumlah_dibayar` decimal(12,2) NOT NULL,
  `tanggal_bayar` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `nama_produk` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi_produk` text COLLATE utf8mb4_general_ci NOT NULL,
  `harga` decimal(12,2) NOT NULL,
  `harga_diskon` decimal(12,2) NOT NULL,
  `status_diskon` tinyint(1) NOT NULL,
  `stok` int NOT NULL,
  `category` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `link_gambar` varchar(300) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `nama_produk`, `deskripsi_produk`, `harga`, `harga_diskon`, `status_diskon`, `stok`, `category`, `link_gambar`) VALUES
('CS001', 'Tofu60 Redux Case', 'An upgraded version of the classic Tofu60 case, offering improved materials, finish, and design features. Compatible with a wide range of 60% PCBs and plates.', 115.00, 115.00, 0, 10, 'Case', 'https://i.postimg.cc/T1D3LRzQ/11.jpg'),
('KB001', 'Sirius Manta', 'A premium mechanical keyboard known for its elegant design and smooth typing experience. The Sirius Manta blends aesthetics with functionality, making it a favorite among hobbyists.', 200.00, 180.00, 1, 10, 'Keyboard', 'https://i.postimg.cc/zfxB42ww/10.jpg'),
('KB002', 'Snake60 R2', 'A high-end 60% keyboard kit with sleek lines and robust build quality. The Snake60 R2 delivers a refined typing experience and top-tier customization options at a heavily discounted price.', 500.00, 250.00, 1, 10, 'Keyboard', 'https://i.postimg.cc/L5chNqtr/2.jpg'),
('KB003', 'KBD8X MKIII Keyboard', 'A beloved full-sized mechanical keyboard featuring top mount design and premium aluminum construction. Now at half price, it\'s a steal for serious keyboard builders.', 500.00, 250.00, 1, 10, 'Keyboard', 'https://i.postimg.cc/JnhynC7d/4.jpg'),
('KB004', 'Magnum65', 'A 65% layout keyboard with a bold design and exceptional build quality. The Magnum65 is for those who want a compact form factor without compromising on performance.', 80.00, 280.00, 0, 10, 'Keyboard', 'https://i.postimg.cc/sfqBVLkw/5.jpg'),
('KB005', 'Quartz Stone Wrist Rest', 'A solid quartz wrist rest designed to offer comfort and elegance. Its cool, stone finish adds a premium touch to your keyboard setup.', 40.00, 40.00, 0, 10, 'Keyboard', 'https://i.postimg.cc/jSQC4SLF/7.jpg'),
('KB006', 'Odin 75 Hot-swap Keyboard with PBTfans Courage red', 'A ready-to-use Odin 75 keyboard with bold Courage Red keycaps. Hot-swap sockets make switch swapping easy without soldering.', 358.30, 394.00, 0, 10, 'Keyboard', 'https://i.postimg.cc/bwH9Mn60/17.jpg'),
('KB007', 'Keychron K8 Wireless', 'A tenkeyless wireless mechanical keyboard compatible with Mac and Windows.', 80.00, 70.00, 1, 10, 'Keyboard', 'https://i.postimg.cc/mrPhMfFc/21.jpg'),
('KB008', 'Akko 3068B Plus', 'A compact 65% keyboard with wireless connectivity and hot-swappable switches.', 90.00, 85.00, 1, 10, 'Keyboard', 'https://i.postimg.cc/0Nhj0WpV/22.png'),
('KB009', 'Ducky One 3 Mini', 'A 60% keyboard known for vibrant colors and premium build.', 120.00, 110.00, 1, 10, 'Keyboard', 'https://i.postimg.cc/vB9Bqrhb/23.jpg'),
('KB010', 'Mode Sonnet Keyboard', 'A custom keyboard with a sleek design and premium materials.', 299.00, 279.00, 1, 10, 'Keyboard', 'https://i.postimg.cc/XqbvTr1F/25.jpg'),
('KB011', 'Keychron Q1 V2', 'A customizable 75% keyboard with QMK/VIA support.', 170.00, 150.00, 1, 10, 'Keyboard', 'https://i.postimg.cc/KjDYFmCW/26.jpg'),
('KB012', 'Ikki68 Aurora', 'A popular entry-level custom keyboard kit.', 135.00, 120.00, 1, 10, 'Keyboard', 'https://i.postimg.cc/J7N0jQtQ/27.jpg'),
('KB013', 'MelGeek Mojo68', 'A semi-transparent wireless keyboard with customizable layout.', 230.00, 210.00, 1, 10, 'Keyboard', 'https://i.postimg.cc/X7NjdSRV/33.jpg'),
('KB014', 'NK65 Entry Edition', 'A budget-friendly 65% mechanical keyboard with a polycarbonate case.', 95.00, 85.00, 1, 10, 'Keyboard', 'https://i.postimg.cc/ydzBNwhC/36.jpg'),
('KB015', 'Keychron V4', 'A budget 60% keyboard with QMK/VIA support.', 65.00, 60.00, 1, 10, 'Keyboard', 'https://i.postimg.cc/43cssJ91/37.jpg'),
('KB016', 'Ajazz AK966', '96% layout wireless mechanical keyboard with knob.', 120.00, 110.00, 1, 10, 'Keyboard', 'https://i.postimg.cc/ZRFmvVjB/38.jpg\r\n'),
('KB017', 'Akko MOD 007 V2', 'A premium 75% keyboard with gasket mount design.', 145.00, 135.00, 1, 10, 'Keyboard', 'https://i.postimg.cc/QCfr0C2j/40.jpg'),
('KB018', 'Rama M65-B', 'High-end aluminum 65% keyboard with elegant design.', 360.00, 340.00, 1, 10, 'Keyboard', 'https://i.postimg.cc/Qd2Z6RyK/41.jpg'),
('KB019', 'CannonKeys Savage65', '65% keyboard kit with a CNC aluminum case.', 230.00, 210.00, 1, 10, 'Keyboard', 'https://i.postimg.cc/6QCJFYkj/42.jpg'),
('KB020', 'Drop ALT Keyboard', 'Compact mechanical keyboard with hot-swap sockets.', 180.00, 170.00, 1, 10, 'Keyboard', 'https://i.postimg.cc/bNFhnG0T/43.jpg'),
('KB021', 'Varmilo VA87M', 'Tenkeyless keyboard with a variety of themes and switches.', 140.00, 130.00, 1, 10, 'Keyboard', 'https://i.postimg.cc/vZgdtQ3d/44.jpg'),
('KB022', 'Zoom65 V2', 'A versatile 65% keyboard with wireless features.', 175.00, 160.00, 1, 10, 'Keyboard', 'https://i.postimg.cc/63wJKCw9/45.jpg'),
('KB023', 'MelGeek Pixel Keyboard', 'A customizable Lego-style keyboard.', 270.00, 250.00, 1, 10, 'Keyboard', 'https://i.postimg.cc/Jnqwzt4j/46.jpg'),
('KB024', 'IDOBAO ID80', 'Gasket-mounted keyboard with a unique acrylic body.', 190.00, 175.00, 1, 10, 'Keyboard', 'https://i.postimg.cc/Zn716gNR/47.jpg'),
('KB025', 'EPOMAKER TH96', '96% keyboard with hot-swap, wireless and knob features.', 130.00, 120.00, 1, 10, 'Keyboard', 'https://i.postimg.cc/jdCVRxVJ/50.jpg'),
('KB026', 'Royal Kludge RK61', 'Budget 60% wireless mechanical keyboard.', 50.00, 45.00, 1, 10, 'Keyboard', 'https://i.postimg.cc/RCg5r3YB/51.jpg'),
('KC001', 'Circus PGA Profile Keycaps', 'A vibrant and playful set of PGA profile keycaps inspired by circus aesthetics. Perfect for mechanical keyboard enthusiasts looking to add a burst of color and uniqueness to their setup.', 80.00, 40.00, 1, 10, 'Keycaps', 'https://i.postimg.cc/zBPyH2VD/1.jpg'),
('KC002', 'Dusk 67 with PBTfans Inkdrop', 'A beautifully themed 65% keyboard featuring the Dusk 67 case and PBTfans Inkdrop keycaps. This bundle is perfect for those who want a cohesive, stunning setup.', 207.20, 207.20, 0, 10, 'Keycaps', 'https://i.postimg.cc/WpC8JTFZ/13.jpg'),
('KC003', 'TET Keyboard With PBTfans Count Dracula', 'A spooky and stylish keyboard pairing the TET layout with the popular PBTfans Count Dracula keycaps. Eye-catching and great for Halloween or gothic setups.', 253.63, 291.00, 0, 10, 'Keycaps', 'https://i.postimg.cc/7ZyNm9NY/16.jpg'),
('KC004', 'KBD8X MKIII HE Gaming Keyboard with PBTfans Blush', 'A performance-focused version of the KBD8X MKIII featuring Hall Effect switches for rapid input and PBTfans Blush keycaps for soft, pastel aesthetics.', 262.86, 337.00, 0, 10, 'Keycaps', 'https://i.postimg.cc/HWXcBgvX/18.jpg'),
('KC005', 'GMK Red Samurai Keycap Set', 'A premium GMK keycap set inspired by traditional samurai aesthetics.', 150.00, 130.00, 1, 10, 'Keycaps', 'https://i.postimg.cc/SKSfpK5B/19.jpg'),
('KC006', 'KAT Milkshake Keycap Set', 'A colorful pastel keycap set with a unique KAT profile.', 110.00, 100.00, 1, 10, 'Keycaps', 'https://i.postimg.cc/c4DsckWf/34.jpg'),
('KC007', 'SA Bliss Keycap Set', 'A vibrant SA profile keycap set inspired by serene aesthetics.', 130.00, 115.00, 1, 10, 'Keycaps', 'https://i.postimg.cc/0yFPT6bQ/35.jpg'),
('KC008', 'GMK Olivia Keycap Set', 'Elegant pink and black themed GMK keycap set.', 150.00, 135.00, 1, 10, 'Keycaps', 'https://i.postimg.cc/zXQsBs52/49.png'),
('KK001', 'Tofu60 Redux Plate', 'A compatible plate for the Tofu60 Redux case, offering improved rigidity and mounting flexibility. Great for customizing your typing feel.', 20.00, 18.00, 1, 10, 'Keyboard_Kit', 'https://i.postimg.cc/L6PJhTRR/6.jpg'),
('KK002', 'KBD67 Lite R4 Mechanical Keyboard Kit', 'A budget-friendly yet high-performing 65% keyboard kit. Ideal for newcomers and veterans alike, with hot-swap functionality and great acoustics.', 119.00, 119.00, 0, 10, 'Keyboard_Kit', 'https://i.postimg.cc/2SfVWZ8W/8.jpg'),
('KK003', 'KBDfans Odin 75 Mechanical Keyboard Kit', 'A compact 75% layout keyboard with a stylish and functional design. The Odin 75 offers great balance between form and usability.', 239.00, 239.00, 0, 10, 'Keyboard_Kit', 'https://i.postimg.cc/mrLkXW92/9.jpg'),
('KK004', 'Sebas Keyboard kit', 'A stylish and sturdy keyboard kit designed with premium materials. Its layout and build make it suitable for both work and play.', 215.00, 215.00, 0, 10, 'Keyboard_Kit', 'https://i.postimg.cc/2jVT6V6m/12.jpg'),
('KK005', 'KBDfans GT-80 Case', 'A durable and elegant keyboard case designed for the GT-80 layout. Built with anodized aluminum and precision machining.', 119.00, 119.00, 0, 10, 'Keyboard_Kit', 'https://i.postimg.cc/pyMXKwxv/14.jpg'),
('KK006', 'Margo Case', 'A uniquely designed keyboard case with gentle curves and premium anodization. A great choice for custom keyboard builds looking to stand out.', 135.20, 169.00, 0, 10, 'Keyboard_Kit', 'https://i.postimg.cc/9F9pdKZn/15.jpg'),
('KK007', 'GMMK Pro Barebone', 'A 75% layout mechanical keyboard with a rotary knob and aluminum body.', 170.00, 170.00, 0, 10, 'Keyboard_Kit', 'https://i.postimg.cc/0NVd5s1b/20.jpg'),
('KK008', 'Tofu65 Kit', 'Aluminum 65% DIY keyboard kit with customizable options.', 170.00, 160.00, 1, 10, 'Keyboard_Kit', 'https://i.postimg.cc/m2Vr52Yz/28.jpg'),
('KK009', 'KBD75 V3 Kit', '75% aluminum keyboard with refined layout and features.', 185.00, 170.00, 1, 10, 'Keyboard_Kit', 'https://i.postimg.cc/Kjv6kFRw/48.jpg'),
('KP001', 'Taco Pad', 'A novelty macropad shaped like a taco. Fun, quirky, and useful for macros, shortcuts, or artisan display. A must-have desk companion for enthusiasts.', 90.00, 90.00, 0, 10, 'keypad', 'https://i.postimg.cc/C5BzGCqG/3.jpg'),
('ST001', 'Durock V2 Stabilizers', 'Premium screw-in stabilizers for mechanical keyboards.', 22.00, 20.00, 1, 10, 'Stabilizers', 'https://i.postimg.cc/g2nGtycQ/31.jpg'),
('Stipen', 'sammy riki ganteng', 'gua ganteng banget', 22.00, 22.00, 0, 1, 'Gundam', 'https://www.youtube.com/watch?v=QwGbGG3VI8E&ab_channel=ALPHINE'),
('SW001', 'Leopold FC660C', 'Topre electro-capacitive switches in a 65% layout.', 230.00, 210.00, 1, 10, 'Switch_kit', 'https://i.postimg.cc/pLGppXyb/24.jpg'),
('SW002', 'NovelKeys Cream Switches (70 pcs)', 'Smooth linear switches with self-lubricating POM housing.', 56.00, 56.00, 0, 10, 'Switch_kit', 'https://i.postimg.cc/jS5j00c8/29.jpg'),
('SW003', 'Akko CS Jelly Purple (45 pcs)', 'Tactile mechanical switches with a unique jelly-like stem.', 20.00, 18.00, 1, 10, 'Switch_kit', 'https://i.postimg.cc/SKYNR6wC/30.jpg'),
('SW004', 'Glorious Panda Switches (36 pcs)', 'Tactile switches with a strong bump and satisfying sound.', 35.00, 30.00, 1, 10, 'Switch_kit', 'https://i.postimg.cc/DfPfSyYj/32.jpg'),
('SW005', 'Gateron Oil King Switches (70 pcs)', 'Smooth linear switches with a deep, satisfying sound.', 40.00, 35.00, 1, 10, 'Switch_kit', 'https://i.postimg.cc/zvzrCKPd/39.jpg'),
('SW006', 'GATERON BLUE G PRO 3.0 (LUBED) Mechanical Keyboard Switch', 'Gateron Blue G Pro 3.0 (Lubed) は、クリッキータイプのメカニカルスイッチで、押すたびに明確なタクタイルフィードバックと爽快なクリック音を提供します。  工場出荷時から潤滑済み（factory-lubed）のため、キーストロークがスムーズで、スプリングの雑音も軽減されています。  ナイロンハウジングとPOMステムを採用しており、耐久性に優れ、安定した動作を実現します。', 25.00, 22.00, 1, 5, 'Switch_kit', 'https://postimg.cc/56jr57D1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `FK_customer_id_carts` (`customer_id`),
  ADD KEY `FK_product_id_carts` (`product_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `FK_customer_id` (`customer_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`detail_id`),
  ADD KEY `FK_order_id` (`order_id`),
  ADD KEY `FK_product_id` (`product_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `FK_order_id_payment` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `cart_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `detail_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `FK_customer_id_carts` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_product_id_carts` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `FK_customer_id` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `FK_order_id` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_product_id` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `FK_order_id_payment` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
