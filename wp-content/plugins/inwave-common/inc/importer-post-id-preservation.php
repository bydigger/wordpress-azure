<?php
/**
 * Plugin Name: WordPress Importer Post ID Preservation
 * Description: When importing posts make sure that post ID from the original site is used on the destination site. This should only be used when first setting up an environment, or if the destination site is not canonical (e.g. a dev or staging environment).
 * Author: Weston Ruter, XWP
 */

/**
 * Force WordPress Importer to never honor the post_exists() check, since we want to override existing posts.
 *
 * This filter depends on a patch not yet committed to the WordPress Importer plugin.
 *
 * @link https://core.trac.wordpress.org/ticket/33721
 */
add_filter( 'wp_import_existing_post', function () {
	return 0;
} );

/**
 * Add the post ID to the array that is passed to wp_insert_post().
 */
add_filter( 'wp_import_post_data_processed', function ( $post_data, $post ) {
	/** @var wpdb $wpdb */
	global $wpdb;

	$eol = defined( 'WP_CLI' ) ? "\n" : '<br>';

	$existing_post = get_post( $post['post_id'] );

	/*
	 * If the post with the given ID does not exist yet, we must create a placeholder
	 * entry in the posts table so that wp_insert_post() won't return invalid_post
	 * when we supply an ID to coerce it to update a post instead of create one.
	 * See https://github.com/xwp/wordpress-develop/blob/f921391922076e558be59ad8caf89dfb0e506554/src/wp-includes/post-functions.php#L2859-L2876
	 */
	if ( ! $existing_post ) {
		printf( 'Post %d does not exist yet, creating placeholder%s', intval( $post['post_id'] ), $eol ); // xss ok

		/*
		 * Note we use REPLACE just in case another process did an INSERT after get_post().
		 * This ensures we do not get a Duplicate entry '481953' for key 'PRIMARY'
		 */
		$r = $wpdb->replace( $wpdb->posts, array(
			'ID' => $post['post_id'],
		) ); // WPCS: DB call ok

		if ( ! $r ) {
			printf( 'Error for post %d: %s%s', intval( $post['post_id'] ), esc_html( $wpdb->last_error ), $eol ); // xss ok
		}
	} else {
		printf( 'Post %d already exists, so re-using after purging related data%s', intval( $post['post_id'] ), $eol ); // xss ok

		wp_delete_object_term_relationships( $existing_post->ID, get_taxonomies() );

		// Delete postmeta because add_post_meta() is used by the importer, insted of update_post_meta().
		$wpdb->delete( $wpdb->postmeta, array(
			'post_id' => $existing_post->ID,
		) );
	}

	/*
	 * Now that we know the original import post ID corresponds to a post in the
	 * this site, we can include this ID here to be passed along to wp_insert_post()
	 * so that it will be updated.
	 *  we can force the inserted ID to be the same as the existing
	 */
	$post_data['ID'] = $post['post_id'];

	return $post_data;
}, 10, 2 );

/**
 * Forcibly invalidate post cache since wp_suspend_cache_invalidation is active.
 */
/*
add_action( 'wp_import_insert_post', function ( $post_id, $original_post_id, $postdata ) {
	unset( $original_post_id );
	wp_cache_delete( $post_id, 'posts' );

	// @todo Why is this not happening automatically upon save_post?
	if ( class_exists( '\CustomizeWidgetsPlus\Widget_Posts' ) && 'widget_instance' === $postdata['post_type'] ) {
		wp_cache_delete( $postdata['post_name'], \CustomizeWidgetsPlus\Widget_Posts::POST_NAME_TO_POST_ID_CACHE_GROUP );
	}
}, 10, 3 );*/

/**
 * Forcibly invalidate post meta cache since wp_suspend_cache_invalidation is active.
 */
/*
add_action( 'import_post_meta', function( $post_id ) {
    wp_cache_delete( $post_id, 'post_meta' );
} );*/
