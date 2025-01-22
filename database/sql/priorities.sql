
--
-- Table structure for table `priorities`
--

CREATE TABLE `priorities` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `example_usage` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action_required` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `priorities`
--

INSERT INTO `priorities` (`id`, `name`, `color_code`, `description`, `example_usage`, `action_required`, `created_at`, `updated_at`) VALUES
(1, 'High', '#D32F2F', 'Immediate attention needed, critical to business operations', 'Urgent legal documents, contracts', 'Immediate action, notifications', '2024-12-12 19:50:39', '2024-12-12 19:50:39'),
(2, 'Medium', '#FF9800', 'Standard documents, no urgent action required', 'Weekly reports, regular memos', 'Review in a normal timeframe', '2024-12-12 19:50:39', '2024-12-12 19:50:39'),
(3, 'Low', '#FFEB3B', 'Informational, can be addressed at convenience', 'Marketing material, training guides', 'Read at leisure, no action needed', '2024-12-12 19:50:39', '2024-12-12 19:50:39'),
(4, 'Critical', '#B71C1C', 'Extremely urgent, emergency situations, high stakes', 'Crisis management, urgent legal docs', 'Immediate action, escalation', '2024-12-12 19:50:39', '2024-12-12 19:50:39'),
(5, 'Informational', '#2196F3', 'For reference, no action needed', 'Reports, newsletters', 'View-only, informational access', '2024-12-12 19:50:39', '2024-12-12 19:50:39'),
(6, 'Review Pending', '#FFC107', 'Requires review/approval for progression', 'Proposal drafts, budgets', 'Approval/review required', '2024-12-12 19:50:39', '2024-12-12 19:50:39'),
(7, 'Confidential', '#757575', 'Sensitive information, restricted access', 'Contracts, financial audits', 'Secure access, encryption', '2024-12-12 19:50:39', '2024-12-12 19:50:39');

CREATE TABLE `levels` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `dta_allowance` decimal(18,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `levels`
--

INSERT INTO `levels` (`id`, `name`, `created_at`, `updated_at`, `deleted_at`, `dta_allowance`) VALUES
(1, 'Level 1', '2024-04-12 14:31:49', '2024-04-12 14:31:49', NULL, '10000.00'),
(2, 'Level 2', '2024-04-12 14:31:49', '2024-04-12 14:31:49', NULL, '10000.00'),
(3, 'Level 3', '2024-04-12 14:31:49', '2024-04-12 14:31:49', NULL, '10000.00'),
(4, 'Level 4', '2024-04-12 14:31:49', '2024-04-12 14:31:49', NULL, '10000.00'),
(5, 'Level 5', '2024-04-12 14:31:49', '2024-04-12 14:31:49', NULL, '15000.00'),
(6, 'Level 6', '2024-04-12 14:31:49', '2024-04-12 14:31:49', NULL, '15000.00'),
(7, 'Level 7', '2024-04-12 14:31:49', '2024-04-12 14:31:49', NULL, '17500.00'),
(8, 'Level 8', '2024-04-12 14:31:49', '2024-04-12 14:31:49', NULL, '17500.00'),
(9, 'Level 9', '2024-04-12 14:31:49', '2024-04-12 14:31:49', NULL, '17500.00'),
(10, 'Level 10', '2024-04-12 14:31:49', '2024-04-12 14:31:49', NULL, '17500.00'),
(11, 'Level 11', '2024-04-12 14:31:49', '2024-04-12 14:31:49', NULL, NULL),
(12, 'Level 12', '2024-04-12 14:31:49', '2024-04-12 14:31:49', NULL, '20000.00'),
(13, 'Level 13', '2024-04-12 14:31:49', '2024-04-12 14:31:49', NULL, '20000.00'),
(14, 'Level 14', '2024-04-12 14:31:49', '2024-04-12 14:31:49', NULL, '25000.00'),
(15, 'Level 15', '2024-04-12 14:31:49', '2024-04-12 14:31:49', NULL, '25000.00'),
(16, 'Level 16', '2024-04-12 14:31:49', '2024-04-12 14:31:49', NULL, '37500.00'),
(17, 'Level 17', '2024-04-12 14:31:49', '2024-04-12 14:31:49', NULL, '37500.00'),
(18, 'Level 18', '2024-04-12 14:31:49', '2024-04-12 14:31:49', NULL, NULL),
(19, 'Level 19', '2024-04-12 14:31:49', '2024-04-12 14:31:49', NULL, NULL),
(20, 'Level 20', '2024-04-12 14:31:49', '2024-04-12 14:31:49', NULL, NULL);


CREATE TABLE `meta_tags` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `document_id` bigint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `signatures` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `signature_data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


ALTER TABLE `signatures`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `signatures`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- Indexes for dumped tables
--

--
-- Indexes for table `meta_tags`
--
ALTER TABLE `meta_tags`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `meta_tags`
--
ALTER TABLE `meta_tags`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- Indexes for dumped tables
--

--
-- Indexes for table `priorities`
--
ALTER TABLE `priorities`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `priorities`
--
ALTER TABLE `priorities`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- Indexes for dumped tables
--

--
-- Indexes for table `levels`
--
ALTER TABLE `levels`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `levels`
--
ALTER TABLE `levels`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;
