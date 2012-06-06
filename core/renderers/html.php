<?php

function renderer_html_go(&$data){
    global $dir_template;
    $return = '';
    foreach($data['templates'] as $templateID => $templatefile){
        
        if (substr($templatefile,0,1) == '/') {
            $templatefile = substr($templatefile,1) . '.html';
        } else {
            $templatefile = $dir_template . $templatefile . '.html';
        }
        $templatefile = find_include($templatefile);
        
        $returnTemplate = render_template($templatefile, $data);
        
        if (strstr($templatefile,'/content/')){
            $returnTemplate = Markdown($returnTemplate);
        }
        
        $return .= $returnTemplate;
    }
    return $return;
}

?>
