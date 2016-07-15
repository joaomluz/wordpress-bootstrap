<?php
/*
Template Name: Cart
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
						
						<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
						<div class="container">
							<div class="loading_products"> </div>
							<table id="cart" class="table table-hover table-condensed class-table-cart">
												<thead>
												<tr>
													<th style="width:50%">Product</th>
													<th style="width:10%">Price</th>
													<th style="width:8%">Quantity</th>
													<th style="width:22%" class="text-center">Subtotal</th>
													<th style="width:10%"></th>
												</tr>
											</thead>
											<tbody id="product_list_ul">
									

											</tbody>
											<tfoot>
												<tr class="visible-xs" id="total-price">
													<td class="text-center"><strong><strong>Total $<span id="total-cart-value-mobile"></span></strong></td>
												</tr>
												<tr>
													<td><a href="<?php echo get_home_url(); ?>" class="btn btn-warning"><i class="fa fa-angle-left"></i> Continue Shopping</a></td>
													<td colspan="2" class="hidden-xs"></td>
													<td class="hidden-xs text-center"><strong>Total $<span id="total-cart-value"></span></strong></td>
													<td><a href="#" class="btn btn-success btn-block" id="btn-checkout">Checkout <i class="fa fa-angle-right"></i></a></td>
												</tr>
											</tfoot>
									</table>
							</div>
					
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
				get_products();
				
				var all_products;
				deferred_get_product.done(function(value) {	
					all_products = value; 
					render_cart();
				});
			
				jQuery(document).on('click', '.remove-btn', function(){
					var cart_item_id = jQuery(this).attr('data-remove');
					delete_item_cart(cart_item_id);
					deferred_remove_item_cart.done(function(value) {	
						location.reload();
					});
				});
				jQuery(document).on('click', '.update-btn', function(){
					var cart_item_id = jQuery(this).attr('data-update');
					delete_item_cart(cart_item_id);
					deferred_remove_item_cart.done(function(value) {	
						post_product('#' + cart_item_id);
					});
				});
			</script>

<?php get_footer(); ?>