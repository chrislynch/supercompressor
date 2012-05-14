<?php
function action_blog_go(&$data){
    /* 
     * Blog can do most of its work by instantiating search. 
     * This helps blog deal with pager and various other items
     */
    include_once('core/actions/search.php');
    $_REQUEST['search.Type'] = 'Blog';
    action_search_go($data);
    
    // TODO: Build some standard blog components, like archives and tag clouds etc.
}
    

?>
