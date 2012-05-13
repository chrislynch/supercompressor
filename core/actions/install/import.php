<?php
/*
 * Open up a CSV file and import it into the system.
 */

function action_install_import_go(&$data){
    global $db;
    
    cliPrint('Import Started');
    
    // Ascertain size of CSV file
    $csvfile = fopen('import.csv','r');
    $rowcount = -1;
    while($csvrecord = fgetcsv($csvfile)){
        $rowcount ++;
    }
    rewind($csvfile);

    // Import every item in CSV file
    $fields = fgetcsv($csvfile);
    $i = 1;
    $importedrowcount = 0;
    while($csvrecord = fgetcsv($csvfile)){
        $record = array();
        for ($index = 0; $index < sizeof($fields); $index++) {
            array_drill_set($fields[$index], $csvrecord[$index], $record);
        }
        data_save($record);
        $importedrowcount ++;
        $importPC = round(($importedrowcount / $rowcount) * 100,2);
        cliPrint('Saved record ID ' . $record['ID'] . ' Import is ' . $importPC . '% complete');
        $i++;
    }

    cliPrint('Stripping field_ and _value from Field names in sc_data');
    mysql_query('UPDATE sc_data SET Field = REPLACE(Field,"field_","Specification.")',$db);
    mysql_query('UPDATE sc_data SET Field = REPLACE(Field,"_value","")',$db);
    
    // Now build category records
    $categoryTypeData = mysql_query('SELECT DISTINCT(Field) FROM sc_data WHERE Field LIKE "Category.%"');
    while($categoryType = mysql_fetch_array($categoryTypeData)){
        $categoryMemberData = mysql_query('SELECT DISTINCT(Value) FROM sc_data WHERE Field = "' . $categoryType[0] . '"');
        while($categoryMember = mysql_fetch_array($categoryMemberData)){
            $record = array();
            $record['ID'] = 0;
            $record['Type'] = 'Category';
            $record['URL'] = url_ify($categoryMember[0]);
            $record['Status'] = 1;
            $record[$categoryType[0]] = $categoryMember[0];
            data_save($record);
        }
    }
    
    
    cliPrint('Import Complete');
}

function cliPrint($message){
    print $message . chr(10) . chr(13);
}

?>
