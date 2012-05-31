<?php
if (!strstr($_SERVER['HTTP_HOST'],'dev.')){
    $data['_configuration']['db']['password']  = 'spider20';
} 
$data['_configuration']['db']['schema']    = 'pestcontrolcentric';

array_drill_set('_configuration.site.name','PestControl',$data);
array_drill_set('_configuration.site.strapline','Centered on controlling the UK\'s pests and vermin',$data);


array_drill_set('seo.title','Pest Control Centric',$data);
array_drill_set('seo.google.analytics.account','',$data);
array_drill_set('seo.google.webmastertools.account','',$data);


array_drill_set('_configuration.about.welcome','',$data);

array_drill_set('_configuration.about.short','',$data);

array_drill_set('_configuration.about.long','',$data);

?>
