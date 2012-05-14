<?php

function widget_categories_go($widget_field,$widget_params,&$data){
    /*
     * Output a list of all the categories.
     * Get this list from the config file
     */
    
    $return = '';
    if (isset($data['menu']['filter']) && sizeof($data['menu']['filter']) > 0){
        foreach($data['_configuration']['categories'] as $category=>$categorySettings){
            $return .= '<h3>Filter By ' . $categorySettings['heading'] . '</h3>';
            $return .= '<ul>@@list.menu.filter.' . $categorySettings['menu'] . '?li-class=menu-item@@</ul>';
        }
    }
    
    
    foreach($data['_configuration']['categories'] as $category=>$categorySettings){
        $return .= '<h3>Browse By ' . $categorySettings['heading'] . '</h3>';
        $return .= '<ul>@@list.menu.browse.' . $categorySettings['menu'] . '?li-class=menu-item@@</ul>';
    }
    return $return;
}

?>
