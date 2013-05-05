<?php
	
	//Adds filter to customize messages for the slide post type

	add_filter( 'post_updated_messages', 'dtslides_updated_messages' );

	function dtslides_updated_messages( $dt_messages ) {

		global $post, $post_ID;

		$dt_messages['dtslide'] = array( 
  
			0  => '',
			1  => sprintf( __( 'Slide updated. <a href="%s">View slide</a>', 'dt-slides' ), esc_url( get_permalink($post_ID) ) ),
			2  => __( 'Custom field updated.', 'dt-slides' ),
			3  => __( 'Custom field deleted.', 'dt-slides' ),
			4  => __( 'Slide updated.', 'dt-slides' ),
			5  => isset($_GET['revision']) ? sprintf( __( 'Slide restored to revision from %s', 'dt-slides' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6  => sprintf( __( 'Slide published. <a href="%s">View slide</a>', 'dt-slides' ), esc_url( get_permalink($post_ID) ) ),
			7  => __( 'Slide saved.', 'dt-slides' ),
			8  => sprintf( __( 'Slide submitted. <a target="_blank" href="%s">Preview slide</a>', 'dt-slides' ), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
			9  => sprintf( __( 'Slide scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview slide</a>', 'dt-slides' ), date_i18n( __( 'M j, Y @ G:i', 'dt-slides' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
			10 => sprintf( __( 'Slide draft updated. <a target="_blank" href="%s">Preview slide</a>', 'dt-slides' ), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
 
		);

		return $dt_messages;
  
	}
	
	// Customize and move featured image box to main column
	
	add_action( 'do_meta_boxes', 'dtslides_image_box' );
	
	function dtslides_image_box() {
		
		$dt_image_options = get_option('dtslides_options');
		
		$dt_image_title = __( 'DTSlide Image', 'dt-slides' ) . ' (' . $dt_image_options['slide_width'] . 'x' . $dt_image_options['slide_height'] . ')';
	
		remove_meta_box( 'postimagediv', 'dtslide', 'side' );
	
		add_meta_box( 'postimagediv', $dt_image_title, 'post_thumbnail_meta_box', 'dtslide', 'normal', 'high' );
	
	}
	
	// Remove permalink metabox
	
	add_action( 'admin_menu', 'dtslides_remove_permalink_meta_box' );

	function dtslides_remove_permalink_meta_box() {
	
		remove_meta_box( 'slugdiv', 'dtslide', 'core' );
	
	}
		
	// Adds meta box for Slide URL
	
	add_action( 'admin_menu', 'dtslides_create_url_meta_box' );

	$dtslides_new_meta_box =

		array(
		
			'slide_url' => array(
			
				'name' => 'slide_url',
				'std'  => ''				
			)

		);

	function dtslides_new_meta_box() {
	
		global $post, $dtslides_new_meta_box;

		foreach ( $dtslides_new_meta_box as $dtslides_meta_box ) {

			$dtslides_meta_box_value = get_post_meta( $post->ID, $dtslides_meta_box['name'].'_value', true );  

			if( $dtslides_meta_box_value == "" ) $dtslides_meta_box_value = $dtslides_meta_box['std'];

			echo "<input type='hidden' name='" . $dtslides_meta_box['name'] . "_noncename' id='" . $dtslides_meta_box['name'] . " _noncename' value='" . wp_create_nonce( plugin_basename(__FILE__) ) . "' />";

			echo "<input type='text' name='" . $dtslides_meta_box['name'] . "_value' value='" . $dtslides_meta_box_value . "' size='55' /><br />";

			echo "<p>" . __('Add the URL this dtslide should link to.','dt-slides') . "</p>";

		}

	}

	function dtslides_create_url_meta_box() {
	
		global $theme_name;

		if ( function_exists('add_meta_box') ) {

			add_meta_box( 'dtslides-url-box', __('DTSlide Link','dt-slides'), 'dtslides_new_meta_box', 'dtslide', 'normal', 'low' );

		}

	}
	
	// Save and retrieve the Slide URL data
	
	add_action( 'save_post', 'dtslides_save_postdata' );

	function dtslides_save_postdata( $post_id ) {

		global $post, $dtslides_new_meta_box;

		foreach( $dtslides_new_meta_box as $dtslides_meta_box ) {

			if ( !isset( $_POST[$dtslides_meta_box['name'].'_noncename']  ) || !wp_verify_nonce( $_POST[$dtslides_meta_box['name'].'_noncename'], plugin_basename(__FILE__) ) ) {

				return $post_id;

			}

			if ( 'page' == $_POST['post_type'] ) {

				if( !current_user_can( 'edit_page', $post_id ) )

				return $post_id;

			}
			
			else {
			
				if( !current_user_can( 'edit_post', $post_id ) )

				return $post_id;

			}

			$dt_data = $_POST[$dtslides_meta_box['name'].'_value'];

			if ( get_post_meta( $post_id, $dtslides_meta_box['name'].'_value' ) == "" ) {
			
				add_post_meta( $post_id, $dtslides_meta_box['name'].'_value', $dt_data, true );
			
			}
			
			elseif ( $dt_data != get_post_meta( $post_id, $dtslides_meta_box['name'].'_value', true ) ) {

				update_post_meta( $post_id, $dtslides_meta_box['name'].'_value', $dt_data );
			
			}

			elseif ( $dt_data == "" ) {

				delete_post_meta( $post_id, $dtslides_meta_box['name'].'_value', get_post_meta( $post_id, $dtslides_meta_box['name'].'_value', true ) );
			
			}
			
		}

	}
	
	// Adds slide image and link to slides column view
	
	add_filter( 'manage_edit-dtslide_columns', 'dtslides_edit_columns' );
 
	function dtslides_edit_columns( $dt_columns ) {
	
		$dt_columns = array(
		
			'cb'         => '<input type="checkbox" />',
			'title'      => __( 'DTSlide Title', 'dt-slides' ),
			'dtslide'      => __( 'DTSlide Image', 'dt-slides' ),
			'slide-link' => __( 'DTSlide Link', 'dt-slides' ),
			'date'       => __( 'Date', 'dt-slides' )

		);
 
		return $dt_columns;
  
	}
	
	add_action( 'manage_posts_custom_column', 'dtslides_custom_columns' );
	
	function dtslides_custom_columns( $dt_column ) {
	
		global $post;
 
		switch ( $dt_column ) {
		
			case 'dtslide' :
			
				echo the_post_thumbnail( array(50,50));
			
			break;
			
			case 'slide-link' :
			
				if ( get_post_meta($post->ID, "slide_url_value", $single = true) != "" ) {
				
					echo "<a href='" . get_post_meta($post->ID, "slide_url_value", $single = true) . "'>" . get_post_meta($post->ID, "slide_url_value", $single = true) . "</a>";
			
				}  
			
				else {
				
					_e('No Link', 'dt-slides');
			
				}
			
			break;

		}
		
	}
	
	// setup contextual help action
	
	add_action( 'current_screen', 'dtslides_contextual_help_action' );

	function dtslides_contextual_help_action() {
	
		$dt_screen_action = get_current_screen();
	
		if ( 'dtslide' == $dt_screen_action->id && 'add' == $dt_screen_action->action ) {
		
			$dt_load_action = 'load-post-new.php';
		
		} elseif ( 'dtslide' == $dt_screen_action->id ) {

			$dt_load_action = 'load-post.php';	
			
		} elseif ( 'edit-slide' == $dt_screen_action->id ) {

			$dt_load_action = 'load-edit.php';

		} elseif ( 'edit-slideshow' == $dt_screen_action->id ) {
			
			$dt_load_action = 'load-edit-tags.php';
		
		} elseif ( 'slide_page_slides_settings' == $dt_screen_action->id ) {
		
			$dt_load_action = 'load-slide_page_slides_settings';
			
		}
		
		if ( !empty( $dt_load_action ) ) {
		
			add_action( $dt_load_action, 'dtslides_add_contextual_help' );
		
		}
		
	}
	
	// add contextual help for slides
	
	function dtslides_add_contextual_help() {
		
			$dt_contextual_screen = get_current_screen();
			
		if ('dtslide' == $dt_contextual_screen->id ) {
		
			$dt_contextual_first_id = 'dtslide';
			
			if ( 'add' == $dt_contextual_screen->action ) {
			
				$dt_contextual_first_title = __( 'Add New Slide', 'dt-slides' );
				
			} else {
			
				$dt_contextual_first_title = __( 'Edit Slide', 'dt-slides' );
			
			}
			
			$dt_contextual_first_content =
			
			'<p>'  . __( '<strong>Title</strong> - Name the slide so it can be easily found later.', 'dt-slides' ) . '</p>' .
			'<p>'  . __( '<strong>Slide Image</strong> - To add an image to a slide, click the "Set featured image" link. Upload an image, or browse the media library for one, click the "Use as featured image" link to add the image and then close the media uploader. The Slide Image metabox should now have a thumbnail image.', 'dt-slides' ) . '</p>' .
			'<p>'  . __( '<strong>Slide Link</strong> - Add the full URL to the Slide Link metabox, such as <em>http://www.jleuze.com/</em> (Optional)', 'dt-slides' ) . '</p>' .
			'<p>'  . __( '<strong>Slideshows</strong> - A slide can be added <a href="http://www.jleuze.com/plugins/dt-slides/multiple-slideshows/">to a slideshow</a> by selecting the slideshow from the Slideshows metabox.', 'dt-slides' ) . '</p>' .
			'<p>'  . __( "<strong>Slide Order</strong> - Slides are sorted chronologically, edit the slide's published date to change the order of the slide.", "dt-slides" ) . '</p>';
			
			$dt_contextual_sidebar =
			
			'<p><strong>' . __( 'For more information', 'dt-slides' ) . '</strong></p>' .
			'<p>'  . __( '<a href="http://www.jleuze.com/plugins/dt-slides/using-dt-slides/" target="_blank">Documentation on Creating Slides</a>', 'dt-slides' ) . '</p>' .
			'<p>'  . __( '<a href="http://wordpress.org/tags/dt-slides" target="_blank">Plugin Support Forum</a>', 'dt-slides' ) . '</p>' .
			'<p>'  . __( '<a class="button" href="http://jleuze.com/donate/" target="_blank">Donate</a>', 'dt-slides' ) . '</p>';
		
			$dt_contextual_screen->add_help_tab( array(

				'id'      => $dt_contextual_first_id,
				'title'   => $dt_contextual_first_title,
				'content' => $dt_contextual_first_content

			) );
		
		} elseif ( 'edit-slide' == $dt_contextual_screen->id ) {

			$dt_contextual_first_id      = 'edit-slide';
			$dt_contextual_first_title   = __( 'Slides Overview', 'dt-slides' );		
			$dt_contextual_first_content =
			
			'<p>'  . __( 'From the slides overview the image, title, and link of each slide can be viewed. Choose a slide to edit, or add a new slide.', 'dt-slides' ) . '</p>';
			
			$dt_contextual_sidebar =
			
			'<p><strong>' . __( 'For more information', 'dt-slides' ) . '</strong></p>' .
			'<p>'  . __( '<a href="http://www.jleuze.com/plugins/dt-slides/installation/" target="_blank">dt Slides Documentation</a>', 'dt-slides' ) . '</p>' .
			'<p>'  . __( '<a href="http://wordpress.org/tags/dt-slides" target="_blank">Plugin Support Forum</a>', 'dt-slides' ) . '</p>' .
			'<p>'  . __( '<a class="button" href="http://jleuze.com/donate/" target="_blank">Donate</a>', 'dt-slides' ) . '</p>';
			
			$dt_contextual_screen->add_help_tab( array(

				'id'      => $dt_contextual_first_id,
				'title'   => $dt_contextual_first_title,
				'content' => $dt_contextual_first_content

			) );
			
		} elseif ( 'edit-slideshow' == $dt_contextual_screen->id ) {
			
			$dt_contextual_first_id      = 'edit-slideshow';
			$dt_contextual_first_title   = __( 'Multiple Slideshows', 'dt-slides' );
			$dt_contextual_first_content =
			
			'<p>'  . __( 'Slides can be organized into slideshows, just as posts can be organized into categories.', 'dt-slides' ) . '</p>' .
			'<p>'  . __( '<strong>Add New Slideshow</strong> - Name the slideshow, specify a Slug or one will be generated from the name, skip the Parent and Description and click "Add New Slideshow".', 'dt-slides' ) . '</p>' .
			'<p>'  . __( '<strong>Add Slide to Slideshow</strong> - Edit a slide and select the slideshow in the Slideshows metabox.', 'dt-slides' ) . '</p>';

			$dt_contextual_second_id      = 'add-specific-slideshow';
			$dt_contextual_second_title   = __( 'Adding A Specific Slideshow', 'dt-slides' );
			$dt_contextual_second_content =
			
			'<p>'  . __( 'Add a slideshow slug to a template tag, shortcode, or select a slideshow in the widget to load a specific slideshow. Here is an example using the shortcode:', 'dt-slides' ) . '</p>' .
			'<p>'  . __( '<code>[dt_slideshow slideshow="slug"]</code>', 'dt-slides' ) . '</p>';

			
			$dt_contextual_sidebar =
			
			'<p><strong>' . __( 'For more information', 'dt-slides' ) . '</strong></p>' .
			'<p>'  . __( '<a href="http://www.jleuze.com/plugins/dt-slides/multiple-slideshows/" target="_blank">Documentation on Adding Multiple Slideshows</a>', 'dt-slides' ) . '</p>' .
			'<p>'  . __( '<a href="http://wordpress.org/tags/dt-slides" target="_blank">Plugin Support Forum</a>', 'dt-slides' ) . '</p>' .
			'<p>'  . __( '<a class="button" href="http://jleuze.com/donate/" target="_blank">Donate</a>', 'dt-slides' ) . '</p>';
			
			$dt_contextual_screen->add_help_tab( array(

				'id'      => $dt_contextual_first_id,
				'title'   => $dt_contextual_first_title,
				'content' => $dt_contextual_first_content

			) );
			
			$dt_contextual_screen->add_help_tab( array(

				'id'      => $dt_contextual_second_id,
				'title'   => $dt_contextual_second_title,
				'content' => $dt_contextual_second_content

			) );
			
		} elseif ( 'slide_page_slides_settings' == $dt_contextual_screen->id ) {
		
			$dt_contextual_first_id      = 'slide_page_slides_settings';
			$dt_contextual_first_title   = __( 'Configure Slideshow', 'dt-slides' );
			$dt_contextual_first_content =
			
			'<p>'  . __( '<em>Before adding any slides, enter the slide height and width in the settings so the slides are the correct dimensions.</em>', 'dt-slides' ) . '</p>' .
			'<p>'  . __( '<strong>Slideshow Quantity</strong> - Choose the number of slides that are loaded in the slideshow.', 'dt-slides' ) . '</p>' .
			'<p>'  . __( '<strong>Slide Height</strong> - Enter the height of your slides in pixels. For slides of different heights, use the height of the tallest slide.', 'dt-slides' ) . '</p>' .
			'<p>'  . __( '<strong>Slide Width</strong> - Enter the width of your slides in pixels. Slides that are narrower than this will be centered in the slideshow.', 'dt-slides' ) . '</p>' .
			'<p>'  . __( '<strong>Transition Style</strong> - Choose the effect that is used to transition between slides.', 'dt-slides' ) . '</p>' .
			'<p>'  . __( '<strong>Transition Speed</strong> - Enter the number of seconds that it should take for a transition between slides to complete.', 'dt-slides' ) . '</p>' .
			'<p>'  . __( '<strong>Slide Duration</strong> - Enter the number of seconds that each slide should be paused on in the slideshow.', 'dt-slides' ) . '</p>' .
			'<p>'  . __( '<strong>Slideshow Navigation</strong> - Slideshows have no navigation by default, previous/next and/or paged navigation can be added.', 'dt-slides' ) . '</p>';

			$dt_contextual_second_id      = 'slide_page_slides_settings_metadata';
			$dt_contextual_second_title   = __( 'Additional Options', 'dt-slides' );
			$dt_contextual_second_content =
			
			'<p>'  . __( 'Only the options below are required, but jQuery Cycle has <a href="http://jquery.malsup.com/cycle/options.html">additional options</a> that can be changed <a href="http://www.jleuze.com/plugins/dt-slides/using-metadata/">using metadata</a>.', 'dt-slides' ) . '</p>' .
			'<p>'  . __( 'Here is an example using metadata with the shortcode to set the slide order to random:', 'dt-slides' ) . '</p>' .
			'<p>'  . __( '<code>[dt_slideshow metadata="random: 1"]</code>', 'dt-slides' ) . '</p>';
			
			$dt_contextual_third_id      = 'slide_page_slides_settings_add';
			$dt_contextual_third_title   = __( 'Add Slideshow', 'dt-slides' );
			$dt_contextual_third_content =
			
			'<p>'  . __( "<strong>Template Tag</strong> - Use this template tag in a theme file: <code><&#63;php if ( function_exists( 'dt_slideshow' ) ) { dt_slideshow(); } &#63;></code>", 'dt-slides' ) . '</p>' .
			'<p>'  . __( "<strong>Shortcode</strong> - Use this shortcode to add a slideshow via the Post or Page editor: <code>[dt_slideshow]</code>", 'dt-slides' ) . '</p>' .
			'<p>'  . __( '<strong>Widget</strong> - Use the dt Slides Widget to add a slideshow to a widgetized area.', 'dt-slides' ) . '</p>' .
			'<p>'  . __( 'Check out the documentation on <a href="http://www.jleuze.com/plugins/dt-slides/adding-a-slideshow/" target="_blank">adding a slideshow</a> for more info.', 'dt-slides' ) . '</p>';
		
			$dt_contextual_sidebar =
			
			'<p><strong>' . __( 'For more information', 'dt-slides' ) . '</strong></p>' .
			'<p>'  . __( '<a href="http://www.jleuze.com/plugins/dt-slides/installation/" target="_blank">Documentation on Configuring dt Slides</a>', 'dt-slides' ) . '</p>' .
			'<p>'  . __( '<a href="http://wordpress.org/tags/dt-slides" target="_blank">Plugin Support Forum</a>', 'dt-slides' ) . '</p>' .
			'<p>'  . __( '<a class="button" href="http://jleuze.com/donate/" target="_blank">Donate</a>', 'dt-slides' ) . '</p>';
			
			$dt_contextual_screen->add_help_tab( array(

				'id'      => $dt_contextual_first_id,
				'title'   => $dt_contextual_first_title,
				'content' => $dt_contextual_first_content

			) );
			
			$dt_contextual_screen->add_help_tab( array(

				'id'      => $dt_contextual_second_id,
				'title'   => $dt_contextual_second_title,
				'content' => $dt_contextual_second_content

			) );
			
			$dt_contextual_screen->add_help_tab( array(

				'id'      => $dt_contextual_third_id,
				'title'   => $dt_contextual_third_title,
				'content' => $dt_contextual_third_content

			) );
		
		}
		
		$dt_contextual_screen->set_help_sidebar( $dt_contextual_sidebar );
		
	}

	// Adds Slideshow settings page
	
	function dtslides_set_options_cap(){

		if ( function_exists( 'members_get_capabilities' ) ) {
	
			return 'dtslides_manage_options';
		
		} else {
	
			return 'manage_options';
		
		}
	
	}
	
	add_filter( 'option_page_capability_dtslides_options', 'dtslides_options_cap' );
	
	function dtslides_options_cap( $capability ) {
	
		if ( function_exists( 'members_get_capabilities' ) ) {
	
			return 'dtslides_manage_options';
		
		} else {
	
			return 'manage_options';
		
		}
		
	}
		
	add_action( 'admin_menu', 'dtslides_menu' );

	function dtslides_menu() {
		
		add_submenu_page( 'edit.php?post_type=dtslide', __( 'Slides Settings', 'dt-slides' ), __( 'Settings', 'dt-slides' ), dtslides_set_options_cap(),  'slides_settings', 'dtslides_settings_page' );
		
	}
	
	function dtslides_settings_page() {
		
		include( 'dt-slides-settings.php' );
	
	}
	
	// Add custom slide capabilities for the Members plugin
	
	add_action( 'admin_init', 'dtslides_members_capabilities' );

	function dtslides_members_capabilities() {
	
		if ( function_exists( 'members_get_capabilities' ) ) {
	
			add_filter( 'members_get_capabilities', 'dtslides_add_members_caps' );
		
		}
	
	}

	function dtslides_add_members_caps( $caps ) {
	
		$caps[] = 'dtslides_manage_options';
		$caps[] = 'dtslides_edit_slide';
		$caps[] = 'dtslides_edit_slides';
		$caps[] = 'dtslides_edit_others_slides';
		$caps[] = 'dtslides_publish_slides';
		$caps[] = 'dtslides_read_slides';
		$caps[] = 'dtslides_read_private_slides';
		$caps[] = 'dtslides_delete_slide';
		$caps[] = 'dtslides_manage_slideshows';
		
		return $caps;
		
	}
	
	// Adds link to settings page on plugins page
		
	add_filter( 'plugin_action_links', 'dtslides_settings_link', 10, 2 );
	
	function dtslides_settings_link( $dt_links, $dt_file ) {
		
		if ( $dt_file == plugin_basename( 'dt-slides/dt-slides-plugin.php' ) ) {
		
			$dt_links[] = '<a href="edit.php?post_type=slide&page=slides_settings">'.__( 'Settings', 'dt-slides' ).'</a>';
	
		}
		
		return $dt_links;
		
	}
	
	// Register options for settings page

	add_action( 'admin_init', 'dtslides_register_settings' );
	
	function dtslides_register_settings() {

		register_setting( 'dtslides_options', 'dtslides_options' );
		
		add_settings_section( 'dtslides_slideshow', __( 'Configure Slideshow', 'dt-slides' ), 'dtslides_section_text', 'dtslides' );

		add_settings_field( 'slide_height', __( 'Slide Height', 'dt-slides' ), 'dtslides_slide_height', 'dtslides', 'dtslides_slideshow' );
		
		add_settings_field( 'slide_width', __( 'Slide Width', 'dt-slides' ), 'dtslides_slide_width', 'dtslides', 'dtslides_slideshow' );

		add_settings_field( 'transition_style', __( 'Transition Style', 'dt-slides' ), 'dtslides_transition_style', 'dtslides', 'dtslides_slideshow' );

		add_settings_field( 'slide_duration', __( 'Slide Duration', 'dt-slides' ), 'dtslides_slide_duration', 'dtslides', 'dtslides_slideshow' );
	
		add_settings_field( 'slideshow_navigation', __( 'Slideshow Navigation', 'dt-slides' ), 'dtslides_slideshow_navigation', 'dtslides', 'dtslides_slideshow' );
		
		add_settings_field( 'slide_autoplay', __( 'Slide Autoplay', 'dt-slides' ), 'dtslides_slide_autoplay', 'dtslides', 'dtslides_slideshow' );

	}
	
	// Validates values for options on settings page
	
	function dtslides_options_validate( $dt_input ) {

		$dt_options = get_option( 'dtslides_options' );

		$dt_options['slideshow_quantity'] = trim( $dt_input['slideshow_quantity'] );

		if ( !preg_match( '/^[0-9]{1,3}$/i', $dt_options['slideshow_quantity'] ) ) {

			$dt_options['slideshow_quantity'] = '';

		}
		
		$dt_options['slide_height'] = trim( $dt_input['slide_height'] );

		if ( !preg_match( '/^[0-9]{1,4}$/i', $dt_options['slide_height'] ) ) {

			$dt_options['slide_height'] = '';

		}
		
		$dt_options['slide_width'] = trim( $dt_input['slide_width'] );

		if ( !preg_match( '/^[0-9]{1,5}$/i', $dt_options['slide_width'] ) ) {

			$dt_options['slide_width'] = '';

		}
		
		$dt_options['transition_style'] = trim( $dt_input['transition_style'] );

		if ( !preg_match( '/^[a-z]{4,20}$/i', $dt_options['transition_style'] ) ) {

			$dt_options['transition_style'] = '';

		}
		
		$dt_options['transition_speed'] = trim( $dt_input['transition_speed'] );

		if ( !preg_match( '/^[0-9]{1,3}$/i', $dt_options['transition_speed'] ) ) {

			$dt_options['transition_speed'] = '';

		}
		
		$dt_options['slide_duration'] = trim( $dt_input['slide_duration'] );

		if ( !preg_match( '/^[0-9]{1,3}$/i', $dt_options['slide_duration'] ) ) {

			$dt_options['slide_duration'] = '';

		}
		
		$dt_options['slideshow_navigation'] = trim( $dt_input['slideshow_navigation'] );

		if ( !preg_match( '/^[a-z]{4,20}$/i', $dt_options['slideshow_navigation'] ) ) {

			$dt_options['slideshow_navigation'] = '';

		}

		return $dt_options;
		
	}
	
	// Adds translation support for language files
	
	add_action( 'plugins_loaded', 'dtslides_localization' );

	function dtslides_localization() {
		
		load_plugin_textdomain( 'dt-slides', false, '/dt-slides/languages/' );
		
	}

	// Adds CSS for the Slides admin pages
	
	add_action( 'admin_enqueue_scripts', 'dtslides_admin_css' );

	function dtslides_admin_css() {
		
		global $post_type;
				
		if ( ( isset( $_GET['post_type'] ) && $_GET['post_type'] == 'dtslide' ) || ( isset( $post_type ) && $post_type == 'slide' ) ) {
	
			wp_enqueue_style( 'dt-slides-admin', plugins_url( 'dt-slides/css/dt-slides-admin.css' ), array(), '1.0' );
	
		}
		
	}

?>