-- --------------------------------------------------------
-- Host:                         193.203.184.55
-- Server version:               10.11.10-MariaDB - MariaDB Server
-- Server OS:                    Linux
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table u701525125_wapanel.addons
CREATE TABLE IF NOT EXISTS `addons` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(50) NOT NULL,
  `category` varchar(128) NOT NULL,
  `name` varchar(255) NOT NULL,
  `logo` varchar(128) NOT NULL,
  `description` text DEFAULT NULL,
  `metadata` text DEFAULT NULL,
  `license` varchar(255) DEFAULT NULL,
  `version` varchar(128) DEFAULT NULL,
  `is_plan_restricted` tinyint(1) NOT NULL DEFAULT 0,
  `update_available` tinyint(1) NOT NULL DEFAULT 0,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table u701525125_wapanel.addons: ~9 rows (approximately)
INSERT INTO `addons` (`id`, `uuid`, `category`, `name`, `logo`, `description`, `metadata`, `license`, `version`, `is_plan_restricted`, `update_available`, `status`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 'd38ce665-739f-4425-a162-6f0cbc3b9ac1', 'chat', 'Embedded Signup', 'whatsapp.png', 'An Embedded Signup add-on allows app users to register using their WhatsApp account.', '{"name":"EmbeddedSignup","input_fields":[{"element":"input","type":"text","name":"whatsapp_client_id","label":"App ID","class":"col-span-1"},{"element":"input","type":"password","name":"whatsapp_client_secret","label":"App secret","class":"col-span-1"},{"element":"input","type":"text","name":"whatsapp_config_id","label":"Config ID","class":"col-span-2"},{"element":"input","type":"password","name":"whatsapp_access_token","label":"Access token","class":"col-span-2"}]}', NULL, '1.3', 1, 0, 1, 1, '2025-01-13 15:27:08', '2025-02-13 20:33:16'),
	(2, '81c34a22-0a2a-4564-b149-a4aa120ab453', 'recaptcha', 'Google Recaptcha', 'google_recaptcha.png', 'Google reCAPTCHA enhances website security by preventing spam and abusive activities.', '{\n    "input_fields": [\n        {\n            "element": "input",\n            "type": "password",\n            "name": "recaptcha_site_key",\n            "label": "Recaptcha site key",\n            "class": "col-span-2"\n        },\n        {\n            "element": "input",\n            "type": "password",\n            "name": "recaptcha_secret_key",\n            "label": "Recaptcha secret key",\n            "class": "col-span-2"\n        }\n    ],\n    "name": "GoogleRecaptcha"\n}', NULL, NULL, 0, 0, 1, 0, '2025-01-13 15:27:08', '2025-02-07 17:36:55'),
	(3, 'e337671f-7874-4005-a1bf-f94035704066', 'analytics', 'Google Analytics', 'google_analytics.png', 'Google Analytics tracks website performance and provides valuable insights for optimization.', '{"input_fields": [{"element": "input", "type": "text", "name": "google_analytics_tracking_id", "label": "Google analytics tracking ID", "class": "col-span-2"}]}', NULL, NULL, 0, 0, 1, 0, '2025-01-13 15:27:08', '2025-01-13 15:27:08'),
	(4, 'c0f03df5-2a51-4a2f-a3e3-1b6d80035071', 'maps', 'Google Maps', 'google_maps.png', 'Google Maps provides interactive maps for whatsapp messages.', '{"input_fields": [{"element": "input", "type": "text", "name": "google_maps_api_key", "label": "Google maps API key", "class": "col-span-2"}]}', NULL, NULL, 0, 0, 1, 0, '2025-01-13 15:27:08', '2025-01-13 15:27:08'),
	(5, 'cda1eefb-e41b-4467-bb0f-e1f2a4454a19', 'payments', 'Razorpay', 'razorpay.png', 'Razorpay is a payment platform that simplies payment processing.', '{\n    "input_fields": [\n        {\n            "element": "input",\n            "type": "text",\n            "name": "razorpay_key_id",\n            "label": "Key ID",\n            "class": "col-span-2"\n        },\n        {\n            "element": "input",\n            "type": "text",\n            "name": "razorpay_secret_key",\n            "label": "Secret Key",\n            "class": "col-span-2"\n        },\n        {\n            "element": "input",\n            "type": "text",\n            "name": "razorpay_webhook_secret",\n            "label": "Webhook secret",\n            "class": "col-span-2"\n        }\n    ],\n    "name": "Razorpay"\n}', NULL, NULL, 0, 0, 1, 0, '2025-01-13 15:27:08', '2025-02-07 17:36:55'),
	(6, '23ef8fc1-1e77-4e34-a6fa-13026351f00a', 'ai', 'AI Assistant', 'ai.png', 'The AI assistant delivers intelligent, AI-driven responses by utilizing user data for training.', '{\n    "name": "IntelliReply"\n}', NULL, NULL, 1, 0, 0, 0, '2025-01-13 15:27:08', '2025-02-07 17:36:55'),
	(7, '5fe511f2-2ae4-4756-bed5-fb4141984920', 'utility', 'Webhooks', 'webhook_icon.png', 'Webhooks enable real-time data transfer by sending automated notifications on specific events.', '{\n    "name": "Webhook"\n}', NULL, NULL, 1, 0, 0, 0, '2025-01-13 15:27:08', '2025-02-07 17:36:55'),
	(8, '2720f1ad-23ef-443b-a925-a2bf4bff8517', 'utility', 'Flow builder', 'flow_icon.png', 'Flow Builder automation allows users to visually create and manage messaging workflows.', '{"name":"FlowBuilder"}', NULL, '1.4', 1, 0, 1, 1, '2025-01-13 15:27:08', '2025-02-07 17:37:07'),
	(9, 'f7474b11-1790-49f1-a877-a538b07891ad', 'two factor authentication', 'Google Authenticator', 'google_authenticator.png', 'Google Authenticator enhances security with two-factor authentication.', '{\n    "name": "GoogleAuthenticator"\n}', 'regular', '1.0', 0, 0, 1, 0, '2025-01-13 15:27:08', '2025-02-07 17:36:55');

-- Dumping structure for table u701525125_wapanel.auto_replies
CREATE TABLE IF NOT EXISTS `auto_replies` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(50) NOT NULL,
  `organization_id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `trigger` text NOT NULL,
  `match_criteria` varchar(100) NOT NULL,
  `metadata` text NOT NULL,
  `created_by` int(11) NOT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `auto_replies_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table u701525125_wapanel.auto_replies: ~0 rows (approximately)

-- Dumping structure for table u701525125_wapanel.billing_credits
CREATE TABLE IF NOT EXISTS `billing_credits` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(50) NOT NULL,
  `organization_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `amount` decimal(19,4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `billing_credits_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table u701525125_wapanel.billing_credits: ~0 rows (approximately)

-- Dumping structure for table u701525125_wapanel.billing_debits
CREATE TABLE IF NOT EXISTS `billing_debits` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(50) NOT NULL,
  `organization_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `amount` decimal(19,4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `billing_debits_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table u701525125_wapanel.billing_debits: ~0 rows (approximately)

