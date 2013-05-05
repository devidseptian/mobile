<?php

	// Populate the sections and settings of the options page
		
	function dtslides_section_text() {
	
		echo "<p>". __( 'Set up your slideshow using the options below.', 'dt-slides' ) ."</p>";

	}
	
	function dtslides_slide_height() {
		
		$dt_px      = __( 'px', 'dt-slides' );
		$dt_options = get_option('dtslides_options');

		echo "<input id='slide_height' name='dtslides_options[slide_height]' size='20' type='text' value='{$dt_options['slide_height']}' /> $dt_px";

	}
		
	function dtslides_slide_width() {
		
		$dt_px      = __( 'px', 'dt-slides' );
		$dt_options = get_option('dtslides_options');

		echo "<input id='slide_width' name='dtslides_options[slide_width]' size='20' type='text' value='{$dt_options['slide_width']}' /> $dt_px";

	}
	
	function dtslides_transition_style() {
						
		$dt_bars        = __( 'bars', 'dt-slides' );
		$dt_zip         = __( 'zip', 'dt-slides' );
		$dt_blinds      = __( 'blinds', 'dt-slides' );
		$dt_blocks      = __( 'blocks', 'dt-slides' );
		$dt_concentric  = __( 'concentric', 'dt-slides' );
		$dt_warp        = __( 'warp', 'dt-slides' );
		$dt_slide       = __( 'slide', 'dt-slides' );
		$dt_bars3d      = __( 'bars3d', 'dt-slides' );
		$dt_cube        = __( 'cube', 'dt-slides' );
		$dt_tiles3d     = __( 'tiles3d', 'dt-slides' );
		$dt_blinds3d    = __( 'blinds3d', 'dt-slides' );
		$dt_turn        = __( 'turn', 'dt-slides' );
		$dt_blocks2     = __( 'blocks2', 'dt-slides' );
		$dt_dissolve    = __( 'dissolve', 'dt-slides' );
		$dt_swipe       = __( 'swipe', 'dt-slides' );
		$dt_options     = get_option( 'dtslides_options' );
		$dt_item        = array(
						
			'0'    	      => $dt_bars,
			'4'           => $dt_zip,
			'2'           => $dt_blinds,
			'5'           => $dt_blocks,
			'7'           => $dt_concentric,
			'8'           => $dt_warp,
			'12'          => $dt_slide,
			'1'           => $dt_bars3d,
			'9'           => $dt_cube,
			'10'          => $dt_tiles3d,
			'3'           => $dt_blinds3d,
			'11'          => $dt_turn,
			'6'           => $dt_blocks2,
			'14'          => $dt_dissolve,
			'13'          => $dt_swipe
			
		);
		
		echo "<select id='transition_style' name='dtslides_options[transition_style]' style='width:142px;'>";
		
		while ( list( $dt_key, $dt_val ) = each( $dt_item ) ) {

			$dt_selected = ( $dt_options['transition_style']==$dt_key ) ? ' selected="selected"' : '';
		
			echo "<option value='$dt_key'$dt_selected>$dt_val</option>";
	
		}
		
		echo "</select>";
		
	}
	
			
	function dtslides_slide_duration() {

		$dt_seconds = __( 'seconds', 'dt-slides' );
		$dt_options = get_option( 'dtslides_options' );

		echo "<input id='slide_duration' name='dtslides_options[slide_duration]' size='20' type='text' value='{$dt_options['slide_duration']}' /> $dt_seconds";

	}
		
	function  dtslides_slideshow_navigation() {
		
		$dt_navnone     = __( 'None', 'dt-slides' );
		$dt_navprevnext = __( 'Previous/Next', 'dt-slides' );
		$dt_navpaged    = __( 'Paged', 'dt-slides' );
		$dt_navboth     = __( 'Both', 'dt-slides' );
			
		$dt_options = get_option( 'dtslides_options' );
		
		$dt_item = array(
			
			'navnone'     => $dt_navnone,
			'navprevnext' => $dt_navprevnext,
			'navpaged'    => $dt_navpaged,
			'navboth'     => $dt_navboth
				
		);
		
		echo "<select id='slideshow_navigation' name='dtslides_options[slideshow_navigation]' style='width:142px;'>";
		
		while ( list( $dt_key, $dt_val ) = each( $dt_item ) ) {
	
			$dt_selected = ( $dt_options['slideshow_navigation']==$dt_key ) ? ' selected="selected"' : '';
		
			echo "<option value='$dt_key'$dt_selected>$dt_val</option>";
	
		}
		
		echo "</select>";
		
	}
	
	function  dtslides_slide_autoplay() {
		
		$dt_on  = __( 'On', 'dt-slides' );
		$dt_off = __( 'Off', 'dt-slides' );
			
		$dt_options = get_option( 'dtslides_options' );
		
		$dt_item = array(
			
			'on'     => $dt_on,
			'off'	 => $dt_off
			
		);
		
		echo "<select id='slide_autoplay' name='dtslides_options[slide_autoplay]' style='width:142px;'>";
		
		while ( list( $dt_key, $dt_val ) = each( $dt_item ) ) {
	
			$dt_selected = ( $dt_options['slide_autoplay']==$dt_key ) ? ' selected="selected"' : '';
		
			echo "<option value='$dt_key'$dt_selected>$dt_val</option>";
	
		}
		
		echo "</select>";
		
	}

?>

<div class="wrap">
	
	<div id="icon-edit" class="icon32"><br /></div>
	
	<h2><?php _e( 'DT Slides Settings', 'dt-slides' ); ?></h2>

	<form action="options.php" method="post">

		<?php // Adds options to settings page
				
		settings_fields( 'dtslides_options' );
				
		do_settings_sections( 'dtslides' );

		?>
		
		<p class="submit">

			<input name="Submit" type="submit" class="button-primary" value="<?php _e( 'Save Changes', 'dt-slides' ) ?>" />
		
		</p>
				
	</form>
	
	<h3><?php _e( 'Add Slideshow', 'dt-slides' ); ?></h3>
	
	<p><?php printf( __ ( 'Use %1$s to add this slideshow to your theme, use %2$s to add it to your Post or Page content, or use the dt Slides Widget.', 'dt-slides'), "<code><&#63;php if ( function_exists( 'dt_slideshow' ) ) { dt_slideshow(); } &#63;></code>", "<code>[dt_slideshow]</code>" )?></p>
	
	<p><?php printf( __ ( 'Visit the %1$sdt Slides homepage%2$s for documentation, tutorials, and videos.', 'dt-slides' ), "<a href='http://www.jleuze.com/plugins/dt-slides/'>", "</a>" )?></p>
	
	<p><em><?php printf( __ ( 'Please %1$spost any questions or problems%2$s in the WordPress.org support forums.', 'dt-slides' ), "<a href='http://wordpress.org/tags/dt-slides?forum_id=10#postform'>", "</a>" )?></em></p>
	
</div><!-- .wrap -->