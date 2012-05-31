<?php
if (!strstr($_SERVER['HTTP_HOST'],'dev.')){
    $data['_configuration']['db']['password']  = 'spider20';
}
$data['_configuration']['db']['schema']    = 'centric_solarpanel';

array_drill_set('_configuration.site.name','SolarPanel',$data);
array_drill_set('_configuration.site.strapline','Bringing trade price solar panels to UK consumers',$data);

array_drill_set('seo.title','Solar Panel Centric - Centred on Solar Panels',$data);
array_drill_set('seo.keywords','solar, solar panels, solar power',$data);
array_drill_set('seo.description','Solar Panel Centric are dedicated to bringing solar panels to UK consumers at trade prices.',$data);

array_drill_set('seo.google.analytics.account','UA-31872896-1',$data);
array_drill_set('seo.google.webmastertools.account','fxi3B2YNo2XXO1-SMgTa1_Jt0vs7BkG9VZoBHtgYKoE',$data);

array_drill_set('_configuration.about.welcome','
Solar Panel Centric is a brand new website dedicated to bringing solar panels to UK consumers at trade prices.
We don\'t install solar panels, we don\'t want to you to sign up the latest government scheme or initiative. We just sell top quality solar panels at incredibly low prices.
<div style="color:red">We are expecting a fresh delivery of solar panels in the immediate future, please register your interest with us to be first in the queue when these arrive</div>',$data);

array_drill_set('_configuration.about.short','Solar Panel Centric is a brand new website dedicated to bringing solar panels to UK consumers at trade prices.
We don\'t install solar panels, we don\'t want to you to sign up the latest government scheme or initiative. We just sell top quality solar panels at incredibly low prices.',$data);

array_drill_set('_configuration.about.long','
**Solar Panel** Centric is dedicated to bringing you the very best possible prices on **solar panels**, delivered direct to your door, anywhere in the UK.
We are open to end users, consumers, and trade customers. If you would like to bulk buy solar panels from us, please contact our office.

**Solar panels** are a great way to generate electricity for free. With high efficiency ratings, our solar panels will help you make the most of even the gloomy British weather.
If you have a south facing roofspace, **solar panels** can be a great investment. However, we like to remind all of our customers that our **solar panels** are not just for the home, they can be used anywhere!

### What is a solar panel? ###
Wikipedia\'s definition of a solar panel is a "packaged, connected assembly of photovoltaic cells". The solar panel can be used as a component of a larger photovoltaic system to generate and supply electricity in commercial and residential applications. Each panel is rated by its DC output power under standard test conditions, and typically ranges from 100 to 450 watts. The efficiency of a panel determines the area of a panel given the same rated output - an 8% efficient 230 watt panel will have twice the area of a 16% efficient 230 watt panel.
Because a single solar panel can produce only a limited amount of power, most installations contain multiple panels. A photovoltaic system typically includes an array of solar panels, an inverter, and sometimes a battery and or solar tracker and interconnection wiring.

### Installing your solar panels ###
**Solar Panel** Centric do not install solar panels or solar panel systems and recommend you contact an installer in your area before undertaking any project.
In the UK, you may also need building permits or planning permission. [The Energy Saving Trust has published this guide](http://www.energysavingtrust.org.uk/Generate-your-own-energy/Solar-panels-PV/Choosing-a-site-and-getting-planning-permission "Click here to read the Energy Saving Trust\'s Guide")',$data);
?>
