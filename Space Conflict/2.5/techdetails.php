<?php

/******************************************
**            Oasis Rage 2.0             **
**             by darkOasis              **
**                                       **
**  special thanks to the developers of  **
**    XNova, Ugamela and RageOnline      **
**                                       **
** techdetails.php                       **
******************************************/

define('INSIDE'  , true);
define('INSTALL' , false);

$xnova_root_path = './';
include($xnova_root_path . 'extension.inc');
include($xnova_root_path . 'common.' . $phpEx);


$Id                  = $_GET['techid'];
$PageTPL             = gettemplate('techtree_details');
$RowsTPL             = gettemplate('techtree_details_rows');

$parse               = $lang;
$parse['te_dt_id']   = $Id;
$parse['te_dt_name'] = $lang['tech'][$Id];
$Liste = "";

if ($Id == 12) {
    $Liste .= "<tr><th>".$lang['tech']['31']." (".$lang['level']." 1)</th></tr>";
    $Liste .= "<tr><td class=\"c\">2</td><tr>";
    $Liste .= "<tr><th>".$lang['tech']['3']." (".$lang['level']." 5)</th></tr>";
    $Liste .= "<tr><th>".$lang['tech']['113']." (".$lang['level']." 3) <a href=\"techtreedetails.php?tech=113\">[i]</a></th></tr>";
}

$parse['Liste'] = $Liste;
$page = parsetemplate($PageTPL, $parse);

display ($page, $lang['Tech'], false, '', false);

/******************************************************************************************
**                                    Revision Notes                                     **
**  @ Official OasisRage 2.0 release - May 2009 - darkOasis                              **
**  @ (please note any changes you make to the source code)                              **
**  @                                                                                    **
**                                                                                       **
******************************************************************************************/

?>