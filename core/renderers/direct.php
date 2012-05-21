<?php

function renderer_direct_go(&$data){
    /*
     * The direct render just prints whatever it is given
     */
    $return = '';
    foreach($data['_content'] as $ID => $content){
        $return .= print_r($content);
    }
    return $return;
}

?>
