-- =====================================================================
--  Republic of Botswana Investment Platform
--  IDEMPOTENT production upgrade (Phases 2-9 schema)
--
--  Safe to run on a live MySQL database that already has the base broker
--  schema. It adds ONLY the columns/tables/keys that are missing, so it can
--  be re-run without error. Run it in phpMyAdmin (SQL tab) or:
--      mysql -u <user> -p <database> < botswana_upgrade_idempotent.sql
--
--  This fixes errors like:
--      SQLSTATE[42S22]: Unknown column 'amount_type' in 'WHERE'
--  which mean the migrations were not applied to this database.
-- =====================================================================

SET NAMES utf8mb4;

-- ---- Helper procedures (existence-checked DDL) ----------------------
DELIMITER $$

DROP PROCEDURE IF EXISTS bw_addcol $$
CREATE PROCEDURE bw_addcol(IN tbl VARCHAR(64), IN col VARCHAR(64), IN ddl TEXT)
BEGIN
  IF (SELECT COUNT(*) FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = tbl AND COLUMN_NAME = col) = 0 THEN
    SET @s = CONCAT('ALTER TABLE `', tbl, '` ADD COLUMN ', ddl);
    PREPARE st FROM @s; EXECUTE st; DEALLOCATE PREPARE st;
  END IF;
END $$

DROP PROCEDURE IF EXISTS bw_addidx $$
CREATE PROCEDURE bw_addidx(IN tbl VARCHAR(64), IN idx VARCHAR(64), IN cols TEXT)
BEGIN
  IF (SELECT COUNT(*) FROM information_schema.STATISTICS
        WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = tbl AND INDEX_NAME = idx) = 0 THEN
    SET @s = CONCAT('ALTER TABLE `', tbl, '` ADD INDEX `', idx, '` (', cols, ')');
    PREPARE st FROM @s; EXECUTE st; DEALLOCATE PREPARE st;
  END IF;
END $$

DROP PROCEDURE IF EXISTS bw_addfk $$
CREATE PROCEDURE bw_addfk(IN fkname VARCHAR(64), IN ddl TEXT)
BEGIN
  -- The foreign key is optional; never let it abort the upgrade (e.g. errno 150
  -- when the legacy assets/plans tables differ in type or storage engine).
  DECLARE CONTINUE HANDLER FOR SQLEXCEPTION BEGIN END;
  IF (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS
        WHERE TABLE_SCHEMA = DATABASE() AND CONSTRAINT_NAME = fkname
          AND CONSTRAINT_TYPE = 'FOREIGN KEY') = 0 THEN
    SET @s = ddl;
    PREPARE st FROM @s; EXECUTE st; DEALLOCATE PREPARE st;
  END IF;
END $$

DELIMITER ;

-- ---- Phase 2: assets + settings -------------------------------------
CALL bw_addcol('assets', 'description', '`description` TEXT NULL');
CALL bw_addcol('assets', 'status', '`status` ENUM(''active'',''inactive'') NOT NULL DEFAULT ''active''');
CALL bw_addcol('settings', 'emblem', '`emblem` VARCHAR(255) NULL');

-- ---- Phase 3 + 6: plans (investment-plan fields) --------------------
CALL bw_addcol('plans', 'asset_id', '`asset_id` BIGINT UNSIGNED NULL');
CALL bw_addcol('plans', 'amount_type', '`amount_type` ENUM(''fixed'',''ranged'') NULL');
CALL bw_addcol('plans', 'fixed_amount', '`fixed_amount` DECIMAL(20,2) NULL');
CALL bw_addcol('plans', 'min_amount', '`min_amount` DECIMAL(20,2) NULL');
CALL bw_addcol('plans', 'max_amount', '`max_amount` DECIMAL(20,2) NULL');
CALL bw_addcol('plans', 'fixed_return', '`fixed_return` DECIMAL(20,2) NULL');
CALL bw_addcol('plans', 'return_percentage', '`return_percentage` DECIMAL(8,2) NULL');
CALL bw_addcol('plans', 'capacity_amount', '`capacity_amount` DECIMAL(20,2) NULL');
CALL bw_addcol('plans', 'offer_starts_at', '`offer_starts_at` DATETIME NULL');
CALL bw_addcol('plans', 'offer_ends_at', '`offer_ends_at` DATETIME NULL');
CALL bw_addidx('plans', 'plans_asset_id_index', '`asset_id`');

