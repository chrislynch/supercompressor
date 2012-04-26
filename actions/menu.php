<?php

function action_menu_go(&$data){
    /*
     * Load up our menus
     */
    
    // Make an empty menu array, as we may ask for it even if there are no menus.
    $data['menu'] = array();
    
    // Start loading up menus.
    $menusSQL = 'SELECT * FROM sc_index WHERE type LIKE class="container" AND status = 1';
    
    $data['menu']['categories'] = array();
    
    $data['menu']['categories'][] = array('link'=>'?action=search&category=ladders',
                                            'text'=>'Ladders');
    $data['menu']['categories'][] = array('link'=>'?action=search&category=ladders',
                                            'text'=>'Combination Ladders');
    $data['menu']['categories'][] = array('link'=>'?action=search&category=ladders',
                                            'text'=>'Step Ladders');
}

?>
