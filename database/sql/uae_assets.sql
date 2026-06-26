-- =====================================================================
--  Switch the seeded assets from Botswana to the United Arab Emirates.
--  - Deactivates the 10 Botswana assets (kept, not deleted, to preserve any
--    existing investments / foreign keys).
--  - Inserts 10 UAE assets (only if not already present).
--  Idempotent + safe to re-run. Run in phpMyAdmin (SQL tab).
-- =====================================================================

SET NAMES utf8mb4;

-- Deactivate the old Botswana assets so they no longer appear.
UPDATE `assets` SET `status` = 'inactive'
WHERE `name` IN (
  'Debswana Diamond Company','Morupule Coal Mine','Botswana Power Corporation',
  'Water Utilities Corporation','Botswana Telecommunications (BTCL)',
  'First National Bank Botswana','Letshego Holdings','Sefalana Holdings',
  'Choppies Enterprises','Botswana Meat Commission'
);

-- Insert the 10 UAE assets (skipped if a row with the same name already exists).
INSERT INTO `assets` (name,category,description,status,amount_type,min_amount,max_amount,return_type,return_percentage,duration,duration_type,payout_interval,created_at,updated_at)
SELECT * FROM (
  SELECT 'ADNOC (Abu Dhabi National Oil Company)' n,'Energy & Oil' c,'The Abu Dhabi national energy group, one of the world largest oil and gas producers.' d,'active' s,'ranged' at,1000 mn,500000 mx,'percentage' rt,12 rp,365 du,'days' dt,'monthly' pi, NOW() ca, NOW() ua
  UNION ALL SELECT 'Emaar Properties','Real Estate','The leading UAE developer behind Burj Khalifa, Dubai Mall and major communities.','active','ranged',1000,300000,'percentage',11,180,'days','monthly',NOW(),NOW()
  UNION ALL SELECT 'DP World','Ports & Logistics','A global ports, logistics and trade-enablement group headquartered in Dubai.','active','ranged',1000,400000,'percentage',10,365,'days','monthly',NOW(),NOW()
  UNION ALL SELECT 'e& (Etisalat Group)','Telecommunications','The UAE flagship telecommunications and technology group.','active','ranged',500,150000,'percentage',10,90,'days','monthly',NOW(),NOW()
  UNION ALL SELECT 'Emirates NBD','Banking & Finance','One of the largest banking groups in the Middle East, based in Dubai.','active','ranged',1000,250000,'percentage',9,365,'days','monthly',NOW(),NOW()
  UNION ALL SELECT 'Emirates Airline','Aviation','The Dubai-based international airline and a global aviation leader.','active','ranged',1000,350000,'percentage',11,270,'days','monthly',NOW(),NOW()
  UNION ALL SELECT 'Aldar Properties','Real Estate','Abu Dhabi leading real-estate developer and investment manager.','active','ranged',500,200000,'percentage',10.5,180,'days','monthly',NOW(),NOW()
  UNION ALL SELECT 'DEWA (Dubai Electricity & Water Authority)','Utilities','Dubai electricity and water utility, driving clean-energy projects.','active','ranged',1000,200000,'percentage',8.5,365,'days','monthly',NOW(),NOW()
  UNION ALL SELECT 'Dubai Islamic Bank','Islamic Finance','The world first full-service Islamic bank, headquartered in Dubai.','active','ranged',500,150000,'percentage',9.5,180,'days','monthly',NOW(),NOW()
  UNION ALL SELECT 'Mubadala Investment Company','Sovereign Investment','Abu Dhabi sovereign investor managing a global, diversified portfolio.','active','ranged',2000,1000000,'percentage',13,365,'days','monthly',NOW(),NOW()
) src
WHERE NOT EXISTS (SELECT 1 FROM `assets` a WHERE a.name = src.n);
