<?php
	class store_widget extends WP_Widget {
 
	// Constructor //
    
		function store_widget() {
			$widget_ops = array( 'classname' => 'store_widget', 'description' => 'Select the items to show in the widget' ); // Widget Settings
			$control_ops = array( 'id_base' => 'store_widget' ); // Widget Control Settings
			$this->WP_Widget( 'store_widget', 'TT Store', $widget_ops, $control_ops ); // Create the widget
		}

	// Extract Args //

		function widget($args, $instance) {
			extract( $args );
			$title 		= apply_filters('widget_title', $instance['title']); // the widget title
			$storenumber 	= $instance['TT_number']; // the number of posts to show

	// Before widget //
		
			echo $before_widget;
		
	// Title of widget //
		
			if ( $title ) { echo $before_title . $title . $after_title; }
		
			echo display_multi_items($store=$storenumber);
				
	// After widget //
		
			echo $after_widget;
		}
		
	// Update Settings //
 
		function update($new_instance, $old_instance) {
			$instance['title'] = strip_tags($new_instance['title']);
			$instance['TT_number'] = strip_tags($new_instance['TT_number']);
			return $instance;
		}
 
	// Widget Control Panel //
	
		function form($instance) {
		global $wpdb;
		$ttstoremultitable = PRO_TABLE_PREFIX."multi";

		$defaults = array( 'title' => 'Store:', 'TT_number' => "1");
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>'" type="text" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('TT_number'); ?>"><?php _e('Which store would you like to show:'); ?></label>
			<select class="widefat" name="<?php echo $this->get_field_name('TT_number'); ?>" id="<?php echo $this->get_field_id('TT_number'); ?>">
			<?php
				$storeoverview=$wpdb->get_results("SELECT id, multiname FROM ".$ttstoremultitable."");
				foreach ($storeoverview as $store_val){
					if($instance['TT_number']==$store_val->id){
						echo "<option value=\"".$store_val->id."\" selected=\"selected\">".$store_val->multiname."</option>";
					} else {
						echo "<option value=\"".$store_val->id."\">".$store_val->multiname."</option>";
					}
				}
				?>
			</select>
		</p>
		Make sure this store will only show one item. Either by limiting the amount of items <a href="wp-admin/admin.php?page=tt-store&option=store">here</a> or by only selecting one item <a href="wp-admin/admin.php?page=tt-store&option=itemselect">here</a>

        <?php }
 
}

// End class store_widget

add_action('widgets_init', create_function('', 'return register_widget("store_widget");'));
?>