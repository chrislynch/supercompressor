<?php
/*
 * Open up a CSV file and import it into the system.
 */

function action_install_import_go(&$data){
    
    cliPrint('Import Started');
    
    $csvfile = fopen('import.csv','r');

    $fields = fgetcsv($csvfile);
    $i = 1;
    while($csvrecord = fgetcsv($csvfile)){
        $record = array();
        for ($index = 0; $index < sizeof($fields); $index++) {
            array_drill_set($fields[$index], $csvrecord[$index], $record);
        }
        data_save($record);
        cliPrint('Saved record ID ' . $record['ID']);
        $i++;
    }

    cliPrint('Import Complete');
}

function cliPrint($message){
    print $message . chr(10) . chr(13);
}

?>
