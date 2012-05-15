<?php
/*
 * =============================================================================
 * GENERIC STARTUP
 * =============================================================================
 */

include 'core/core.php';
include 'core/widgets.php';
include 'lib/phpmarkdownextra/markdown.php';

$dir_template   = 'templates/default/';

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

// Pager configuration
array_drill_set('pager.itemsperpage', 10, $data['_configuration']);
array_drill_set('pager.maxpages', 10, $data['_configuration']);

// Category configuration
array_drill_set('_configuration.categories.products.heading','Product',$data);
array_drill_set('_configuration.categories.products.menu','Products',$data);
array_drill_set('_configuration.categories.brand.heading','Brand',$data);
array_drill_set('_configuration.categories.brand.menu','Brands',$data);

// Generic content
array_drill_set('_configuration.about.short','<p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo. Quisque sit amet est et sapien ullamcorper pharetra. Vestibulum erat wisi, condimentum sed, commodo vitae, ornare sit amet, wisi. Aenean fermentum, elit eget tincidunt condimentum, eros ipsum rutrum orci, sagittis tempus lacus enim ac dui. Donec non enim in turpis pulvinar facilisis. Ut felis. Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus</p>',$data);
array_drill_set('_configuration.about.welcome','<p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo. Quisque sit amet est et sapien ullamcorper pharetra. Vestibulum erat wisi, condimentum sed, commodo vitae, ornare sit amet, wisi. Aenean fermentum, elit eget tincidunt condimentum, eros ipsum rutrum orci, sagittis tempus lacus enim ac dui. Donec non enim in turpis pulvinar facilisis. Ut felis. Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus</p>',$data);
array_drill_set('_configuration.about.long','<p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo. Quisque sit amet est et sapien ullamcorper pharetra. Vestibulum erat wisi, condimentum sed, commodo vitae, ornare sit amet, wisi. Aenean fermentum, elit eget tincidunt condimentum, eros ipsum rutrum orci, sagittis tempus lacus enim ac dui. Donec non enim in turpis pulvinar facilisis. Ut felis. Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus</p><p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo. Quisque sit amet est et sapien ullamcorper pharetra. Vestibulum erat wisi, condimentum sed, commodo vitae, ornare sit amet, wisi. Aenean fermentum, elit eget tincidunt condimentum, eros ipsum rutrum orci, sagittis tempus lacus enim ac dui. Donec non enim in turpis pulvinar facilisis. Ut felis. Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus</p><p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo. Quisque sit amet est et sapien ullamcorper pharetra. Vestibulum erat wisi, condimentum sed, commodo vitae, ornare sit amet, wisi. Aenean fermentum, elit eget tincidunt condimentum, eros ipsum rutrum orci, sagittis tempus lacus enim ac dui. Donec non enim in turpis pulvinar facilisis. Ut felis. Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus</p>',$data);

// Content Starter
$data['_content'] = array();

// SEO Starter
array_drill_set('seo.title','centric',$data);
array_drill_set('seo.abstract','SEO Abstract',$data);
array_drill_set('seo.keywords','SEO Keywords',$data);
    
array_drill_set('seo.copyright','eCommerceCentric Ltd. http://www.ecommercecentric.co.uk',$data);
array_drill_set('seo.google.analytics.account','',$data);

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
