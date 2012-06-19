<?php

function action_menu_go(&$data){
    /*
     * Load up our menus
     */
    global $db;
    
    // Make an empty menu array, as we may ask for it even if there are no menus.
    $data['menu'] = array();
    $data['menu']['browse'] = array();
    $data['menu']['filter'] = array();
    $data['menu']['bestsellers'] = array();
    
    $menus = array();
    $menusData = mysql_query('SELECT SUBSTRING_INDEX(Field,".",-1), Field FROM sc_data WHERE Field LIKE "Category.%" GROUP BY Field');
    while($menu = mysql_fetch_array($menusData)){
        $menus[$menu[0]] = $menu[1];
    }
    $menus['Sections'] = 'Section';
    ksort($menus);
    
    // Build code for regular menus
    foreach($menus as $menuKey => $menuField){
        
        $data['menu']['browse'][$menuKey] = array();
        
        $menusSQL = 'SELECT value as category,count(value) as members
                     FROM   sc_data 
                     JOIN   sc_index ON sc_index.ID = sc_data.ID and sc_index.Type = "Product"
                     WHERE  field = "' . $menuField . '"';
        $menusSQL .= 'GROUP BY value ' ;
        if ($menuKey == 'Sections') {
            $menusSQL .= 'ORDER BY count(value) DESC';
        } else {
            $menusSQL .= 'ORDER BY value';
        }
                     
        $menusData = mysql_query($menusSQL,$db);
        
        while($menuItem = mysql_fetch_assoc($menusData)){
            $data['menu']['browse'][$menuKey][] = array('link'=>'' .  /* $menuField . */ '/' . $menuItem['category'],
                                                'text'=> $menuItem['category']);
        }
    }
    
    if ($data['_configuration']['actions'][0] == 'search') {
        // We ALSO need to produce filters, to run ahead of our browses       
        $IDs = implode(',',$data['search']['results']);
        
        foreach($menus as $menuKey => $menuField){
            if ($menuKey !== 'Sections'){
                $data['filter'][$menuKey] = array();
            }
            
            $menusSQL = 'SELECT value as category,count(value) as members
                         FROM   sc_data 
                         JOIN   sc_index ON sc_index.ID = sc_data.ID and sc_index.Type = "Product"
                         WHERE  field = "' . $menuField . '"';
            $menusSQL .= ' AND sc_data.ID IN (' . $IDs . ') ';
            $menusSQL .= ' GROUP BY value ' ;
            $menusSQL .= ' ORDER BY value';
            
            $menusData = mysql_query($menusSQL,$db);

            while($menuItem = mysql_fetch_assoc($menusData)){
                $data['menu']['filter'][$menuKey][] = array('link'=>url_build(array('action' => 'search',
                                                                                    'search.' . $menuField => $menuItem['category']),'',TRUE),
                                                            'text'=> $menuItem['category'] . ' (' . $menuItem['members'] . ')');
            }
        }
    }
    
    // Other menus that are always around
    // Build code for regular menus
        
    $menusSQL = 'SELECT i.ID,i.URL as URL,n.Value as Name
                    FROM   sc_data d
                    JOIN   sc_index i ON i.ID = d.ID
                    JOIN   sc_data n ON n.ID = i.ID AND n.Field = "Title"
                    WHERE  d.field = "Product.SellPrice"
                    ORDER BY d.Value DESC
                    LIMIT 10';
    $menusData = mysql_query($menusSQL,$db);

    while($menuItem = mysql_fetch_assoc($menusData)){
        $data['menu']['bestsellers'][$menuItem['ID']] = array('link'=>'/' . $menuItem['URL'],
                                                              'text'=> $menuItem['Name']);
    }
    // shuffle($data['menu']['bestsellers']);
    
    // Build the "Help and Advice" menu
    // Use Kirby style numbering on items to understand their order and whether we should display them or not.
    $data['menu']['pages'][] = content_scandir('content/pages','page');
    $data['menu']['blog'][] = content_scandir('content/blog','blog',10);
}

?>
