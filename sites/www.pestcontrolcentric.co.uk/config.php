<?php
if (!strstr($_SERVER['HTTP_HOST'],'dev.')){
    $data['_configuration']['db']['password']  = 'spider20';
    
    array_drill_set('seo.google.analytics.account','UA-31872896-3',$data);
    array_drill_set('seo.google.webmastertools.account','88YQLuDCV3El0mDSUqMbTnLXbeZFrbTKwtojUhQifMw',$data);
} 
$data['_configuration']['db']['schema']    = 'pestcontrolcentric';

array_drill_set('_configuration.site.name','PestControl',$data);
array_drill_set('_configuration.site.strapline','Centered on controlling the UK\'s pests and vermin',$data);

array_drill_set('seo.title','Pest Control Centric',$data);

$dir_template   = 'templates/conversion/';
$data['_configuration']['template']['name'] = 'conversion';
?>
