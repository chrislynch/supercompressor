<?php

/*
 * Supercompressor - eCommerce.
 */

$data = array();

$dir_template   = 'templates/shop/';
$dir_site       = 'site/localhost/';

/*
 * Build the data array
 */
$data['h1'] = 'Main page title';
$data['seo'] = array();
$data['seo']['title'] = 'SEO Title';
array_drill_set('footer.copyright','Chris Lynch',$data);

/*
 * Render the page
 */
$return = render_template($dir_template . 'header.html',$data);

print $return;

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
        $widget = substr($template,$widget_start+2,$widget_end - ($widget_start + 2));
        $widget_return = render_widget($widget,$data);
        $widget = '@@' . $widget . '@@';
        $template = str_replace($widget, $widget_return, $template);
        
        $widget_start = stripos($template, '@@');
    }
    
    return $template;
}

function render_widget($widget,$data){
    /*
     * Render a given widget against the given data array
     */
    $widget = explode('?',$widget);
    $widget_type = $widget[0];
    $widget_param_string = $widget[1];
    
    $widget_param_string = explode('&',$widget_param_string);
    $widget_params = array();
    foreach($widget_param_string as $widget_param_string_item){
        $widget_param_string_item = explode('=',$widget_param_string_item);
        $widget_params[$widget_param_string_item[0]] = $widget_param_string_item[1];
    }
    
    switch ($widget_type){
        case 'data':
            $return = array_drill_get($widget_params['field'],$data);
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
        return $data[$array_path];
    }
}

function array_drill_set($array_path,$value,&$data){
    /*
     * Set an item as deep down in our array as we want.
     */
    $array_path = explode('.',$array_path);
    
    while(sizeof($array_path) > 1){
        $array_path_item = array_shift($array_path);
        if (!isset($data[$array_path_item])){
            $data[$array_path_item] = array();
            $data_target =& $data[$array_path_item];
        }
    }
    
    $data_target[$array_path[0]] = $value;
}

?>
