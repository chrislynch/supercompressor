<?php
if (!strstr($_SERVER['HTTP_HOST'],'dev.')){
    $data['_configuration']['db']['password']  = 'spider20';
} 
$data['_configuration']['db']['schema']    = 'centric_solarpanel';

array_drill_set('_configuration.site.name','SolarPanel<span class="centric">Centric</span>',$data);
array_drill_set('_configuration.site.strapline','Bringing trade price solar panels to UK consumers',$data);

array_drill_set('seo.title','solarpanelcentric',$data);
array_drill_set('seo.google.analytics.account','UA-31872896-1',$data);
array_drill_set('seo.google.webmastertools.account','fxi3B2YNo2XXO1-SMgTa1_Jt0vs7BkG9VZoBHtgYKoE',$data);

array_drill_set('_configuration.about.welcome','
Solar Panel Centric is a brand new website dedicated to bringing solar panels to UK consumers at trade prices.
We don\'t install solar panels, we don\'t want to you to sign up the latest government scheme or initiative. We just sell top quality solar panels at incredibly low prices.',$data);

array_drill_set('_configuration.about.short','Solar Panel Centric is a brand new website dedicated to bringing solar panels to UK consumers at trade prices.
We don\'t install solar panels, we don\'t want to you to sign up the latest government scheme or initiative. We just sell top quality solar panels at incredibly low prices.',$data);

array_drill_set('_configuration.about.long','
Solar Panel Centric is dedicated to bringing you the very best possible prices on solar panels, delivered direct to your door, anywhere in the UK.

We are open to end users, consumers, and trade customers. If you would like to bulk buy solar panels from us, please contact our office on @@@.

Solar panels are a great way to generate electricity for free. With high efficiency ratings, our solar panels will help you make the most of even the gloomy British weather.',$data);
?>
