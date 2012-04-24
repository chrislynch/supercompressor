<?php
/* Make DB Connection */
$db = mysql_connect('localhost', 'root', '');
mysql_select_db('sc',$db);

/* Page */
$data['content'] = array();

/* SEO */
array_drill_set('seo.title','SEO Title',$data);
array_drill_set('seo.abstract','SEO Abstract',$data);
array_drill_set('seo.keywords','SEO Keywords',$data);
array_drill_set('seo.description','SEO Description',$data);
array_drill_set('seo.copyright','SEO Copyright',$data);
array_drill_set('seo.google.analytics.account','',$data);

/* Cart */
array_drill_set('cart.items','0 items',$data);
array_drill_set('cart.value','&pound;0.00',$data);

?>
