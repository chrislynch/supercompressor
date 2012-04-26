<?php

function action_cart_go(&$data){
    array_drill_set('Cart.Items','0 items',$data);
    array_drill_set('Cart.Value','&pound;0.00',$data);
}

?>
