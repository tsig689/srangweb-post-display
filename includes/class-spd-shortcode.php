<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SPD_Shortcode {
	public function register() {
		add_shortcode( 'sw_posts', array( $this, 'render_posts_shortcode' ) );
		add_shortcode( 'sw_post_views', array( $this, 'render_post_views_shortcode' ) );
	}

	public function render_posts_shortcode( $atts ) {
		$atts = shortcode_atts(
			array(
				'source'            => 'latest',
				'category'          => '',
				'categories'        => '',
				'tag'               => '',
				'ids'               => '',
				'limit'             => 6,
				'columns'           => 3,
				'pagination'        => 'false',
				'pager_id'          => 'main',
				'show_image'        => 'true',
				'show_excerpt'      => 'true',
				'excerpt_length'    => 18,
				'show_date'         => 'true',
				'show_category'     => 'false',
				'show_views'        => 'false',
				'show_filter'       => 'false',
				'filter_all_label'  => 'All',
				'orderby'           => 'date',
				'order'             => 'DESC',
				'class'             => '',
				'display'           => 'card', // card | title
				'title_tag'         => 'h3',   // used by card mode
				'title_list_style'  => 'ul',   // ul | ol | div
				'show_title_link'   => 'true',
			),
			$atts,
			'sw_posts'
		);

		$allowed_sources    = array( 'latest', 'category', 'tag', 'related', 'ids' );
		$allowed_orderby    = array( 'date', 'title', 'modified', 'rand', 'menu_order' );
		$allowed_order      = array( 'ASC', 'DESC' );
		$allowed_display    = array( 'card', 'title' );
		$allowed_title_tags = array( 'h2', 'h3', 'h4', 'div', 'p', 'span' );
		$allowed_list_style = array( 'ul', 'ol', 'div' );

		$atts['source']           = in_array( sanitize_key( $atts['source'] ), $allowed_sources, true ) ? sanitize_key( $atts['source'] ) : 'latest';
		$atts['category']         = sanitize_title( $atts['category'] );
		$atts['categories']       = SPD_Helpers::sanitize_csv_slugs( $atts['categories'] );
		$atts['tag']              = sanitize_title( $atts['tag'] );
		$atts['ids']              = SPD_Helpers::sanitize_csv_ids( $atts['ids'] );
		$atts['limit']            = SPD_Helpers::sanitize_limit( $atts['limit'] );
		$atts['columns']          = SPD_Helpers::sanitize_columns( $atts['columns'] );
		$atts['pagination']       = SPD_Helpers::to_bool( $atts['pagination'] );
		$atts['pager_id']         = sanitize_key( $atts['pager_id'] );
		$atts['show_image']       = SPD_Helpers::to_bool( $atts['show_image'] );
		$atts['show_excerpt']     = SPD_Helpers::to_bool( $atts['show_excerpt'] );
		$atts['excerpt_length']   = SPD_Helpers::sanitize_excerpt_length( $atts['excerpt_length'] );
		$atts['show_date']        = SPD_Helpers::to_bool( $atts['show_date'] );
		$atts['show_category']    = SPD_Helpers::to_bool( $atts['show_category'] );
		$atts['show_views']       = SPD_Helpers::to_bool( $atts['show_views'] );
		$atts['show_filter']      = SPD_Helpers::to_bool( $atts['show_filter'] );
		$atts['filter_all_label'] = sanitize_text_field( $atts['filter_all_label'] );
		$atts['orderby']          = in_array( sanitize_key( $atts['orderby'] ), $allowed_orderby, true ) ? sanitize_key( $atts['orderby'] ) : 'date';
		$atts['order']            = in_array( strtoupper( sanitize_text_field( $atts['order'] ) ), $allowed_order, true ) ? strtoupper( sanitize_text_field( $atts['order'] ) ) : 'DESC';
		$atts['class']            = SPD_Helpers::sanitize_custom_classes( $atts['class'] );
		$atts['display']          = in_array( sanitize_key( $atts['display'] ), $allowed_display, true ) ? sanitize_key( $atts['display'] ) : 'card';
		$atts['title_tag']        = in_array( strtolower( sanitize_key( $atts['title_tag'] ) ), $allowed_title_tags, true ) ? strtolower( sanitize_key( $atts['title_tag'] ) ) : 'h3';
		$atts['title_list_style'] = in_array( strtolower( sanitize_key( $atts['title_list_style'] ) ), $allowed_list_style, true ) ? strtolower( sanitize_key( $atts['title_list_style'] ) ) : 'ul';
		$atts['show_title_link']  = SPD_Helpers::to_bool( $atts['show_title_link'] );

		$paged         = SPD_Pagination::get_current_page( $atts['pager_id'] );
		$query_builder = new SPD_Query();
		$query         = $query_builder->build_query( $atts, $paged );
		$renderer      = new SPD_Render();

		ob_start();

		$wrap_class = 'spd-wrap';
		if ( ! empty( $atts['class'] ) ) {
			$wrap_class .= ' ' . $atts['class'];
		}

		if ( 'title' === $atts['display'] ) {
			$wrap_class .= ' spd-wrap-title-only';
		}

		echo '<div class="' . esc_attr( $wrap_class ) . '" data-spd-source="' . esc_attr( $atts['source'] ) . '" data-spd-display="' . esc_attr( $atts['display'] ) . '">';

		if ( $atts['show_filter'] ) {
			echo SPD_Filter::render_category_filter( $atts );
		}

		echo $renderer->render_posts( $query, $atts );

		if ( $atts['pagination'] && $query->max_num_pages > 1 ) {
			echo SPD_Pagination::render( $query->max_num_pages, $paged, $atts['pager_id'] );
		}

		echo '</div>';

		wp_reset_postdata();

		return ob_get_clean();
	}

	public function render_post_views_shortcode( $atts ) {
		$atts = shortcode_atts(
			array(
				'icon'  => 'true',
				'label' => 'false',
				'text'  => '',
				'class' => '',
			),
			$atts,
			'sw_post_views'
		);

		if ( ! is_singular( 'post' ) ) {
			return '';
		}

		$post_id = get_the_ID();
		if ( ! $post_id ) {
			return '';
		}

		$views         = SPD_Views::get_views( $post_id );
		$show_icon     = SPD_Helpers::to_bool( $atts['icon'] );
		$show_label    = SPD_Helpers::to_bool( $atts['label'] );
		$text          = sanitize_text_field( $atts['text'] );
		$classes       = 'spd-post-views-inline';
		$extra_classes = SPD_Helpers::sanitize_custom_classes( $atts['class'] );

		if ( ! empty( $extra_classes ) ) {
			$classes .= ' ' . $extra_classes;
		}

		$output = '';

		if ( ! empty( $text ) ) {
			$output .= $text . ' ';
		}

		if ( $show_icon ) {
			$output .= '👁 ';
		}

		$output .= SPD_Helpers::format_views( $views );

		if ( $show_label ) {
			$output .= ' ครั้ง';
		}

		return '<span class="' . esc_attr( $classes ) . '">' . esc_html( $output ) . '</span>';
	}
}
