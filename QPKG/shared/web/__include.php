<?php
if (!isset($_COOKIE['NAS_USER']) || empty($_COOKIE['NAS_USER'])) {
    die("not logged in! ;)");
}
// Get Session ID
$strSessionID = $_COOKIE['NAS_SID'];

// Set URL
$QNAPDOCURL = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]/cgi-bin";

// Set specific variables by qpkg.conf
$qpkg_conf = parse_ini_file('/etc/config/qpkg.conf', 1, INI_SCANNER_RAW);
$roon_qpkg_conf = $qpkg_conf['RoonServer'];

// Make vars accessible
define("QNAPDOCURL", $QNAPDOCURL);
define("QNAPLOCALDOC", $_SERVER['DOCUMENT_ROOT']);
define("QPKGINSTALLPATH", $qpkg_conf['RoonServer']['Install_Path']);

# Getting free space of database directory
if (array_key_exists('DB_Path', $roon_qpkg_conf)) {
    $qpkg_conf_db = $qpkg_conf['RoonServer']['DB_Path'];
    $originalpath = explode('/', $qpkg_conf_db);
    unset($originalpath[1]);
    $dblocation = implode('/', $originalpath);
}


$RoonVersion = file(rtrim(QPKGINSTALLPATH) . "/RoonServer/VERSION");
$alsafull = file_get_contents('/proc/asound/cards');
$alsaraw = fopen("/proc/asound/cards", 'r');
$alsatext = fread($alsaraw, 25000);

preg_match_all("/\[[^\]]*\]/", $alsafull, $alsa);

if (isset($qpkg_conf_db)) {
    $db_vol_cap = disk_total_space($qpkg_conf_db);
    $db_free_space = disk_free_space($qpkg_conf_db);
    $db_perc = round(100 - (($db_free_space / $db_vol_cap) * 100));
}
?>
