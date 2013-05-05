<?php
/*
	Plugin Name: DT Slides
	Description: Easily create responsive slideshows with WordPress that are mobile friendly and simple to customize.
	Plugin URI: http://www.dynamicthemes.net/
	Author: Josh Leuze
	Author URI: http://www.dynamicthemes.net/
	License: GPL2
	Version: 1.0
*/

/*  Copyright 2013 dynamicteam (email : dynamicthemes@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

	// Adds custom post type for Slides
	
	add_action( 'init', 'dtslides_register_slides' );

	function dtslides_register_slides() {
	
		$dt_labels = array(

			'name'               => __( 'DTSlides', 'dt-slides' ),
			'singular_name'      => __( 'DTSlide', 'dt-slides' ),
			'add_new'            => __( 'Add New', 'dt-slides' ),
			'add_new_item'       => __( 'Add New DTSlide', 'dt-slides' ),
			'edit_item'          => __( 'Edit DTSlide', 'dt-slides' ),
			'new_item'           => __( 'New DTSlide', 'dt-slides' ),
			'view_item'          => __( 'View DTSlide', 'dt-slides' ),
			'search_items'       => __( 'Search DTSlides', 'dt-slides' ),
			'not_found'          => __( 'No dtslides found', 'dt-slides' ),
			'not_found_in_trash' => __( 'No slides found in Trash', 'dt-slides' ), 
			'parent_item_colon'  => '',
			'menu_name'          => __( 'DTSlides', 'dt-slides' )

		);
				
		if ( function_exists( 'members_get_capabilities' ) ) {
	
			$dt_capabilities = array(
		
				'edit_post'          => 'dtslides_edit_slide',
				'edit_posts'         => 'dtslides_edit_slides',
				'edit_others_posts'  => 'dtslides_edit_others_slides',
				'publish_posts'      => 'dtslides_publish_slides',
				'read_post'          => 'dtslides_read_slide',
				'read_private_posts' => 'dtslides_read_private_slides',
				'delete_post'        => 'dtslides_delete_slide'

			);
			
			$dt_capabilitytype = 'dtslide';
			
			$dt_mapmetacap = false;
		
		} else {
		
			$dt_capabilities = array(
		
				'edit_post'          => 'edit_post',
				'edit_posts'         => 'edit_posts',
				'edit_others_posts'  => 'edit_others_posts',
				'publish_posts'      => 'publish_posts',
				'read_post'          => 'read_post',
				'read_private_posts' => 'read_private_posts',
				'delete_post'        => 'delete_post'

			);
			
			$dt_capabilitytype = 'post';
			
			$dt_mapmetacap = true;
		
		}
		
		$dt_args = array(
	
			'labels'              => $dt_labels,
			'public'              => true,
			'publicly_queryable'  => false,
			'exclude_from_search' => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_icon'           => ''. plugins_url( '/images/slides-icon-16x16.png', __FILE__ ),
			'capability_type'     => $dt_capabilitytype,
			'capabilities'        => $dt_capabilities,
			'map_meta_cap'        => $dt_mapmetacap,
			'hierarchical'        => false,
			'supports'            => array( 'title', 'thumbnail' ),
			'taxonomies'          => array( 'slideshow' ),
			'has_archive'         => false,
			'rewrite'             => false,
			'query_var'           => true,
			'can_export'          => true,
			'show_in_nav_menus'   => false
		
		);
  
		register_post_type( 'dtslide', $dt_args );
		
	}

	// Adds custom taxonomy for Slideshows
	
	add_action( 'init', 'dtslides_register_taxonomy' );
	
	function dtslides_register_taxonomy() {
	
		$dt_tax_labels = array(
				
			'name'              => __( 'Slideshows', 'dt-slides' ),
			'singular_name'     => __( 'Slideshow', 'dt-slides' ),
			'search_items'      => __( 'Search Slideshows', 'dt-slides' ),
			'popular_items'     => __( 'Popular Slideshows', 'dt-slides' ),
			'all_items'         => __( 'All Slideshows', 'dt-slides' ),
			'parent_item'       => __( 'Parent Slideshow', 'dt-slides' ),
			'parent_item_colon' => __( 'Parent Slideshow:', 'dt-slides' ),
			'edit_item'         => __( 'Edit Slideshow', 'dt-slides' ),
			'update_item'       => __( 'Update Slideshow', 'dt-slides' ),
			'add_new_item'      => __( 'Add New Slideshow', 'dt-slides' ),
			'new_item_name'     => __( 'New Slideshow Name', 'dt-slides' ),
			'menu_name'         => __( 'Slideshows', 'dt-slides' )
				
		);
		
		if ( function_exists( 'members_get_capabilities' ) ) {
	
			$dt_tax_capabilities = array(
		
				'manage_terms' => 'dtslides_manage_slideshows',
				'edit_terms'   => 'dtslides_manage_slideshows',
				'delete_terms' => 'dtslides_manage_slideshows',
				'assign_terms' => 'dtslides_edit_slides'

			);
		
		} else {
		
			$dt_tax_capabilities = array(
		
				'manage_terms' => 'manage_categories',
				'edit_terms'   => 'manage_categories',
				'delete_terms' => 'manage_categories',
				'assign_terms' => 'edit_posts'

			);
		
		}
		
		$dt_tax_args = array(
	
			'labels'            => $dt_tax_labels,
			'public'            => true,
			'show_in_nav_menus' => false,
			'show_ui'           => true,
			'show_tagcloud'     => false,
			'hierarchical'      => true,
			'rewrite'           => array( 'slug' => 'slideshow' ),
			'capabilities'      => $dt_tax_capabilities
		
		);
	
		register_taxonomy( 'slideshow', 'dtslide', $dt_tax_args );
		
	}
	
	// Adds featured image functionality for Slides
	
	add_action( 'after_setup_theme', 'dtslides_featured_image_array', '9999' );

	function dtslides_featured_image_array() {
	
		global $_wp_theme_features;

		if ( !isset( $_wp_theme_features['post-thumbnails'] ) ) {
		
			$_wp_theme_features['post-thumbnails'] = array( array( 'dtslide' ) );
			
		}

		elseif ( is_array( $_wp_theme_features['post-thumbnails'] ) ) {
        
			$_wp_theme_features['post-thumbnails'][0][] = 'dtslide';
			
		}
		
	}
	
	// Adds featured image size for Slides
	
	add_action( 'plugins_loaded', 'dtslides_featured_image' );
	
	function dtslides_featured_image() {
		
		$dt_options = get_option( 'dtslides_options' );
				
		add_image_size( 'featured-slide', $dt_options['slide_width'], $dt_options['slide_height'], true );
		
		add_image_size( 'featured-slide-thumb', 250, 9999 );
	
	}
	
	// Adds CSS for the slideshow
	
	add_action( 'wp_enqueue_scripts', 'dtslides_css' );

	function dtslides_css() {
				
			wp_enqueue_style( 'dt-slides', plugins_url('/css/dt-slides.css', __FILE__), array(), '1.0' );
			
	}
	
	// Adds JavaScript for the slideshow
	
	add_action( 'wp_print_scripts', 'dtslides_javascript' );
		
	function dtslides_javascript() {
 		
		$dt_options = get_option( 'dtslides_options' );
 
		if( !is_admin() ) {
	  
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'jquery-flux', plugins_url( '/js/flux.js', __FILE__ ), array( 'jquery' ) );
			
			wp_enqueue_script( 'dtslides-script', plugins_url( '/js/slideshow.js', __FILE__ ), array( 'jquery', 'jquery-flux' ) );
			
			wp_localize_script( 'dtslides-script', 'dtslidessettings',
			
				array(
				
					'dtslideshowduration'   => $dt_options['slide_duration'] * 1000,
					'dtslideshowheight'     => $dt_options['slide_height'],
					'dtslideshowwidth'      => $dt_options['slide_width'],
					'dtslideshowtransition' => $dt_options['transition_style'],
					'dtslideshownavigation' => $dt_options['slideshow_navigation'],
					'dtslideshowautoplay'   => $dt_options['slide_autoplay']
					
				)
				
			);
			
		}
	
	}
	
	// Load admin functions if in the backend
	
	if ( is_admin() ) {
	
		require_once( 'includes/dt-slides-admin.php' );
		
	}
	
	// Adds default values for options on settings page
	
	register_activation_hook( __FILE__, 'dtslides_default_options' );
	
	function dtslides_default_options() {
	
		$dt_temp = get_option( 'dtslides_options' );
		
		if ( ( $dt_temp['slideshow_quantity']=='' )||( !is_array( $dt_temp ) ) ) {

			$dt_defaults_args = array(
			
				'slide_height'         => '200',
				'slide_width'          => '940',
				'transition_style'     => '0',
				'slide_duration'       => '5',
				'slideshow_navigation' => 'navnone',
				'slide_autoplay'       => 'On'
				
			);	
			
			update_option( 'dtslides_options', $dt_defaults_args );
	
		}

	}
	
	// Adds function to load slideshow in theme
	
	function dt_slideshow( $slideshow='', $metadata='' ) {
	
		include( 'includes/dt-slideshow.php' );
		
	}
		
		/* To load the slideshow, add this line to your theme:
	
			<?php if(function_exists('dt_slideshow')) { dt_slideshow(); } ?>
	
		*/
		
	// Adds shortcode to load slideshow in content
	
	function dt_slideshow_shortcode( $dt_atts ) {
	
		extract( shortcode_atts( array (
		
			'slideshow' => '',
			'metadata'  => '',
			
		), $dt_atts ) );
		
		$slideshow_att = $slideshow;
		
		$metadata_att = $metadata;
	
		ob_start();
		
		dt_slideshow( $slideshow=$slideshow_att, $metadata=$metadata_att );
		
		$dt_slideshow_content = ob_get_clean();
		
		return $dt_slideshow_content;
	
	}
	
	add_shortcode( 'dt_slideshow', 'dt_slideshow_shortcode' );
	
		/* To load the slideshow, add this line to your page or post:
	
			[dt_slideshow]
	
		*/
		
	// Adds widget to load slideshow in sidebar

	add_action( 'widgets_init', 'dtslides_register_widget' );

	function dtslides_register_widget() {
	
		register_widget( 'dtslides_widget' );
	
	}

	class dtslides_widget extends WP_Widget {

		function dtslides_widget() {

			$widget_ops = array(
			
				'classname'   => 'dt-slides-widget',
				'description' => __( 'Add a slideshow widget to a sidebar', 'dt-slides' )
			
			);

			$control_ops = array( 'id_base' => 'dt-slides-widget' );

			$this->WP_Widget( 'dt-slides-widget', __( 'dt Slides Widget', 'dt-slides' ), $widget_ops, $control_ops );
		}

		function widget( $args, $instance ) {
		
			extract( $args );
						
			$title         = apply_filters( 'widget_title', $instance['title'] );
			$slideshow_arg = $instance['slideshow'];
			$metadata_arg  = $instance['metadata'];

			echo $before_widget;
			
			if ( $title ) {
			
				echo $before_title . $title . $after_title;
				
			}
			
			dt_slideshow( $slideshow=$slideshow_arg, $metadata=$metadata_arg );

			echo $after_widget;
		
		}

		function update( $new_instance, $old_instance ) {
		
			$instance = $old_instance;

			$instance['title']     = strip_tags( $new_instance['title'] );
			$instance['slideshow'] = strip_tags( $new_instance['slideshow'] );
			$instance['metadata']  = strip_tags( $new_instance['metadata'] );

			return $instance;
		
		}
		
		function form( $instance ) {
		
			$defaults = array(
			
				'title'     => '',
				'slideshow' => '',
				'metadata'  => ''
				
			);
			
			$instance = wp_parse_args( (array) $instance, $defaults );
		
			echo '<p><label for="' . $this->get_field_id( 'title' ) . '">' . __('Title:', 'dt-slides') . '</label>
			<input type="text" class="widefat" id="' . $this->get_field_id( 'title' ) . '" name="' . $this->get_field_name( 'title' ) . '" value="' . $instance['title'] . '" /></p>';
			
			// If the slideshow taxonomy has terms, create a select list of those terms
			
			$slideshow_terms       = get_terms( 'slideshow' );
			$slideshow_terms_count = count($slideshow_terms);
			$slideshow_value       = $instance['slideshow'];
			
			if ( $slideshow_terms_count > 0 ) {
			
				echo '<p><label for="' . $this->get_field_id( 'slideshow' ) . '">' . __('Slideshow:', 'dt-slides') . '</label>';
			
				echo '<select name="' . $this->get_field_name( 'slideshow' ) . '" id="' . $this->get_field_id( 'slideshow' ) . '" class="widefat">';
				
				echo '<option value="">All Slides</option>';
				
				foreach ( $slideshow_terms as $slideshow_terms ) {
				
					if ( $slideshow_terms->slug == $slideshow_value ) {
					
						echo '<option selected="selected" value="' . $slideshow_terms->slug . '">' . $slideshow_terms->name . '</option>';
					
					} else {
				
						echo '<option value="' . $slideshow_terms->slug . '">' . $slideshow_terms->name . '</option>';
					
					}
        
				}
					
				echo '</select>';
				
			}

			echo '<p><label for="' . $this->get_field_id( 'metadata' ) . '">' . __('Metadata:', 'dt-slides') . '</label>
			<input type="text" class="widefat" id="' . $this->get_field_id( 'metadata' ) . '" name="' . $this->get_field_name( 'metadata' ) . '" value="' . $instance['metadata'] . '" /></p>';
			
		}

	}
	
/*
	Now slides the silent dt on, and leaves
	A shining furrow, as thy thoughts in me.
*/
	
?>