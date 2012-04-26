<?php
/* 
 * Homepage action 
 * ===============
 * Load up all the content for the homepage
 */

$records = mysql_query('SELECT ID FROM sc_index LIMIT 20');
while($record = mysql_fetch_assoc($records)){
    data_load($record['ID']);
}

?>
