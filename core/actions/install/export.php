<?php
/*
 * Open up a CSV file and import it into the system.
 */

function action_install_export_go(&$data){
    global $db;
    
    cliPrint('Export Started');
    
    // Ascertain size of CSV file
    $csvfile = fopen(array_drill_get('_configuration.site.domaindir',$data) . 'export.csv','w');
    
    // Load up a list of all the fields that we have
    $fields = array('ID' => 'ID','Type' => 'Type', 'URL' => 'URL' , 'Status' => 'Status');
    $fieldsData = mysql_query('SELECT DISTINCT(Field) FROM sc_data',$db);
    while($field = mysql_fetch_array($fieldsData)){
        $fields[$field[0]] = $field[0];
    }
    fputcsv($csvfile,$fields);
    
    // Now load all the data and start exporting it
    $index = mysql_query('SELECT * FROM sc_index ORDER BY ID ASC',$db);
    
    // Exporting records
    while($indexRecord = mysql_fetch_assoc($index)){
        $record = array();
        foreach($fields as $field){
            $record[$field] = '';
        }
        $data = mysql_query('SELECT Field,Value FROM sc_data WHERE ID = ' . $indexRecord['ID'], $db);
        while($dataRecord = mysql_fetch_assoc($data)){
            $record[$dataRecord['Field']] = $dataRecord['Value'];
        }
        fputcsv($csvfile, $record);
    }
    
    // Close file
    fclose($csvfile);

    cliPrint('Export Complete');
}

function cliPrint($message){
    print $message . chr(10) . chr(13);
}

?>
