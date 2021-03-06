<?php
/*
 * Item: The action we run when we know that we are looking at a single item
 */

function action_item_go(&$data){
    /*
     * The specific item will have already been loaded.
     * This function is here to do any cleaning up activities after a single item load
     * and then to set up the template that we will be using
     */
    foreach($data['_content'] as $key=>$item){
        $data['templates'][0] = 'items/' . strtolower($item['Type']);
    }
}

?>
