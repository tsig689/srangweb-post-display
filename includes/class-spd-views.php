<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SPD_Views {

	const META_KEY = 'spd_post_views';

	public static function increment( $post_id ) {
		if ( ! $post_id || 'post' !== get_post_type( $post_id ) ) {
			return;
		}

		// ไม่นับ admin/editor ที่ล็อกอินอยู่
		if ( is_user_logged_in() && current_user_can( 'edit_posts' ) ) {
			return;
		}

		$cookie_name = 'spd_viewed_' . $post_id;

		// กันรีเฟรชรัว ๆ ภายใน 1 ชั่วโมง
		if ( isset( $_COOKIE[ $cookie_name ] ) ) {
			return;
		}

		$count = (int) get_post_meta( $post_id, self::META_KEY, true );
		$count++;

		update_post_meta( $post_id, self::META_KEY, $count );

		setcookie(
			$cookie_name,
			'1',
			time() + HOUR_IN_SECONDS,
			COOKIEPATH ? COOKIEPATH : '/',
			COOKIE_DOMAIN,
			is_ssl(),
			true
		);
	}

	public static function get_views( $post_id ) {
		$post_id = absint( $post_id );

		if ( ! $post_id ) {
			return 0;
		}

		return (int) get_post_meta( $post_id, self::META_KEY, true );
	}
}
