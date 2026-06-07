-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 07 Jun 2026 pada 13.19
-- Versi server: 8.0.30
-- Versi PHP: 8.2.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vokatif3`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `role_id`, `name`, `email`, `phone`, `avatar`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 1, 'Admin Vokatif', 'admin@vokatif.test', '081111111111', NULL, NULL, '$2y$10$oBt5DFTxK0AD8Z/4wTReRutTsOxNFAaMK07bz.rMYHDMeln6p.JHW', NULL, '2026-05-31 01:57:12', '2026-05-31 01:57:12'),
(2, 2, 'Vokatif Organizer', 'organizer@vokatif.test', '082222222222', NULL, NULL, '$2y$10$FS.iPGDVSH1xm.PA5WHM0ezV0IjhEWQY/VDYQCA6cHO9QWw6jvu0m', NULL, '2026-05-31 01:57:12', '2026-05-31 01:57:12'),
(3, 3, 'Fahrel Demo User', 'user@vokatif.test', '083333333333', NULL, NULL, '$2y$10$w3Qb5DOOK9jeZn8u9L6LzeiqzAj9bG4NISBe0NmbuEvmPWdA32DmO', NULL, '2026-05-31 01:57:12', '2026-05-31 01:57:12'),
(4, 3, 'bima', 'bima@gmail.com', NULL, NULL, NULL, '$2y$10$cxdMkxsxTB3ElhZ3bAzdYuxGGTXUfa.lFo9SHRTaGGqDIsp0J.4z6', NULL, '2026-06-03 10:30:27', '2026-06-03 10:30:27'),
(5, 3, 'bimaa', 'bimaa@gmail.com', NULL, NULL, NULL, '$2y$10$dCEXp3ITl4rzG4HryHAjAu9DW4v5h2zxWAC3VLjTmmX4.t3ogI9CG', NULL, '2026-06-03 10:35:16', '2026-06-03 10:35:16'),
(6, 3, 'bimaaa', 'bimaaa@gmail.com', NULL, NULL, NULL, '$2y$10$68P8LOCiyXRbZSWqMTCVuOJz95C2jR6If2yIbIiaQzkWt.y3kgy.u', NULL, '2026-06-03 10:36:19', '2026-06-03 10:36:19'),
(7, 3, 'eka', 'eka@gmail.com', NULL, NULL, NULL, '$2y$10$L6JXSXWYZ9rMp42qAkJKVOYIR9z3JNpK/PQXfGxdqgyRu7usQwl7S', NULL, '2026-06-03 10:37:50', '2026-06-03 10:37:50'),
(8, 3, 'Billy', 'billy@gmail.com', NULL, NULL, NULL, '$2y$10$td/Cj8PwXSqDLtPPNdMqsehbr3BWwm0BCMRZfylU16HO.rWtjsHWq', NULL, '2026-06-03 10:38:20', '2026-06-03 10:38:20'),
(9, 3, 'Abiyyu', 'abiyyu@gmail.com', NULL, NULL, NULL, '$2y$10$ORrF1LMjDtcmGKDn71rGAeDM.QQWk9jnBjg/bB.FoJRk.ZZ.eKnfm', NULL, '2026-06-03 10:38:59', '2026-06-03 10:38:59'),
(10, 3, 'Fahrel', 'fahrel@gmail.com', NULL, NULL, NULL, '$2y$10$0GOk8HDPZ9wYg3q5y2R3B.BPzfhlJolIH6cmkjwlTK/LMLmPqTgBG', NULL, '2026-06-03 10:40:11', '2026-06-03 10:40:11'),
(11, 3, 'Dimas', 'dimas@gmail.com', NULL, NULL, NULL, '$2y$10$uqlzUVufdDFFuhGYXWi05utPJ5v1REsW1pR7IOH706h5biu6SKGxG', NULL, '2026-06-03 10:40:40', '2026-06-03 10:40:40');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_role_id_foreign` (`role_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
