<?php
require_once 'config.php';
require_once 'classes/version.class.php';
$db = new db_e;
$version = new version;

if ( isset($_GET['oldVersion']) ) {
	$oldVersion = $_GET['oldVersion'];	
} else {
	$oldVersion = $version->getVersion();
}
switch ( $oldVersion ) {
	case '120.00':
		$tables[] = "ALTER TABLE ".$config['db_prefix']."external_settings ADD COLUMN `exMasks` text";
		$tables[] = "ALTER TABLE ".$config['db_prefix']."external_settings ADD COLUMN `scriptVersion` float";
		$tables[] = "ALTER TABLE ".$config['db_prefix']."external_settings ALTER COLUMN `scriptVersion` SET DEFAULT '102.01'";
		$tables[] = "UPDATE ".$config['db_prefix']."external_settings SET scriptVersion = 102.01";
		break;
}
print( 'Начинаем обновление БД...<br />' );
for ( $i = 0; $i < count( $tables ); $i++ ) {
	$db->ExecQuery( $tables[$i] );
	print( ($i + 1).'/'.count( $tables ).'<br />' );
}
print( 'Обновление завершено!' );
?>