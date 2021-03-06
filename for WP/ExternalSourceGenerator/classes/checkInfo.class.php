<?php
class checkInfo {
	function checkRefsOnBlackRefs() {
		require_once 'settings.class.php';
		global $config;
		$db = new db_e;
		$st = new settings;
		
		$query = "SELECT * FROM ".$config['db_prefix']."external_referers";
		$referers_res = $db->ExecQuery( $query );
		$referers = $referers_res['rows'];
		
		$sett = $st->getSettings();
		$blackRefs = explode( "\n", $sett['rows'][0]['blackRefs'] );	
		
		for ( $i = 0; $i < count( $blackRefs ); $i++ ) {
			for ( $j = 0; $j < count( $referers ); $j++ ) {
				if ( strpos( $referers[$j]['referer'], $blackRefs[$i] ) !== FALSE ) {
					$wr['id'][] = $referers[$j]['id'];
				}
			}
		}
		if ( count( $wr['id'] ) > 0 ) {
			$query = "DELETE FROM ".$config['db_prefix']."external_referers".$db->GenWhere( $wr );
			$db->ExecQuery( $query );
		}
	}
	
	function deleteRobotsUA() {
		require_once 'browser.class.php';
		global $config;
		$db = new db_e;
		$browser = new BrowserExt;
		
		$wr['id'] = array();
		$query = "SELECT * FROM ".$config['db_prefix']."external_ua";
		$ua_res = $db->ExecQuery( $query );
		$ua = $ua_res['rows'];
		for ( $i = 0; $i < count( $ua ); $i++ ) {
			$userAgent = $ua[$i]['ua'];
			$browser->Browser( $userAgent );
			if ( $browser->isRobot() ) {
				$wr['id'][] = $ua[$i]['id'];
			}
		}
		if ( count( $wr['id'] ) > 0 ) {
			$query = "DELETE FROM ".$config['db_prefix']."external_ua".$db->GenWhere( $wr );
			$db->ExecQuery( $query );
		}
	}
	
	function deleteHttp() {
		global $config;
		$db = new db_e;
	
		$query = "DELETE FROM ".$config['db_prefix']."external_referers WHERE referer='http://' OR referer='https://'";
		$db->ExecQuery( $query );
	}
}
?>