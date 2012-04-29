<?php

function action_seo_go(&$data){
    /* SEO */
    
    // Title
    if (sizeof($data['_content']) == 1){
        foreach($data['_content'] as $contentItem){
            array_drill_set('seo.title',$contentItem['Title'],$data);
        }
    } else {
        array_drill_set('seo.title','SEO Title',$data);
    }
    
    
    array_drill_set('seo.abstract','SEO Abstract',$data);
    array_drill_set('seo.keywords','SEO Keywords',$data);
    array_drill_set('seo.description','SEO Description',$data);
    array_drill_set('seo.copyright','SEO Copyright',$data);
    array_drill_set('seo.google.analytics.account','',$data);
}
?>
