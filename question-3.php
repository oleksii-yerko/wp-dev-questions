<?php

/**
 * Для чого цей код? Як кінцевий користувач може його використати?
 */

function new_shortcode($atts, $content = null) {
    extract(shortcode_atts(array(
        "type" => "warning"
    ), $atts));

    return '<div class="alert alert-'.$type.'">'.$content.'</div>';
}
add_shortcode("warning_box", "new_shortcode");