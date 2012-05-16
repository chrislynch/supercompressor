<?php

function widget_categories_go($widget_field,$widget_params,&$data){
    /*
     * Output a list of all the categories.
     * Get this list from the config file
     */
    
    $return = '';
    
    if (isset($data['menu']['filter']) && sizeof($data['menu']['filter']) > 0){
        foreach($data['menu']['filter'] as $category=>$categoryData){
            if (sizeof($categoryData) > 1){
                $return .= '<h3>Filter By ' . $category . '</h3>';
                $return .= '<ul>@@list.menu.filter.' . $category . '?li-class=menu-item@@</ul>';
            }
        }
    }
    
    if ($return == ''){
        foreach($data['menu']['browse'] as $category=>$categorydata){
            if ($category !== 'Sections'){
                $return .= '<h3>Browse By ' . $category . '</h3>';
                $return .= '<ul>@@list.menu.browse.' . $category . '?li-class=menu-item@@</ul>';
            }
        }
    }
    
    return $return;
}

?>
