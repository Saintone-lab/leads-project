-- =====================================================================
-- Raw SQL setara migration Kanban (2026-07-13)
-- Jalankan berurutan dari atas ke bawah via phpMyAdmin.
-- Charset/engine mengikuti konvensi project: InnoDB, utf8mb4_unicode_ci
-- =====================================================================

-- 1) 2026_07_13_083309_create_kanban_tables.php
-- ---------------------------------------------------------------------
CREATE TABLE `kanban_boards` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NULL,
  `created_by` BIGINT UNSIGNED NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `kanban_boards_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `kanban_columns` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `board_id` BIGINT UNSIGNED NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `position` INT NOT NULL DEFAULT 0,
  `color` VARCHAR(255) NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `kanban_columns_board_id_foreign` FOREIGN KEY (`board_id`) REFERENCES `kanban_boards` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `kanban_board_members` (
  `board_id` BIGINT UNSIGNED NOT NULL,
  `user_id` BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (`board_id`, `user_id`),
  CONSTRAINT `kanban_board_members_board_id_foreign` FOREIGN KEY (`board_id`) REFERENCES `kanban_boards` (`id`) ON DELETE CASCADE,
  CONSTRAINT `kanban_board_members_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `kanban_tasks` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `board_id` BIGINT UNSIGNED NOT NULL,
  `column_id` BIGINT UNSIGNED NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NULL,
  `due_date` DATE NULL,
  `position` INT NOT NULL DEFAULT 0,
  `assigned_to` BIGINT UNSIGNED NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `kanban_tasks_board_id_foreign` FOREIGN KEY (`board_id`) REFERENCES `kanban_boards` (`id`) ON DELETE CASCADE,
  CONSTRAINT `kanban_tasks_column_id_foreign` FOREIGN KEY (`column_id`) REFERENCES `kanban_columns` (`id`) ON DELETE CASCADE,
  CONSTRAINT `kanban_tasks_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 2) 2026_07_13_085500_add_kanban_comments_and_activities.php
-- ---------------------------------------------------------------------
CREATE TABLE `kanban_task_comments` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `task_id` BIGINT UNSIGNED NOT NULL,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `comment` TEXT NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `kanban_task_comments_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `kanban_tasks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `kanban_task_comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `kanban_task_comment_mentions` (
  `comment_id` BIGINT UNSIGNED NOT NULL,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`comment_id`, `user_id`),
  CONSTRAINT `kanban_task_comment_mentions_comment_id_foreign` FOREIGN KEY (`comment_id`) REFERENCES `kanban_task_comments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `kanban_task_comment_mentions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `kanban_task_activities` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `task_id` BIGINT UNSIGNED NOT NULL,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `activity_type` VARCHAR(255) NOT NULL,
  `activity_data` JSON NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `kanban_task_activities_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `kanban_tasks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `kanban_task_activities_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 3) 2026_07_13_090000_add_checklist_and_labels_to_kanban.php
-- ---------------------------------------------------------------------
ALTER TABLE `kanban_tasks`
  ADD COLUMN `labels` JSON NULL AFTER `due_date`;

CREATE TABLE `kanban_checklists` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `task_id` BIGINT UNSIGNED NOT NULL,
  `title` VARCHAR(255) NOT NULL DEFAULT 'Checklist',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `kanban_checklists_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `kanban_tasks` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `kanban_checklist_items` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `checklist_id` BIGINT UNSIGNED NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `is_completed` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `kanban_checklist_items_checklist_id_foreign` FOREIGN KEY (`checklist_id`) REFERENCES `kanban_checklists` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 4) 2026_07_13_093000_create_kanban_task_attachments_table.php
-- ---------------------------------------------------------------------
CREATE TABLE `kanban_task_attachments` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `task_id` BIGINT UNSIGNED NOT NULL,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `file_path` VARCHAR(255) NOT NULL,
  `file_name` VARCHAR(255) NOT NULL,
  `file_size` BIGINT NULL,
  `file_type` VARCHAR(255) NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `kanban_task_attachments_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `kanban_tasks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `kanban_task_attachments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 5) 2026_07_13_094500_add_labels_to_kanban_boards_table.php
-- ---------------------------------------------------------------------
ALTER TABLE `kanban_boards`
  ADD COLUMN `labels` JSON NULL AFTER `description`;


-- 6) 2026_07_13_095900_create_kanban_task_assignees_table.php
-- ---------------------------------------------------------------------
CREATE TABLE `kanban_task_assignees` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `task_id` BIGINT UNSIGNED NOT NULL,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `kanban_task_assignees_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `kanban_tasks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `kanban_task_assignees_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Migrasi data assignee lama (kolom kanban_tasks.assigned_to) ke tabel pivot
INSERT INTO `kanban_task_assignees` (`task_id`, `user_id`, `created_at`, `updated_at`)
SELECT `id`, `assigned_to`, NOW(), NOW()
FROM `kanban_tasks`
WHERE `assigned_to` IS NOT NULL;


-- 7) 2026_07_13_100000_create_kanban_task_delete_requests_table.php
-- ---------------------------------------------------------------------
CREATE TABLE `kanban_task_delete_requests` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `board_id` BIGINT UNSIGNED NOT NULL,
  `task_id` BIGINT UNSIGNED NOT NULL,
  `requested_by` BIGINT UNSIGNED NOT NULL,
  `status` VARCHAR(255) NOT NULL DEFAULT 'pending',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `kanban_task_delete_requests_board_id_foreign` FOREIGN KEY (`board_id`) REFERENCES `kanban_boards` (`id`) ON DELETE CASCADE,
  CONSTRAINT `kanban_task_delete_requests_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `kanban_tasks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `kanban_task_delete_requests_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 8) 2026_07_13_101000_add_priority_to_kanban_tasks_table.php
-- ---------------------------------------------------------------------
ALTER TABLE `kanban_tasks`
  ADD COLUMN `priority` VARCHAR(255) NOT NULL DEFAULT 'medium' AFTER `due_date`;


-- =====================================================================
-- Setelah run manual di server, tandai migration ini "sudah dijalankan"
-- supaya `php artisan migrate` tidak mencoba menjalankan ulang:
-- =====================================================================
-- INSERT INTO `migrations` (`migration`, `batch`) VALUES
-- ('2026_07_13_083309_create_kanban_tables', <batch_terakhir+1>),
-- ('2026_07_13_085500_add_kanban_comments_and_activities', <batch_terakhir+1>),
-- ('2026_07_13_090000_add_checklist_and_labels_to_kanban', <batch_terakhir+1>),
-- ('2026_07_13_093000_create_kanban_task_attachments_table', <batch_terakhir+1>),
-- ('2026_07_13_094500_add_labels_to_kanban_boards_table', <batch_terakhir+1>),
-- ('2026_07_13_095900_create_kanban_task_assignees_table', <batch_terakhir+1>),
-- ('2026_07_13_100000_create_kanban_task_delete_requests_table', <batch_terakhir+1>),
-- ('2026_07_13_101000_add_priority_to_kanban_tasks_table', <batch_terakhir+1>);