-- Dumping structure for table u701525125_wapanel.billing_invoices
CREATE TABLE IF NOT EXISTS `billing_invoices` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(50) NOT NULL,
  `organization_id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `subtotal` decimal(19,4) NOT NULL,
  `coupon_id` int(11) DEFAULT NULL,
  `coupon_amount` decimal(23,2) DEFAULT 0.00,
  `tax` decimal(23,10) NOT NULL DEFAULT 0.0000000000,
  `tax_type` enum('inclusive','exclusive') NOT NULL,
  `total` decimal(19,4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `billing_invoices_uuid_unique` (`uuid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table u701525125_wapanel.billing_invoices: ~0 rows (approximately)

-- Dumping structure for table u701525125_wapanel.billing_items
CREATE TABLE IF NOT EXISTS `billing_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `billing_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `interval` int(11) NOT NULL,
  `amount` decimal(19,4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table u701525125_wapanel.billing_items: ~0 rows (approximately)

-- Dumping structure for table u701525125_wapanel.billing_payments
CREATE TABLE IF NOT EXISTS `billing_payments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(50) NOT NULL,
  `organization_id` int(11) NOT NULL,
  `processor` varchar(255) NOT NULL,
  `details` text DEFAULT NULL,
  `amount` decimal(19,4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `billing_payments_uuid_unique` (`uuid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping structure for table u701525125_wapanel.billing_tax_rates
CREATE TABLE IF NOT EXISTS `billing_tax_rates` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(50) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `rate` decimal(19,4) NOT NULL,
  `amount` decimal(19,4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `billing_tax_rates_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table u701525125_wapanel.billing_tax_rates: ~0 rows (approximately)

-- Dumping structure for table u701525125_wapanel.billing_transactions
CREATE TABLE IF NOT EXISTS `billing_transactions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(50) NOT NULL,
  `organization_id` int(11) NOT NULL,
  `entity_type` enum('payment','invoice','credit','debit') NOT NULL,
  `entity_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `amount` decimal(19,4) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `billing_transactions_uuid_unique` (`uuid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table u701525125_wapanel.billing_transactions: ~0 rows (approximately)

-- Dumping structure for table u701525125_wapanel.blog_authors
CREATE TABLE IF NOT EXISTS `blog_authors` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(128) NOT NULL,
  `first_name` varchar(128) NOT NULL,
  `last_name` varchar(128) NOT NULL,
  `bio` text DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `blog_authors_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table u701525125_wapanel.blog_authors: ~0 rows (approximately)

-- Dumping structure for table u701525125_wapanel.blog_categories
CREATE TABLE IF NOT EXISTS `blog_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT 0,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `blog_categories_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table u701525125_wapanel.blog_categories: ~0 rows (approximately)

-- Dumping structure for table u701525125_wapanel.blog_posts
CREATE TABLE IF NOT EXISTS `blog_posts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(50) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `author_id` int(11) NOT NULL,
  `is_featured` tinyint(4) NOT NULL DEFAULT 0,
  `published` int(11) NOT NULL DEFAULT 0,
  `deleted` tinyint(4) NOT NULL DEFAULT 0,
  `created_by` int(11) NOT NULL,
  `publish_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `blog_posts_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table u701525125_wapanel.blog_posts: ~0 rows (approximately)

-- Dumping structure for table u701525125_wapanel.blog_tags
CREATE TABLE IF NOT EXISTS `blog_tags` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT 0,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `blog_tags_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table u701525125_wapanel.blog_tags: ~0 rows (approximately)

-- Dumping structure for table u701525125_wapanel.campaigns
CREATE TABLE IF NOT EXISTS `campaigns` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(50) NOT NULL,
  `organization_id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `template_id` int(11) NOT NULL,
  `contact_group_id` int(11) NOT NULL,
  `metadata` text NOT NULL,
  `status` varchar(128) NOT NULL DEFAULT 'pending',
  `scheduled_at` datetime DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `campaigns_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table u701525125_wapanel.campaigns: ~0 rows (approximately)

-- Dumping structure for table u701525125_wapanel.campaign_logs
CREATE TABLE IF NOT EXISTS `campaign_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `campaign_id` int(11) NOT NULL,
  `contact_id` int(11) NOT NULL,
  `chat_id` int(11) DEFAULT NULL,
  `metadata` text DEFAULT NULL,
  `status` enum('pending','success','failed','ongoing') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table u701525125_wapanel.campaign_logs: ~0 rows (approximately)

-- Dumping structure for table u701525125_wapanel.chats
CREATE TABLE IF NOT EXISTS `chats` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(50) NOT NULL,
  `organization_id` int(11) NOT NULL,
  `wam_id` varchar(128) DEFAULT NULL,
  `contact_id` int(11) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `type` enum('inbound','outbound') DEFAULT NULL,
  `metadata` text NOT NULL,
  `media_id` int(11) DEFAULT NULL,
  `status` varchar(191) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `chats_uuid_unique` (`uuid`),
  KEY `chats_contact_id_index` (`contact_id`),
  KEY `chats_created_at_index` (`created_at`),
  KEY `idx_chats_contact_org_deleted_at` (`contact_id`,`organization_id`,`deleted_at`)
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping structure for table u701525125_wapanel.chat_logs
CREATE TABLE IF NOT EXISTS `chat_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `contact_id` int(11) NOT NULL,
  `entity_type` varchar(128) NOT NULL,
  `entity_id` int(11) NOT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping structure for table u701525125_wapanel.chat_media
CREATE TABLE IF NOT EXISTS `chat_media` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `path` varchar(255) DEFAULT NULL,
  `location` enum('local','amazon') NOT NULL DEFAULT 'local',
  `type` varchar(255) DEFAULT NULL,
  `size` varchar(128) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping structure for table u701525125_wapanel.chat_notes
CREATE TABLE IF NOT EXISTS `chat_notes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(50) NOT NULL,
  `contact_id` bigint(20) unsigned NOT NULL,
  `content` text DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table u701525125_wapanel.chat_notes: ~0 rows (approximately)

-- Dumping structure for table u701525125_wapanel.chat_status_logs
CREATE TABLE IF NOT EXISTS `chat_status_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `chat_id` int(11) NOT NULL,
  `metadata` text NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=108 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping structure for table u701525125_wapanel.chat_tickets
CREATE TABLE IF NOT EXISTS `chat_tickets` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `contact_id` int(11) NOT NULL,
  `assigned_to` int(11) DEFAULT NULL,
  `status` varchar(128) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `chat_tickets_contact_id_index` (`contact_id`),
  KEY `idx_chat_tickets_contact_assigned_to_status` (`contact_id`,`assigned_to`,`status`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping structure for table u701525125_wapanel.chat_ticket_logs
CREATE TABLE IF NOT EXISTS `chat_ticket_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `contact_id` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping structure for table u701525125_wapanel.contacts
CREATE TABLE IF NOT EXISTS `contacts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(50) NOT NULL,
  `organization_id` int(11) NOT NULL,
  `first_name` varchar(128) DEFAULT NULL,
  `last_name` varchar(128) DEFAULT NULL,
  `phone` varchar(191) DEFAULT NULL,
  `email` varchar(128) DEFAULT NULL,
  `latest_chat_created_at` timestamp NULL DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `metadata` text DEFAULT NULL,
  `contact_group_id` int(11) DEFAULT NULL,
  `is_favorite` tinyint(4) NOT NULL DEFAULT 0,
  `ai_assistance_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `contacts_uuid_unique` (`uuid`),
  KEY `contacts_organization_id_index` (`organization_id`),
  KEY `contacts_deleted_at_index` (`deleted_at`),
  KEY `contacts_latest_chat_created_at_index` (`latest_chat_created_at`),
  KEY `idx_contacts_first_name` (`first_name`),
  KEY `idx_contacts_last_name` (`last_name`),
  KEY `idx_contacts_email` (`email`),
  KEY `idx_contacts_phone` (`phone`),
  FULLTEXT KEY `fulltext_contacts_name_email_phone` (`first_name`,`last_name`,`phone`,`email`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping structure for table u701525125_wapanel.contact_fields
CREATE TABLE IF NOT EXISTS `contact_fields` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint(20) unsigned NOT NULL,
  `uuid` char(50) NOT NULL,
  `position` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(128) NOT NULL,
  `type` varchar(128) NOT NULL,
  `value` text DEFAULT NULL,
  `required` tinyint(3) unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping structure for table u701525125_wapanel.contact_groups
CREATE TABLE IF NOT EXISTS `contact_groups` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(50) NOT NULL,
  `organization_id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `contact_groups_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table u701525125_wapanel.contact_groups: ~0 rows (approximately)

-- Dumping structure for table u701525125_wapanel.coupons
CREATE TABLE IF NOT EXISTS `coupons` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `percentage` decimal(5,2) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `quantity_redeemed` int(11) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `coupons_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table u701525125_wapanel.coupons: ~0 rows (approximately)

-- Dumping structure for table u701525125_wapanel.documents
CREATE TABLE IF NOT EXISTS `documents` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) NOT NULL,
  `organization_id` bigint(20) unsigned NOT NULL,
  `source` varchar(128) NOT NULL,
  `title` varchar(128) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `embeddings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`embeddings`)),
  `status` varchar(128) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `documents_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table u701525125_wapanel.documents: ~0 rows (approximately)

-- Dumping structure for table u701525125_wapanel.email_logs
CREATE TABLE IF NOT EXISTS `email_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(50) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `recipient` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` enum('queued','sent','failed') NOT NULL DEFAULT 'queued',
  `attempts` int(11) NOT NULL DEFAULT 0,
  `metadata` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_logs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table u701525125_wapanel.email_logs: ~0 rows (approximately)

-- Dumping structure for table u701525125_wapanel.email_templates
CREATE TABLE IF NOT EXISTS `email_templates` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `subject` varchar(128) NOT NULL,
  `body` blob NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_by` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table u701525125_wapanel.email_templates: ~9 rows (approximately)
INSERT INTO `email_templates` (`id`, `name`, `subject`, `body`, `updated_at`, `updated_by`) VALUES
	(1, 'Reset Password', 'Reset Password', _binary 0x3c703e4869207b7b46697273744e616d657d7d2c3c2f703e3c703e596f752068617665207375626d697474656420612070617373776f726420726573657420666f7220796f7572206163636f756e742e204966207468697320776173206e6f7420796f752c2073696d706c792069676e6f7265207468697320656d61696c2e2042757420696620796f75206469642c20636c69636b206f6e2074686973206c696e6b207b7b4c696e6b7d7d20746f20726573657420796f75722070617373776f72642e204966207468617420646f65736e277420776f726b2c20636f707920616e642070617374652074686973206c696e6b20746f20796f75722062726f777365722e3c2f703e3c703e7b7b4c696e6b7d7d3c2f703e, '2025-01-13 15:27:08', 1),
	(2, 'Password Reset Notification', 'Your Password has been reset', _binary 0x3c703e4869207b7b46697273744e616d657d7d2c3c2f703e3c703e596f75722070617373776f726420686173206265656e207265736574207375636365737366756c6c792120596f752063616e206c6f67696e20746f20796f7572206163636f756e742e3c2f703e, '2025-01-13 15:27:08', 1),
	(3, 'Registration', 'Welcome to {{CompanyName}}', _binary 0x3c703e48656c6c6f207b7b46697273744e616d657d7d2c3c2f703e3c703e4920616d204a6f652c2074686520666f756e646572206f66207b7b436f6d70616e794e616d657d7d20616e64204920776f756c64206c696b6520746f20657874656e64206d7920686561727466656c742077656c636f6d6520746f20796f7520666f72206a6f696e696e67206f757220706c6174666f726d2e20576520617265206578636974656420746f206861766520796f75206f6e626f6172642e204665656c206672656520746f206578706c6f7265206f757220706c6174666f726d20616e64206c6574207573206b6e6f7720696620796f75206861766520616e79207175657374696f6e73206f72206e65656420617373697374616e63652e203c2f703e3c703e5468616e6b20796f7520666f722063686f6f73696e67206f757220706c6174666f726d213c2f703e3c703e4265737420726567617264732c3c2f703e3c703e546865207b7b436f6d70616e794e616d657d7d205465616d3c2f703e3c703e3c62723e3c2f703e, '2025-01-13 15:27:08', 1),
	(4, 'Invite', 'You have been invited to join {{CompanyName}}', _binary 0x3c703e48656c6c6f2074686572652c3c2f703e3c703e3c7370616e207374796c653d22636f6c6f723a207267622835352c2036352c203831293b223e596f7527766520726563656976656420616e20696e7669746174696f6e20746f206a6f696e207b7b436f6d70616e794e616d657d7d2773206163636f756e742066726f6d207b7b46697273744e616d657d7d2e3c2f7370616e3e3c2f703e3c703e546f2067657420737461727465642c2073696d706c7920636c69636b206f6e2074686520666f6c6c6f77696e67206c696e6b3a207b7b4c696e6b7d7d3c2f703e3c703e5468616e6b20796f7520616e642077656c636f6d652061626f617264213c2f703e3c703e4265737420726567617264732c3c2f703e3c703e7b7b436f6d70616e794e616d657d7d205465616d203c2f703e3c703e3c62723e3c2f703e, '2025-01-13 15:27:08', 1),
	(5, 'Verify Email', 'Please verify your email', _binary 0x3c703e4869207b66697273744e616d657d2c3c2f703e3c703e506c656173652076657269667920796f757220656d61696c20627920636c69636b696e67206f6e20746865206c696e6b2062656c6f772e3c2f703e3c703e7b766572696669636174696f6e4c696e6b7d3c2f703e3c703e3c7370616e207374796c653d226c65747465722d73706163696e673a2030656d3b20746578742d616c69676e3a20766172282d2d62732d626f64792d746578742d616c69676e293b223e4265737420726567617264732c3c2f7370616e3e3c2f703e3c703e3c62723e3c2f703e, '2025-01-13 15:27:08', 1),
	(6, 'Payment Success', 'Your subscription payment was successful', _binary 0x3c703e48656c6c6f2c3c2f703e3c703e596f757220737562736372697074696f6e207061796d656e7420776173207375636365737366756c213c2f703e, '2025-01-13 15:27:08', 1),
	(7, 'Payment Failed', 'Your subscription payment was unsuccessful', _binary 0x3c703e48656c6c6f2c3c2f703e3c703e596f7572207061796d656e742077617320756e7375636365737366756c2c20706c6561736520636865636b20796f7572207061796d656e7420616e6420636f6e6669726d2e3c2f703e3c703e3c62723e3c2f703e, '2025-01-13 15:27:08', 1),
	(8, 'Subscription Renewal', 'Your subscription has been renewed successfully', _binary 0x3c703e4869207b7b46697273744e616d657d7d2c3c2f703e3c703e596f757220737562736372697074696f6e20686173206265656e2072656e65776564207375636365737366756c6c792e203c2f703e, '2025-01-13 15:27:08', 1),
	(9, 'Subscription Plan Purchase', 'Your have subscribed to {{plan}} successfully', _binary 0x3c703e4869207b7b46697273744e616d657d7d2c3c2f703e3c703e596f752068617665206265656e207375627363726962656420746f20746865207b7b706c616e7d7d20706c616e207375636365737366756c6c792e3c2f703e, '2025-01-13 15:27:08', 1);

-- Dumping structure for table u701525125_wapanel.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table u701525125_wapanel.failed_jobs: ~0 rows (approximately)

-- Dumping structure for table u701525125_wapanel.faqs
CREATE TABLE IF NOT EXISTS `faqs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `question` text DEFAULT NULL,
  `answer` text DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table u701525125_wapanel.faqs: ~0 rows (approximately)

-- Dumping structure for table u701525125_wapanel.flows
CREATE TABLE IF NOT EXISTS `flows` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) NOT NULL,
  `organization_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `trigger` enum('new_contact','keywords') DEFAULT NULL,
  `keywords` text DEFAULT NULL,
  `metadata` text DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `flows_uuid_unique` (`uuid`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping structure for table u701525125_wapanel.flow_logs
CREATE TABLE IF NOT EXISTS `flow_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `flow_id` bigint(20) unsigned NOT NULL,
  `chat_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `flow_logs_flow_id_foreign` (`flow_id`),
  CONSTRAINT `flow_logs_flow_id_foreign` FOREIGN KEY (`flow_id`) REFERENCES `flows` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping structure for table u701525125_wapanel.flow_media
CREATE TABLE IF NOT EXISTS `flow_media` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) NOT NULL,
  `flow_id` bigint(20) unsigned NOT NULL,
  `step_id` bigint(20) unsigned NOT NULL,
  `path` varchar(255) DEFAULT NULL,
  `location` enum('local','aws') NOT NULL DEFAULT 'local',
  `metadata` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `flow_media_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table u701525125_wapanel.flow_media: ~0 rows (approximately)

-- Dumping structure for table u701525125_wapanel.flow_user_data
CREATE TABLE IF NOT EXISTS `flow_user_data` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `contact_id` bigint(20) unsigned NOT NULL,
  `flow_id` bigint(20) unsigned NOT NULL,
  `current_step` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `flow_user_data_flow_id_foreign` (`flow_id`),
  KEY `flow_user_data_contact_id_index` (`contact_id`),
  CONSTRAINT `flow_user_data_flow_id_foreign` FOREIGN KEY (`flow_id`) REFERENCES `flows` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping structure for table u701525125_wapanel.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table u701525125_wapanel.jobs: ~0 rows (approximately)

-- Dumping structure for table u701525125_wapanel.languages
CREATE TABLE IF NOT EXISTS `languages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `code` varchar(100) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table u701525125_wapanel.languages: ~3 rows (approximately)
INSERT INTO `languages` (`id`, `name`, `code`, `status`, `deleted_at`, `deleted_by`, `created_at`, `updated_at`) VALUES
	(1, 'English', 'en', 'active', NULL, NULL, '2025-01-13 15:27:08', '2025-01-13 15:27:08'),
	(2, 'French', 'fr', 'active', '2025-02-13 20:52:34', 1, '2025-01-13 15:27:08', '2025-02-13 20:52:34'),
	(3, 'Spanish', 'es', 'active', '2025-02-13 20:52:37', 1, '2025-01-13 15:27:08', '2025-02-13 20:52:37');

-- Dumping structure for table u701525125_wapanel.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=97 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table u701525125_wapanel.migrations: ~91 rows (approximately)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2024_03_20_050200_create_auto_replies_table', 1),
	(2, '2024_03_20_050311_create_billing_credits_table', 1),
	(3, '2024_03_20_050348_create_billing_debits_table', 1),
	(4, '2024_03_20_050430_create_billing_invoices_table', 1),
	(5, '2024_03_20_050508_create_billing_items_table', 1),
	(6, '2024_03_20_050600_create_billing_payments_table', 1),
	(7, '2024_03_20_050635_create_billing_tax_rates_table', 1),
	(8, '2024_03_20_050711_create_billing_transactions_table', 1),
	(9, '2024_03_20_050751_create_blog_authors_table', 1),
	(10, '2024_03_20_050826_create_blog_categories_table', 1),
	(11, '2024_03_20_050912_create_blog_posts_table', 1),
	(12, '2024_03_20_050959_create_blog_tags_table', 1),
	(13, '2024_03_20_051036_create_campaigns_table', 1),
	(14, '2024_03_20_051111_create_campaign_logs_table', 1),
	(15, '2024_03_20_051154_create_chats_table', 1),
	(16, '2024_03_20_051253_create_chat_logs_table', 1),
	(17, '2024_03_20_051336_create_chat_media_table', 1),
	(18, '2024_03_20_051414_create_contacts_table', 1),
	(19, '2024_03_20_051449_create_contact_groups_table', 1),
	(20, '2024_03_20_051537_create_coupons_table', 1),
	(21, '2024_03_20_051613_create_email_logs_table', 1),
	(22, '2024_03_20_051655_create_email_templates_table', 1),
	(23, '2024_03_20_051739_create_failed_jobs_table', 1),
	(24, '2024_03_20_051807_create_faqs_table', 1),
	(25, '2024_03_20_051847_create_jobs_table', 1),
	(26, '2024_03_20_051919_create_modules_table', 1),
	(27, '2024_03_20_051953_create_notifications_table', 1),
	(28, '2024_03_20_052034_create_organizations_table', 1),
	(29, '2024_03_20_052107_create_pages_table', 1),
	(30, '2024_03_20_052141_create_password_reset_tokens_table', 1),
	(31, '2024_03_20_052223_create_payment_gateways_table', 1),
	(32, '2024_03_20_052338_create_reviews_table', 1),
	(33, '2024_03_20_052401_create_users_table', 1),
	(34, '2024_03_20_052430_create_roles_table', 1),
	(35, '2024_03_20_052513_create_role_permissions_table', 1),
	(36, '2024_03_20_052620_create_settings_table', 1),
	(37, '2024_03_20_052654_create_subscriptions_table', 1),
	(38, '2024_03_20_052731_create_subscription_plans_table', 1),
	(39, '2024_03_20_052808_create_tax_rates_table', 1),
	(40, '2024_03_20_052839_create_teams_table', 1),
	(41, '2024_03_20_052914_create_team_invites_table', 1),
	(42, '2024_03_20_052920_create_ticket_categories_table', 1),
	(43, '2024_03_20_052956_create_templates_table', 1),
	(44, '2024_03_20_053038_create_tickets_table', 1),
	(45, '2024_03_20_053205_create_ticket_comments_table', 1),
	(46, '2024_04_08_133150_create_organization_api_keys_table', 1),
	(47, '2024_04_24_211852_create_languages', 1),
	(48, '2024_04_27_155643_create_contact_fields_table', 1),
	(49, '2024_04_27_160152_add_metadata_to_contacts_table', 1),
	(50, '2024_05_11_052902_create_chat_notes_table', 1),
	(51, '2024_05_11_052925_create_chat_tickets_table', 1),
	(52, '2024_05_11_052940_create_chat_ticket_logs_table', 1),
	(53, '2024_05_11_053846_rename_chat_logs_table', 1),
	(54, '2024_05_11_054010_create_chat_logs_2_table', 1),
	(55, '2024_05_11_063255_add_user_id_to_chats_table', 1),
	(56, '2024_05_11_063540_add_role_to_team_invites_table', 1),
	(57, '2024_05_11_063819_update_agent_role_to_teams_table', 1),
	(58, '2024_05_11_064650_add_deleted_by_to_organization_api_keys_table', 1),
	(59, '2024_05_11_065031_add_organization_id_to_tickets_table', 1),
	(60, '2024_05_28_080331_make_password_nullable_in_users_table', 1),
	(61, '2024_05_30_125859_modify_campaigns_table', 1),
	(62, '2024_06_03_124254_create_addons_table', 1),
	(63, '2024_06_07_040536_update_users_table_for_facebook_login', 1),
	(64, '2024_06_07_040843_update_chat_media_table', 1),
	(65, '2024_06_07_074903_add_soft_delete_to_teams_and_organizations', 1),
	(66, '2024_06_09_155053_modify_billing_payments_table', 1),
	(67, '2024_06_12_070820_modify_faqs_table', 1),
	(68, '2024_07_04_053236_modify_amount_columns_in_billing_tables', 1),
	(69, '2024_07_04_054143_modify_contacts_table_encoding', 1),
	(70, '2024_07_09_011419_drop_seo_from_pages_table', 1),
	(71, '2024_07_17_062442_allow_null_content_in_pages_table', 1),
	(72, '2024_07_24_080535_add_latest_chat_created_at_to_contacts_table', 1),
	(73, '2024_08_01_050752_add_ongoing_to_status_enum_in_campaign_logs_table', 1),
	(74, '2024_08_08_130306_add_is_read_to_chats_table', 1),
	(75, '2024_08_10_071237_create_documents_table', 1),
	(76, '2024_10_16_201832_change_metadata_column_in_organizations_table', 1),
	(77, '2024_11_12_101941_add_license_column_to_addons_table', 1),
	(78, '2024_11_25_114450_add_version_and_update_needed_to_addons_table', 1),
	(79, '2024_11_28_083453_add_tfa_secret_to_users_table', 1),
	(80, '2024_11_29_070806_create_seeder_histories_table', 1),
	(81, '2024_12_20_081118_add_is_plan_restricted_to_addons_table', 1),
	(82, '2024_12_20_130829_add_is_active_table', 1),
	(83, '2025_01_24_090926_add_index_to_chats_table', 2),
	(84, '2025_01_24_091012_add_index_to_chat_tickets_table', 2),
	(85, '2025_01_24_091043_add_index_to_contacts_first_name', 2),
	(86, '2025_01_24_091115_add_fulltext_index_to_contacts_table', 2),
	(87, '2025_01_29_071445_modify_status_column_in_chats_table', 2),
	(90, '2025_01_20_171113_create_webhook_workflows_table', 4),
	(91, '2025_02_15_174435_create_webhook_logs_table', 5),
	(94, '2019_12_14_000001_create_personal_access_tokens_table', 6),
	(96, '2025_02_16_083243_create_products_table', 7);

-- Dumping structure for table u701525125_wapanel.modules
CREATE TABLE IF NOT EXISTS `modules` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `actions` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table u701525125_wapanel.modules: ~8 rows (approximately)
INSERT INTO `modules` (`id`, `name`, `actions`) VALUES
	(1, 'customers', 'view, create, edit, delete'),
	(2, 'organizations', 'view, create, edit, delete'),
	(3, 'billing', 'view'),
	(4, 'support', 'view, create, assign'),
	(5, 'team', 'view, create, edit, delete'),
	(6, 'roles', 'view, create, edit, delete'),
	(7, 'subscription_plans', 'view, create, edit, delete'),
	(8, 'settings', 'general, timezone, broadcast_driver, payment_gateways, smtp, email_templates, billing, tax_rates, coupons, frontend');

-- Dumping structure for table u701525125_wapanel.notifications
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(50) NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `title` varchar(191) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `url` varchar(191) DEFAULT NULL,
  `seen` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `notifications_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table u701525125_wapanel.notifications: ~0 rows (approximately)

-- Dumping structure for table u701525125_wapanel.organizations
CREATE TABLE IF NOT EXISTS `organizations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(50) NOT NULL,
  `identifier` varchar(128) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `timezone` varchar(128) DEFAULT NULL,
  `created_by` bigint(20) unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `organizations_uuid_unique` (`uuid`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping structure for table u701525125_wapanel.organization_api_keys
CREATE TABLE IF NOT EXISTS `organization_api_keys` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(50) NOT NULL,
  `organization_id` bigint(20) unsigned NOT NULL,
  `token` varchar(255) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `organization_api_keys_token_unique` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table u701525125_wapanel.organization_api_keys: ~0 rows (approximately)

-- Dumping structure for table u701525125_wapanel.pages
CREATE TABLE IF NOT EXISTS `pages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `content` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table u701525125_wapanel.pages: ~2 rows (approximately)
INSERT INTO `pages` (`id`, `name`, `content`, `updated_at`, `created_at`) VALUES
	(1, 'Privacy Policy', 'Introduction<p>Thanks for using our products and services ("Services"). The Services are provided by &lt;Your Business Name&gt;.</p><p>By using our Services, you are agreeing to these terms. Please read them carefully.</p><p>Our Services are very diverse, so sometimes additional terms or product requirements (including age requirements) may apply. Additional terms will be available with the relevant Services, and those additional terms become part of your agreement with us if you use those Services.</p>Using our services<p>You must follow any policies made available to you within the Services.</p><p>Don\'t misuse our Services. For example, don\'t interfere with our Services or try to access them using a method other than the interface and the instructions that we provide. You may use our Services only as permitted by law, including applicable export and re-export control laws and regulations. We may suspend or stop providing our Services to you if you do not comply with our terms or policies or if we are investigating suspected misconduct.</p><p>Using our Services does not give you ownership of any intellectual property rights in our Services or the content you access. You may not use content from our Services unless you obtain permission from its owner or are otherwise permitted by law. These terms do not grant you the right to use any branding or logos used in our Services. Don\'t remove, obscure, or alter any legal notices displayed in or along with our Services.</p>Privacy and copyright protection<p>&lt;Your Business Name&gt;\'s privacy policies explain how we treat your personal data and protect your privacy when you use our Services. By using our Services, you agree that &lt;Your Business Name&gt; can use such data in accordance with our privacy policies.</p><p>We respond to notices of alleged copyright infringement and terminate accounts of repeat infringers according to the process set out in the U.S. Digital Millennium Copyright Act.</p><p>We provide information to help copyright holders manage their intellectual property online. If you think somebody is violating your copyrights and want to notify us, you can find information about submitting notices and &lt;Your Business Name&gt;\'s policy about responding to notices in our Help Center.</p>Your content in our services<p>Some of our Services allow you to upload, submit, store, send or receive content. You retain ownership of any intellectual property rights that you hold in that content. In short, what belongs to you stays yours.</p><p>When you upload, submit, store, send or receive content to or through our Services, you give &lt;Your Business Name&gt; (and those we work with) a worldwide license to use, host, store, reproduce, modify, create derivative works (such as those resulting from translations, adaptations or other changes we make so that your content works better with our Services), communicate, publish, publicly perform, publicly display and distribute such content. The rights you grant in this license are for the limited purpose of operating, promoting, and improving our Services, and to develop new ones. This license continues even if you stop using our Services (for example, for a business listing you have added to &lt;Your Business Name&gt; Maps). Some Services may offer you ways to access and remove content that has been provided to that Service. Also, in some of our Services, there are terms or settings that narrow the scope of our use of the content submitted in those Services. Make sure you have the necessary rights to grant us this license for any content that you submit to our Services.</p>', '2025-01-13 15:27:08', '2025-01-13 15:27:08'),
	(2, 'Terms of Service', 'Introduction<p>Thanks for using our products and services ("Services"). The Services are provided by &lt;Your Business Name&gt;.</p><p>By using our Services, you are agreeing to these terms. Please read them carefully.</p><p>Our Services are very diverse, so sometimes additional terms or product requirements (including age requirements) may apply. Additional terms will be available with the relevant Services, and those additional terms become part of your agreement with us if you use those Services.</p>Using our services<p>You must follow any policies made available to you within the Services.</p><p>Don\'t misuse our Services. For example, don\'t interfere with our Services or try to access them using a method other than the interface and the instructions that we provide. You may use our Services only as permitted by law, including applicable export and re-export control laws and regulations. We may suspend or stop providing our Services to you if you do not comply with our terms or policies or if we are investigating suspected misconduct.</p><p>Using our Services does not give you ownership of any intellectual property rights in our Services or the content you access. You may not use content from our Services unless you obtain permission from its owner or are otherwise permitted by law. These terms do not grant you the right to use any branding or logos used in our Services. Don\'t remove, obscure, or alter any legal notices displayed in or along with our Services.</p>Privacy and copyright protection<p>&lt;Your Business Name&gt;\'s privacy policies explain how we treat your personal data and protect your privacy when you use our Services. By using our Services, you agree that &lt;Your Business Name&gt; can use such data in accordance with our privacy policies.</p><p>We respond to notices of alleged copyright infringement and terminate accounts of repeat infringers according to the process set out in the U.S. Digital Millennium Copyright Act.</p><p>We provide information to help copyright holders manage their intellectual property online. If you think somebody is violating your copyrights and want to notify us, you can find information about submitting notices and &lt;Your Business Name&gt;\'s policy about responding to notices in our Help Center.</p>Your content in our services<p>Some of our Services allow you to upload, submit, store, send or receive content. You retain ownership of any intellectual property rights that you hold in that content. In short, what belongs to you stays yours.</p><p>When you upload, submit, store, send or receive content to or through our Services, you give &lt;Your Business Name&gt; (and those we work with) a worldwide license to use, host, store, reproduce, modify, create derivative works (such as those resulting from translations, adaptations or other changes we make so that your content works better with our Services), communicate, publish, publicly perform, publicly display and distribute such content. The rights you grant in this license are for the limited purpose of operating, promoting, and improving our Services, and to develop new ones. This license continues even if you stop using our Services (for example, for a business listing you have added to &lt;Your Business Name&gt; Maps). Some Services may offer you ways to access and remove content that has been provided to that Service. Also, in some of our Services, there are terms or settings that narrow the scope of our use of the content submitted in those Services. Make sure you have the necessary rights to grant us this license for any content that you submit to our Services.</p>', '2025-01-13 15:27:08', '2025-01-13 15:27:08');

-- Dumping structure for table u701525125_wapanel.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table u701525125_wapanel.password_reset_tokens: ~0 rows (approximately)

-- Dumping structure for table u701525125_wapanel.payment_gateways
CREATE TABLE IF NOT EXISTS `payment_gateways` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `metadata` text DEFAULT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table u701525125_wapanel.payment_gateways: ~3 rows (approximately)
INSERT INTO `payment_gateways` (`id`, `name`, `metadata`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 'Paypal', NULL, 0, '2025-01-13 15:27:08', '2025-01-13 15:27:08'),
	(2, 'Stripe', NULL, 0, '2025-01-13 15:27:08', '2025-01-13 15:27:08'),
	(3, 'Flutterwave', NULL, 0, '2025-01-13 15:27:08', '2025-01-13 15:27:08');

-- Dumping structure for table u701525125_wapanel.personal_access_tokens
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(191) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(191) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping structure for table u701525125_wapanel.products
CREATE TABLE IF NOT EXISTS `products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(50) DEFAULT NULL,
  `organization_id` int(11) NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `catalog_id` char(191) DEFAULT NULL,
  `image` longtext DEFAULT NULL,
  `retailer_id` char(191) DEFAULT NULL,
  `product_id` char(191) DEFAULT NULL,
  `price` text DEFAULT NULL,
  `assign_label` text DEFAULT NULL,
  `currency` text DEFAULT NULL,
  `availability` text DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `synced_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_uuid_unique` (`uuid`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping structure for table u701525125_wapanel.reviews
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `position` varchar(255) DEFAULT NULL,
  `review` text NOT NULL,
  `rating` int(11) NOT NULL DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table u701525125_wapanel.reviews: ~0 rows (approximately)

-- Dumping structure for table u701525125_wapanel.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(50) NOT NULL,
  `name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_uuid_unique` (`uuid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table u701525125_wapanel.roles: ~1 rows (approximately)
INSERT INTO `roles` (`id`, `uuid`, `name`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'd02ca5a1-905d-4cea-90a4-4360c0255219', 'admin', '2025-02-17 19:24:33', '2025-02-17 19:27:28', NULL);

-- Dumping structure for table u701525125_wapanel.role_permissions
CREATE TABLE IF NOT EXISTS `role_permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` bigint(20) unsigned NOT NULL,
  `module` varchar(128) NOT NULL,
  `action` varchar(128) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `role_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table u701525125_wapanel.role_permissions: ~13 rows (approximately)
INSERT INTO `role_permissions` (`id`, `role_id`, `module`, `action`) VALUES
	(1, 1, 'customers', 'view'),
	(2, 1, 'customers', 'delete'),
	(3, 1, 'customers', 'create'),
	(4, 1, 'customers', 'edit'),
	(5, 1, 'organizations', 'delete'),
	(6, 1, 'organizations', 'view'),
	(7, 1, 'organizations', 'create'),
	(8, 1, 'organizations', 'edit'),
	(9, 1, 'billing', 'view'),
	(10, 1, 'subscription_plans', 'view'),
	(11, 1, 'subscription_plans', 'delete'),
	(12, 1, 'subscription_plans', 'create'),
	(13, 1, 'subscription_plans', 'edit');

-- Dumping structure for table u701525125_wapanel.seeder_histories
CREATE TABLE IF NOT EXISTS `seeder_histories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `seeder_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `seeder_histories_seeder_name_unique` (`seeder_name`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table u701525125_wapanel.seeder_histories: ~18 rows (approximately)
INSERT INTO `seeder_histories` (`id`, `seeder_name`, `created_at`, `updated_at`) VALUES
	(1, 'Database\\Seeders\\AddonsLicenseSeeder', '2025-01-13 15:27:07', '2025-01-13 15:27:07'),
	(2, 'Database\\Seeders\\AddonsTableSeeder', '2025-01-13 15:27:08', '2025-01-13 15:27:08'),
	(3, 'Database\\Seeders\\AddonsTableSeeder2', '2025-01-13 15:27:08', '2025-01-13 15:27:08'),
	(4, 'Database\\Seeders\\AddonsTableSeeder3', '2025-01-13 15:27:08', '2025-01-13 15:27:08'),
	(5, 'Database\\Seeders\\AddonsTableSeeder4', '2025-01-13 15:27:08', '2025-01-13 15:27:08'),
	(6, 'Database\\Seeders\\AddonsTableSeeder5', '2025-01-13 15:27:08', '2025-01-13 15:27:08'),
	(7, 'Database\\Seeders\\AddonsTableSeeder6', '2025-01-13 15:27:08', '2025-01-13 15:27:08'),
	(8, 'Database\\Seeders\\AddonsTableSeeder7', '2025-01-13 15:27:08', '2025-01-13 15:27:08'),
	(9, 'Database\\Seeders\\EmailTemplateSeeder', '2025-01-13 15:27:08', '2025-01-13 15:27:08'),
	(10, 'Database\\Seeders\\LanguageTableSeeder', '2025-01-13 15:27:08', '2025-01-13 15:27:08'),
	(11, 'Database\\Seeders\\ModulesTableSeeder', '2025-01-13 15:27:08', '2025-01-13 15:27:08'),
	(12, 'Database\\Seeders\\PageSeeder', '2025-01-13 15:27:08', '2025-01-13 15:27:08'),
	(13, 'Database\\Seeders\\PaymentGatewaysTableSeeder', '2025-01-13 15:27:08', '2025-01-13 15:27:08'),
	(14, 'Database\\Seeders\\RolesTableSeeder', '2025-01-13 15:27:08', '2025-01-13 15:27:08'),
	(15, 'Database\\Seeders\\SettingsTableSeeder', '2025-01-13 15:27:08', '2025-01-13 15:27:08'),
	(16, 'Database\\Seeders\\TicketCategoriesTableSeeder', '2025-01-13 15:27:08', '2025-01-13 15:27:08'),
	(17, 'Database\\Seeders\\WebhookModuleSeeder', '2025-01-13 15:27:08', '2025-01-13 15:27:08'),
	(18, 'Database\\Seeders\\AddonsTableSeeder8', '2025-02-07 17:36:55', '2025-02-07 17:36:55');

-- Dumping structure for table u701525125_wapanel.settings
CREATE TABLE IF NOT EXISTS `settings` (
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table u701525125_wapanel.settings: ~62 rows (approximately)
INSERT INTO `settings` (`key`, `value`) VALUES
	('_method', 'put'),
	('address', NULL),
	('allow_facebook_login', '0'),
	('allow_google_login', '0'),
	('app_environment', 'local'),
	('available_version', '2.7'),
	('aws_access_key', NULL),
	('aws_bucket', NULL),
	('aws_default_region', NULL),
	('aws_secret_key', NULL),
	('billing_address', NULL),
	('billing_city', NULL),
	('billing_country', NULL),
	('billing_name', NULL),
	('billing_phone_1', NULL),
	('billing_phone_2', NULL),
	('billing_postal_code', NULL),
	('billing_state', NULL),
	('billing_tax_id', NULL),
	('broadcast_driver', 'pusher'),
	('company_name', 'SGR CREATIONS'),
	('currency', 'USD'),
	('date_format', 'd-M-y'),
	('default_image_api', NULL),
	('display_frontend', '0'),
	('email', NULL),
	('facebook_login', NULL),
	('favicon', NULL),
	('google_analytics_status', '0'),
	('google_analytics_tracking_id', NULL),
	('google_login', NULL),
	('google_maps_api_key', NULL),
	('invoice_prefix', NULL),
	('is_tax_inclusive', '1'),
	('is_update_available', '0'),
	('last_update_check', '2025-02-13 20:53:21'),
	('logo', NULL),
	('mail_config', NULL),
	('phone', NULL),
	('pusher_app_cluster', 'ap2'),
	('pusher_app_id', '1768906'),
	('pusher_app_key', 'c0cdba32acd8b2421657'),
	('pusher_app_secret', 'ff8d6b9707ecef772c5c'),
	('recaptcha_active', '0'),
	('recaptcha_secret_key', NULL),
	('recaptcha_site_key', NULL),
	('release_date', '3rd February 2024'),
	('smtp_email_active', '0'),
	('socials', '[]'),
	('storage_system', 'local'),
	('time_format', 'H:i'),
	('timezone', 'Asia/Kolkata'),
	('title', NULL),
	('trial_period', '14'),
	('type', 'timezone'),
	('verify_email', '0'),
	('version', '2.7'),
	('whatsapp_callback_token', '20250113152708MS8o');

-- Dumping structure for table u701525125_wapanel.subscriptions
CREATE TABLE IF NOT EXISTS `subscriptions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(50) NOT NULL,
  `organization_id` bigint(20) unsigned NOT NULL,
  `plan_id` bigint(20) unsigned DEFAULT NULL,
  `payment_details` text DEFAULT NULL,
  `start_date` timestamp NULL DEFAULT NULL,
  `valid_until` datetime DEFAULT NULL,
  `status` enum('trial','active') NOT NULL DEFAULT 'trial',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `subscriptions_uuid_unique` (`uuid`),
  KEY `subscriptions_organization_id_foreign` (`organization_id`),
  CONSTRAINT `subscriptions_organization_id_foreign` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping structure for table u701525125_wapanel.subscription_plans
CREATE TABLE IF NOT EXISTS `subscription_plans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` decimal(23,2) NOT NULL,
  `period` enum('monthly','yearly') NOT NULL,
  `metadata` text NOT NULL,
  `status` enum('active','inactive','deleted') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `subscription_plans_uuid_unique` (`uuid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping structure for table u701525125_wapanel.tax_rates
CREATE TABLE IF NOT EXISTS `tax_rates` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `percentage` decimal(5,2) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table u701525125_wapanel.tax_rates: ~0 rows (approximately)

-- Dumping structure for table u701525125_wapanel.teams
CREATE TABLE IF NOT EXISTS `teams` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(50) NOT NULL,
  `organization_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `role` enum('owner','manager','agent') NOT NULL DEFAULT 'manager',
  `status` enum('active','suspended') NOT NULL DEFAULT 'active',
  `created_by` bigint(20) unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `teams_uuid_unique` (`uuid`),
  KEY `teams_organization_id_foreign` (`organization_id`),
  KEY `teams_user_id_foreign` (`user_id`),
  KEY `teams_created_by_foreign` (`created_by`),
  CONSTRAINT `teams_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `teams_organization_id_foreign` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`),
  CONSTRAINT `teams_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping structure for table u701525125_wapanel.team_invites
CREATE TABLE IF NOT EXISTS `team_invites` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint(20) unsigned NOT NULL,
  `email` varchar(128) NOT NULL,
  `code` varchar(255) NOT NULL,
  `role` varchar(128) DEFAULT NULL,
  `invited_by` bigint(20) unsigned NOT NULL,
  `expire_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `team_invites_organization_id_foreign` (`organization_id`),
  KEY `team_invites_invited_by_foreign` (`invited_by`),
  CONSTRAINT `team_invites_invited_by_foreign` FOREIGN KEY (`invited_by`) REFERENCES `users` (`id`),
  CONSTRAINT `team_invites_organization_id_foreign` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table u701525125_wapanel.team_invites: ~0 rows (approximately)

-- Dumping structure for table u701525125_wapanel.templates
CREATE TABLE IF NOT EXISTS `templates` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(50) NOT NULL,
  `organization_id` bigint(20) unsigned NOT NULL,
  `meta_id` varchar(128) NOT NULL,
  `name` varchar(128) NOT NULL,
  `category` varchar(128) NOT NULL,
  `language` varchar(128) NOT NULL,
  `metadata` text NOT NULL,
  `status` varchar(128) NOT NULL,
  `created_by` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `templates_organization_id_foreign` (`organization_id`),
  KEY `templates_created_by_foreign` (`created_by`),
  CONSTRAINT `templates_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `templates_organization_id_foreign` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping structure for table u701525125_wapanel.tickets
CREATE TABLE IF NOT EXISTS `tickets` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(50) NOT NULL,
  `reference` varchar(128) NOT NULL,
  `organization_id` bigint(20) unsigned DEFAULT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `category_id` bigint(20) unsigned NOT NULL,
  `subject` varchar(1024) NOT NULL,
  `message` varchar(1024) NOT NULL,
  `priority` enum('critical','high','medium','low') DEFAULT NULL,
  `assigned_to` bigint(20) unsigned DEFAULT NULL,
  `status` enum('open','pending','resolved','closed') NOT NULL DEFAULT 'pending',
  `closed_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tickets_user_id_foreign` (`user_id`),
  KEY `tickets_category_id_foreign` (`category_id`),
  KEY `tickets_assigned_to_foreign` (`assigned_to`),
  KEY `tickets_closed_by_foreign` (`closed_by`),
  CONSTRAINT `tickets_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`),
  CONSTRAINT `tickets_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `ticket_categories` (`id`),
  CONSTRAINT `tickets_closed_by_foreign` FOREIGN KEY (`closed_by`) REFERENCES `users` (`id`),
  CONSTRAINT `tickets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table u701525125_wapanel.tickets: ~0 rows (approximately)

-- Dumping structure for table u701525125_wapanel.ticket_categories
CREATE TABLE IF NOT EXISTS `ticket_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table u701525125_wapanel.ticket_categories: ~6 rows (approximately)
INSERT INTO `ticket_categories` (`id`, `name`) VALUES
	(1, 'Signup/login issues'),
	(2, 'Campaigns issues'),
	(3, 'Whatsapp issue'),
	(4, 'Template Issues'),
	(5, 'Chatbot Issues'),
	(6, 'Other');

-- Dumping structure for table u701525125_wapanel.ticket_comments
CREATE TABLE IF NOT EXISTS `ticket_comments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ticket_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `message` varchar(1024) NOT NULL,
  `seen` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ticket_comments_ticket_id_foreign` (`ticket_id`),
  KEY `ticket_comments_user_id_foreign` (`user_id`),
  CONSTRAINT `ticket_comments_ticket_id_foreign` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ticket_comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table u701525125_wapanel.ticket_comments: ~0 rows (approximately)

-- Dumping structure for table u701525125_wapanel.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(128) NOT NULL,
  `last_name` varchar(128) DEFAULT NULL,
  `email` varchar(191) NOT NULL,
  `facebook_id` varchar(255) DEFAULT NULL,
  `avatar` varchar(191) DEFAULT NULL,
  `role` varchar(191) NOT NULL DEFAULT 'user',
  `phone` varchar(191) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `tfa_secret` varchar(255) DEFAULT NULL,
  `tfa` tinyint(4) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 1,
  `meta` text DEFAULT NULL,
  `plan` text DEFAULT NULL,
  `plan_id` bigint(20) unsigned DEFAULT NULL,
  `will_expire` date DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_deleted_at_unique` (`email`,`deleted_at`),
  UNIQUE KEY `users_facebook_id_unique` (`facebook_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table u701525125_wapanel.users: ~2 rows (approximately)
INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `facebook_id`, `avatar`, `role`, `phone`, `address`, `email_verified_at`, `password`, `tfa_secret`, `tfa`, `status`, `meta`, `plan`, `plan_id`, `will_expire`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'Super', 'Admin', 'admin@gmail.com', NULL, NULL, 'admin', NULL, NULL, NULL, '$2y$10$FaeC5FyrZ4Fx7jF35yfp6.aNp363poHnnvDEdsyEjuyOrth5Z6KC.', NULL, 0, 1, NULL, NULL, NULL, NULL, NULL, '2025-01-13 15:27:08', '2025-01-13 15:27:08', NULL);

-- Dumping structure for table u701525125_wapanel.webhook_logs
CREATE TABLE IF NOT EXISTS `webhook_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `chat_id` bigint(20) unsigned DEFAULT NULL,
  `webhook_id` bigint(20) unsigned DEFAULT NULL,
  `phone_number` text DEFAULT NULL,
  `metadata` text DEFAULT NULL,
  `template` text DEFAULT NULL,
  `response` text DEFAULT NULL,
  `status` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `webhook_logs_chat_id_foreign` (`chat_id`),
  KEY `webhook_logs_webhook_id_foreign` (`webhook_id`),
  CONSTRAINT `webhook_logs_chat_id_foreign` FOREIGN KEY (`chat_id`) REFERENCES `chats` (`id`) ON DELETE SET NULL,
  CONSTRAINT `webhook_logs_webhook_id_foreign` FOREIGN KEY (`webhook_id`) REFERENCES `webhook_workflows` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping structure for table u701525125_wapanel.webhook_workflows
CREATE TABLE IF NOT EXISTS `webhook_workflows` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(50) NOT NULL,
  `organization_id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `template_id` bigint(20) unsigned NOT NULL,
  `status` tinyint(1) NOT NULL,
  `verified` tinyint(1) NOT NULL,
  `total_call` bigint(20) NOT NULL DEFAULT 0,
  `total_error` bigint(20) NOT NULL DEFAULT 0,
  `total_success` bigint(20) NOT NULL DEFAULT 0,
  `map_name` varchar(191) DEFAULT NULL,
  `workflow_data` longtext DEFAULT NULL,
  `last_data` longtext DEFAULT NULL,
  `last_data_mapped` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `webhook_workflows_template_id_foreign` (`template_id`),
  CONSTRAINT `webhook_workflows_template_id_foreign` FOREIGN KEY (`template_id`) REFERENCES `templates` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
