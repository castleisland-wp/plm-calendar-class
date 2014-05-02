<?php
/* ****
Activation functions.
******/

function plm_add_options()
{
	add_option('plm_calendar_medium', '800px', '', 'yes');
	add_option('plm_calendar_wide', '1280px', '', 'yes');
	add_option('plm_calendar_xwide', '1440px', '', 'yes');
	add_option('plm_calendar_hday', 'yes', '', 'yes');
}

function plm_calendar_activate()
{
	plm_add_options();
}

register_activation_hook(__FILE__, 'plm_calendar_activate');

function plm_calendar_styles()
{
	$mediafmt = 'screen and (min-width: %s)';
	$medium = sprintf($mediafmt, get_option('plm_calendar_medium'));
	$wide = sprintf($mediafmt, get_option('plm_calendar_wide'));
	$xwide = sprintf($mediafmt, get_option('plm_calendar_xwide'));

	wp_enqueue_style('plm_calendar', plugins_url('styles/style.css', __FILE__));
	wp_enqueue_style('plm_calendar_med', plugins_url('styles/medium.css', __FILE__),array(),FALSE,$medium);
	wp_enqueue_style('plm_calendar_wide', plugins_url('styles/wide.css', __FILE__),array(),FALSE, $wide);
	wp_enqueue_style('plm_calendar_xwide', plugins_url('styles/xwide.css', __FILE__),array(),FALSE, $xwide);
}

add_action('wp_enqueue_scripts', 'plm_calendar_styles');

?>