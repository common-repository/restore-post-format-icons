<?php
/*
Plugin Name: Restore Post Format Icons
Description: Restores post format icons in list tables, removed in WordPress 5.2.
Author: Sergey Biryukov
Author URI: http://profiles.wordpress.org/sergeybiryukov/
Version: 1.0
*/ 

class Restore_Post_Format_Icons {

	function __construct() {
		add_filter( 'manage_posts_columns',       array( $this, 'add_column' ), 10, 2 );
		add_action( 'manage_posts_custom_column', array( $this, 'display_column' ), 10, 2 );
		add_action( 'admin_enqueue_scripts',      array( $this, 'add_inline_style' ) );
	}

	function add_column( $posts_columns, $post_type ) {
		$filtered_columns = array();

		foreach ( $posts_columns as $key => $column ) {
			if ( 'title' === $key ) {
				$filtered_columns['format'] = '<span class="dashicons dashicons-images-alt"></span>';
			}

			$filtered_columns[ $key ] = $column;
		}

		return $filtered_columns;
	}

	function display_column( $column_name, $post_id ) {
		if ( 'format' !== $column_name ) {
			return;
		}

		$format = get_post_format( $post_id );
		if ( $format ) {
			printf( '<span class="post-format-icon post-format-%s"></span>', esc_attr( $format ) );
		} else {
			echo '<span aria-hidden="true">&#8212;</span>';
		}
	}

	function add_inline_style( $hook_suffix ) {
		if ( 'edit.php' !== $hook_suffix ) {
			return;
		}

		wp_add_inline_style( 'wp-admin', '.fixed .column-format { width: 1em }' );
	}

}

new Restore_Post_Format_Icons;
?>