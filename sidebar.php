<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package Face fforward
 */
?>
	<div id="secondary" class="widget-area" role="complementary">
		<div class="fforward-browse">
		<?php do_action( 'before_sidebar' ); ?>
		<?php if ( ! dynamic_sidebar( 'sidebar-2' ) ) : ?>
			<aside id="meta" class="widget">
				<h1 class="widget-title"><?php _e( 'Meta', 'ffforward' ); ?></h1>
				<ul>
					<?php wp_register(); ?>
					<li><?php wp_loginout(); ?></li>
					<?php wp_meta(); ?>
				</ul>
			</aside>

		<?php endif; // end sidebar widget area ?>
			<aside id="fforward-type-picker" class="widget">
				<h1 class="widget-title"><?php _e( 'Post Types' ); ?></h1>
				<ul>
					<li><a href="/type/gallery"><div class="genericon genericon-gallery"></div> Gallery</a></li>
			</aside>
		</div>
	</div><!-- #secondary -->
