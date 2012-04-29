<?php
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
            if (isset($data['_content'])){
                // Assume that have not drilled down, yet, and the item we need is the first item in the content array
                foreach($data['_content'] as $contentItem){
                    $return = array_drill_get($widget_field,$contentItem);
                    break;
                }
                break;
            }
            // Drop through to 'data' if did not find content
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
    
    if(is_array($return)){
        $tempreturn = '';
        foreach($return as $returnKey=>$returnValue){
            if (strlen($returnValue) > 0){
                $tempreturn .= '<label>' . ucwords(str_ireplace('_', ' ', $returnKey)) . '</label>:&nbsp;' . $returnValue . '<br>';
            }
        }
        $return = $tempreturn;
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
?>
