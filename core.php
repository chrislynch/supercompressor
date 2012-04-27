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

function render_widget($widget,$data, $recursion_depth = 0){
    /*
     * Render a given widget against the given data array
     */
    $widget = explode('?',$widget);
    $widget_type = $widget[0];
    if (isset($widget[1])){
        $widget_param_string = $widget[1];
    } else {
        $widget_param_string = '';
    }
    
    $widget_type_array = explode('.',$widget_type);
    $widget_type = array_shift($widget_type_array);
    $widget_field = implode('.',$widget_type_array);
                
    $widget_param_string = explode('&',$widget_param_string);
    $widget_params = array();
    foreach($widget_param_string as $widget_param_string_item){
        if (strstr($widget_param_string_item,'=')){
            $widget_param_string_item = explode('=',$widget_param_string_item);
            $widget_params[$widget_param_string_item[0]] = $widget_param_string_item[1];
        }
    }
    
    $return = '';
    
    switch ($widget_type){
        case 'content':
        case 'data':
            $return = array_drill_get($widget_field,$data);
            break;
        case 'loop':
            global $dir_template;
            $loopcontent = array_drill_get($widget_field,$data);
            if (is_array($loopcontent)){
                foreach($loopcontent as $loopcontentID => $loopcontentitem){
                    $return .= render_template($dir_template . $widget_params['template'], $loopcontentitem);
                }
            }
            break;
        case 'list':
            $listcontent = array_drill_get($widget_field,$data);
            if (isset($widget_params['ul-length'])) { $listlength = $widget_params['ul-length']; } else {$listlength = -1;}
            foreach($listcontent as $listcontentID => $listcontentitem){
                $return .= '<li class="' . @$widget_params['li-class'] . '"><a href="' . $listcontentitem['link'] . '">' . $listcontentitem['text'] . '</a></li>';
                $listlength --;
                if ($listlength == 0){ break;}
            }
            break;
        default:
            $return = '';
    }
    
    if(isset($widget_params['default'])){
        if (strlen($return) == 0){
            $return = $widget_params['default'];
        }
    }
    if(isset($widget_params['prefix'])){
        if (strlen($return) > 0){
            $return = $widget_params['prefix'] . $return;
        }
    }
    if(isset($widget_params['postfix'])){
        if (strlen($return) > 0){
            $return = $return . $widget_params['postfix'];
        }
    }
    
    return $return;
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
        $data_target =& $data[$array_path[0]];

        while(sizeof($array_path) > 1){
            $array_path_item = array_shift($array_path);
            if (!isset($data[$array_path_item])){
                $data[$array_path_item] = array();
            }
            $data_target =& $data[$array_path_item];
        }

        $data_target[$array_path[0]] = $value;
    } else {
        $data[$array_path] = $value;
    }
}

function data_load($ID){
    /*
     * Load an item of data from the database
     */
    global $db;
    global $data;
    
    $data['content'][$ID] = array();
    $datarecord =& $data['content'][$ID];
    
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
    $sc_index_array = array('ID','Type','URL','Status');
    
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
?>
