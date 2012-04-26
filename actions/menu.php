<?php

function action_menu_go(&$data){
    /*
     * Load up our menus
     */
    global $db;
    
    // Make an empty menu array, as we may ask for it even if there are no menus.
    $data['menu'] = array();
    $data['menu']['Products'] = array();
    $data['menu']['Brands'] = array();
    
    // Start loading up menus.
    $menusSQL = 'SELECT value as category,count(value) as members
                 FROM   sc_data 
                 WHERE  field = "Category.Product"
                 GROUP BY value
                 ORDER BY value';
    $menusData = mysql_query($menusSQL,$db);
       
    while($menuItem = mysql_fetch_assoc($menusData)){
        $data['menu']['Products'][] = array('link'=>'?action=search&search.Category.Product=' . $menuItem['category'],
                                            'text'=> $menuItem['category'] . ' (' . $menuItem['members'] . ')');
    }
    
    $menusSQL = 'SELECT value as category,count(value) as members
                 FROM   sc_data 
                 WHERE  field = "Category.Brand"
                 GROUP BY value
                 ORDER BY value';
    $menusData = mysql_query($menusSQL,$db);
       
    while($menuItem = mysql_fetch_assoc($menusData)){
        $data['menu']['Brands'][] = array('link'=>'?action=search&search.Category.Brand=' . $menuItem['category'],
                                            'text'=> $menuItem['category'] . ' (' . $menuItem['members'] . ')');
    }
    
}

?>
