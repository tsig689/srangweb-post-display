<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SPD_Query {

	public function build_query( $atts, $paged = 1 ) {
		$args = array(
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'posts_per_page'      => $atts['limit'],
			'ignore_sticky_posts' => true,
			'paged'               => $atts['pagination'] ? max( 1, $paged ) : 1,
			'orderby'             => $atts['orderby'],
			'order'               => $atts['order'],
		);

		switch ( $atts['source'] ) {
			case 'category':
				$args = $this->apply_category_source( $args, $atts );
				break;

			case 'tag':
				$args = $this->apply_tag_source( $args, $atts );
				break;

			case 'related':
				$args = $this->apply_related_source( $args );
				break;

			case 'ids':
				$args = $this->apply_ids_source( $args, $atts );
				break;

			case 'latest':
			default:
				$args = $this->apply_latest_source( $args, $atts );
				break;
		}

		$args = $this->apply_filter_query( $args, $atts );

		return new WP_Query( $args );
	}

	private function apply_latest_source( $args, $atts ) {
		if ( ! empty( $atts['category'] ) ) {
			$args['category_name'] = $atts['category'];
		}

		if ( ! empty( $atts['tag'] ) ) {
			$args['tag'] = $atts['tag'];
		}

		return $args;
	}

	private function apply_category_source( $args, $atts ) {
		if ( ! empty( $atts['category'] ) ) {
			$args['category_name'] = $atts['category'];
		} else {
			$args['post__in'] = array( 0 );
		}

		return $args;
	}

	private function apply_tag_source( $args, $atts ) {
		if ( ! empty( $atts['tag'] ) ) {
			$args['tag'] = $atts['tag'];
		} else {
			$args['post__in'] = array( 0 );
		}

		return $args;
	}

	private function apply_related_source( $args ) {
		if ( ! is_singular( 'post' ) ) {
			$args['post__in'] = array( 0 );
			return $args;
		}

		$current_post_id = get_queried_object_id();

		if ( ! $current_post_id ) {
			$args['post__in'] = array( 0 );
			return $args;
		}

		$args['post__not_in'] = array( $current_post_id );

		$category_ids = wp_get_post_categories( $current_post_id );
		$post_tags    = wp_get_post_tags( $current_post_id, array( 'fields' => 'ids' ) );

		if ( ! empty( $category_ids ) ) {
			$args['category__in'] = $category_ids;
			return $args;
		}

		if ( ! empty( $post_tags ) ) {
			$args['tag__in'] = $post_tags;
			return $args;
		}

		return $args;
	}

	private function apply_ids_source( $args, $atts ) {
		if ( empty( $atts['ids'] ) || ! is_array( $atts['ids'] ) ) {
			$args['post__in'] = array( 0 );
			return $args;
		}

		$args['post__in']            = $atts['ids'];
		$args['orderby']             = 'post__in';
		$args['posts_per_page']      = min( count( $atts['ids'] ), $atts['limit'] );
		$args['ignore_sticky_posts'] = true;

		return $args;
	}

	private function apply_filter_query( $args, $atts ) {
		if ( empty( $atts['show_filter'] ) ) {
			return $args;
		}

		$active_slug = SPD_Filter::get_active_category_slug( $atts['pager_id'] );

		if ( empty( $active_slug ) ) {
			return $args;
		}

		$args['category_name'] = $active_slug;

		return $args;
	}
}
