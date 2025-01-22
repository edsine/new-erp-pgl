
-- --------------------------------------------------------

--
-- Table structure for table `dm_clicked_links`
--

CREATE TABLE `dm_clicked_links` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `link_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

/* CREATE TABLE `documents_` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `document_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `folder_id` bigint UNSIGNED NOT NULL,
  `created_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
 */
-- --------------------------------------------------------

--
-- Table structure for table `documents_categories`
--

CREATE TABLE `documents_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `department_id` bigint DEFAULT NULL,
  `branch_id` bigint DEFAULT NULL,
  `parent_id` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `documents_comments`
--

CREATE TABLE `documents_comments` (
  `id` bigint UNSIGNED NOT NULL,
  `document_id` bigint DEFAULT NULL,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_by` bigint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- --------------------------------------------------------

--
-- Table structure for table `documents_comments_files`
--

CREATE TABLE `documents_comments_files` (
  `id` bigint UNSIGNED NOT NULL,
  `document_id` bigint DEFAULT NULL,
  `comment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `documents_has_meta_tags`
--

CREATE TABLE `documents_has_meta_tags` (
  `id` bigint UNSIGNED NOT NULL,
  `document_id` bigint DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `documents_has_roles`
--

CREATE TABLE `documents_has_roles` (
  `id` bigint UNSIGNED NOT NULL,
  `document_id` bigint DEFAULT NULL,
  `role_id` bigint DEFAULT NULL,
  `assigned_by` bigint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `is_download` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `documents_has_users`
--

CREATE TABLE `documents_has_users` (
  `id` bigint UNSIGNED NOT NULL,
  `document_id` bigint DEFAULT NULL,
  `user_id` bigint DEFAULT NULL,
  `assigned_by` bigint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `is_download` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `allow_share` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `priority_id` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `documents_has_users_files`
--

CREATE TABLE `documents_has_users_files` (
  `id` bigint UNSIGNED NOT NULL,
  `category_id` bigint DEFAULT NULL,
  `user_id` bigint DEFAULT NULL,
  `assigned_by` bigint DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `is_download` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `allow_share` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `priority_id` bigint DEFAULT NULL,
  `document_id` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `documents_histories`
--

CREATE TABLE `documents_histories` (
  `id` bigint UNSIGNED NOT NULL,
  `document_id` bigint DEFAULT NULL,
  `document_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `documents_manager`
--

CREATE TABLE `documents_manager` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `document_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` bigint DEFAULT NULL,
  `created_by` bigint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `department_id` bigint DEFAULT NULL,
  `branch_id` bigint DEFAULT NULL,
  `status` enum('0','1','2') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `document_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- --------------------------------------------------------

--
-- Table structure for table `file_urls`
--

CREATE TABLE `file_urls` (
  `id` bigint UNSIGNED NOT NULL,
  `random_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `actual_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `incoming_documents_categories`
--

CREATE TABLE `incoming_documents_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `department_id` bigint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- --------------------------------------------------------

--
-- Table structure for table `incoming_documents_comments`
--

CREATE TABLE `incoming_documents_comments` (
  `id` bigint UNSIGNED NOT NULL,
  `document_id` bigint DEFAULT NULL,
  `comment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- --------------------------------------------------------

--
-- Table structure for table `incoming_documents_comments_files`
--

CREATE TABLE `incoming_documents_comments_files` (
  `id` bigint UNSIGNED NOT NULL,
  `document_id` bigint DEFAULT NULL,
  `comment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `incoming_documents_has_meta_tags`
--

CREATE TABLE `incoming_documents_has_meta_tags` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `document_id` bigint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `incoming_documents_has_users`
--

CREATE TABLE `incoming_documents_has_users` (
  `id` bigint UNSIGNED NOT NULL,
  `document_id` bigint DEFAULT NULL,
  `user_id` bigint DEFAULT NULL,
  `assigned_by` bigint DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `is_download` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `allow_share` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `incoming_documents_has_users_files`
--

CREATE TABLE `incoming_documents_has_users_files` (
  `id` bigint UNSIGNED NOT NULL,
  `document_id` bigint DEFAULT NULL,
  `user_id` bigint DEFAULT NULL,
  `assigned_by` bigint DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `is_download` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `allow_share` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `incoming_documents_manager`
--

CREATE TABLE `incoming_documents_manager` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `document_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` bigint DEFAULT NULL,
  `created_by` bigint DEFAULT NULL,
  `status` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `approved_date_time` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `full_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department_id` bigint DEFAULT NULL,
  `branch_id` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


--
-- Indexes for dumped tables
--

--
-- Indexes for table `dm_clicked_links`
--
ALTER TABLE `dm_clicked_links`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dm_clicked_links_user_id_foreign` (`user_id`);

--
-- Indexes for table `documents`
--
/* ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `documents_folder_id_title_unique` (`folder_id`,`title`),
  ADD KEY `documents_created_by_foreign` (`created_by`);
 */
--
-- Indexes for table `documents_categories`
--
ALTER TABLE `documents_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `documents_comments`
--
ALTER TABLE `documents_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `documents_comments_files`
--
ALTER TABLE `documents_comments_files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `documents_has_meta_tags`
--
ALTER TABLE `documents_has_meta_tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `documents_has_roles`
--
ALTER TABLE `documents_has_roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `documents_has_users`
--
ALTER TABLE `documents_has_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `documents_has_users_files`
--
ALTER TABLE `documents_has_users_files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `documents_histories`
--
ALTER TABLE `documents_histories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `documents_manager`
--
ALTER TABLE `documents_manager`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `file_urls`
--
ALTER TABLE `file_urls`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `file_urls_random_code_unique` (`random_code`);

--
-- Indexes for table `incoming_documents_categories`
--
ALTER TABLE `incoming_documents_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `incoming_documents_comments`
--
ALTER TABLE `incoming_documents_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `incoming_documents_comments_files`
--
ALTER TABLE `incoming_documents_comments_files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `incoming_documents_has_meta_tags`
--
ALTER TABLE `incoming_documents_has_meta_tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `incoming_documents_has_users`
--
ALTER TABLE `incoming_documents_has_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `incoming_documents_has_users_files`
--
ALTER TABLE `incoming_documents_has_users_files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `incoming_documents_manager`
--
ALTER TABLE `incoming_documents_manager`
  ADD PRIMARY KEY (`id`);

  --
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dm_clicked_links`
--
ALTER TABLE `dm_clicked_links`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `documents`
--
/* ALTER TABLE `documents`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;
 */
--
-- AUTO_INCREMENT for table `documents_categories`
--
ALTER TABLE `documents_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `documents_comments`
--
ALTER TABLE `documents_comments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `documents_comments_files`
--
ALTER TABLE `documents_comments_files`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `documents_has_meta_tags`
--
ALTER TABLE `documents_has_meta_tags`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `documents_has_roles`
--
ALTER TABLE `documents_has_roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `documents_has_users`
--
ALTER TABLE `documents_has_users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `documents_has_users_files`
--
ALTER TABLE `documents_has_users_files`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `documents_histories`
--
ALTER TABLE `documents_histories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `documents_manager`
--
ALTER TABLE `documents_manager`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `file_urls`
--
ALTER TABLE `file_urls`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `incoming_documents_categories`
--
ALTER TABLE `incoming_documents_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `incoming_documents_comments`
--
ALTER TABLE `incoming_documents_comments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `incoming_documents_comments_files`
--
ALTER TABLE `incoming_documents_comments_files`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `incoming_documents_has_meta_tags`
--
ALTER TABLE `incoming_documents_has_meta_tags`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `incoming_documents_has_users`
--
ALTER TABLE `incoming_documents_has_users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `incoming_documents_has_users_files`
--
ALTER TABLE `incoming_documents_has_users_files`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `incoming_documents_manager`
--
ALTER TABLE `incoming_documents_manager`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

COMMIT;

