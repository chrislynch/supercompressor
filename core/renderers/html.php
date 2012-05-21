<?php

function renderer_html_go(&$data){
    global $dir_template;
    $return = '';
    foreach($data['templates'] as $templateID => $templatefile){
        $return .= render_template($dir_template . $templatefile . '.html', $data);
    }
    return $return;
}

?>
