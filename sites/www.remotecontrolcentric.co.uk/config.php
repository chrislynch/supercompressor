<?php
if (!strstr($_SERVER['HTTP_HOST'],'dev.')){
    $data['_configuration']['db']['password']  = 'spider20';
} else {
    $data['_configuration']['db']['schema']    = 'centric_remotecontrol';
}

array_drill_set('_configuration.site.name','RemoteControl<span class="centric">Centric</span>',$data);
array_drill_set('_configuration.site.strapline','Replacing the UK\' lost and broken remote controls',$data);

array_drill_set('seo.title','solarpanelcentric',$data);
array_drill_set('seo.google.analytics.account','UA-31872896-1',$data);
array_drill_set('seo.google.webmastertools.account','fxi3B2YNo2XXO1-SMgTa1_Jt0vs7BkG9VZoBHtgYKoE',$data);

array_drill_set('_configuration.about.welcome','',$data);

array_drill_set('_configuration.about.short','',$data);

array_drill_set('_configuration.about.long','',$data);
?>
