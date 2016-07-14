<?php
/*
Template Name: Homepage
*/
?>

<?php get_header(); ?>

<?php 

$response = wp_remote_get( 'http://shoppingcart-mcfadyenbrazil.rhcloud.com/api/shoppingcart' );
if( is_array($response) ) {
  $header = $response['headers']; // array of http header lines
  $body = $response['body']; // use the content
	var_dump( $body );
}

?>
			
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
				
				render_products();

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
					var res = '<li><div class="row"><div class="col-sm-4">';
					res+= '<span class="img"><img class="img-thumbnail" src="'+ product_obj.image +'" alt=""></span>';
					res+= '<span class="product clearfix"><span class="name">'+ product_obj.name +'</span><span class="price"><i class="fa fa-money"></i> $'+ product_obj.price +'</span></span>';
					//res+= '</div><div class="col-sm-4"><form method="post" action="" id="ajax_form" class="form-inline" role="form"><input type="hidden" name="product_id" value="'+ product_obj.id +'" /><div class="form-group"><label for="quantity">Quantity:</label><input type="number" name="quantity" value="1" /> <button type="submit" class="btn btn-success">Add to cart</button></div></form>';
					res+= '</div><div class="col-sm-4"><form method="post" action="" id="ajax_form" class="form-inline add-to-cart" role="form"><input type="hidden" name="product_id" value="'+ product_obj.id +'" /><div class="form-group"><input type="hidden" name="quantity" value="1" /> <button type="submit" class="btn btn-success">Add to cart</button></div></form>';
					res+= '</a></div></div></li>';
					return res;
				}
				 
				function post_product(obj) {
					
					var values = jQuery(obj).serialize();

					jQuery.ajax({
						'url' : 'http://shoppingcart-mcfadyenbrazil.rhcloud.com/api/shoppingcart/items',
						'type' : 'POST',
						'data' : values ,
						'xhrFields' : {
								withCredentials: true
						},
        		'success' : function (response, textStatus, jqXHR){
							get_cart();
						},
						'error': function(jqXHR, textStatus, errorThrown) {
							 console.log(textStatus, errorThrown);
						}
						
					});
				}
				
				jQuery(document).on('click', '.add-to-cart', function(){
						event.preventDefault();
						post_product(this);
				});
				
				
				function get_cart() {
					jQuery.ajax({
						'url' : 'http://shoppingcart-mcfadyenbrazil.rhcloud.com/api/shoppingcart',
						'type' : 'GET',
						'xhrFields' : {
								withCredentials: true
						},
						'success' : function(data) {
							console.log(data);
						},
						'error': function (jqXHR, status, err) {
							deferred_get_product.resolve(false);
						}
						
					});
				}
			</script>

<?php get_footer(); ?>