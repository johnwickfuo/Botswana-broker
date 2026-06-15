-- =====================================================================
--  Republic of Botswana Investment Platform
--  Consolidated schema changes (Phases 2-9)
--  Dialect: MySQL 5.7+ / 8.0 (InnoDB, utf8mb4)
--
--  This is the DDL equivalent of the platform migrations:
--    2026_06_14_100001  add_investment_fields_to_assets_table
--    2026_06_14_100002  create_asset_documents_table
--    2026_06_14_100003  add_emblem_to_settings_table
--    2026_06_14_100004  add_investment_plan_fields_to_plans_table
--    2026_06_14_100005  upgrade_user_plans_for_investments
--    2026_06_14_100006  add_capacity_and_offer_window_to_plans
--    2026_06_14_100007  create_wallet_transactions_table
--    2026_06_14_100008  create_plan_payouts_table
--    2026_06_15_100009  add_omang_to_kycs_table
--    2026_06_15_100010  create_audit_logs_table
--
--  Apply ONCE against a database that already contains the base broker
--  schema (assets, settings, plans, user_plans, kycs, users). Phase 1 was
--  front-end design tokens only and introduced no database changes.
-- =====================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- Phase 2 — Assets
-- ---------------------------------------------------------------------

-- 100001: repurpose the legacy `assets` table for investment assets
ALTER TABLE `assets`
    ADD COLUMN `description` TEXT NULL AFTER `category`,
    ADD COLUMN `status` ENUM('active','inactive') NOT NULL DEFAULT 'active' AFTER `description`;

-- 100003: national emblem slot on settings (client-uploaded; never fabricated)
ALTER TABLE `settings`
    ADD COLUMN `emblem` VARCHAR(255) NULL AFTER `logo`;

