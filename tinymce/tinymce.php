<?php
add_filter('mce_external_plugins', "tinyTT_register");
add_filter('mce_buttons', 'tinyTT_add_button', 0);

function tinyTT_add_button($buttons)
{
    array_push($buttons, "separator", "tinyTT");
    return $buttons;
}
 
function tinyTT_register($plugin_array)
{
    $url = plugins_url( '/ttmce.js' , __FILE__ );
    $plugin_array["tinyTT"] = $url;
    return $plugin_array;
}
?>