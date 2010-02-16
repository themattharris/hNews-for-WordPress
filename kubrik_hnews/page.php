<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */

get_header(); ?>

	<div id="content" class="narrowcolumn hnews hatom" role="main">

		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<div <?php post_class('post') ?> id="post-<?php the_ID(); ?>">
		<h2 class="entry-title"><?php the_title(); ?></h2>
			<div class="entry-content">
				<?php the_content('<p class="serif">Read the rest of this page &raquo;</p>'); ?>

				<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>

			</div>
			
      <!-- hNews meta -->
			<p class="postmetadata alt hnewsmeta">
			  <small>
			    <?php the_hnews_geo('Published at <span class="geo">', '</span>') ?>
			    <?php the_hnews_source_org('by <span class="vcard">', '</span>.') ?> 
			    <?php the_hnews_license_url('Licensed as ', ' and ') ?>
          <?php the_hnews_principles_url('published under ', '.') ?>
				</small>
			</p>
		</div>
		<?php endwhile; endif; ?>
	<?php edit_post_link('Edit this entry.', '<p>', '</p>'); ?>
	
	<?php comments_template(); ?>
	
	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
