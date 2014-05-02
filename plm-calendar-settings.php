<?php
/* ****
Settings API Routines
******/

function plm_calendar_admin() 
{
	add_options_page(
		"Paul's Spiffy Calendar Settings",
		"Paul's Calendar",
		'administrator',
		'plm_calendar_menu',
		'plm_calendar_menu_display'
		);
}

add_action('admin_menu', 'plm_calendar_admin');

function plm_calendar_options()
{
	add_settings_section(
		'plm_calendar_section',
		'Calendar Options',
		'plm_calendar_section_display',
		'plm_calendar_menu'
		);

	add_settings_field(
		'plm_calendar_medium',
		'Medium Resolution',
		'plm_calendar_medium_disp',
		'plm_calendar_menu',
		'plm_calendar_section',
		array('Select the width in pixels where the calendar display switches to two columns.')
		);

	add_settings_field(
		'plm_calendar_wide',
		'Wide Resolution',
		'plm_calendar_wide_disp',
		'plm_calendar_menu',
		'plm_calendar_section',
		array('Select the width in pixels where the calendar display switches to three columns.')
		);

	add_settings_field(
		'plm_calendar_xwide',
		'Extra Wide Resolution',
		'plm_calendar_xwide_disp',
		'plm_calendar_menu',
		'plm_calendar_section',
		array('Select the width in pixels where the calendar display switches to four columns.')
		);
	add_settings_field(
		'plm_calendar_hday',
		'Highlight Holidays?',
		'plm_holiday_disp',
		'plm_calendar_menu',
		'plm_calendar_section',
		array('Check here to emphasize holidays in calendar shortcodes')
		);

	register_setting(
		'plm_calendar_menu', 
		'plm_calendar_medium');

	register_setting(
		'plm_calendar_menu', 
		'plm_calendar_wide');

	register_setting(
		'plm_calendar_menu', 
		'plm_calendar_xwide');

	register_setting(
		'plm_calendar_menu', 
		'plm_calendar_hday');
}

add_action('admin_init', 'plm_calendar_options');

// Display Menus 
function plm_calendar_menu_display()
{
?>
<div class="wrap">
	<h2>Paul's Juicy Calendar Settings</h2>
	<?php settings_errors(); ?>
	<form method="post" action="options.php">
		<?php settings_fields('plm_calendar_menu'); ?>
		<?php do_settings_sections('plm_calendar_menu'); ?>
		<?php submit_button(); ?>
	</form>
</div>
<?php
} 

function plm_calendar_section_display()
{ 
echo "<p>Use this section to control the reponsive layout of the yearly calendar....</p>";

 }

 function plm_calendar_medium_disp($args)
 {
 ?>
 
 <select name="plm_calendar_medium" id="plm_calendar_medium">
 	<option value="640px" <?php selected(get_option('plm_calendar_medium'), '640px', true); ?>>640px</option>
 	<option value="720px" <?php selected(get_option('plm_calendar_medium'), '720px', true); ?>>720px</option>
 	<option value="800px" <?php selected(get_option('plm_calendar_medium'), '800px', true); ?>>800px</option>
 </select>
<label for="plm_calendar_medium">&nbsp;<?php echo $args[0]; ?></label>
 <?php
 }

 function plm_calendar_wide_disp($args)
 {
?>
 
 <select name="plm_calendar_wide" id="plm_calendar_wide">
 	<option value="960px" <?php selected(get_option('plm_calendar_wide'), '960px', true); ?>>960px</option>
 	<option value="1024px" <?php selected(get_option('plm_calendar_wide'), '1024px', true); ?>>1024px</option>
 	<option value="1280px" <?php selected(get_option('plm_calendar_wide'), '1280px', true); ?>>1280px</option>
 </select>
<label for="plm_calendar_wide">&nbsp;<?php echo $args[0]; ?></label>
 <?php
 }

 function plm_calendar_xwide_disp($args)
 {
?> 
 <select name="plm_calendar_xwide" id="plm_calendar_xwide">
 	<option value="1440px" <?php selected(get_option('plm_calendar_xwide'), '1440px', true); ?>>1440px</option>
 	<option value="1600px" <?php selected(get_option('plm_calendar_xwide'), '1600px', true); ?>>1600px</option>
 	<option value="1768px" <?php selected(get_option('plm_calendar_xwide'), '1768px', true); ?>>1768px</option>
 </select>
<label for="plm_calendar_xwide">&nbsp;<?php echo $args[0]; ?></label>
 <?php
}

function plm_holiday_disp($args)
{
?>
<input type="checkbox" id="plm_calendar_hday" name="plm_calendar_hday" <?php checked(get_option('plm_calendar_hday'), 1, TRUE) ?> value="1" />
<label for="plm_calendar_hday">&nbsp;<?php echo $args[0] . get_option('plm_calendar_hday') ;?></label>
<?php
}

?>