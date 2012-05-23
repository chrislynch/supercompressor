<?php

function action_seo_go(&$data){
    /* SEO */
    
    // Title & Description
    if (sizeof($data['_content']) == 1){
        foreach($data['_content'] as $ID => $contentItem){
            if (is_numeric($ID)){
                array_drill_set('seo.title',@$contentItem['Title'],$data);
                array_drill_set('seo.description',@$contentItem['Teaser'],$data);
                array_drill_set('seo.canonical','http://' . $data['_configuration']['site']['domain'] . '/' . @$contentItem['SEO']['Canonical'],$data);
            }
        }
    } else {    
        
    }
}
?>
