<?php

include 'core/core.php';
include 'core/widgets.php';

$data = array();

$data['_configuration'] = array();

$data['db']['server']    = 'localhost'; 
$data['db']['username']  = 'root'; 
$data['db']['password']  = '';
$data['db']['schema']    = 'sc';

$data['actions'] = array();
$data['actions'][-2] = 'menu';
$data['actions'][-1] = 'cart';
$data['actions'][1]  = 'seo';

array_drill_set('pager.itemsperpage', 10, $data['_configuration']);
array_drill_set('pager.maxpages', 10, $data['_configuration']);

$data['_content'] = array();

$dir_template   = 'templates/localhost/';
$dir_site       = 'sites/localhost/';

// Load the custom configuration file.
if (file_exists($dir_site . 'config.php')){include $dir_site . 'config.php';}

$db = mysql_connect($data['db']['server'], $data['db']['username'], $data['db']['password']);
mysql_select_db($data['db']['schema'],$db);

?>
