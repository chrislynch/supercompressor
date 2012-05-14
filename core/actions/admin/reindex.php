<?php

function action_admin_reindex_go(&$data){
    global $db;
    
    mysql_query('DELETE FROM sc_search');
    mysql_query('INSERT INTO sc_search(')
}

?>