-- 100002: official documents (certificates / supporting) attached to an asset
CREATE TABLE IF NOT EXISTS `asset_documents` (
    `id`            BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `asset_id`      BIGINT UNSIGNED NOT NULL,
    `type`          ENUM('certificate','supporting') NOT NULL DEFAULT 'certificate',
    `path`          VARCHAR(255) NOT NULL,
    `original_name` VARCHAR(255) NULL,
    `mime`          VARCHAR(255) NULL,
    `size`          BIGINT UNSIGNED NULL,
    `created_at`    TIMESTAMP NULL DEFAULT NULL,
    `updated_at`    TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `asset_documents_asset_id_type_index` (`asset_id`,`type`),
    CONSTRAINT `asset_documents_asset_id_foreign`
        FOREIGN KEY (`asset_id`) REFERENCES `assets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Phase 3 + Phase 6 — Investment plans (extends the legacy `plans` table)
-- ---------------------------------------------------------------------

-- 100004: spec-compliant investment-plan fields
ALTER TABLE `plans`
    ADD COLUMN `asset_id`          BIGINT UNSIGNED NULL AFTER `id`,
    ADD COLUMN `amount_type`       ENUM('fixed','ranged') NULL AFTER `description`,
    ADD COLUMN `fixed_amount`      DECIMAL(20,2) NULL AFTER `amount_type`,
    ADD COLUMN `min_amount`        DECIMAL(20,2) NULL AFTER `fixed_amount`,
    ADD COLUMN `max_amount`        DECIMAL(20,2) NULL AFTER `min_amount`,
    ADD COLUMN `fixed_return`      DECIMAL(20,2) NULL AFTER `max_amount`,
    ADD COLUMN `return_percentage` DECIMAL(8,2)  NULL AFTER `fixed_return`,
    ADD KEY `plans_asset_id_index` (`asset_id`),
    ADD CONSTRAINT `plans_asset_id_foreign`
        FOREIGN KEY (`asset_id`) REFERENCES `assets` (`id`) ON DELETE SET NULL;

-- 100006: capacity cap + offer window
ALTER TABLE `plans`
    ADD COLUMN `capacity_amount` DECIMAL(20,2) NULL AFTER `return_percentage`,
    ADD COLUMN `offer_starts_at` DATETIME NULL AFTER `capacity_amount`,
    ADD COLUMN `offer_ends_at`   DATETIME NULL AFTER `offer_starts_at`;

-- ---------------------------------------------------------------------
-- Phase 5 — Investments (brings the legacy `user_plans` table up to the
-- modern UserPlan model + adds the investment-record fields)
-- ---------------------------------------------------------------------

-- 100005: modern + investment-record columns on user_plans
ALTER TABLE `user_plans`
    ADD COLUMN `user_id`                BIGINT UNSIGNED NULL,
    ADD COLUMN `plan_id`                BIGINT UNSIGNED NULL,
    ADD COLUMN `invested_amount`        DECIMAL(20,2) NULL,
    ADD COLUMN `current_value`          DECIMAL(20,2) NULL,
    ADD COLUMN `roi_percentage`         DECIMAL(8,2)  NULL,
    ADD COLUMN `expected_return`        DECIMAL(20,2) NULL,
    ADD COLUMN `total_profit`           DECIMAL(20,2) NOT NULL DEFAULT 0,
    ADD COLUMN `status`                 VARCHAR(255) NOT NULL DEFAULT 'active',
    ADD COLUMN `activated_at`           DATETIME NULL,
    ADD COLUMN `expires_at`             DATETIME NULL,
    ADD COLUMN `last_payout_at`         DATETIME NULL,
    ADD COLUMN `compounding_enabled`    TINYINT(1) NOT NULL DEFAULT 0,
    ADD COLUMN `compounding_percentage` DECIMAL(8,2) NULL,
    ADD COLUMN `payment_method`         VARCHAR(255) NULL,
    ADD COLUMN `payment_reference`      VARCHAR(255) NULL,
    ADD COLUMN `notes`                  TEXT NULL,
    ADD COLUMN `start_date`             DATETIME NULL,
    ADD COLUMN `maturity_date`          DATETIME NULL,
    ADD COLUMN `locked`                 TINYINT(1) NOT NULL DEFAULT 0,
    ADD COLUMN `terms_accepted_at`      DATETIME NULL,
    ADD COLUMN `deleted_at`             TIMESTAMP NULL DEFAULT NULL,
    ADD KEY `user_plans_user_id_index` (`user_id`),
    ADD KEY `user_plans_plan_id_index` (`plan_id`),
    ADD KEY `user_plans_status_index` (`status`);

-- 100007: wallet ledger (every balance movement with running balance)
CREATE TABLE IF NOT EXISTS `wallet_transactions` (
    `id`             BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id`        BIGINT UNSIGNED NOT NULL,
    `type`           ENUM('debit','credit') NOT NULL,
    `amount`         DECIMAL(20,2) NOT NULL,
    `balance_after`  DECIMAL(20,2) NOT NULL,
    `description`    VARCHAR(255) NULL,
    `reference_type` VARCHAR(255) NULL,
    `reference_id`   BIGINT UNSIGNED NULL,
    `created_at`     TIMESTAMP NULL DEFAULT NULL,
    `updated_at`     TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `wallet_transactions_user_id_index` (`user_id`),
    KEY `wallet_transactions_reference_type_reference_id_index` (`reference_type`,`reference_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Phase 6 — Payouts (schedule + periodic returns + maturity)
-- ---------------------------------------------------------------------

-- 100008: payout schedule/ledger for the PlanPayout model
CREATE TABLE IF NOT EXISTS `plan_payouts` (
    `id`             BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_plan_id`   BIGINT UNSIGNED NOT NULL,
    `user_id`        BIGINT UNSIGNED NOT NULL,
    `amount`         DECIMAL(20,2) NOT NULL,
    `roi_percentage` DECIMAL(8,2) NULL,
    `type`           VARCHAR(255) NOT NULL DEFAULT 'return',   -- return | principal
    `status`         VARCHAR(255) NOT NULL DEFAULT 'pending',  -- pending | processed
    `due_date`       DATETIME NULL,
    `processed_at`   DATETIME NULL,
    `remarks`        VARCHAR(255) NULL,
    `created_at`     TIMESTAMP NULL DEFAULT NULL,
    `updated_at`     TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `plan_payouts_user_plan_id_index` (`user_plan_id`),
    KEY `plan_payouts_user_id_index` (`user_id`),
    KEY `plan_payouts_due_date_index` (`due_date`),
    KEY `plan_payouts_status_due_date_index` (`status`,`due_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Phase 7 — KYC (Omang / national ID)
-- ---------------------------------------------------------------------

-- 100009: Omang / national ID number on KYC applications
ALTER TABLE `kycs`
    ADD COLUMN `id_number` VARCHAR(255) NULL AFTER `document_type`;

-- ---------------------------------------------------------------------
-- Phase 9 — Audit trail
-- ---------------------------------------------------------------------

-- 100010: audit log for sensitive actions (asset/plan changes, investments,
-- payouts, KYC decisions, deposit/withdrawal approvals)
CREATE TABLE IF NOT EXISTS `audit_logs` (
    `id`           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `actor_type`   VARCHAR(255) NULL,   -- admin | user | system
    `actor_id`     BIGINT UNSIGNED NULL,
    `actor_name`   VARCHAR(255) NULL,
    `action`       VARCHAR(255) NOT NULL,
    `subject_type` VARCHAR(255) NULL,
    `subject_id`   BIGINT UNSIGNED NULL,
    `description`  TEXT NULL,
    `properties`   JSON NULL,
    `ip_address`   VARCHAR(255) NULL,
    `created_at`   TIMESTAMP NULL DEFAULT NULL,
    `updated_at`   TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `audit_logs_action_index` (`action`),
    KEY `audit_logs_subject_type_subject_id_index` (`subject_type`,`subject_id`),
    KEY `audit_logs_actor_type_actor_id_index` (`actor_type`,`actor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- Optionally record these migrations as run (uncomment and set <batch>):
-- INSERT INTO `migrations` (`migration`, `batch`) VALUES
--   ('2026_06_14_100001_add_investment_fields_to_assets_table', <batch>),
--   ('2026_06_14_100002_create_asset_documents_table', <batch>),
--   ('2026_06_14_100003_add_emblem_to_settings_table', <batch>),
--   ('2026_06_14_100004_add_investment_plan_fields_to_plans_table', <batch>),
--   ('2026_06_14_100005_upgrade_user_plans_for_investments', <batch>),
--   ('2026_06_14_100006_add_capacity_and_offer_window_to_plans', <batch>),
--   ('2026_06_14_100007_create_wallet_transactions_table', <batch>),
--   ('2026_06_14_100008_create_plan_payouts_table', <batch>),
--   ('2026_06_15_100009_add_omang_to_kycs_table', <batch>),
--   ('2026_06_15_100010_create_audit_logs_table', <batch>);

-- End of consolidated schema.
