<?php
function action_home_go(&$data){
    /* 
 * Homepage action 
 * ===============
 * Load up all the content for the homepage
 */

    $records = mysql_query('SELECT ID FROM sc_index');
    while($record = mysql_fetch_assoc($records)){
        data_load($record['ID']);
    }
}


?>
