<?php
// Action Hook, um die eigenen Styles zu integrieren
add_action( 'wp_enqueue_scripts', 'childtheme_styles' );

function childtheme_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}