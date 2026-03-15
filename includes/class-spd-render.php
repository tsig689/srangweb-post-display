<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SPD_Render {

	public function render_posts( $query, $atts ) {
		if ( ! $query->have_posts() ) {
			return '<p class="spd-empty">' . esc_html__( 'No posts found.', 'srangweb-post-display' ) . '</p>';
		}

		$output  = '<div class="spd-grid columns-' . esc_attr( $atts['columns'] ) . '">';
		while ( $query->have_posts() ) {
			$query->the_post();
			$output .= $this->render_card( get_the_ID(), $atts );
		}
		$output .= '</div>';

		return $output;
	}

	private function render_card( $post_id, $atts ) {
		$title         = get_the_title( $post_id );
		$link          = get_permalink( $post_id );
		$excerpt       = SPD_Helpers::get_excerpt( $post_id, $atts['excerpt_length'] );
		$category_name = SPD_Helpers::get_first_category_name( $post_id );
		$views         = SPD_Views::get_views( $post_id );

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

					<h3 class="spd-title"><?php echo esc_html( $title ); ?></h3>

					<?php if ( $atts['show_date'] || $atts['show_views'] ) : ?>
						<div class="spd-meta">
							<?php if ( $atts['show_date'] ) : ?>
								<span class="spd-date"><?php echo esc_html( get_the_date( '', $post_id ) ); ?></span>
							<?php endif; ?>

							<?php if ( $atts['show_date'] && $atts['show_views'] ) : ?>
								<span class="spd-meta-separator">•</span>
							<?php endif; ?>

							<?php if ( $atts['show_views'] ) : ?>
								<span class="spd-views">👁 <?php echo esc_html( SPD_Helpers::format_views( $views ) ); ?></span>
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
