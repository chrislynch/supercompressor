<?php
/*
 * =============================================================================
 * GENERIC STARTUP
 * =============================================================================
 */

include 'core/core.php';
include 'core/widgets.php';
include 'lib/phpmarkdownextra/markdown.php';

$data = array();

$data['_configuration'] = array();

$data['_configuration']['db']['server']    = 'localhost'; 
$data['_configuration']['db']['username']  = 'root'; 
$data['_configuration']['db']['password']  = '';
$data['_configuration']['db']['schema']    = 'sc';

$data['_configuration']['actions'] = array();
$data['_configuration']['actions'][-1] = 'cart';
$data['_configuration']['actions'][1]  = 'seo';
$data['_configuration']['actions'][2] = 'menu';

$data['_configuration']['renderers'][0] = 'html';

// Templates
$data['_configuration']['templates'][-4] = 'html-header';
$data['_configuration']['templates'][-3] = 'page-header';
$data['_configuration']['templates'][-2] = 'sidebar-left';
$data['_configuration']['templates'][-1] = 'content-header';
$data['_configuration']['templates'][1] = 'content-footer';
$data['_configuration']['templates'][2] = 'sidebar-right';
$data['_configuration']['templates'][3] = 'page-footer';
$data['_configuration']['templates'][4] = 'html-footer';

// Pager configuration
array_drill_set('pager.itemsperpage', 10, $data['_configuration']);
array_drill_set('pager.maxpages', 10, $data['_configuration']);

// Category configuration
/*
array_drill_set('_configuration.categories.products.heading','Product',$data);
array_drill_set('_configuration.categories.products.menu','Products',$data);
array_drill_set('_configuration.categories.brand.heading','Brand',$data);
array_drill_set('_configuration.categories.brand.menu','Brands',$data);
*/

// Content & Templates Starters
$data['_content'] = array();
$data['templates'] = $data['_configuration']['templates'];

$dir_template   = 'templates/default/';
$data['_configuration']['template']['name'] = 'default';

// SEO Starter
array_drill_set('seo.title','',$data);
array_drill_set('seo.abstract','',$data);
array_drill_set('seo.keywords','',$data);
    
array_drill_set('seo.copyright','eCommerceCentric Ltd. http://www.ecommercecentric.co.uk',$data);
array_drill_set('seo.google.analytics.account','',$data);
array_drill_set('seo.google.webmastertools.account','',$data);

/*
 * =============================================================================
 * ACTION CONFIGURATION
 * =============================================================================
 */
array_drill_set('_configuration.content.span',16,$data);
array_drill_set('_configuration.cart.on',TRUE,$data);

/*
 * =============================================================================
 * IDENTIFY CURRENT SITE
 * =============================================================================
 */
array_drill_set('_configuration.site.domain',$_SERVER['HTTP_HOST'],$data);
array_drill_set('_configuration.site.domaindir','sites/' . $_SERVER['HTTP_HOST'] . '/',$data);
array_drill_set('_configuration.site.configfile','sites/' . $_SERVER['HTTP_HOST'] . '/config.php',$data);
array_drill_set('_configuration.site.templatedir',$_SERVER['HTTP_HOST'],$data);

/*
 * ===================================================================================
 * SITE SPECIFIC VALUES BELOW. THESE ARE DEFAULTS AND SHOULD BE REPLACED IN CONFIG.PHP
 * ===================================================================================
 */

array_drill_set('_configuration.site.name','eCommerceCentric Ltd',$data);
array_drill_set('_configuration.site.strapline','Centered on eCommerce',$data);

/*
 * ===================================================================================
 * END OF SITE SPECIFIC VALUES.
 * ===================================================================================
 */

/*
 * =============================================================================
 * INCLUDE CONFIG.PHP AND CONNECT TO DATABASE
 * =============================================================================
 */

if (file_exists(array_drill_get('_configuration.site.configfile',$data))){
    include array_drill_get('_configuration.site.configfile',$data);
}

$db = mysql_connect($data['_configuration']['db']['server'], $data['_configuration']['db']['username'], $data['_configuration']['db']['password']);
mysql_select_db($data['_configuration']['db']['schema'],$db);

?>
