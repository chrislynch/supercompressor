<?php

/*
 * Supercompressor - eCommerce.
 */

$data = array();

$dir_template   = 'templates/localhost/';
$dir_site       = 'sites/localhost/';

/*
 * Bootstrap the data array
 */
include $dir_site . 'bootstrap.php';

/*
 * Decide what action we are doing and then do it
 */
if (isset($_REQUEST['action'])){
    $action = $_REQUEST['action'];
} else {
    $action = 'home';
}

if (file_exists($dir_site . '/actions/' . $action . '.php')){
    include $dir_site . '/actions/' . $action . '.php';
} else {
    include 'actions/' . $action . '.php';
}

/*
 * Render the page
 */
$return = '';
// $return .= render_template($dir_template . 'html-header.html',$data);
$return .= render_template($dir_template . 'page-header.html',$data);
$return .= render_template($dir_template . 'sidebar-1.html',$data);
$return .= render_template($dir_template . 'sidebar-2.html',$data);
$return .= render_template($dir_template . 'content-header.html',$data);

// $return = render_template($dir_template . $action . '.html',$data);
$return .= render_template($dir_template . 'content.html',$data);

$return .= render_template($dir_template . 'content-footer.html',$data);
$return .= render_template($dir_template . 'sidebar-3.html',$data);
$return .= render_template($dir_template . 'sidebar-4.html',$data);
$return .= render_template($dir_template . 'page-footer.html',$data);
$return .= render_template($dir_template . 'html-footer.html',$data);

print $return;
print '<hr><pre>' . print_r($data,TRUE) . '</pre>';

/*
 * END OF SUPERCOMPRESSOR
 */

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

function render_widget($widget,$data){
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
    
    switch ($widget_type){
        case 'content':
        case 'data':
            $return = array_drill_get($widget_field,$data);
            break;
        case 'loop':
            global $dir_template;
            $loopcontent = array_drill_get($widget_field,$data);
            $return = '';
            foreach($loopcontent as $loopcontentID => $loopcontentitem){
                $return .= render_template($dir_template . $widget_params['template'], $loopcontentitem);
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
    
    $records = mysql_query('SELECT * FROM sc_data WHERE ID = ' . $ID . ' ');
    while ($record = mysql_fetch_assoc($records)){
        array_drill_set($record['Field'], $record['Value'], $datarecord);
    }
    
}

?>
