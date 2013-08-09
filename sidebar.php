<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package Face fforward
 */
?>
	<div id="secondary" class="widget-area" role="complementary">
		<div class="fforward-browse">

		<?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>

		<?php do_action( 'before_sidebar' ); ?>
		<?php if ( ! dynamic_sidebar( 'sidebar-1' ) ) : ?>
			<aside id="meta" class="widget">
				<h1 class="widget-title"><?php _e( 'Meta', 'fforward' ); ?></h1>
				<ul>
					<?php wp_register(); ?>
					<li><?php wp_loginout(); ?></li>
					<?php wp_meta(); ?>
				</ul>
			</aside>

		<?php endif; // end sidebar widget area ?>
		</div>
	</div><!-- #secondary -->
