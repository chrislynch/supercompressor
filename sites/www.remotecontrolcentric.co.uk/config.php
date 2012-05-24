<?php
if (!strstr($_SERVER['HTTP_HOST'],'dev.')){
    $data['_configuration']['db']['password']  = 'spider20';
} 
$data['_configuration']['db']['schema']    = 'centric_remotecontrol';

array_drill_set('_configuration.site.name','RemoteControl<span class="centric">Centric</span>',$data);
array_drill_set('_configuration.site.strapline','Replacing the UK\'s lost and damaged remote controls',$data);


array_drill_set('seo.title','Remote Control Centric',$data);
array_drill_set('seo.google.analytics.account','UA-31872896-2',$data);
array_drill_set('seo.google.webmastertools.account','Shk8GVA_8ltC7meV9luOBRbh_19ZfeWgNVdl6H2q6_w',$data);


array_drill_set('_configuration.about.welcome','
A lost or broken remote control can make an expensive television absolutely worthless. Have no fear! Remote Control Centric have access to an enormous range of replacement LG remote controls, 
replacement Samsung remote controls, and more.',$data);

array_drill_set('_configuration.about.short','',$data);

array_drill_set('_configuration.about.long','',$data);
?>
