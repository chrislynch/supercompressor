<?php
/*
 * Open up a CSV file and import it into the system.
 */

$csvfile = fopen('import.csv','r');

$fields = fgetcsv($csvfile);

while($csvrecord = fgetcsv($csvfile)){
    $record = array();
    for ($index = 0; $index < sizeof($fields); $index++) {
        array_drill_set($fields[$index], $csvrecord[$index], $record);
    }
    data_save($record);
}

include 'actions/home.php';

?>
