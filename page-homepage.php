<?php
/*
Template Name: Homepage
*/
?>

<?php get_header(); ?>

			
			<div id="content" class="clearfix row">
			
				<div id="main" class="col-sm-12 clearfix" role="main">

					<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					
					<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article">
					
						<header>

							<?php 
								$post_thumbnail_id = get_post_thumbnail_id();
								$featured_src = wp_get_attachment_image_src( $post_thumbnail_id, 'wpbs-featured-home' );
							?>
						
						</header>
						
						<section class="row post_content">
							
						
							<div class="col-sm-12">
						
								<div class="col-md-12">
									<div class="loading_products"> </div>
									<ul class="widget-products" id="product_list_ul">
										
									</ul>
								
               </div>
								
							</div>
							
													
						</section> <!-- end article header -->
						
						<footer>
			
							<p class="clearfix"><?php the_tags('<span class="tags">' . __("Tags","wpbootstrap") . ': ', ', ', '</span>'); ?></p>
							
						</footer> <!-- end article footer -->
					
					</article> <!-- end article -->
					
					<?php 
						// No comments on homepage
						//comments_template();
					?>
					
					<?php endwhile; ?>	
					
					<?php else : ?>
					
					<article id="post-not-found">
					    <header>
					    	<h1><?php _e("Not Found", "wpbootstrap"); ?></h1>
					    </header>
					    <section class="post_content">
					    	<p><?php _e("Sorry, but the requested resource was not found on this site.", "wpbootstrap"); ?></p>
					    </section>
					    <footer>
					    </footer>
					</article>
					
					<?php endif; ?>
			
				</div> <!-- end #main -->
    
				<?php //get_sidebar(); // sidebar 1 ?>
    
			</div> <!-- end #content -->

			<script>

				var items_in_cart;
				get_cart();
				
				deferred_get_cart.done(function(value) {	
					items_in_cart = value.items;
					render_products();
				});

				
				
				jQuery(document).on('click', '.add-to-cart', function(){
					event.preventDefault();
					var id = jQuery(this).attr('data-product-id');
					var already_in_cart = false;
					
					jQuery.each( items_in_cart, function( key, product ) {
						if (product.product_id == id) {
							already_in_cart = true;
						}

					});
					
					if (!already_in_cart){
						post_product(this);
					} else {
						window.open("/cart","_self");
					}
				});
			</script>

<?php get_footer(); ?>