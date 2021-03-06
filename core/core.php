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

function array_drill_get($array_path,$data,$default = ''){
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
            return $default;
        }
    } else {
        if (isset($data[$array_path])){
            return $data[$array_path];
        } else {
            return $default;
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

function array_drill_append($array_path,$value,&$data){
    $newValue = array_drill_get($array_path, $data) . $value;
    array_drill_set($array_path,$newValue,$data);
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
            array_drill_set($field, $value, $datarecord);
        }
    }
    
    $records = mysql_query('SELECT * FROM sc_data WHERE ID = ' . $ID . ' ');
    while ($record = mysql_fetch_assoc($records)){
        array_drill_set($record['Field'], $record['Value'], $datarecord);
    }
    
    $datatypeInclude = find_include('datatypes/' . strtolower($datarecord['Type']) . '.php');
    if (file_exists($datatypeInclude)){
        include_once($datatypeInclude);
        $parameters = array( &$datarecord );
        call_user_func_array('datatype_' . str_ireplace('/', '_', $datarecord['Type'])  . '_load',$parameters);
    }
    
    return $datarecord;
    
}

function data_save($dataitem){
    /*
     * Save this data item to the database
     */
    global $db;
    
    // We must have a unique URL. If the URL value is blank, URLify the title
    if (strlen($dataitem['URL']) == 0){
        $dataitem['URL'] = url_ify($dataitem['Title']);
    }
    
    // Place the item's core into the index.    
    $SQL = 'INSERT INTO sc_index 
                    SET ID = ' . $dataitem['ID'] . ',
                        Type = "' . $dataitem['Type'] . '",
                        URL = "' . $dataitem['URL'] . '",
                        Status = ' . $dataitem['Status'] . '
                 ON DUPLICATE KEY UPDATE
                        Type = "' . $dataitem['Type'] . '",
                        URL = "' . $dataitem['URL'] . '",
                        Status = ' . $dataitem['Status'];
    // print_r($SQL);
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
            $SQL = 'INSERT INTO sc_data
                            SET ID = ' . $ID . ',
                                Field = "' . $datakey . '",
                                Value = "' . $datavalue . '"';
            // print_r($SQL);
            mysql_query($SQL,$db);  
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
    $data['x'] = $url;
    
    // Check to see if this is a request for an XML file
    if (strstr(strtolower($url),'.xml')){
        return 'xml';
    } else if (strstr(strtolower($url),'.txt')){
        return 'txt';
    } else if (strstr(strtolower($url),'.html')){
        return '404';
    } else if (isset($_REQUEST['action'])){
        if (file_exists('core/actions/' . $_REQUEST['action'] . '.php')){
            return $_REQUEST['action'];
        } else {
            return '403';
        }
    } else if (strlen($url) > 0){
        // Try to match the start of the URL to an existing action
        $action_text = explode('/',$url); 
        $action_text = $action_text[0];
        if (file_exists('core/actions/' . $action_text . '.php')){
            if (file_exists('templates/default/actions/' . $action_text . '.html')){
                return $action_text;
            } else {
                return '403';
            }
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
        
    } else {
        // With the obvious exception of a blank URL, which takes us home.
        return 'home';
    }
}

function url_build($params,$url = '', $keepparameters = TRUE){
    // Build a URL with a query string, keeping current parameters or throwing them away
    $return = $url . '?';
    $urlparams = '';
    $suppliedparams = '';
	
    if ($keepparameters){
        foreach($_GET as $key=>$value){
            if (!(key_exists($key, $params)) && $key !== 'x'){
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

function find_include($findfile){
    return include_find($findfile);
}

function include_find($findfile){
    // Check to see if this file exists in the site specific directory as an override. If not, we load it from core.
    global $data;
    $domaindir = array_drill_get('_configuration.site.domaindir',$data,'');
    if ($domaindir !== '' && file_exists($domaindir . $findfile)){
        return $domaindir . $findfile;
    } else {
        if (file_exists('core/' . $findfile)){
            return 'core/' . $findfile;
        } else {
            if (strstr($findfile,'templates')){
                $findfile = explode('/',$findfile);
                if ($findfile[1] !== 'default'){ 
                    $findfile[1] = 'default';
                    $findfile = implode('/',$findfile);
                    return include_find($findfile);
                } else {
                    return FALSE;
                }
            } else {
                return FALSE;
            }
        }
    }        
}

function content_scandir($scandir,$action,$limit = -1,$offset = 0){
    global $data;
    $return = array();
    $scandir = array_drill_get('_configuration.site.domaindir',$data) . $scandir;
    
    $sort = 0; // SCANDIR_SORT_ASCENDING;
    if ($action == 'blog') { $sort = 1; } // SCANDIR_SORT_DESCENDING;
    
    if (file_exists($scandir)){    
        $pages = scandir($scandir,$sort);
        foreach($pages as $page){
            if (!is_dir($page)){
                $offset --;
                if ($offset < 0){
                    $page = explode('-',$page);
                    if (is_numeric($page[0])){
                        $page = implode('-',$page);
                        $pageFile = explode('.',$page);
                        $pageFile = $pageFile[0];

                        $page = explode('-',$page);
                        array_shift($page);           
                        $page = implode('-',$page);
                        $pageTitle = explode('.',$page);
                        $pageTitle = $pageTitle[0];
                        $pageTitle = ucwords(str_ireplace('-',' ',$pageTitle));

                        $return = array('link' => "?action=$action&$action=" . $pageFile,
                                                        'text' => $pageTitle);
                        $limit --;
                        if ($limit == 0){
                            return $return;
                        }
                    }    
                }
            }
        }
    }
    return $return;
}

?>
