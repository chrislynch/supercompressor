<?php
if (!strstr($_SERVER['HTTP_HOST'],'dev.')){
    $data['_configuration']['db']['password']  = 'spider20';
    
    array_drill_set('seo.google.analytics.account','UA-31872896-2',$data);
    array_drill_set('seo.google.webmastertools.account','Shk8GVA_8ltC7meV9luOBRbh_19ZfeWgNVdl6H2q6_w',$data);
} 
$data['_configuration']['db']['schema']    = 'centric_remotecontrol';

$dir_template   = 'templates/default/';
$data['_configuration']['template']['name'] = 'default';

array_drill_set('_configuration.site.name','RemoteControl',$data);
array_drill_set('_configuration.site.strapline','Replacing the UK\'s lost and damaged remote controls',$data);

array_drill_set('seo.title','Remote Control Centric',$data);

array_drill_set('_configuration.about.welcome','
',$data);
?>
