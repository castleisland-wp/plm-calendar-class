<?php


/**
* 
*/
add_action('widgets_init', 
	create_function('', 'return register_widget("plm_holiday_list_widget");'));

class plm_holiday_list_widget extends WP_Widget
{
	
	function __construct()
	{
		parent::__construct('plm_holiday_list_widget', 
			__('Holiday List', 'plm_calendar_class'),
			array('description' => __('This widget displays a list of major holidays for a given year', 'plm_calendar_class'),
				));
	}
	//Widget form!
	function form($instance)
	{
		$tempDT = new DateTime('today');
		if ($instance)
		{
			$year=esc_attr($instance['year']);
		} 
		else 
		{
			$year = $tempDT->format('Y');
		}
		$curryear = $tempDT->format('Y');
		unset($tempDT);
 
?>
		<label for="<?php echo $this->get_field_id('year'); ?>"><?php _e('Select Year', 'plm_calendar_class'); ?></label>
		<select class="widefat" id="<?php echo $this->get_field_id('year'); ?>" name="<?php echo $this->get_field_name('year'); ?>">
<?php
		for ($i=$curryear-10; $i < ($curryear+10); $i++) { 
?>
			<option class="widefat" value="<?php echo $i; ?>"<?php selected($year, $i, TRUE); ?>><?php echo $i; ?></option>
<?php
		}
?>
		</select>
<?php
	}

	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;

		$instance['year'] = strip_tags($new_instance['year']);

		return $instance;
	}

	function widget($args, $instance)
	{
		extract($args);

		$year = $instance['year'];
		$hlist = plm_holiday_list($year);
		$tempDT = new DateTime('today');

		if ($year=='') {
			$year = 2019;
		}

		echo $before_widget;

		echo $before_title . 'Major Holidays of ' . $year . $after_title;
		echo '<dl>';

		foreach ($hlist as $mdd => $holiday) {
			echo '<dt>' . $holiday . '</dt>';
			$nmon = substr($mdd, 0, 2);
			$nday = substr($mdd, 2, 2);
			$tempDT->SetDate($year, $nmon, $nday);
			echo '<dd>' . $tempDT->format('l F j') . '</dd>';
		}

		unset($tempDT);
		echo '</dl>' . $after_widget;
	}
}

add_action('widgets_init', 
	create_function('', 'return register_widget("plm_holiday_count");'));

class plm_holiday_count extends WP_Widget
{
	
	function __construct()
	{
		parent::__construct('plm_holiday_count', 
			__("Days Until Holidays", 'plm_calendar_class'),
	 		array('description' => __('This Widget tells you the number of days until a selected holiday.', 'plm_calendar_class'),
	 			)
	 		);
	} 



// 	Widget form!
	function form($instance)
	{
		if ($instance) {
			$title = esc_attr($instance['title']);
			$holiday = esc_attr($instance['holiday']);
			$spell = esc_attr($instance['spell']);

		} else {
			$title = '';
			$holiday = 'newyears';
			$spell = '';
		}

?>
	<p>
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'plm_calendar_class'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('holiday'); ?>"><?php _e('Select Holiday', 'plm_calendar_class'); ?></label>
		<select class="widefat" id="<?php echo $this->get_field_id('holiday'); ?>" name="<?php echo $this->get_field_name('holiday'); ?>">
			<option class = "widefat" value="newyears" <?php if($holiday=='newyears') echo ' selected'; ?>>New Year's Day</option>
			<option class = "widefat" value="valentine" <?php if($holiday=='valentine') echo ' selected'; ?>>Valentine's Day</option>
			<option class = "widefat" value="stpaddy" <?php if($holiday=='stpaddy') echo ' selected'; ?>>St. Patrick's Day</option>
			<option class = "widefat" value="easter" <?php if($holiday=='easter') echo ' selected'; ?>>Easter</option>
			<option class = "widefat" value="mother" <?php if($holiday=='mother') echo ' selected'; ?>>Mother's Day</option>
			<option class = "widefat" value="memorial" <?php if($holiday=='memorial') echo ' selected'; ?>>Memorial Day</option>
			<option class = "widefat" value="father" <?php if($holiday=='father') echo ' selected'; ?>>Father's Day</option>
			<option class = "widefat" value="july4" <?php if($holiday=='July4') echo ' selected'; ?>>Independence Day</option>
			<option class = "widefat" value="labor" <?php if($holiday=='labor') echo ' selected'; ?>>Labor Day</option>
			<option class = "widefat" value="columbus" <?php if($holiday=='columbus') echo ' selected'; ?>>Columbus Day</option>
			<option class = "widefat" value="halloween" <?php if($holiday=='halloween') echo ' selected'; ?>>Halloween</option>
			<option class = "widefat" value="veteran" <?php if($holiday=='veteran') echo ' selected'; ?>>Veteran's Day</option>
			<option class = "widefat" value="thanksgiving" <?php if($holiday=='thanksgiving') echo ' selected'; ?>>Thanksgiving</option>
			<option class = "widefat" value="christmas" <?php if($holiday=='christmas') echo ' selected'; ?>>Christmas</option>
		</select>
	</p>
<?php
		if (function_exists('plm_textualize_number')) 
		{
?>
		<label for="<?php echo $this->get_field_id('spell'); ?>"><?php _e('Spell out the numbers? ', 'plm_calendar_class'); ?></label>
		<input class="widefat "type="checkbox" id="<?php echo $this->get_field_id('spell'); ?>" name="<?php echo $this->get_field_name('spell'); ?>" <?php checked($spell, 'yes') ?> value="yes" />
<?php 
		}
	}

	//Update Settings!
	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		// Fields
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['holiday'] = strip_tags($new_instance['holiday']);
		$instance['spell'] = strip_tags($new_instance['spell']);
		return $instance;
	}

/*
	Output Widget!
*/

	function widget($args, $instance)
	{
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);

		//


		echo $before_widget;

		if($title != '') 
		{
			echo $before_title . $title . $after_title;
		} 

		$holiday = $instance['holiday'];

		$returnArray = plm_holiday_diff($holiday);

		$daycount = $returnArray['days'];

		if ((function_exists('plm_textualize_number') ) AND $instance['spell'] == 'yes') {
			$daycount = ucfirst(plm_textualize_number($daycount));
		}

		echo '<p>' . $daycount . ' days until ' . $returnArray['holiday'] . '.</p>';
	
		echo $after_widget;


	}
}

?>