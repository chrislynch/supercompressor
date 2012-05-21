<?php
function action_home_go(&$data){
    /* 
    * Homepage action 
    * ===============
    * Load up all the content for the homepage
    */

    $records = mysql_query('SELECT sci.ID
                            FROM sc_index sci
                            JOIN sc_data sd1 ON sd1.ID = sci.ID AND sd1.`Field` = "Product.SellPrice"
                            JOIN sc_data sd2 ON sd2.ID = sci.ID AND sd2.`Field` = "Product.ListPrice"
                            ORDER BY (sd2.`Value` - sd1.`Value`) DESC
                            LIMIT 4');
    $data['_content']['sale'] = array();
    while($record = mysql_fetch_assoc($records)){
        data_load($record['ID'],$data['_content']['sale']);
    }
    
    $records = mysql_query('SELECT sci.ID
                            FROM sc_index sci
                            JOIN sc_data sd1 ON sd1.ID = sci.ID AND sd1.`Field` = "Product.SellPrice"
                            ORDER BY sd1.`Value` ASC
                            LIMIT 4');
    $data['_content']['budget'] = array();
    while($record = mysql_fetch_assoc($records)){
        data_load($record['ID'],$data['_content']['budget']);
    }
    
}

?>
