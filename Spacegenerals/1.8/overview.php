<?php

define('INSIDE'  , true);
define('INSTALL' , false);

$rocketnova_root_path = './';
include($rocketnova_root_path . 'extension.inc');
include($rocketnova_root_path . 'common.' . $phpEx);


// Schutz vor unregestrierten
if (!$IsUserChecked)
{
	header('Location: index.php');
	die();
}

$lunarow   = doquery("SELECT * FROM {{table}} WHERE `id_owner` = '".$planetrow['id_owner']."' AND `galaxy` = '".$planetrow['galaxy']."' AND `system` = '".$planetrow['system']."' AND `lunapos` = '".$planetrow['planet']."';", 'lunas', true);

CheckPlanetUsedFields ($lunarow);

$mode = $_GET['mode'];
$pl = mysql_escape_string($_GET['pl']);
$_POST['deleteid'] = intval($_POST['deleteid']);

includeLang('resources');
includeLang('overview');

switch ($mode) {
	case 'renameplanet':
		// -----------------------------------------------------------------------------------------------
		if ($_POST['action'] == $lang['namer']) {
			// Reponse au changement de nom de la planete
			$UserPlanet     = CheckInputStrings ( $_POST['newname'] );
			$newname        = mysql_escape_string(strip_tags(trim( $UserPlanet )));
			if (preg_match("/[^A-z0-9 _\-]/", $newname) == 1) {
				message($lang['OVERVIEW_ERROR_CHARACTER'],$lang['OVERVIEW_ERROR_TITLE']);
			}
			$newname =str_replace("'","", $newname);
			if ($newname != "") {
				// Deja on met jour la planete qu'on garde en memoire (pour le nom)
				$planetrow['name'] = $newname;
				// Ensuite, on enregistre dans la base de donnÃ©es
				doquery("UPDATE {{table}} SET `name` = '".$newname."' WHERE `id` = '". $user['current_planet'] ."' LIMIT 1;", "planets");
				// Est ce qu'il sagit d'une lune ??
				if ($planetrow['planet_type'] == 3) {
					// Oui ... alors y a plus qu'a changer son nom dans la table des lunes aussi !!!
					doquery("UPDATE {{table}} SET `name` = '".$newname."' WHERE `galaxy` = '".$planetrow['galaxy']."' AND `system` = '".$planetrow['system']."' AND `lunapos` = '".$planetrow['planet']."' LIMIT 1;", "lunas");
				}
			}

		} elseif ($_POST['action'] == $lang['colony_abandon']) {
			// Cas d'abandon d'une colonie
			// Affichage de la forme d'abandon de colonie
			$parse                   = $lang;
			$parse['planet_id']      = $planetrow['id'];
			$parse['galaxy_galaxy']  = $galaxyrow['galaxy'];
			$parse['galaxy_system']  = $galaxyrow['system'];
			$parse['galaxy_planet']  = $galaxyrow['planet'];
			$parse['planet_name']    = $planetrow['name'];

			$page                   .= parsetemplate(gettemplate('overview_deleteplanet'), $parse);

			// On affiche la forme pour l'abandon de la colonie
			display($page, $lang['rename_and_abandon_planet']);

		} elseif ($_POST['kolonieloeschen'] == 1 && $_POST['deleteid'] == $user['current_planet']) {
			// Controle du mot de passe pour abandon de colonie
			if (md5($_POST['pw']) == $user["password"] && $user['id_planet'] != $user['current_planet'] && $planetrow['planet_type'] != 3){

				include_once($game_root_path . 'includes/functions/AbandonColony.' . $phpEx);
				if (CheckFleets($planetrow)){
					$strMessage = $lang['OVERVIEW_ERROR_FLEET'];
					message($strMessage, $lang['colony_abandon'], 'overview.php?mode=renameplanet',3);
				}

				AbandonColony($user,$planetrow);

                $user_galaxy = $galaxyrow['galaxy'];
                $user_system = $galaxyrow['system'];
                $user_planet = $galaxyrow['planet'];

				$QryUpdateUser    = "UPDATE {{table}} SET ";
				$QryUpdateUser   .= "'current_planet' = 'id_planet' ";
				$QryUpdateUser   .= "WHERE ";
				$QryUpdateUser   .= "`id` = '". mysql_real_escape_string($user['id']) ."' LIMIT 1";
				doquery( $QryUpdateUser, "users");

				doquery("DELETE FROM {{table}} WHERE 'galaxy' = '". $galaxyrow['galaxy'] ."' && 'system' = '". $galaxyrow['system'] ."' && 'lunapos' = '". $galaxyrow['planet'] ."' ;", 'lunas');

				doquery("DELETE FROM {{table}} WHERE 'galaxy' = '". $galaxyrow['galaxy'] ."' && 'system' = '". $galaxyrow['system'] ."' && 'planet' = '". $galaxyrow['planet'] ."' ;", 'galaxy');
                
				doquery("DELETE FROM {{table}} WHERE 'galaxy' = '". $galaxyrow['galaxy'] ."' && 'system' = '". $galaxyrow['system'] ."' && 'planet' = '". $galaxyrow['planet'] ."' ;", 'planets');
         
				// Tout s'est bien passÃ© ! La colo a Ã©tÃ© effacÃ©e !!
				message($lang['deletemessage_ok']   , $lang['colony_abandon'], 'overview.php?mode=renameplanet');

			} elseif ($user['id_planet'] == $user["current_planet"]) {
				// Et puis quoi encore ??? On ne peut pas effacer la planete mere ..
				// Uniquement les colonies crÃ©es apres coup !!!
				message($lang['deletemessage_wrong'], $lang['colony_abandon'], 'overview.php?mode=renameplanet');

			} else {
				// Erreur de saisie du mot de passe je n'efface pas !!!
				message($lang['deletemessage_fail'] , $lang['colony_abandon'], 'overview.php?mode=renameplanet');

			}
		}

		$parse = $lang;

		$parse['planet_id']     = $planetrow['id'];
		$parse['galaxy_galaxy'] = $galaxyrow['galaxy'];
		$parse['galaxy_system'] = $galaxyrow['system'];
		$parse['galaxy_planet'] = $galaxyrow['planet'];
		$parse['planet_name']   = $planetrow['name'];

		$page                  .= parsetemplate(gettemplate('overview_renameplanet'), $parse);

		// On affiche la page permettant d'abandonner OU de renomme une Colonie / Planete
		display($page, $lang['rename_and_abandon_planet']);
		break;

	default:

		// --- Gestion des messages ----------------------------------------------------------------------
		$Have_new_message = "";
		if ($user['new_message'] != 0) {
			$Have_new_message .= "<tr>";
			if       ($user['new_message'] == 1) {
				$Have_new_message .= "<th colspan=4><a href=messages.$phpEx>". $lang['Have_new_message']."</a></th>";
			} elseif ($user['new_message'] > 1) {
				$Have_new_message .= "<th colspan=4><a href=messages.$phpEx>";
				$m = pretty_number($user['new_message']);
				$Have_new_message .= str_replace('%m', $m, $lang['Have_new_messages']);
				$Have_new_message .= "</a></th>";
			}
			$Have_new_message .= "</tr>";
		}
		// -----------------------------------------------------------------------------------------------

		// --- Gestion Officiers -------------------------------------------------------------------------
		// Passage au niveau suivant, ajout du point de compÃ©tence et affichage du passage au nouveau level
		$XpMinierUp  = $user['lvl_minier'] * 10000;
		$XpRaidUp    = $user['lvl_raid']   * 100;
		$XpMinier    = $user['xpminier'];
		$XPRaid      = $user['xpraid'];

		$LvlUpMinier = $user['lvl_minier'] + 1;
		$LvlUpRaid   = $user['lvl_raid']   + 1;

		if( ($LvlUpMinier + $LvlUpRaid) <= 100 ) {
			if ($XpMinier >= $XpMinierUp) {
				$QryUpdateUser  = "UPDATE {{table}} SET ";
				$QryUpdateUser .= "`lvl_minier` = '".$LvlUpMinier."', ";
				$QryUpdateUser .= "`rpg_points` = `rpg_points` + 1 ";
				$QryUpdateUser .= "WHERE ";
				$QryUpdateUser .= "`id` = '". $user['id'] ."';";
				doquery( $QryUpdateUser, 'users');
				$HaveNewLevelMineur  = "<tr>";
				$HaveNewLevelMineur .= "<th colspan=4><a href=officier.$phpEx>". $lang['Have_new_level_mineur']."</a></th>";
			}
			if ($XPRaid >= $XpRaidUp) {
				$QryUpdateUser  = "UPDATE {{table}} SET ";
				$QryUpdateUser .= "`lvl_raid` = '".$LvlUpRaid."', ";
				$QryUpdateUser .= "`rpg_points` = `rpg_points` + 1 ";
				$QryUpdateUser .= "WHERE ";
				$QryUpdateUser .= "`id` = '". $user['id'] ."';";
				doquery( $QryUpdateUser, 'users');
				$HaveNewLevelMineur  = "<tr>";
				$HaveNewLevelMineur .= "<th colspan=4><a href=officier.$phpEx>". $lang['Have_new_level_raid']."</a></th>";
			}
		}
		// -----------------------------------------------------------------------------------------------

		// --- Gestion des flottes personnelles ---------------------------------------------------------
		// Toutes de vert vetues
		$OwnFleets       = doquery("SELECT * FROM {{table}} WHERE `fleet_owner` = '". $user['id'] ."';", 'fleets');
		$Record          = 0;
		while ($FleetRow = mysql_fetch_array($OwnFleets)) {
			$Record++;

			$StartTime   = $FleetRow['fleet_start_time'];
			$StayTime    = $FleetRow['fleet_end_stay'];
			$EndTime     = $FleetRow['fleet_end_time'];

			// Flotte a l'aller
			$Label = "fs";
			if ($StartTime > time()) {
				$fpage[$StartTime] = BuildFleetEventTable ( $FleetRow, 0, true, $Label, $Record );
			}

			if ( ($FleetRow['fleet_mission'] <> 4) && ($FleetRow['fleet_mission'] <> 10) ) {
				// Flotte en stationnement
				$Label = "ft";
				if ($StayTime > time()) {
					$fpage[$StayTime] = BuildFleetEventTable ( $FleetRow, 1, true, $Label, $Record );
				}

				// Flotte au retour
				$Label = "fe";
				if ($EndTime > time()) {
					$fpage[$EndTime]  = BuildFleetEventTable ( $FleetRow, 2, true, $Label, $Record );
				}
			}
		} // End While

		// -----------------------------------------------------------------------------------------------

		// --- Gestion des flottes autres que personnelles ----------------------------------------------
		// Flotte ennemies (ou amie) mais non personnelles
		$OtherFleets     = doquery("SELECT * FROM {{table}} WHERE `fleet_target_owner` = '".$user['id']."';", 'fleets');

		$Record          = 2000;
		while ($FleetRow = mysql_fetch_array($OtherFleets)) {
			if ($FleetRow['fleet_owner'] != $user['id']) {
				if ($FleetRow['fleet_mission'] != 8)
				if ($FleetRow['fleet_mission'] != 12)
					{
					$Record++;
					$StartTime = $FleetRow['fleet_start_time'];
					$StayTime  = $FleetRow['fleet_end_stay'];

					if ($StartTime > time()) {
						$Label = "ofs";
						$fpage[$StartTime] = BuildFleetEventTable ( $FleetRow, 0, false, $Label, $Record );
					}
					if ($FleetRow['fleet_mission'] == 5) {
						// Flotte en stationnement
						$Label = "oft";
						if ($StayTime > time()) {
							$fpage[$StayTime] = BuildFleetEventTable ( $FleetRow, 1, false, $Label, $Record );
						}
					}
				}
			}
		}

		// -----------------------------------------------------------------------------------------------

		// --- Gestion de la liste des planetes ----------------------------------------------------------
		// Planetes ...
		$planets_query = doquery("SELECT * FROM {{table}} WHERE id_owner='{$user['id']}'", "planets");
		$Colone  = 1;

		$AllPlanets = "<tr>";
		while ($UserPlanet = mysql_fetch_array($planets_query)) {
			if ($UserPlanet["id"] != $user["current_planet"] && $UserPlanet['planet_type'] != 3) {
				$AllPlanets .= "<th>". $UserPlanet['name'] ."<br>";
				$AllPlanets .= "<a href=\"?cp=". $UserPlanet['id'] ."&re=0\" title=\"". $UserPlanet['name'] ."\"><img src=\"". $dpath ."planeten/small/s_". $UserPlanet['image'] .".jpg\" height=\"50\" width=\"50\"></a><br>";
				$AllPlanets .= "<center>";

				if ($UserPlanet['b_building'] != 0) {
					UpdatePlanetBatimentQueueList ( $UserPlanet, $user );
					if ( $UserPlanet['b_building'] != 0 ) {
						$BuildQueue      = $UserPlanet['b_building_id'];
						$QueueArray      = explode ( ";", $BuildQueue );
						$CurrentBuild    = explode ( ",", $QueueArray[0] );
						$BuildElement    = $CurrentBuild[0];
						$BuildLevel      = $CurrentBuild[1];
						$BuildRestTime   = pretty_time( $CurrentBuild[3] - time() );
						$AllPlanets     .= '' . $lang['tech'][$BuildElement] . ' (' . $BuildLevel . ')';
						$AllPlanets     .= "<br><font color=\"#7f7f7f\">(". $BuildRestTime .")</font>";
					} else {
						CheckPlanetUsedFields ($UserPlanet);
						$AllPlanets     .= $lang['Free'];
					}
				} else {
					$AllPlanets    .= $lang['Free'];
				}

				$AllPlanets .= "</center></th>";
				if ($Colone <= 2) {
					$Colone++;
				} else {
					$AllPlanets .= "</tr><tr>";
					$Colone      = 1;
				}
			}
		}
		// -----------------------------------------------------------------------------------------------

		// --- Gestion des attaques missiles -------------------------------------------------------------
		$iraks_query = doquery("SELECT * FROM {{table}} WHERE owner = '" . $user['id'] . "'", 'iraks');
		$Record = 4000;
		while ($irak = mysql_fetch_array ($iraks_query)) {
			$Record++;
			$fpage[$irak['zeit']] = '';

			if ($irak['zeit'] > time()) {
				$time = $irak['zeit'] - time();

				$fpage[$irak['zeit']] .= InsertJavaScriptChronoApplet ( "fm", $Record, $time, true );

				$planet_start = doquery("SELECT * FROM {{table}} WHERE
					galaxy = '" . $irak['galaxy'] . "' AND
					system = '" . $irak['system'] . "' AND
					planet = '" . $irak['planet'] . "' AND
					planet_type = '1'", 'planets');

				$user_planet = doquery("SELECT * FROM {{table}} WHERE
					galaxy = '" . $irak['galaxy_angreifer'] . "' AND
					system = '" . $irak['system_angreifer'] . "' AND
					planet = '" . $irak['planet_angreifer'] . "' AND
					planet_type = '1'", 'planets', true);

				if (mysql_num_rows($planet_start) == 1) {
					$planet = mysql_fetch_array($planet_start);
				}

				$fpage[$irak['zeit']] .= "<tr><th><div id=\"bxxfs$i\" class=\"z\"></div><font color=\"lime\">" . gmdate("H:i:s", $irak['zeit'] + 1 * 60 * 60) . "</font> </th><th colspan=\"3\"><font color=\"#0099FF\">"
					. str_replace(array('##mips##', '##from##'), array($irak['anzahl'], $user_planet['name']), $lang['OVERVIEW_ATTACKING_MIPS1']);
				$fpage[$irak['zeit']] .= '<a href="galaxy.php?mode=3&galaxy=' . $irak["galaxy_angreifer"] . '&system=' . $irak["system_angreifer"] . '&planet=' . $irak["planet_angreifer"] . '">[' . $irak["galaxy_angreifer"] . ':' . $irak["system_angreifer"] . ':' . $irak["planet_angreifer"] . ']</a>';
				$fpage[$irak['zeit']] .= str_replace('##to##', $planet["name"], $lang['OVERVIEW_ATTACKING_MIPS2']);
				$fpage[$irak['zeit']] .= '<a href="galaxy.php?mode=3&galaxy=' . $irak["galaxy"] . '&system=' . $irak["system"] . '&planet=' . $irak["planet"] . '">[' . $irak["galaxy"] . ':' . $irak["system"] . ':' . $irak["planet"] . ']</a>';
				$fpage[$irak['zeit']] .= '</font>';
				$fpage[$irak['zeit']] .= InsertJavaScriptChronoApplet ( "fm", $Record, $time, false );
				$fpage[$irak['zeit']] .= "</th>";
			}
		}

		// -----------------------------------------------------------------------------------------------

		$parse                         = $lang;

		// -----------------------------------------------------------------------------------------------
		// News Frame ...
		// External Chat Frame ...
		// Banner ADS Google (meme si je suis contre cela)
		if ($game_config['OverviewNewsFrame'] == '1') {
			$parse['NewsFrame']          = "<tr><th>". $lang['ov_news_title'] ."</th><th colspan=\"3\">". stripslashes($game_config['OverviewNewsText']) ."</th></tr>";
		}
		if ($game_config['OverviewExternChat'] == '1') {
			$parse['ExternalTchatFrame'] = "<tr><th colspan=\"4\">". stripslashes( $game_config['OverviewExternChatCmd'] ) ."</th></tr>";
		}
		if ($game_config['OverviewClickBanner'] != '') {
			$parse['ClickBanner'] = stripslashes( $game_config['OverviewClickBanner'] );
		}

		// --- Gestion de l'affichage d'une lune ---------------------------------------------------------
		if ($planetrow['galaxy'] == $lunarow['galaxy'] && $planetrow['system'] == $lunarow['system'] && $planetrow['planet'] == $lunarow['lunapos'] && $planetrow['planet_type'] != 3) {
			$lune = doquery("SELECT * FROM {{table}} WHERE galaxy={$lunarow['galaxy']} AND system={$lunarow['system']} AND planet={$lunarow['lunapos']} AND planet_type='3'", 'planets', true);
			$parse['moon_img'] = "<a href=\"?cp={$lune['id']}&re=0\" title=\"{$UserPlanet['name']}\"><img src=\"{$dpath}planeten/{$lunarow['image']}.jpg\" height=\"50\" width=\"50\"></a>";
			$parse['moon'] = $lunarow['name'];
		} else {
			$parse['moon_img'] = "";
			$parse['moon'] = "";
		}
		// Moon END

		$parse['planet_name']          = $planetrow['name'];
		$parse['planet_diameter']      = pretty_number($planetrow['diameter']);
		$parse['planet_field_current'] = $planetrow['field_current'];
		$parse['planet_field_max']     = CalculateMaxPlanetFields($planetrow);
		$parse['planet_temp_min']      = $planetrow['temp_min'];
		$parse['planet_temp_max']      = $planetrow['temp_max'];
		$parse['galaxy_galaxy']        = $planetrow['galaxy'];
		$parse['galaxy_planet']        = $planetrow['planet'];
		$parse['galaxy_system']        = $planetrow['system'];
		$StatRecord = doquery("SELECT * FROM {{table}} WHERE `stat_type` = '1' AND `stat_code` = '1' AND `id_owner` = '". $user['id'] ."';", 'statpoints', true);

		$parse['user_points']          = pretty_number( $StatRecord['build_points'] );
		$parse['user_fleet']           = pretty_number( $StatRecord['fleet_points'] );
		$parse['user_def']           = pretty_number( $StatRecord['defs_points'] );
		$parse['player_points_tech']   = pretty_number( $StatRecord['tech_points'] );
		$parse['total_points']         = pretty_number( $StatRecord['total_points'] );;

		$parse['user_rank']            = $StatRecord['total_rank'];
		$ile = $StatRecord['total_old_rank'] - $StatRecord['total_rank'];
		if ($ile >= 1) {
			$parse['ile']              = "<font color=lime>+" . $ile . "</font>";
		} elseif ($ile < 0) {
			$parse['ile']              = "<font color=red>-" . $ile . "</font>";
		} elseif ($ile == 0) {
			$parse['ile']              = "<font color=lightblue>" . $ile . "</font>";
		}
		$parse['u_user_rank']          = $StatRecord['total_rank'];
		$parse['user_name']            = $lang['user_name'];
		$parse['user_username']        = $user['username'];
		$parse['user_id']              = $lang['user_id'];
		$parse['user_userid']          = $user['id'];
		// Bewegte Resourcen
		$moved_query = doquery("SELECT `fleet_owner`,`fleet_mission`,SUM(`fleet_resource_metal`) as metal,SUM(`fleet_resource_crystal`) as crystal,SUM(`fleet_resource_deuterium`)as deuterium FROM {{table}} WHERE fleet_owner = ".$user['id']." GROUP BY fleet_mission",'fleets');

		// Gesamte bewegte Resourcen
		$g_metal= 0;
		$g_crystal= 0;
		$g_deuterium= 0;
		$g_debris= 0;

		$parse['res_atk_metal']= 0;
		$parse['res_atk_crystal']= 0;
		$parse['res_atk_deuterium']= 0;
		$parse['res_trans_metal']= 0;
		$parse['res_trans_crystal']= 0;
		$parse['res_trans_deuterium']= 0;
		$parse['res_statio_metal']= 0;
		$parse['res_statio_crystal']= 0;
		$parse['res_statio_deuterium']= 0;
		$parse['res_debris_metal']= 0;
		$parse['res_debris_crystal']= 0;
		$parse['res_debris_deuterium']= 0;

		while($moved = mysql_fetch_assoc($moved_query)) {

			switch($moved['fleet_mission']) {

				// Angriff
				case 1:
					$parse['res_atk_metal']= pretty_number($moved['metal']);
					$parse['res_atk_crystal']= pretty_number($moved['crystal']);
					$parse['res_atk_deuterium']= pretty_number($moved['deuterium']);

					$g_metal+= $moved['metal'];
					$g_crystal+= $moved['crystal'];
					$g_deuterium+= $moved['deuterium'];

					break;

				// Transport
				case 3:
					$parse['res_trans_metal']= pretty_number($moved['metal']);
					$parse['res_trans_crystal']= pretty_number($moved['crystal']);
					$parse['res_trans_deuterium']= pretty_number($moved['deuterium']);

					$g_metal+= $moved['metal'];
					$g_crystal+= $moved['crystal'];
					$g_deuterium+= $moved['deuterium'];

					break;

				// Stationieren
				case 4:
					$parse['res_statio_metal']= pretty_number($moved['metal']);
					$parse['res_statio_crystal']= pretty_number($moved['crystal']);
					$parse['res_statio_deuterium']= pretty_number($moved['deuterium']);

					$g_metal+= $moved['metal'];
					$g_crystal+= $moved['crystal'];
					$g_deuterium+= $moved['deuterium'];

					break;

				// Trümmerfeld
				case 8:
					$parse['res_debris_metal']= pretty_number($moved['metal']);
					$parse['res_debris_crystal']= pretty_number($moved['crystal']);
					$parse['res_debris_deuterium']= pretty_number($moved['deuterium']);

					$g_metal+= $moved['metal'];
					$g_crystal+= $moved['crystal'];
					$g_deuterium+= $moved['deuterium'];

					break;
			}

		}

		$parse['res_all_metal']= pretty_number($g_metal);
		$parse['res_all_crystal']= pretty_number($g_crystal);
		$parse['res_all_deuterium']= pretty_number($g_deuterium);
		// Bewegte Resourcen ENDE

		if (count($fpage) > 0) {
			ksort($fpage);
			foreach ($fpage as $time => $content) {
				$flotten .= $content . "\n";
			}
		}

		$parse['fleet_list']  = $flotten;
		$parse['energy_used'] = $planetrow["energy_max"] - $planetrow["energy_used"];

		$parse['Have_new_message']      = $Have_new_message;
		$parse['Have_new_level_mineur'] = $HaveNewLevelMineur;
		$parse['Have_new_level_raid']   = $HaveNewLevelRaid;
		$parse['time']                  = date("D M d H:i:s", time());
		$parse['dpath']                 = $dpath;
		$parse['planet_image']          = $planetrow['image'];
		$parse['anothers_planets']      = $AllPlanets;
		$MaxUsers = doquery ("SELECT COUNT(*) AS `count` FROM {{table}} WHERE `db_deaktjava` = '0' OR `db_deaktjava` = '1';", 'users', true);
		$parse['max_users'] = $MaxUsers['count'];
		$parse['metal_debris']          = pretty_number($galaxyrow['metal']);
		$parse['crystal_debris']        = pretty_number($galaxyrow['crystal']);
		if (($galaxyrow['metal'] != 0 || $galaxyrow['crystal'] != 0) && $planetrow[$resource[209]] != 0) {
			$parse['get_link'] = " (<a href=\"quickfleet.php?mode=8&g=".$galaxyrow['galaxy']."&s=".$galaxyrow['system']."&p=".$galaxyrow['planet']."&t=2\">". $lang['type_mission'][8] ."</a>)";
		} else {
			$parse['get_link'] = '';
		}

		if ( $planetrow['b_building'] != 0 ) {
			UpdatePlanetBatimentQueueList ( $planetrow, $user );
			if ( $planetrow['b_building'] != 0 ) {
				$BuildQueue = explode (";", $planetrow['b_building_id']);
				$CurrBuild  = explode (",", $BuildQueue[0]);
				$RestTime   = $planetrow['b_building'] - time();
				$PlanetID   = $planetrow['id'];
				$Build  = InsertBuildListScript ( "overview" );
				$Build .= $lang['tech'][$CurrBuild[0]] .' ('. ($CurrBuild[1]) .')';
				$Build .= "<br /><div id=\"blc\" class=\"z\">". pretty_time( $RestTime ) ."</div>";
				$Build .= "\n<script language=\"JavaScript\">";
				$Build .= "\n	pp = \"". $RestTime ."\";\n";  // temps necessaire (a compter de maintenant et sans ajouter time() )
				$Build .= "\n	pk = \"". 1 ."\";\n";          // id index (dans la liste de construction)
				$Build .= "\n	pm = \"cancel\";\n";           // mot de controle
				$Build .= "\n	pl = \"". $PlanetID ."\";\n";  // id planete
				$Build .= "\n	t();\n";
				$Build .= "\n</script>\n";

				$parse['building'] = $Build;
			} else {
				$parse['building'] = $lang['Free'];
			}
		} else {
			$parse['building'] = $lang['Free'];
		}
		{ // Vista normal
			$query = doquery('SELECT username FROM {{table}} ORDER BY register_time DESC', 'users', true);
			$parse['last_user'] = $query['username'];
			$query = doquery("SELECT COUNT(DISTINCT(id)) FROM {{table}} WHERE onlinetime>" . (time()-900), 'users', true);
			$parse['online_users'] = $query[0];
			// $count = doquery(","users",true);
			$parse['users_amount'] = $game_config['users_amount'];
		}
		// Rajout d'une barre pourcentage
		// Calcul du pourcentage de remplissage
		$parse['case_pourcentage'] = floor($planetrow["field_current"] / CalculateMaxPlanetFields($planetrow) * 100) . $lang['o/o'];
		// Barre de remplissage
		$parse['case_barre'] = floor($planetrow["field_current"] / CalculateMaxPlanetFields($planetrow) * 100) * 2.5;
		// Couleur de la barre de remplissage
		if ($parse['case_barre'] > (100 * 2.5)) {
			$parse['case_barre'] = 250;
			$parse['case_barre_barcolor'] = '#C00000';
		} elseif ($parse['case_barre'] > (80 * 2.5)) {
			$parse['case_barre_barcolor'] = '#C0C000';
		} else {
			$parse['case_barre_barcolor'] = '#00C000';
		}

		//Mode AmÃ©liorations
		$parse['colonie_empire'] = $lang['colonie_empire'];
		$parse['xpminier']	= $user['xpminier'];
		$parse['xpraid']	= $user['xpraid'];
		$parse['lvl_minier']= $user['lvl_minier'];
		$parse['lvl_raid'] 	= $user['lvl_raid'];
		$parse['az'] 		= $user['angriffszone'];
		$parse['levelupm'] = $user['lvl_minier']+1;
		$parse['levelupr'] = $user['lvl_raid']+1;
		$LvlMinier = $user['lvl_minier'];
		$LvlRaid = $user['lvl_raid'];


		$parse['lvl_up_minier'] = $LvlMinier * 10000;
		$parse['lvl_up_raid']   = $LvlRaid * 100;
		// Nombre de raids, pertes, etc ...
		$parse['Raids'] = $lang['Raids'];
		$parse['NumberOfRaids'] = $lang['NumberOfRaids'];
		$parse['RaidsWin'] = $lang['RaidsWin'];
		$parse['RaidsLoose'] = $lang['RaidsLoose'];

		$parse['raids'] = $user['raids'];
		$parse['raidswin'] = $user['raidswin'];
		$parse['raidsloose'] = $user['raidsloose'];

		//Extras
		$parse['avatar'] = $user['avatar'];

		//Compteur de Membres en lign
		$OnlineUsers = doquery("SELECT COUNT(*) FROM {{table}} WHERE onlinetime>='".(time()-15*60)."'",'users', 'true');
		$parse['NumberMembersOnline'] = $OnlineUsers[0];
		$OnlineAdmins = doquery("SELECT * FROM {{table}} WHERE onlinetime>='".(time()-10*60)."' AND authlevel>=1",'users');
		if($OnlineAdmins){
			$parse['OnlineAdmins'] = "";
			while ($oas = mysql_fetch_array($OnlineAdmins)) {
				$parse['OnlineAdmins'] .= "<a href=\"messages.php?mode=write&id=". $oas['id'] ."\" >". $oas['username'] ."</a>&nbsp;&bull;&nbsp;";
			}
		}else{
			$parse['OnlineAdmins'] = "--";
		}

		$page = parsetemplate(gettemplate('overview_body'), $parse);

		display($page, $lang['Overview']);
		break;
}

?>