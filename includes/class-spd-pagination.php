<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SPD_Pagination {

	public static function get_current_page( $pager_id = 'main' ) {
		$pager_id  = sanitize_key( $pager_id );
		$query_key = 'spd_page_' . $pager_id;

		if ( isset( $_GET[ $query_key ] ) ) {
			$page = absint( wp_unslash( $_GET[ $query_key ] ) );
			return max( 1, $page );
		}

		return 1;
	}

	public static function render( $max_pages, $current_page, $pager_id = 'main' ) {
		$max_pages    = absint( $max_pages );
		$current_page = absint( $current_page );
		$pager_id     = sanitize_key( $pager_id );

		if ( $max_pages <= 1 ) {
			return '';
		}

		$query_key = 'spd_page_' . $pager_id;
		$base_url  = remove_query_arg( $query_key );
		$links     = paginate_links(
			array(
				'base'      => esc_url( add_query_arg( $query_key, '%#%', $base_url ) ),
				'format'    => '',
				'current'   => max( 1, $current_page ),
				'total'     => max( 1, $max_pages ),
				'mid_size'  => 1,
				'prev_text' => '&laquo;',
				'next_text' => '&raquo;',
				'type'      => 'list',
			)
		);

		if ( empty( $links ) ) {
			return '';
		}

		return '<nav class="spd-pagination" aria-label="Posts Pagination">' . $links . '</nav>';
	}
}
