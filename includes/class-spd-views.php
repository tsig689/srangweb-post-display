<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SPD_Views {

	public static function maybe_track_view() {
		if ( ! is_singular( 'post' ) ) {
			return;
		}

		$post_id = get_queried_object_id();
		if ( ! $post_id ) {
			return;
		}

		$views = (int) get_post_meta( $post_id, '_spd_views', true );
		update_post_meta( $post_id, '_spd_views', $views + 1 );
	}

	public static function get_views( $post_id ) {
		return (int) get_post_meta( $post_id, '_spd_views', true );
	}
}
