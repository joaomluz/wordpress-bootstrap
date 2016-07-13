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
				
				var deferred_get_product = jQuery.Deferred();
				
				function get_products() {
					jQuery.ajax({
						'url' : 'http://shoppingcart-mcfadyenbrazil.rhcloud.com/api/products',
						'type' : 'GET',
						'dataType' : 'json',
						'success' : function(data) {
							deferred_get_product.resolve(data);
						},
						'error': function (jqXHR, status, err) {
							deferred_get_product.resolve(false);
						}
						
					});
				}
				
				function hide_loader(){
						jQuery('.loading_products').hide('fast');
				}
				
				function render_products(){
					get_products();
					deferred_get_product.done(function(value) {	
						
						if (!value) {
							//Handle error
						} else {
							hide_loader();
							jQuery.each( value, function( key, product_obj ) {
								jQuery("#product_list_ul").append( render_product_li(product_obj) );
							});
							
						}
						
					});
				}
				
				
				function render_product_li(product_obj){
					var res = '<li><a href="#" id="'+ product_obj.id +'">';
					res+= '<span class="img"><img class="img-thumbnail" src="'+ product_obj.image +'" alt=""></span>';
					res+= '<span class="product clearfix"><span class="name">'+ product_obj.name +'</span><span class="price"><i class="fa fa-money"></i> $'+ product_obj.price +'</span></span>';
					res+= '</a></li>';
					return res;
				}
				 
		
			</script>

<?php get_footer(); ?>