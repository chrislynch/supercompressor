<?php

function action_cart_go(&$data){
    array_drill_set('cart.items','0 items',$data);
    array_drill_set('cart.value','&pound;0.00',$data);
}

?>
