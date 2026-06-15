-- =====================================================================
--  Assets become the investable entity.
--  Adds investment-term columns to `assets`, an `asset_id` to `user_plans`,
--  and inserts 10 Botswana assets citizens can invest in.
--  Idempotent + safe to re-run. Run in phpMyAdmin (SQL tab).
-- =====================================================================

SET NAMES utf8mb4;

DELIMITER $$
DROP PROCEDURE IF EXISTS bw_addcol2 $$
CREATE PROCEDURE bw_addcol2(IN tbl VARCHAR(64), IN col VARCHAR(64), IN ddl TEXT)
BEGIN
  IF (SELECT COUNT(*) FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = tbl AND COLUMN_NAME = col) = 0 THEN
    SET @s = CONCAT('ALTER TABLE `', tbl, '` ADD COLUMN ', ddl);
    PREPARE st FROM @s; EXECUTE st; DEALLOCATE PREPARE st;
  END IF;
END $$
DELIMITER ;

-- Investment terms on assets
CALL bw_addcol2('assets', 'amount_type', '`amount_type` ENUM(''fixed'',''ranged'') NOT NULL DEFAULT ''ranged''');
CALL bw_addcol2('assets', 'fixed_amount', '`fixed_amount` DECIMAL(20,2) NULL');
CALL bw_addcol2('assets', 'min_amount', '`min_amount` DECIMAL(20,2) NULL');
CALL bw_addcol2('assets', 'max_amount', '`max_amount` DECIMAL(20,2) NULL');
CALL bw_addcol2('assets', 'return_type', '`return_type` ENUM(''percentage'',''fixed'') NOT NULL DEFAULT ''percentage''');
CALL bw_addcol2('assets', 'fixed_return', '`fixed_return` DECIMAL(20,2) NULL');
CALL bw_addcol2('assets', 'return_percentage', '`return_percentage` DECIMAL(8,2) NULL');
CALL bw_addcol2('assets', 'duration', '`duration` INT NULL');
CALL bw_addcol2('assets', 'duration_type', '`duration_type` VARCHAR(255) NOT NULL DEFAULT ''days''');
CALL bw_addcol2('assets', 'payout_interval', '`payout_interval` VARCHAR(255) NOT NULL DEFAULT ''monthly''');
CALL bw_addcol2('assets', 'capacity_amount', '`capacity_amount` DECIMAL(20,2) NULL');
CALL bw_addcol2('assets', 'offer_starts_at', '`offer_starts_at` DATETIME NULL');
CALL bw_addcol2('assets', 'offer_ends_at', '`offer_ends_at` DATETIME NULL');

-- Investments reference the asset
CALL bw_addcol2('user_plans', 'asset_id', '`asset_id` BIGINT UNSIGNED NULL');

DROP PROCEDURE IF EXISTS bw_addcol2;

-- ---- 10 Botswana assets (inserted only if not already present) ------
INSERT INTO `assets` (name,category,description,status,amount_type,min_amount,max_amount,return_type,return_percentage,duration,duration_type,payout_interval,created_at,updated_at)
SELECT * FROM (
  SELECT 'Debswana Diamond Company' n,'Mining' c,'The world-leading diamond producer, a partnership between the Government of Botswana and De Beers.' d,'active' s,'ranged' at,1000 mn,500000 mx,'percentage' rt,12 rp,365 du,'days' dt,'monthly' pi, NOW() ca, NOW() ua
  UNION ALL SELECT 'Morupule Coal Mine','Energy & Mining','Botswana principal coal mine, supplying the nation power stations.','active','ranged',500,200000,'percentage',9,180,'days','monthly',NOW(),NOW()
  UNION ALL SELECT 'Botswana Power Corporation','Energy','The national electricity utility generating and distributing power across Botswana.','active','ranged',1000,300000,'percentage',8,365,'days','monthly',NOW(),NOW()
  UNION ALL SELECT 'Water Utilities Corporation','Water & Sanitation','The national water utility responsible for water supply and sanitation.','active','ranged',500,150000,'percentage',7.5,180,'days','monthly',NOW(),NOW()
  UNION ALL SELECT 'Botswana Telecommunications (BTCL)','Telecommunications','The national telecommunications operator, listed on the Botswana Stock Exchange.','active','ranged',500,100000,'percentage',10,90,'days','monthly',NOW(),NOW()
  UNION ALL SELECT 'First National Bank Botswana','Finance & Banking','One of the largest banks in Botswana, listed on the Botswana Stock Exchange.','active','ranged',1000,250000,'percentage',11,365,'days','monthly',NOW(),NOW()
  UNION ALL SELECT 'Letshego Holdings','Finance','A pan-African inclusive-finance group headquartered in Gaborone.','active','ranged',500,100000,'percentage',13,180,'days','monthly',NOW(),NOW()
  UNION ALL SELECT 'Sefalana Holdings','Retail & FMCG','A leading wholesale, retail and consumer-goods group.','active','ranged',500,80000,'percentage',10.5,180,'days','monthly',NOW(),NOW()
  UNION ALL SELECT 'Choppies Enterprises','Retail','A major retail supermarket chain operating across Botswana and the region.','active','ranged',500,60000,'percentage',9.5,90,'days','monthly',NOW(),NOW()
  UNION ALL SELECT 'Botswana Meat Commission','Agriculture & Livestock','The national beef producer and exporter supporting the livestock sector.','active','ranged',500,120000,'percentage',8.5,270,'days','monthly',NOW(),NOW()
) src
WHERE NOT EXISTS (SELECT 1 FROM `assets` a WHERE a.name = src.n);

-- ---- Record the migration so artisan migrate stays a no-op -----------
INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_06_15_100011_add_investment_terms_to_assets_table', (SELECT IFNULL(MAX(batch),0)+1 FROM `migrations`)
WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE migration = '2026_06_15_100011_add_investment_terms_to_assets_table');
