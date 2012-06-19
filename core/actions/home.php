<?php
function action_home_go(&$data){
    /* 
    * Homepage action 
    * ===============
    * Load up all the content for the homepage
    */

    $homes = mysql_query('SELECT sd_Title.Value as Title, sd_Body.Value as Body
                            FROM sc_index sci 
                            JOIN sc_data sd_Title ON sd_Title.ID = sci.ID AND sd_Title.Field = "Title"
                            JOIN sc_data sd_Body ON sd_Body.ID = sci.ID AND sd_Body.Field = "Body"
                            JOIN sc_data sd_Order ON sd_Order.ID = sci.ID AND sd_Order.Field = "Order"
                            WHERE sci.Type = "Home"
                            ORDER BY sd_Order.Value');
    
    while($home = mysql_fetch_assoc($homes)){
        array_drill_set('home.' . $home['Title'] . '.Title',$home['Title'],$data);
        array_drill_set('home.' . $home['Title'] . '.Body',$home['Body'],$data);
        array_drill_set('home.' . $home['Title'] . '.Products',array(),$data);
        
        $records = mysql_query('SELECT sci.ID
                                FROM sc_index sci
                                JOIN sc_data sd1 ON sd1.ID = sci.ID AND sd1.`Field` = "Home"
                                WHERE sd1.`Value` = "' . $home['Title'] . '"');
        $data['_content']['home'] = array();
        while($record = mysql_fetch_assoc($records)){
            data_load($record['ID'],$data['home'][$home['Title']]['Products']);
        }
    }
    
    
    
    
}

?>
