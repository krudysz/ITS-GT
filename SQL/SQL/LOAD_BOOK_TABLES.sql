# ==== TIMER ====
SET @start=UNIX_TIMESTAMP();
# ===============
SELECT 'table_index_1.sql' AS ' ';
\. table_index_1.sql
#-----------------------------------------------//
SELECT 'table_tags.sql' AS ' ';
\. table_tags.sql
#-----------------------------------------------//
SELECT 'table_webct_tag_id.sql' AS ' ';
\. table_webct_tag_id.sql
#-----------------------------------------------//
SELECT 'dspfirst.sql' AS ' ';
\. dspfirst.sql
#-----------------------------------------------//
SELECT 'dspfirst_tags.sql' AS ' ';
\. dspfirst_tags.sql
#-----------------------------------------------//
SELECT 'dspfirst_guess.sql' AS ' ';
\. dspfirst_guess.sql
#-----------------------------------------------//
SELECT 'dspfirst_map.sql' AS ' ';
\. dspfirst_map.sql
SELECT '' AS '';
#-----------------------------------------------//


# ==== TIMER ====
SET
@s=@seconds:=UNIX_TIMESTAMP()-@start,
@d=TRUNCATE(@s/86400,0), @s=MOD(@s,86400),
@h=TRUNCATE(@s/3600,0), @s=MOD(@s,3600),
@m=TRUNCATE(@s/60,0), @s=MOD(@s,60),
@day=IF(@d>0,CONCAT(@d,' day'),''),
@hour=IF(@d+@h>0,CONCAT(IF(@d>0,LPAD(@h,2,'0'),@h),' hour'),''),
@min=IF(@d+@h+@m>0,CONCAT(IF(@d+@h>0,LPAD(@m,2,'0'),@m),' min.'),''),
@sec=CONCAT(IF(@d+@h+@m>0,LPAD(@s,2,'0'),@s),' sec.');

SELECT CONCAT(@seconds,' sec.') AS seconds, CONCAT_WS(' ',@day,@hour,@min,@sec) AS elapsed;
# ===============