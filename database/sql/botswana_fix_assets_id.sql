-- =====================================================================
--  Repair the `assets` table id on the live database.
--
--  Symptom:  SQLSTATE[HY000] 1364 Field 'id' doesn't have a default value
--            (and earlier: FK errno 150 referencing assets.id)
--  Cause:    the legacy (previously unused) `assets` table lost its PRIMARY
--            KEY, so `id` is neither auto-increment nor referenceable.
--  Safe to re-run. Assumes the assets table has no rows with id = 0.
-- =====================================================================

-- 1) Restore the PRIMARY KEY on assets.id only if it is missing.
SET @has_pk := (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'assets' AND CONSTRAINT_TYPE = 'PRIMARY KEY');
SET @sql := IF(@has_pk = 0, 'ALTER TABLE `assets` ADD PRIMARY KEY (`id`)', 'DO 0');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

-- 2) Make id BIGINT UNSIGNED AUTO_INCREMENT (matches the intended schema and
--    also lets the optional plans.asset_id foreign key be created later).
ALTER TABLE `assets` MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT;

-- (Optional) now that assets.id is a proper BIGINT UNSIGNED key, the foreign
-- key from plans can be added if you want referential integrity:
-- ALTER TABLE `plans` ADD CONSTRAINT `plans_asset_id_foreign`
--     FOREIGN KEY (`asset_id`) REFERENCES `assets` (`id`) ON DELETE SET NULL;
