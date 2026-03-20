<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SPD_Render {

	public function render_posts( $query, $atts ) {
		if ( ! $query->have_posts() ) {
			return '<div class="spd-empty">' . esc_html__( 'No posts found.', 'srangweb-post-display' ) . '</div>';
		}

		if ( isset( $atts['display'] ) && 'title' === $atts['display'] ) {
			return $this->render_title_list( $query, $atts );
		}

		$output = '<div class="spd-grid columns-' . esc_attr( absint( $atts['columns'] ) ) . '">';

		while ( $query->have_posts() ) {
			$query->the_post();
			$output .= $this->render_card( get_the_ID(), $atts );
		}

		$output .= '</div>';

		return $output;
	}

	private function render_title_list( $query, $atts ) {
		$list_style = ! empty( $atts['title_list_style'] ) ? $atts['title_list_style'] : 'ul';

		if ( 'none' === $list_style ) {
			$output = '<div class="spd-title-list spd-title-list-none">';

			while ( $query->have_posts() ) {
				$query->the_post();

				$post_id = get_the_ID();
				$title   = get_the_title( $post_id );
				$link    = get_permalink( $post_id );

				$output .= '<div class="spd-title-item">';

				if ( ! empty( $atts['show_title_link'] ) ) {
					$output .= '<a class="spd-title-link" href="' . esc_url( $link ) . '">' . esc_html( $title ) . '</a>';
				} else {
					$output .= '<span class="spd-title-text">' . esc_html( $title ) . '</span>';
				}

				$output .= '</div>';
			}

			$output .= '</div>';

			return $output;
		}

		$wrapper = in_array( $list_style, array( 'ul', 'ol' ), true ) ? $list_style : 'ul';

		$output = '<' . esc_attr( $wrapper ) . ' class="spd-title-list spd-title-list-' . esc_attr( $list_style ) . '">';

		while ( $query->have_posts() ) {
			$query->the_post();

			$post_id = get_the_ID();
			$title   = get_the_title( $post_id );
			$link    = get_permalink( $post_id );

			$output .= '<li class="spd-title-item">';

			if ( ! empty( $atts['show_title_link'] ) ) {
				$output .= '<a class="spd-title-link" href="' . esc_url( $link ) . '">' . esc_html( $title ) . '</a>';
			} else {
				$output .= '<span class="spd-title-text">' . esc_html( $title ) . '</span>';
			}

			$output .= '</li>';
		}

		$output .= '</' . esc_attr( $wrapper ) . '>';

		return $output;
	}

	private function render_card( $post_id, $atts ) {
		$title         = get_the_title( $post_id );
		$link          = get_permalink( $post_id );
		$excerpt       = SPD_Helpers::get_excerpt( $post_id, $atts['excerpt_length'] );
		$category_name = SPD_Helpers::get_first_category_name( $post_id );
		$views         = SPD_Views::get_views( $post_id );
		$title_tag     = ! empty( $atts['title_tag'] ) ? $atts['title_tag'] : 'h3';

		ob_start();
		?>
		<article class="spd-card">
			<a class="spd-card-link" href="<?php echo esc_url( $link ); ?>">
				<?php if ( $atts['show_image'] && has_post_thumbnail( $post_id ) ) : ?>
					<div class="spd-thumb">
						<?php echo get_the_post_thumbnail( $post_id, 'large', array( 'loading' => 'lazy' ) ); ?>
					</div>
				<?php endif; ?>

				<div class="spd-content">
					<?php if ( $atts['show_category'] && ! empty( $category_name ) ) : ?>
						<div class="spd-meta spd-meta-top"><?php echo esc_html( $category_name ); ?></div>
					<?php endif; ?>

					<<?php echo esc_attr( $title_tag ); ?> class="spd-title"><?php echo esc_html( $title ); ?></<?php echo esc_attr( $title_tag ); ?>>

					<?php if ( $atts['show_date'] || $atts['show_views'] ) : ?>
						<div class="spd-meta">
							<?php if ( $atts['show_date'] ) : ?>
								<span class="spd-date"><?php echo esc_html( get_the_date( '', $post_id ) ); ?></span>
							<?php endif; ?>

							<?php if ( $atts['show_date'] && $atts['show_views'] ) : ?>
								<span class="spd-meta-separator">•</span>
							<?php endif; ?>

							<?php if ( $atts['show_views'] ) : ?>
								<span class="spd-views"><?php echo esc_html( SPD_Helpers::format_views( $views ) ); ?></span>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<?php if ( $atts['show_excerpt'] && ! empty( $excerpt ) ) : ?>
						<div class="spd-excerpt"><?php echo esc_html( $excerpt ); ?></div>
					<?php endif; ?>
				</div>
			</a>
		</article>
		<?php
		return ob_get_clean();
	}
}