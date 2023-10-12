<?php 
/**
 * Functions that helps to ease plugin development.
 *
 * @package Optics POS
 */

function optics_pos_directory() {
	return ABSPATH . 'wp-content/plugins/optics-pos';
}

function optics_pos_directory_uri() {
	return plugins_url() . '/optics-pos';
}

function optics_pos_file() {
	return optics_pos_directory() . '/optics-pos.php';
}

function optics_pos_data() {
	return get_plugin_data( optics_pos_file() );
}
