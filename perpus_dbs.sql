-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 19, 2026 at 03:21 AM
-- Server version: 5.7.39
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `perpus_dbs`
--

-- --------------------------------------------------------

--
-- Table structure for table `anggota`
--

CREATE TABLE `anggota` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nis` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kelas` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_hp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `anggota`
--

INSERT INTO `anggota` (`id`, `nis`, `password`, `user_id`, `nama`, `kelas`, `no_hp`, `alamat`, `created_at`, `updated_at`) VALUES
(2, '1234501', '$2y$12$OaV2bJJxdU9g/VzcMejl4uAqMbQlZEf7etbrLNU66Ijxlsj9..ilm', 1, 'Bambang', 'XII RPL 1', '081234567890', 'Jakarta Selatan', '2026-05-11 00:13:08', '2026-05-17 23:05:02'),
(3, '1234502', '$2y$12$vxm9gGWI7DFPIZIPu8NK5.ly2yZM.nwZwndNJC8FlmW/QHqq..p66', 3, 'Nheyna Azzahra', 'XI RPL', '081234567800', '-', '2026-05-17 21:23:35', '2026-05-17 22:33:10'),
(5, '1234504', '$2y$12$Fpip1WTmxLAL6.bnyiNSke.DXj/KTAWEPy3dXbRYKnwjGfwOIiDRi', 4, 'arva', 'XI RPL', NULL, NULL, '2026-05-17 21:24:58', '2026-05-17 22:33:10'),
(7, '1234506', '$2y$12$Vor7eqj8vyf.arSwe0.suuTkiyUmxnvKOlEnOxByU85F2GTWWufTO', 7, 'Hotmian', 'XI Perhotelan', '0888888888888', 'Manggarai', '2026-05-17 22:49:55', '2026-05-17 23:04:51'),
(8, '1234507', '$2y$12$Sgv5r8E2KqGNdJOsc/DvPehTiTcYLOC9lcZJx/AXEno0FBAm8aRei', 8, 'Ocit', 'XI MP', '081234501', 'Bogor', '2026-05-17 23:06:55', '2026-05-18 19:41:18');

-- --------------------------------------------------------

--
-- Table structure for table `buku`
--

