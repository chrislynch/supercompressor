<?php

function action_search_go(&$data){
    /*
     * Go and search for some data.
     */
    
    $searchSQL = 'SELECT sc.ID from sc_index sc ';
    $i = 1;
    foreach($_REQUEST as $key=>$value){
        $key = str_ireplace('_', '.', $key);
        if (strstr($key,'search.')){
            $joinID = 'scd' . $i;
            $searchKey = str_ireplace('search.', '', $key);
            $searchSQL .= ' JOIN sc_data ' . $joinID . ' ON ' . $joinID . '.ID = sc.ID AND Field = "' . $searchKey . '" AND Value = "' . $value . '" ';
            $i ++;
        }
    }
    $searchSQL .= ' WHERE Status = 1';
    $searchSQL .= ' GROUP BY sc.ID';

    if (isset($_GET['debug'])) { print $searchSQL; }
    
    $searchData = mysql_query($searchSQL);
    while($record = mysql_fetch_assoc($searchData)){
        data_load($record['ID']);
    }
}

?>
