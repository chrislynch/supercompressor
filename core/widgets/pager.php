<?php

function widget_pager_go($widget_field,$widget_params,&$data){
    if (isset($data['pager']['pages'])){
        $pagecount = $data['pager']['pages'];
        
        $pager = 'Pages:<ul>';
        for ($i = 0; $i < $pagecount; $i++) {
            $pager .= '<li><a href="' . url_build(array('page'=>$i)) . '">' . ($i+1) . '</a></li>';
        }
        $pager .= '</ul>';

        return $pager;
        
    } else {
        return '';
    }
    
    
}
?>
