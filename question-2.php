<?php

/**
 * Уявімо що ми маємо файл wp-content/plugins/hello-world.php із цим контентом
 * Чого бракує цьому плагіну щоб він запускався і працював корректно?
 */

add_filter('the_content', 'hello_world');
function hello_world($content){
    return $content . "<h1> Hello World </h1>";
}