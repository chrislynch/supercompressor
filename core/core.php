<?php


function render_template($template,$data){
    /*
     * Open up a template and work your way through all the widgets contained within
     */
    $template = file_get_contents($template);
    $widget_start = stripos($template, '@@');
    while(!($widget_start === FALSE)){
        $widget_end = stripos($template,'@@',$widget_start + 2);
        if (!($widget_end === FALSE)){
            $widget = substr($template,$widget_start+2,$widget_end - ($widget_start + 2));
            $widget_return = render_widget($widget,$data);
            $widget = '@@' . $widget . '@@';
            $template = str_replace($widget, $widget_return, $template);
        
            $widget_start = stripos($template, '@@');
        } else {
            // We have a mismatched set of @@ characters. Stop processing widgets
            $widget_start = FALSE;
        }
    }
    
    return $template;
}

function array_drill_get($array_path,$data){
    /*
     * Drill down through our array until we find the item we want or run out of array
     */
    if (strstr($array_path,'.')){
        // Drilling down at least one more level
        $array_path = explode('.',$array_path);
        $array_item = array_shift($array_path);
        $array_path = implode('.',$array_path);
        if (isset($data[$array_item])){
            return array_drill_get($array_path,$data[$array_item]);
        } else {
            return '';
        }
    } else {
        if (isset($data[$array_path])){
            return $data[$array_path];
        } else {
            return '';
        }
    }
}

function array_drill_set($array_path,$value,&$data){
    /*
     * Set an item as deep down in our array as we want.
     */
    if (strstr($array_path,'.')){
        $array_path = explode('.',$array_path);
        $data_target =& $data;
        
        while(sizeof($array_path) > 1){
            $array_path_item = array_shift($array_path);
            if (!isset($data_target[$array_path_item])){
                $data_target[$array_path_item] = array();
            }
            $data_target =& $data_target[$array_path_item];
        }
        $data_target[$array_path[0]] = $value;
    } else {
        $data[$array_path] = $value;
    }
}

function data_load($ID, &$todata = FALSE){
    /*
     * Load an item of data from the database
     */
    global $db;
    global $data;
    
    if ($todata === FALSE) { $todata =& $data['_content']; }
    
    $todata[$ID] = array();
    $datarecord =& $todata[$ID];
    
    $records = mysql_query('SELECT * FROM sc_index WHERE ID = ' . $ID . ' ');
    while ($record = mysql_fetch_assoc($records)){
        foreach($record as $field=>$value){
            if ($field == 'URL') { $value = '?x=' . $value; }
            array_drill_set($field, $value, $datarecord);
        }
    }
    
    $records = mysql_query('SELECT * FROM sc_data WHERE ID = ' . $ID . ' ');
    while ($record = mysql_fetch_assoc($records)){
        if ($record['Field'] == 'URL') { $record['Value'] = '?x=' . $record['Value']; }
        array_drill_set($record['Field'], $record['Value'], $datarecord);
    }
    
    $datatypeInclude = 'core/datatypes/' . strtolower($datarecord['Type']) . '.php';
    if (file_exists($datatypeInclude)){
        include_once($datatypeInclude);
        $parameters = array( &$datarecord );
        call_user_func_array('datatype_' . str_ireplace('/', '_', $datarecord['Type'])  . '_load',$parameters);
    }
    
}

function data_save($dataitem){
    /*
     * Save this data item to the database
     */
    global $db;
    
    // Place the item's core into the index.    
    $SQL = 'INSERT INTO sc_index 
                    SET ID = ' . $dataitem['ID'] . ',
                        Type = "' . $dataitem['Type'] . '",
                        URL = "' . $dataitem['URL'] . '",
                        Status = ' . $dataitem['Status'] . '
                 ON DUPLICATE KEY UPDATE
                        ID = ' . $dataitem['ID'] . ',
                        Type = "' . $dataitem['Type'] . '",
                        URL = "' . $dataitem['URL'] . '",
                        Status = ' . $dataitem['Status'];
    
    mysql_query($SQL,$db);
    
    // Now delete all items from the sc_data table
    mysql_query('DELETE FROM sc_data WHERE ID = ' . $dataitem['ID']);
    
    // Then insert each individual piece of data, traversing our way down the array
    foreach($dataitem as $datakey => $datavalue){
        data_save_item($dataitem['ID'],$datakey,$datavalue);
    }   
}

function data_save_item($ID,$datakey,$datavalue){
    global $db;
    // $sc_index_array = array('ID','Type','URL','Status');
    $sc_index_array = array(); // Changed core to make everything available to search
    
    if (in_array($datakey, $sc_index_array)){
        // This item is part of the sc_index core, and so we can ignore it
    } else {
        // If this item is in array, we need to work our way down inside it. 
        // If not, then we have found something to save
        if (is_array($datavalue)){
            // $datakey .= '.' . $datakey;
            foreach($datavalue as $datavaluekey => $datavaluevalue){
                data_save_item($ID,$datakey . '.' . $datavaluekey,$datavaluevalue);
            }
        } else {
            mysql_query('INSERT INTO sc_data
                            SET ID = ' . $ID . ',
                                Field = "' . $datakey . '",
                                Value = "' . $datavalue . '"',$db);  
        }
    }
}

function data_index($ID = 0){
    /*
     * Index some, or all, of our data.
     */
    
    // Clear any existing index data.
    
}

function url_action($url,&$data){
    // Turn a URL into an action.
    // Provide access to $data just in case.
    
    // Check to see if this is a request for an XML file
    if (strstr(strtolower($url),'.xml')){
        return 'xml';
    } else {
        // Try to find the URL as a clean URL in the index
        $urlSQL = 'SELECT ID FROM sc_index WHERE URL = "' . $url . '"';
        $urlData = mysql_query($urlSQL);
        if (mysql_num_rows($urlData) == 1){
            // We have a single item match
            while ($urlItem = mysql_fetch_assoc($urlData)){
                data_load($urlItem['ID']);
            }
            // Return the action that we are going to run.
            return 'item';
        } else {
            // For now, assume that *anything else* is a search
            return 'search';
        }
    }
}

function url_build($params,$url = '', $keepparameters = TRUE){
    // Build a URL with a query string, keeping current parameters or throwing them away
    $return = $url . '?';
    $urlparams = '';
    $suppliedparams = '';
	
    if ($keepparameters){
        foreach($_GET as $key=>$value){
            if (!(key_exists($key, $params)) && $key !== 'q'){
                $key = str_ireplace('_', '.', $key);
                $urlparams .= '&' . $key . '=' . urlencode($value); 
            }
        }	
    }
	
    foreach($params as $key=>$value){
        if(strlen($value) > 0){
            $suppliedparams .= '&' . $key . '=' . $value;
        }
    }
	
    $return .= $urlparams . $suppliedparams;
	
    return $return;
}

function url_ify($url){
    $url = str_ireplace(' ', '-', $url);
    return $url;
}


?>
