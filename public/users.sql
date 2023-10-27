-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Dec 12, 2022 at 12:04 PM
-- Server version: 5.7.34
-- PHP Version: 8.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nikah_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_verified_at` timestamp NULL DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `gender` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_card_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_expiry` date DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `id_card_front` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_card_back` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `selfie` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `device_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qr_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `email_verified_at`, `password`, `phone`, `phone_verified_at`, `address`, `gender`, `profile_image`, `id_card_number`, `id_expiry`, `date_of_birth`, `id_card_front`, `id_card_back`, `selfie`, `device_token`, `qr_number`, `role`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'admin', 'super', 'admin@renesistech.com', '2022-12-09 09:11:13', '$2y$10$fLu3DlUhK68CgY2leZLPDePioxF4s3ktQrWLPwW35kNOPYpwaim7W', '+1 (586) 926-9123', '2022-12-09 09:11:13', '551 Weber Extensions Suite 804\nEast Verlie, SC 92883', 'Male', NULL, '1430095552', '2025-12-31', '1994-03-19', NULL, NULL, NULL, NULL, '543861', 'admin', 'gzCHDyB105', '2022-12-09 09:11:15', '2022-12-09 09:11:15', NULL),
(2, 'imam', 'sab', 'imam@renesistech.com', '2022-12-09 09:11:13', '$2y$10$5SQsGcZ74FMC8Ra5/ThEDudTM0hFsEulkhUFiZPzzU31AqC.pOziG', '+1.715.882.8685', '2022-12-09 09:11:13', '92847 OHara Tunnel Apt 684nNorth Zena, RI 65226', 'Male', NULL, '1726164416', '2025-12-31', '1994-03-19', NULL, NULL, NULL, NULL, '475538', 'imam', 'mM9A1LGzcq', '2022-12-09 09:11:15', '2022-12-09 09:11:15', NULL),
(3, 'saad', 'siddiqui', 'saad@renesistech.com', '2022-12-09 09:11:13', '$2y$10$mtqBRxXngRPFZ7GwaondMuzM2JN71zyJW9Svpsc.qHvXZ17aHpaUu', '838-522-2872', '2022-12-09 09:11:14', '8575 Zena Land Apt. 883\nVernerview, CO 28596-3834', 'Male', NULL, '854941823', '2025-12-31', '1994-03-19', NULL, NULL, NULL, NULL, '386106', 'user', 't1UlBvNoKG', '2022-12-09 09:11:15', '2022-12-09 09:11:15', NULL),
(4, 'partner', 'female', 'female@renesistech.com', '2022-12-09 09:11:14', '$2y$10$mpMtoxCPK/zof1ajxaNqbugMF7UwOKvZAhSXj83QZliWYYeZ/Brdq', '1-858-893-4383', '2022-12-09 09:11:14', '7090 Genesis Stravenue\nSouth Amari, LA 69560', 'Female', NULL, '1226633574', '2025-12-31', '1994-03-19', NULL, NULL, NULL, NULL, '856021', 'user', 'LlsfbdsfkO', '2022-12-09 09:11:15', '2022-12-09 09:11:15', NULL),
(5, NULL, NULL, 'wakeel1@example.net', NULL, NULL, NULL, NULL, NULL, 'Male', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'wakeel', NULL, '2022-12-09 09:11:15', '2022-12-09 09:11:15', NULL),
(6, '', '', 'wakeel2@example.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '520347', 'wakeel', NULL, '2022-12-09 09:11:15', '2022-12-09 09:11:15', NULL),
(7, NULL, NULL, 'wali1@example.net', '2022-12-09 09:11:14', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1994-03-19', NULL, NULL, NULL, NULL, NULL, 'wali', NULL, '2022-12-13 09:41:02', '2022-12-12 09:41:08', NULL),
(8, 'usman', 'haider', 'usman@renesis.org', '2022-12-09 09:11:14', '$2y$10$EPL5HQ6jwKiyJUSt0sWfSuszzXbA1AN63ZmnaacgnOI9oNlvu.dTq', '1-346-864-7418', '2022-12-09 09:11:14', '70657 Barrows Trace Suite 867\nEast Dallasfurt, MT 04720', 'Male', NULL, '1961131964', '2025-12-31', '1994-03-19', NULL, NULL, NULL, NULL, '640853', 'user', '19iNtXNlhv', '2022-12-09 09:11:15', '2022-12-09 09:11:15', NULL),
(9, 'partner2', 'female', 'partner2@example.com', '2022-12-09 09:11:14', '$2y$10$2ckOkWKtZCH6MaPbsVXquejVtZgrAZT9LicO8SPvdL7zHMbMkYqJm', '+16784621639', '2022-12-09 09:11:14', '65001 Spencer Hill\nHerthafurt, GA 20766-7797', 'Female', NULL, '1243113910', '2025-12-31', '1994-03-19', NULL, NULL, NULL, NULL, '519384', 'user', 'LnQw9Ek27X', '2022-12-09 09:11:15', '2022-12-09 09:11:15', NULL),
(10, 'Juston', 'Barton', 'tjacobi@example.com', '2022-12-09 09:11:14', '$2y$10$HMGLJ6dvXBssAxQVEZ8icunfZ8Tr9HQdXnjJCmfBRCsmYXNr4YL8a', '+1.586.416.5699', '2022-12-09 09:11:14', '353 Madelyn Freeway\nTowneland, UT 13958-7559', 'Male', NULL, '2036173771', '2025-12-31', '1994-03-19', NULL, NULL, NULL, NULL, '488057', 'user', '1RfBxdwRtK', '2022-12-09 09:11:15', '2022-12-09 09:11:15', NULL),
(11, 'Greyson', 'Heller', 'mccullough.idella@example.net', '2022-12-09 09:11:14', '$2y$10$lScufUrODsPPlm0z8hDeLOBQHu9v.VIFolqda2h8L8JpsAIpgp6xy', '+1.212.234.3023', '2022-12-09 09:11:14', '266 Konopelski Fork Suite 707\nLake William, CA 34938-7711', 'Male', NULL, '173332213', '2025-12-31', '1994-03-19', NULL, NULL, NULL, NULL, '263814', 'user', 'rxN8V0rmm4', '2022-12-09 09:11:15', '2022-12-09 09:11:15', NULL),
(12, 'Vincenzo', 'Vandervort', 'ocie.williamson@example.net', '2022-12-09 09:11:14', '$2y$10$DE3o5OvCl4POerDKmhKWR.udhnJk37M.5xclGJXntPieCy31yDXju', '347.254.1604', '2022-12-09 09:11:14', '9480 Eleonore Valleys Apt. 213\nSchneiderberg, IA 26234', 'Male', NULL, '7705106', '2025-12-31', '1994-03-19', NULL, NULL, NULL, NULL, '091738', 'user', 'XvId44VzYI', '2022-12-09 09:11:15', '2022-12-09 09:11:15', NULL),
(13, 'Angus', 'Wiza', 'leann81@example.org', '2022-12-09 09:11:14', '$2y$10$feuDVhYYYcvNPfWmQMlYLuXsyL.pKqRASjM2PaIuiOAPVbLac3VYS', '231-519-9337', '2022-12-09 09:11:14', '206 Rohan Extension Apt. 897\nPort Saige, NH 26232', 'Male', NULL, '1304693731', '2025-12-31', '1994-03-19', NULL, NULL, NULL, NULL, '283451', 'user', '3VipTOr3Mw', '2022-12-09 09:11:15', '2022-12-09 09:11:15', NULL),
(14, 'Celia', 'Casper', 'pbechtelar@example.org', '2022-12-09 09:11:14', '$2y$10$XT14ld1we/bM5tNPXN.ZzOvD2r9WvMG9XJVHkGZySf6ryGV3YTpl.', '219.621.8522', '2022-12-09 09:11:14', '2504 Fay Stravenue Suite 156\nPort Robert, NV 03203-2566', 'Male', NULL, '743887254', '2025-12-31', '1994-03-19', NULL, NULL, NULL, NULL, '886739', 'user', 'EY1ahghCgI', '2022-12-09 09:11:15', '2022-12-09 09:11:15', NULL),
(15, 'Lilyan', 'Waters', 'nathen.anderson@example.org', '2022-12-09 09:11:14', '$2y$10$itpGYC0IeVEIqmW1Uuu4TOUBkQE4GWWtM.oUwj./Luz.82K4Jxib2', '+1-804-778-0193', '2022-12-09 09:11:14', '56813 Geovanni Spurs\nSouth Friedrich, KY 63173', 'Male', NULL, '1456768284', '2025-12-31', '1994-03-19', NULL, NULL, NULL, NULL, '642738', 'user', 'mkKlt1r3Ot', '2022-12-09 09:11:15', '2022-12-09 09:11:15', NULL),
(16, 'Weldon', 'Yost', 'rogelio.spinka@example.net', '2022-12-09 09:11:14', '$2y$10$shGBlUF7591Hh2Spi2.QKOz2VTErjfwz.HrxixzpFUt6NSaL6Oosu', '+1.872.808.4592', '2022-12-09 09:11:14', '90102 Caesar Plains Apt. 877\nRempelton, GA 12856', 'Male', NULL, '349804171', '2025-12-31', '1994-03-19', NULL, NULL, NULL, NULL, '702369', 'user', 'wYBAYOGj9o', '2022-12-09 09:11:15', '2022-12-09 09:11:15', NULL),
(17, 'Tia', 'Hamill', 'schaden.lisette@example.net', '2022-12-09 09:11:14', '$2y$10$fKpspyiDwVSF9G.OCggBiOBPNX4urKh6P.T4tl7wvNzI9VKjtU9lS', '+1-828-886-9834', '2022-12-09 09:11:14', '970 Bartoletti Drive Apt. 126\nEvertfurt, IN 04654-8798', 'Male', NULL, '399020547', '2025-12-31', '1994-03-19', NULL, NULL, NULL, NULL, '723210', 'user', 'HJiKoVFL9V', '2022-12-09 09:11:15', '2022-12-09 09:11:15', NULL),
(18, 'Alta', 'Kris', 'urunte@example.com', '2022-12-09 09:11:14', '$2y$10$xERd4jdAMi9REv7SZlASeehRy/nqtnhGXtBmKE9/kiDru7tlV44qK', '+1.603.508.7337', '2022-12-09 09:11:14', '133 McDermott Cliff Suite 532\nSouth Elouise, AZ 80421-5856', 'Male', NULL, '332252662', '2025-12-31', '1994-03-19', NULL, NULL, NULL, NULL, '917320', 'user', 'dRqIPa2bwx', '2022-12-09 09:11:15', '2022-12-09 09:11:15', NULL),
(19, 'Shanel', 'Reinger', 'asia.rempel@example.org', '2022-12-09 09:11:14', '$2y$10$aOZZqEWehlsN6t7zCI2OwexVQmEFpLvu5kUgY.uGbI1JiYG3.fI8.', '1-628-438-1519', '2022-12-09 09:11:15', '539 Wunsch Port Apt. 125\nFranciscamouth, OH 66204', 'Male', NULL, '654416133', '2025-12-31', '1994-03-19', NULL, NULL, NULL, NULL, '637838', 'user', 'OivaH48Vqq', '2022-12-09 09:11:15', '2022-12-09 09:11:15', NULL),
(20, 'Robyn', 'OHara', 'barton.gisselle@example.net', '2022-12-09 09:11:15', '$2y$10$2Gus/mrPITrZyyHG1CE.MuPfVv3Yc1z9Z8j2yRO9g53.Zc2q/AnbS', '+1.830.227.7925', '2022-12-09 09:11:15', '694 Savannah Cape Apt. 870\nKilbackton, GA 43905-4447', 'Male', NULL, '1434414259', '2025-12-31', '1994-03-19', NULL, NULL, NULL, NULL, '552834', 'user', 'gBXKjJseKz', '2022-12-09 09:11:15', '2022-12-09 09:11:15', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_phone_unique` (`phone`),
  ADD UNIQUE KEY `users_id_card_number_unique` (`id_card_number`),
  ADD UNIQUE KEY `users_qr_number_unique` (`qr_number`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
