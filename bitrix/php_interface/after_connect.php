<?
$DB->Query("SET LOCAL time_zone='".date('P')."'");
$DB->Query("SET sql_mode=''");
$DB->Query("SET innodb_strict_mode=0");
$DB->Query("SET NAMES 'utf8'");
$DB->Query('SET collation_connection = "utf8_unicode_ci"');
?>