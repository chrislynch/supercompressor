<?php

function action_search_go(&$data){
    /*
     * Go and search for some data.
     */
    $itemsperPage = array_drill_get('_configuration.pager.itemsperpage', $data);
    $maxPages = array_drill_get('_configuration.pager.maxpages', $data);
    $maxitems = $itemsperPage * $maxPages;
    
    // Build the initial search
    
    if (isset($_REQUEST['search'])){
        $selectSQL = 'SELECT sc.ID from sc_index sc JOIN sc_search scs ON scs.ID = sc.ID';
        $searchSQL = '';
    } else {
        $selectSQL = 'SELECT sc.ID from sc_index sc ';
        $searchSQL = '';
    }
    // Add in any keyword searches
    
    if (isset($_REQUEST['search'])){
        $SQL .= ' MATCH(s.search_text) AGAINST ("' . $_REQUEST['search'] .'") as Relevance,';
        $SQL .= ' MATCH(s.search_text) AGAINST ("' . $_REQUEST['search'] .'" WITH QUERY EXPANSION) as Expanded_Relevance'; 
        if ($warp_search_usePhraseMatch){ $SQL .= ',';}
        if ($warp_search_usePhraseMatch){ $SQL .= ' MATCH(search_text) AGAINST ("""' . $_REQUEST['search'] . '""" IN BOOLEAN MODE) as PhraseMatch ';}
    }
    
    if(isset($data['x'])){
        $x = $data['x'];
        if (strstr($x,'/')){
            $x = explode('/',$x);
            for ($index = 0; $index < count($x); $index = $index +2) {
                $_REQUEST['search.Category.' . $x[$index]] = $x[$index+1];
            }
        } else {
            $_REQUEST['search.Section'] = $data['x'];
        }
    }
    
    // Add on browses and filters
    $i = 1;
    foreach($_REQUEST as $key=>$value){
        $key = str_ireplace('_', '.', $key);
        if (strstr($key,'search.')){
            $joinID = 'scd' . $i;
            $searchKey = str_ireplace('search.', '', $key);
            $searchSQL .= ' JOIN sc_data ' . $joinID . ' ON ' . $joinID . '.ID = sc.ID AND ' . $joinID . '.Field = "' . $searchKey . '" AND ' . $joinID . '.Value = "' . $value . '" ';
            $i ++;
        }
    }
    $searchSQL .= ' WHERE Status = 1';
    $searchSQL .= ' GROUP BY sc.ID';
    
    $searchSQLLimit = ' LIMIT ' . $maxitems;
    
    // Run the SQL and 
    if(isset($_REQUEST['debug'])){ print $selectSQL . $searchSQL . $searchSQLLimit; }
    $searchData = mysql_query($selectSQL . $searchSQL . $searchSQLLimit);
    
    // Set up the pager
    
    $noofResults = mysql_num_rows($searchData);
    $noofPages = floor($noofResults / $itemsperPage);
    if (($noofResults % $itemsperPage) > 0) { $noofPages ++; }
    $data['pager'] = array('pages' => $noofPages );   
    
    if (isset($_GET['page'])){
        $pageStart =  ($_GET['page'] * $itemsperPage);
        $pageEnd = $itemsperPage + $pageStart;
    } else {
        $pageStart =  0;
        $pageEnd = $itemsperPage + $pageStart;
    }

    // $searchData = mysql_query($searchSQL . $searchSQLLimit);
    
    $item = 0;
    $data['search'] = array();
    $data['search']['results'] = array();
    
    while($record = mysql_fetch_assoc($searchData)){
        $data['search']['results'][$record['ID']] = $record['ID'];
        if ($item >= $pageStart && $item < $pageEnd){
            data_load($record['ID']);
        }
        $item++;
        
    }
}

?>