CREATE TABLE `buku` (
  `id_buku` bigint(20) UNSIGNED NOT NULL,
  `judul` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pengarang` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `penerbit` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tahun_terbit` year(4) DEFAULT NULL,
  `isbn` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stok` int(11) NOT NULL DEFAULT '0',
  `cover` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `buku`
--

INSERT INTO `buku` (`id_buku`, `judul`, `pengarang`, `penerbit`, `tahun_terbit`, `isbn`, `stok`, `cover`, `deskripsi`, `created_at`, `updated_at`) VALUES
(1, 'Laut Bercerita', 'Leila S. Chudori', NULL, '2017', NULL, 10, 'covers/R2105TEXrm8ZzXAqEISst87q8DS6znzGl6lcfPmR.jpg', 'Novel sejarah tentang aktivis 1998', '2026-05-11 00:34:37', '2026-05-15 07:03:37'),
(2, 'Laskar Pelangi', 'Andrea Hirata', NULL, '2005', NULL, 20, 'covers/boL1LrOeJkieHWOqIGCrDQ6XS917GOGoayymzjsp.jpg', 'Novel tentang semangat anak-anak Belitung', '2026-05-12 17:56:03', '2026-05-17 20:08:41'),
(3, 'Bumi Manusia', 'Pramoedya Ananta Toer', NULL, '1980', NULL, 8, 'covers/koLiSowYyw5IcpMEn9KxIBjq4wvXy5SydmsaeNJM.jpg', 'Novel sejarah era kolonial Belanda', '2026-05-12 18:02:53', '2026-05-17 22:52:41'),
(4, 'Perahu Kertas', 'Dewi Lestari', NULL, '2009', NULL, 6, 'covers/xHc0JPWNS3zLmM1KM75EQ33sELlaPWJQgFHa4zJj.jpg', 'Novel tentang mimpi dan cinta', '2026-05-12 18:04:34', '2026-05-12 18:04:34'),
(5, 'Sukarno: Biografi Lengkap Negarawan Sejati', 'Anom Whani Wicaksana', NULL, NULL, NULL, 10, 'covers/uBig1gIiHOGapD6qpvGVTKnjtdXtrU31MsYEPIHq.webp', 'Perjalanan hidup sang presiden pertama, sosok yang memainkan peran sentral dalam perjuangan kemerdekaan Indonesia.', '2026-05-18 00:35:51', '2026-05-18 00:35:51'),
(6, 'Sejarah Dunia Yang Disembunyikan', 'Jonathan Black', 'Alvabet', '2015', NULL, 15, 'covers/eD9gFgN2L1bk7zOyuMiT8IeZmQPf8pA90AJXlqz1.jpg', 'Buku ini memberi pembanca nya banyak pengetahuan menarik tentang sejarah dunia yang mungkin saja belum banyak diketahui oleh publik.', '2026-05-18 00:46:40', '2026-05-18 00:46:40');

-- --------------------------------------------------------

--
-- Table structure for table `buku_kategori`
--

CREATE TABLE `buku_kategori` (
  `buku_id` bigint(20) UNSIGNED NOT NULL,
  `kategori_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `buku_kategori`
--

INSERT INTO `buku_kategori` (`buku_id`, `kategori_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 4),
(6, 5);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_kategori` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id`, `nama_kategori`, `deskripsi`, `created_at`, `updated_at`) VALUES
(1, 'Fiksi', NULL, '2026-05-11 00:33:28', '2026-05-11 00:33:28'),
(2, 'Non-fiksi', NULL, '2026-05-11 00:34:58', '2026-05-11 00:34:58'),
(3, 'Novel', NULL, '2026-05-18 00:25:16', '2026-05-18 00:25:16'),
(4, 'Biografi', NULL, '2026-05-18 00:25:45', '2026-05-18 00:25:45'),
(5, 'Sejarah', NULL, '2026-05-18 00:47:06', '2026-05-18 00:47:06');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2026_04_29_022311_create_buku_table', 1),
(6, '2026_04_29_022335_create_anggota_table', 1),
(7, '2026_04_29_022354_create_kategori_table', 1),
(8, '2026_04_29_022422_create_buku_kategori_table', 1),
(9, '2026_04_29_022443_create_peminjaman_table', 1),
(10, '2026_04_29_022512_add_role_to_users_table', 1),
(11, '2026_05_13_022704_add_status_pending_to_peminjaman_table', 2),
(12, '2026_05_18_000001_backfill_users_for_anggota', 3);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `anggota_id` bigint(20) UNSIGNED NOT NULL,
  `buku_id` bigint(20) UNSIGNED NOT NULL,
  `tgl_pinjam` date NOT NULL,
  `tgl_kembali_rencana` date NOT NULL,
  `tgl_kembali_aktual` date DEFAULT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `denda` int(11) NOT NULL DEFAULT '0',
  `catatan_admin` text COLLATE utf8mb4_unicode_ci,
  `struk_disetujui_at` timestamp NULL DEFAULT NULL,
  `struk_disetujui_oleh` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `peminjaman`
--

INSERT INTO `peminjaman` (`id`, `anggota_id`, `buku_id`, `tgl_pinjam`, `tgl_kembali_rencana`, `tgl_kembali_aktual`, `status`, `denda`, `catatan_admin`, `struk_disetujui_at`, `struk_disetujui_oleh`, `created_at`, `updated_at`) VALUES
(1, 2, 1, '2026-05-13', '2026-05-13', '2026-05-15', 'terlambat', 2000, NULL, NULL, NULL, '2026-05-12 00:33:04', '2026-05-15 07:03:37'),
(2, 2, 2, '2026-05-15', '2026-05-20', '2026-05-18', 'dikembalikan', 0, NULL, '2026-05-17 22:37:37', 2, '2026-05-15 07:07:02', '2026-05-17 22:37:37'),
(3, 7, 3, '2026-05-18', '2026-05-19', '2026-05-18', 'dikembalikan', 0, NULL, '2026-05-17 22:52:41', 2, '2026-05-17 22:51:35', '2026-05-17 22:52:41');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','anggota') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'anggota',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Bambang', '1234501', 'anggota', NULL, '$2y$12$fz5tQ0LX5ylnYUh3M95.e.2EhwuQ.0x8jWBuHhIdkUtCFzg3jBKtu', NULL, '2026-05-11 00:13:07', '2026-05-17 23:05:03'),
(2, 'Admin Perpustakaan', 'admin@gmail.com', 'admin', NULL, '$2y$12$.uIh9hQwL3vsdbN1Ae9xZeI.F2dcGyT/tJa8e9TMbaqquYU08mXfm', NULL, '2026-05-11 00:29:08', '2026-05-11 00:29:08'),
(3, 'Nheyna Azzahra', '1234502', 'anggota', NULL, '$2y$12$vxm9gGWI7DFPIZIPu8NK5.ly2yZM.nwZwndNJC8FlmW/QHqq..p66', NULL, '2026-05-17 22:33:10', '2026-05-17 22:33:10'),
(4, 'arva', '1234504', 'anggota', NULL, '$2y$12$Fpip1WTmxLAL6.bnyiNSke.DXj/KTAWEPy3dXbRYKnwjGfwOIiDRi', NULL, '2026-05-17 22:33:10', '2026-05-17 22:33:10'),
(5, 'Muniroh', '1234505', 'anggota', NULL, '$2y$12$ipoI8fw2Cs57eQpcWNhA.urn55HW3yJi4MCYAaGdXaAAblU/aUpLS', NULL, '2026-05-17 22:45:46', '2026-05-17 22:45:46'),
(7, 'Hotmian', '1234506', 'anggota', NULL, '$2y$12$r26EmBNPuILHLfmtyiFZzu4WWIiz7Acd8HuhWWXPCHivFFDaSHMtG', NULL, '2026-05-17 22:49:55', '2026-05-17 23:04:51'),
(8, 'Ocit', '1234507', 'anggota', NULL, '$2y$12$eQoEIRVt0A4vfHWTL5Xez.aqkh8swRHH7dsG9RbYVKbtyA.bsQY4a', NULL, '2026-05-17 23:06:55', '2026-05-17 23:09:57');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `anggota`
--
ALTER TABLE `anggota`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `anggota_nis_unique` (`nis`),
  ADD KEY `anggota_user_id_foreign` (`user_id`);

--
-- Indexes for table `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`id_buku`),
  ADD UNIQUE KEY `buku_isbn_unique` (`isbn`);

--
-- Indexes for table `buku_kategori`
--
ALTER TABLE `buku_kategori`
  ADD PRIMARY KEY (`buku_id`,`kategori_id`),
  ADD KEY `buku_kategori_kategori_id_foreign` (`kategori_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id`),
  ADD KEY `peminjaman_anggota_id_foreign` (`anggota_id`),
  ADD KEY `peminjaman_buku_id_foreign` (`buku_id`),
  ADD KEY `peminjaman_struk_disetujui_oleh_foreign` (`struk_disetujui_oleh`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `anggota`
--
ALTER TABLE `anggota`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `buku`
--
ALTER TABLE `buku`
  MODIFY `id_buku` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `anggota`
--
ALTER TABLE `anggota`
  ADD CONSTRAINT `anggota_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `buku_kategori`
--
ALTER TABLE `buku_kategori`
  ADD CONSTRAINT `buku_kategori_buku_id_foreign` FOREIGN KEY (`buku_id`) REFERENCES `buku` (`id_buku`) ON DELETE CASCADE,
  ADD CONSTRAINT `buku_kategori_kategori_id_foreign` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD CONSTRAINT `peminjaman_anggota_id_foreign` FOREIGN KEY (`anggota_id`) REFERENCES `anggota` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `peminjaman_buku_id_foreign` FOREIGN KEY (`buku_id`) REFERENCES `buku` (`id_buku`) ON DELETE CASCADE,
  ADD CONSTRAINT `peminjaman_struk_disetujui_oleh_foreign` FOREIGN KEY (`struk_disetujui_oleh`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
