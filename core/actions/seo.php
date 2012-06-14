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
        // What to do depends on our action
        // TODO: Decide if SEO action should do this, or if each action should do its own SEO. Centralisation makes sense...
        if (isset($_GET['action'])){
            switch($_GET['action']){
                case 'blog':
                    array_drill_append('seo.title',' Blog',$data);
                    break;
                case 'search':
                    $newTitle = '';
                    foreach($_GET as $key=>$value){
                        if (strstr($key,'search')){
                            if (strlen($newTitle) > 0) { $newTitle .= ' and '; }
                            $newTitle .= $value;
                        }
                    }
                    $newTitle = 'Searching for ' . $newTitle;
                    array_drill_set('seo.title',$newTitle,$data);
            }
        }
    }
}
?>
