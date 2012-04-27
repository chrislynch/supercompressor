<?php

function action_menu_go(&$data){
    /*
     * Load up our menus
     */
    global $db;
    
    // Make an empty menu array, as we may ask for it even if there are no menus.
    $data['menu'] = array();
    $menus = array('Products'  => 'Category.Product',
                    'Brands'   => 'Category.Brand',
                    'Sections' => 'Section');
    
    foreach($menus as $menuKey => $menuField){
        
        $data['menu'][$menuKey] = array();
        
        $menusSQL = 'SELECT value as category,count(value) as members
                 FROM   sc_data 
                 WHERE  field = "' . $menuField . '"
                 GROUP BY value
                 ORDER BY value';
        $menusData = mysql_query($menusSQL,$db);
        
        while($menuItem = mysql_fetch_assoc($menusData)){
            $data['menu'][$menuKey][] = array('link'=>'?action=search&search.' . $menuField . '=' . $menuItem['category'],
                                                'text'=> $menuItem['category'] . ' (' . $menuItem['members'] . ')');
        }
    }
    
}

?>