-- ---- Phase 5: user_plans (modern + investment-record columns) -------
CALL bw_addcol('user_plans', 'user_id', '`user_id` BIGINT UNSIGNED NULL');
CALL bw_addcol('user_plans', 'plan_id', '`plan_id` BIGINT UNSIGNED NULL');
CALL bw_addcol('user_plans', 'invested_amount', '`invested_amount` DECIMAL(20,2) NULL');
CALL bw_addcol('user_plans', 'current_value', '`current_value` DECIMAL(20,2) NULL');
CALL bw_addcol('user_plans', 'roi_percentage', '`roi_percentage` DECIMAL(8,2) NULL');
CALL bw_addcol('user_plans', 'expected_return', '`expected_return` DECIMAL(20,2) NULL');
CALL bw_addcol('user_plans', 'total_profit', '`total_profit` DECIMAL(20,2) NOT NULL DEFAULT 0');
CALL bw_addcol('user_plans', 'status', '`status` VARCHAR(255) NOT NULL DEFAULT ''active''');
CALL bw_addcol('user_plans', 'activated_at', '`activated_at` DATETIME NULL');
CALL bw_addcol('user_plans', 'expires_at', '`expires_at` DATETIME NULL');
CALL bw_addcol('user_plans', 'last_payout_at', '`last_payout_at` DATETIME NULL');
CALL bw_addcol('user_plans', 'compounding_enabled', '`compounding_enabled` TINYINT(1) NOT NULL DEFAULT 0');
CALL bw_addcol('user_plans', 'compounding_percentage', '`compounding_percentage` DECIMAL(8,2) NULL');
CALL bw_addcol('user_plans', 'payment_method', '`payment_method` VARCHAR(255) NULL');
CALL bw_addcol('user_plans', 'payment_reference', '`payment_reference` VARCHAR(255) NULL');
CALL bw_addcol('user_plans', 'notes', '`notes` TEXT NULL');
CALL bw_addcol('user_plans', 'start_date', '`start_date` DATETIME NULL');
CALL bw_addcol('user_plans', 'maturity_date', '`maturity_date` DATETIME NULL');
CALL bw_addcol('user_plans', 'locked', '`locked` TINYINT(1) NOT NULL DEFAULT 0');
CALL bw_addcol('user_plans', 'terms_accepted_at', '`terms_accepted_at` DATETIME NULL');
CALL bw_addcol('user_plans', 'deleted_at', '`deleted_at` TIMESTAMP NULL DEFAULT NULL');
CALL bw_addidx('user_plans', 'user_plans_user_id_index', '`user_id`');
CALL bw_addidx('user_plans', 'user_plans_plan_id_index', '`plan_id`');
CALL bw_addidx('user_plans', 'user_plans_status_index', '`status`');

-- ---- Phase 7: kycs (Omang / national ID) ----------------------------
CALL bw_addcol('kycs', 'id_number', '`id_number` VARCHAR(255) NULL');

-- ---- New tables (created only if absent) ----------------------------
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
    KEY `asset_documents_asset_id_type_index` (`asset_id`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

CREATE TABLE IF NOT EXISTS `plan_payouts` (
    `id`             BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_plan_id`   BIGINT UNSIGNED NOT NULL,
    `user_id`        BIGINT UNSIGNED NOT NULL,
    `amount`         DECIMAL(20,2) NOT NULL,
    `roi_percentage` DECIMAL(8,2) NULL,
    `type`           VARCHAR(255) NOT NULL DEFAULT 'return',
    `status`         VARCHAR(255) NOT NULL DEFAULT 'pending',
    `due_date`       DATETIME NULL,
    `processed_at`   DATETIME NULL,
    `remarks`        VARCHAR(255) NULL,
    `created_at`     TIMESTAMP NULL DEFAULT NULL,
    `updated_at`     TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `plan_payouts_user_plan_id_index` (`user_plan_id`),
    KEY `plan_payouts_user_id_index` (`user_id`),
    KEY `plan_payouts_status_due_date_index` (`status`,`due_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `audit_logs` (
    `id`           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `actor_type`   VARCHAR(255) NULL,
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

-- ---- Foreign key for plans.asset_id (added last; optional) ----------
CALL bw_addfk('plans_asset_id_foreign',
    'ALTER TABLE `plans` ADD CONSTRAINT `plans_asset_id_foreign` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`id`) ON DELETE SET NULL');

-- ---- Mark these migrations as run so `php artisan migrate` is a no-op ----
SET @b = (SELECT IFNULL(MAX(batch),0) + 1 FROM `migrations`);
INSERT INTO `migrations` (`migration`, `batch`)
SELECT t.m, @b FROM (
    SELECT '2026_06_14_100001_add_investment_fields_to_assets_table' AS m
    UNION ALL SELECT '2026_06_14_100002_create_asset_documents_table'
    UNION ALL SELECT '2026_06_14_100003_add_emblem_to_settings_table'
    UNION ALL SELECT '2026_06_14_100004_add_investment_plan_fields_to_plans_table'
    UNION ALL SELECT '2026_06_14_100005_upgrade_user_plans_for_investments'
    UNION ALL SELECT '2026_06_14_100006_add_capacity_and_offer_window_to_plans'
    UNION ALL SELECT '2026_06_14_100007_create_wallet_transactions_table'
    UNION ALL SELECT '2026_06_14_100008_create_plan_payouts_table'
    UNION ALL SELECT '2026_06_15_100009_add_omang_to_kycs_table'
    UNION ALL SELECT '2026_06_15_100010_create_audit_logs_table'
) t
WHERE NOT EXISTS (SELECT 1 FROM `migrations` m2 WHERE m2.migration = t.m);

-- ---- Clean up helper procedures -------------------------------------
DROP PROCEDURE IF EXISTS bw_addcol;
DROP PROCEDURE IF EXISTS bw_addidx;
DROP PROCEDURE IF EXISTS bw_addfk;

-- Done. After running, clear caches:  php artisan config:clear && php artisan cache:clear
