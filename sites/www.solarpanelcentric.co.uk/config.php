<?php
if (!strstr($_SERVER['HTTP_HOST'],'dev.')){
    $data['_configuration']['db']['password']  = 'spider20';
    
    array_drill_set('seo.google.analytics.account','UA-31872896-1',$data);
    array_drill_set('seo.google.webmastertools.account','fxi3B2YNo2XXO1-SMgTa1_Jt0vs7BkG9VZoBHtgYKoE',$data);
}
$data['_configuration']['db']['schema']    = 'centric_solarpanel';

array_drill_set('_configuration.site.name','SolarPanel',$data);
array_drill_set('_configuration.site.strapline','Bringing trade price solar panels to UK consumers',$data);

array_drill_set('seo.title','Solar Panel Centric - Centred on Solar Panels',$data);
array_drill_set('seo.keywords','solar, solar panels, solar power',$data);
array_drill_set('seo.description','Solar Panel Centric are dedicated to bringing solar panels to UK consumers at trade prices.',$data);
?>
