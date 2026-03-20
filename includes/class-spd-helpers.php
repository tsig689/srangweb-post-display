<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SPD_Helpers {
	public static function to_bool( $value ) {
		if ( is_bool( $value ) ) {
			return $value;
		}
		$value = strtolower( trim( (string) $value ) );
		return in_array( $value, array( '1', 'true', 'yes', 'on' ), true );
	}

	public static function sanitize_csv_ids( $value ) {
		if ( is_array( $value ) ) {
			$ids = $value;
		} else {
			$ids = explode( ',', (string) $value );
		}
		$ids = array_map( 'absint', $ids );
		$ids = array_filter( $ids );
		return array_values( array_unique( $ids ) );
	}

	public static function sanitize_csv_slugs( $value ) {
		if ( is_array( $value ) ) {
			$slugs = $value;
		} else {
			$slugs = explode( ',', (string) $value );
		}
		$slugs = array_map( 'sanitize_title', $slugs );
		$slugs = array_filter( $slugs );
		return array_values( array_unique( $slugs ) );
	}

	public static function sanitize_columns( $value ) {
		$value = absint( $value );
		if ( $value < 1 ) {
			$value = 1;
		}
		if ( $value > 4 ) {
			$value = 4;
		}
		return $value;
	}

	public static function sanitize_limit( $value ) {
		$value = absint( $value );
		if ( $value < 1 ) {
			$value = 6;
		}
		if ( $value > 50 ) {
			$value = 50;
		}
		return $value;
	}

	public static function sanitize_excerpt_length( $value ) {
		$value = absint( $value );
		if ( $value < 1 ) {
			$value = 18;
		}
		if ( $value > 80 ) {
			$value = 80;
		}
		return $value;
	}

	public static function get_excerpt( $post_id, $length = 18 ) {
		$length = self::sanitize_excerpt_length( $length );
		$excerpt = get_the_excerpt( $post_id );
		if ( empty( $excerpt ) ) {
			$content = get_post_field( 'post_content', $post_id );
			$content = strip_shortcodes( $content );
			$content = wp_strip_all_tags( $content );
			return wp_trim_words( $content, $length, '...' );
		}
		return wp_trim_words( $excerpt, $length, '...' );
	}

	public static function get_first_category_name( $post_id ) {
		$terms = get_the_category( $post_id );
		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			return $terms[0]->name;
		}
		return '';
	}

	public static function format_views( $views ) {
		return number_format_i18n( (int) $views );
	}

	public static function sanitize_custom_classes( $class_string ) {
		$class_parts = preg_split( '/\s+/', trim( (string) $class_string ) );
		$class_parts = array_filter( array_map( 'sanitize_html_class', $class_parts ) );
		return implode( ' ', $class_parts );
	}
}
