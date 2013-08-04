<?php
/**
 * @package Face Foward
 */

$post_format = get_post_format();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php if ( has_post_format( 'link' ) ) : ?>
			<?php 
				ob_start();
				ob_end_clean();
				preg_match( '/<a\s[^>]*?href=[\'"](.+?)[\'"]/is', get_the_content(), $matches );
				$post_link = $matches[1];
			?>

			<h1 class="entry-title"><a href="<?php echo $post_link; ?>" rel="bookmark"><?php the_title(); ?></a></h1>
		<?php else : ?>
			<h1 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
		<?php endif; ?>

		<?php if ( 'post' == get_post_type() ) : ?>
		<div class="entry-meta">
			<span class="entry-type <?php echo $post_format; ?>"><div class="genericon genericon-<?php echo $post_format; ?>"></div></span>
			<?php fforward_posted_on(); ?>
		</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<?php if ( has_post_format( 'image' ) ) : ?>
		<div class="entry-image">
		<?php
			ob_start();
			ob_end_clean();
			preg_match_all( '/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', get_the_content(), $matches );
			echo '<img src="' . $matches[1][0] . '"/>';
		?>
		</div>
	<?php endif; ?>

	<?php if ( is_search() ) : // Only display Excerpts for Search ?>
		<div class="entry-summary">
			<?php the_excerpt(); ?>
		</div><!-- .entry-summary -->
	<?php else : ?>
		<div class="entry-content">
			<?php if ( has_post_format( 'image' ) ) : ?>
				<?php
					ob_start();
					the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'fforward' ) );
					$postOutput = preg_replace('/(<img [^>]*>)/','', ob_get_contents());
					ob_end_clean();
					echo $postOutput;
				?>
			<?php else : ?>
				<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'fforward' ) ); ?>
			<?php endif; ?>

			<?php
				wp_link_pages( array(
					'before' => '<div class="page-links">' . __( 'Pages:', 'fforward' ),
					'after'  => '</div>',
				) );
			?>
		</div><!-- .entry-content -->
	<?php endif; ?>

	<footer class="entry-meta">
		<?php if ( 'post' == get_post_type() ) : // Hide category and tag text for pages on Search ?>
			<?php
				/* translators: used between list items, there is a space after the comma */
				$categories_list = get_the_category_list( __( ', ', 'fforward' ) );
				if ( $categories_list && fforward_categorized_blog() ) :
			?>
			<span class="cat-links">
				<?php printf( __( 'Posted in %1$s', 'ffroward' ), $categories_list ); ?>
			</span>
			<?php endif; // End if categories ?>

			<?php
				/* translators: used between list items, there is a space after the comma */
				$tags_list = get_the_tag_list( '', __( ', ', 'fforward' ) );
				if ( $tags_list ) :
			?>
			<span class="tags-links">
				<?php printf( __( 'Tagged %1$s', 'fforward' ), $tags_list ); ?>
			</span>
			<?php endif; // End if $tags_list ?>
		<?php endif; // End if 'post' == get_post_type() ?>

		<?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
		<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'fforward' ), __( '1 Comment', 'fforward' ), __( '% Comments', 'fforward' ) ); ?></span>
		<?php endif; ?>

		<?php edit_post_link( __( 'Edit', 'fforward' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-meta -->
</article><!-- #post-## -->
