<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SPD_Views {
	const META_KEY = '_spd_post_views';

	public static function register_hooks() {
		add_action( 'wp', array( __CLASS__, 'maybe_track_view' ) );
	}

	public static function maybe_track_view() {
		if ( is_admin() || ! is_singular( 'post' ) ) {
			return;
		}
		$post_id = get_queried_object_id();
		if ( $post_id ) {
			self::increment_views( $post_id );
		}
	}

	public static function increment_views( $post_id ) {
		$views = (int) get_post_meta( $post_id, self::META_KEY, true );
		$views++;
		update_post_meta( $post_id, self::META_KEY, $views );
	}

	public static function get_views( $post_id ) {
		return (int) get_post_meta( $post_id, self::META_KEY, true );
	}
}
