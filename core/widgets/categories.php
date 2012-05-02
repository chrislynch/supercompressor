<?php

function widget_categories_go($widget_field,$widget_params,&$data){
    /*
     * Output a list of all the categories.
     * Get this list from the config file
     */
    
    $return = '';
    foreach($data['_configuration']['categories'] as $category=>$categorySettings){
        $return .= '<h3>' . $categorySettings['heading'] . '</h3>';
        $return .= '<ul>@@list.menu.' . $categorySettings['menu'] . '?li-class=menu-item@@</ul>';
    }
    return $return;
}

?>
