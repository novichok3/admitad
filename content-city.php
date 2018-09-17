<?php
/**
 * Single post partial template.
 *
 * @package understrap
 */

?>
<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

	<header class="entry-header">

		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

		<div class="entry-meta">

			<?php //understrap_posted_on(); ?>

		</div><!-- .entry-meta -->

	</header><!-- .entry-header -->

	<?php echo get_the_post_thumbnail( $post->ID, 'large' ); ?>

	<div class="entry-content">

        <div class="city_content"><?php echo $post->post_content; ?></div>

        <?php $args = array('post_type' => 'real_estate', 'meta_key' => 'real_estate_city_id', 'meta_value' => $post->ID, ); ?>

        <?php $query = new WP_Query($args); ?>

        <div class="real_estate_count">Всего объектов в этом городе: <?php echo $query->found_posts; ?></div>

        <?php while ( $query->have_posts() ) : $query->the_post() ?>

            <?php get_template_part('real_estate','property_list') ?>

        <?php endwhile ?>


	</div><!-- .entry-content -->

</article><!-- #post-## -->
