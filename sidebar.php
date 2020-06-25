<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package _sTheme
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
?>

<aside id="secondary" class="widget-area aside">
<?php
    get_search_form();
?>
<h1 class="side-title">最近の投稿</h1>

<!-- アイキャッチつき -->
<?php
// posts_per_pageで取得件数の指定、orderbyでソート順を新着順にしています。
$args = array('posts_per_page' => 5, 'orderby' => 'date');
$query = new WP_Query($args);
?>
<?php if( $query->have_posts() ) : ?>
<ul>
    <?php while ($query->have_posts()) : $query->the_post(); ?>
    <li class="recently-post">
        <div class="thumbnail"><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('thumbnail'); ?></a></div>
        <div class="title">
            <a href="<?php the_permalink(); ?>" class="recently-title"><?php the_title(); ?></a>
            <p class="recently-date"><?php echo get_the_date('Y.n.j'); ?></p>
        </div>
   </li>
   <?php endwhile; ?>
</ul>
<?php endif; ?>
<?php wp_reset_postdata(); ?>

</aside><!-- #secondary -->
