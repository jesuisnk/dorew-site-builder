-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: mysql-nosine.alwaysdata.net
-- Generation Time: Jul 22, 2024 at 03:44 AM
-- Server version: 10.6.18-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nosine_builder`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `nick` varchar(30) NOT NULL,
  `pass` text NOT NULL,
  `subdomain` varchar(255) NOT NULL,
  `parked` varchar(100) DEFAULT '0',
  `alwaysdata` int(11) NOT NULL DEFAULT 0,
  `email` varchar(255) DEFAULT NULL,
  `confirm` varchar(30) NOT NULL DEFAULT '0',
  `right` varchar(3) NOT NULL DEFAULT '0',
  `max_upload` int(11) NOT NULL DEFAULT 5242880,
  `default_index` varchar(255) DEFAULT 'index',
  `default_404` varchar(255) DEFAULT '_404',
  `default_login` varchar(255) DEFAULT 'dorew',
  `reg` int(11) NOT NULL,
  `on` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nick`, `pass`, `subdomain`, `parked`, `alwaysdata`, `email`, `confirm`, `right`, `max_upload`, `default_index`, `default_404`, `default_login`, `reg`, `on`) VALUES
(1, 'sei', 'a5c63f93700e69f366f48e363300a510', 'demo', NULL, 0, '', '1', '-1', 5242880, 'index', '_404', 'dorew', 0, 0),
(2, 'trum', 'b87d4a25b1cf8098d86153ed1bcc9f97', 'oubliez', NULL, 0, '', '1', '1', 5242880, 'index', '_404', 'dorew', 1660092735, 1660092735),
(3, 'dai', 'd77c8ff6354feeea1e15306e2fa7d61c', 'dai', NULL, 0, '', '1', '1', 5242880, 'index', '_404', 'dorew', 1660121261, 1660121261),
(8, 'pre', '395e474206fc2f6c63b89fdd9a3b4666', 'pre', NULL, 0, 'zakahyu@gmail.com', '1', '0', 5242880, 'index', '_404', 'dorew', 1660398023, 1660398023),
(9, 'tester', 'a5c63f93700e69f366f48e363300a510', 'tester', NULL, 0, 'kioku17@protonmail.com', '1', '0', 5242880, 'index', '_404', 'dorew', 1660260341, 1660260341),
(11, 'forum', 'ece49e219d3a664c2fce1a911d83deef', 'forum', NULL, 0, 'forum@nosine.gq', '1', '0', 5242880, 'index', '_404', 'dorew', 1660394139, 1660394139),
(12, 'yakito', '2dd519a83cbfa93abf7ac808632a6c03', 'all4u', NULL, 0, 'HuuPhuong99x@gmail.com', '1', '0', 5242880, 'index', '_404', 'dorew', 1660396723, 1660396723),
(13, 'kirito', 'f4e4d8931f5e05924b6a7c0a5f7b151e', 'kiriyo', NULL, 0, 'dinh.thai.047@gmail.com', 'f4e4d8', '0', 5242880, 'index', '_404', 'dorew', 1660397011, 1660397011),
(14, 'kenbi', 'f4e4d8931f5e05924b6a7c0a5f7b151e', 'kenbi', NULL, 0, 'copecute5@gmail.com', '1', '0', 5242880, 'index', '_404', 'dorew', 1660397131, 1660397131),
(15, 'vanduc', '5465676a90bef6c7d464ab32ad4a542c', 'vanduc', NULL, 0, 'ducboyfa@gmail.com', '1', '0', 5242880, 'index', '_404', 'dorew', 1660397933, 1660397933),
(16, 'home', 'ece49e219d3a664c2fce1a911d83deef', 'home', NULL, 0, 'home@nosine.gq', '1', '0', 5242880, 'index', '_404', 'dorew', 1660394081, 1660394081),
(17, 'linh0905', '638419d7b46ff5b35d30335868f98323', 'just4u', NULL, 0, 'tranvulinh037@gmail.com', '1', '0', 5242880, 'index', '_404', 'dorew', 1660407808, 1660407808),
(18, 'phuongs', '986ed549203b6c48c6189c5a2068d7c8', 'phuong', NULL, 0, 'rainylirss@gmail.com', '1', '0', 5242880, 'index', '_404', 'dorew', 1660449556, 1660449556),
(19, 'khanhvy', 'e2723e136ede2c73ff36feb7ab71d3ac', 'khanhvy', NULL, 0, 'songao.1198@gmail.com', '1', '0', 5242880, 'index', '_404', 'dorew', 1660470009, 1660470009),
(20, 'salamonvikkas', '125027df80a454c9643ccef5ce765886', 'vikkas', NULL, 0, 'salamonvikas1994@gmail.com', '1', '0', 5242880, 'index', '_404', 'dorew', 1660638771, 1660638771),
(21, 'meotongtai', '4ccd395957a565c13010028bcbd151de', 'minhanh', NULL, 0, 'meotongtai1133@gmail.com', '1', '0', 5242880, 'index', '_404', 'dorew', 1660656919, 1660656919),
(22, 'code', '52e426b770c5fc36b366ce783675769b', 'code', NULL, 0, 'lukico9x@gmail.com', '1', '0', 5242880, 'home', '_404', 'login', 1660658017, 1660658017),
(23, 'nguoidi', '00f26b890210ffbdec76a84b89a0f53d', 'nguoidi', NULL, 0, 'sunthien.com@gmail.com', 'bce701', '0', 5242880, 'index', '_404', 'dorew', 1660792277, 1660792277),
(24, 'thai123', '5ab29b644cc9c89bed62a939e2073dc2', 'diendan', NULL, 0, 'Cuopnik1@gmail.com', '5ab29b', '0', 5242880, 'index', '_404', 'dorew', 1660967273, 1660967273),
(25, 'jkuibap', 'eb861aae37c91c6addab0a170ab2f287', 'jkuibap', NULL, 0, 'jkuibap69@gmail.com', '1', '0', 5242880, 'index', '_404', 'dorew', 1661061617, 1661061617),
(26, 'nhiken', 'e2e3d180d4624a7cfc52e029dec51334', 'nhiken', NULL, 0, 'nhiyuken1509@gmail.com', '1', '0', 5242880, 'index', '_404', 'dorew', 1661704426, 1661704426),
(27, 'boypho', '605f25a253ad0b6f0895001a447d0ddc', 'taptin', NULL, 0, 'cuopnik3@gmail.com', '1', '0', 5242880, 'index', '_404', 'dorew', 1662568713, 1662568713),
(28, 'mrducz2k', '8f3aa90bce0c0efd08b388344f9aa401', 'file', NULL, 0, 'mrducz2k@gmail.com', '8f3aa9', '0', 5242880, 'index', '_404', 'dorew', 1662617521, 1662617521),
(29, 'mrducz2k1', '8f3aa90bce0c0efd08b388344f9aa401', 'files', NULL, 0, 'nhox2kvtvp@gmail.com', '1', '0', 5242880, 'index', '_404', 'dorew', 1662617599, 1662617599),
(30, 'copecute', '3256f71410fa9ac407fb41875baca12a', 'admin', NULL, 0, 'copecute10x@gmail.com', '1', '0', 5242880, 'index', '_404', 'copecute', 1662697594, 1662697594),
(31, 'mboy2k', '5c336a5377f9c829b25cb9eda391eb3f', 'mboy2k', NULL, 0, 'nguyen.duy.mboy2k@gmail.com', '1', '0', 5242880, 'index', '_404', 'dorew', 1662698547, 1662698547),
(32, 'khanh', '678111e530aba2ea76d31568ca445a17', 'game', NULL, 0, 'nguyenkhanh95gl@gmail.com', '1', '0', 5242880, 'index', '_404', 'dorew', 1662742581, 1662742581),
(33, 'swamp', 'ece49e219d3a664c2fce1a911d83deef', 'swamp', NULL, 0, 'moleys.pm@gmail.com', '-1', '0', 5242880, 'index', '_404', 'dorew', 1662840615, 1662840615),
(34, 'ads', 'bb6d98f7c21b71b43301953875593d06', 'truyenhay', NULL, 0, 'ng.g@gmail.com', 'bb6d98', '0', 5242880, 'index', '_404', 'dorew', 1662899683, 1662899683),
(35, 'adsss', 'bb6d98f7c21b71b43301953875593d06', 'share', NULL, 0, 'vannam.rh@gmail.com', '1', '0', 5242880, 'index', '_404', 'dorew', 1662899861, 1662899861),
(36, 'upi', 'ece49e219d3a664c2fce1a911d83deef', 'upi', 'upi.dorew.gq', 732659, 'vr.numeron@gmail.com', '1', '0', 5242880, 'index', '_404', 'dorew', 1664249436, 1664249436),
(37, 'tinklh', '65d72057ac27c587c2a24bdea46f13a7', 'tinklh', '0', 0, 'tinklh2003@gmail.com', '1', '0', 5242880, 'index', '_404', 'dorew', 1664823767, 1664823767),
(38, 'khach', '5c336a5377f9c829b25cb9eda391eb3f', 'hoi8', '0', 0, 'dinhhiep47d@gmail.com', '1', '0', 5242880, 'index', '_404', 'dorew', 1665061232, 1665061232),
(39, 'kazens', '5c336a5377f9c829b25cb9eda391eb3f', 'kazens', '0', 0, 'kazens@gmail.com', '5c336a', '0', 5242880, 'index', '_404', 'dorew', 1666112989, 1666112989),
(40, 'dj199x', '5c336a5377f9c829b25cb9eda391eb3f', 'dj199x', '0', 0, 'dj199x@gmail.com', '1', '0', 5242880, 'index', '_404', 'dorew', 1666113081, 1666113081),
(41, 'isme', '696a962dd4bf02565263c8470a24f548', 'isme', '0', 0, 'meotom.1198@gmail.com', '696a96', '0', 5242880, 'index', '_404', 'dorew', 1669303538, 1669303538),
(42, 'hoangchan', 'f14bf220fe7af8a05f29d1f70baa3449', 'hoangchan', '0', 0, 'hoangchantk@gmail.com', '1', '0', 5242880, 'index', '_404', 'dorew', 1669431496, 1669431496);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
