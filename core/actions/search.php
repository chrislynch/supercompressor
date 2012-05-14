<?php

function action_search_go(&$data){
    /*
     * Go and search for some data.
     */
    $itemsperPage = array_drill_get('_configuration.pager.itemsperpage', $data);
    $maxPages = array_drill_get('_configuration.pager.maxpages', $data);
    $maxitems = $itemsperPage * $maxPages;
    
    // Build the initial search
    $searchSQL = 'SELECT sc.ID from sc_index sc ';
    
    // Add in any keyword searches
    /*
     * $SQL .= ' MATCH(s.search_text) AGAINST ("' . $keywords .'") as Relevance,';
				$SQL .= ' MATCH(s.search_text) AGAINST ("' . $keywords .'" WITH QUERY EXPANSION) as Expanded_Relevance'; 
				if ($warp_search_usePhraseMatch){ $SQL .= ',';}
			}
			if ($warp_search_usePhraseMatch){ $SQL .= ' MATCH(search_text) AGAINST ("""' . $keywords . '""" IN BOOLEAN MODE) as PhraseMatch ';}
     */
    
    // Add on browses and filters
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
    
    $searchSQLLimit = ' LIMIT ' . $maxitems;
    
    // Run the SQL and get the size of the resultset
    $searchData = mysql_query($searchSQL . $searchSQLLimit);
    $noofResults = mysql_num_rows($searchData);
    $noofPages = floor($noofResults / $itemsperPage);
    if (($noofResults % $itemsperPage) > 0) { $noofPages ++; }
    $data['pager'] = array('pages' => $noofPages );
    
    // Set up the pager
    if (isset($_GET['page'])){
        $searchSQLLimit = ' LIMIT ' . ($_GET['page'] * $itemsperPage) . ',' . $itemsperPage;
    } else {
        $searchSQLLimit = ' LIMIT 0,' . $itemsperPage;
    }

    // Now run the SQL with the limits in place
    if (isset($_GET['debug'])) { print $searchSQL . $searchSQLLimit; }
    $searchData = mysql_query($searchSQL . $searchSQLLimit);
    
    while($record = mysql_fetch_assoc($searchData)){
        data_load($record['ID']);
    }
}

?>
