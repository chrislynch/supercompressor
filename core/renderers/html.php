<?php

function renderer_html_go(&$data){
    global $dir_template;
    $return = '';
    foreach($data['templates'] as $templateID => $templatefile){
        if (substr($templatefile,0,1) == '/') {
            $return .= render_template(substr($templatefile,1) . '.html', $data);
        } else {
            $return .= render_template($dir_template . $templatefile . '.html', $data);
        }
    }
    return $return;
}

?>
