	var deferred_get_product = jQuery.Deferred();
	var deferred_get_cart = jQuery.Deferred();
	var deferred_remove_item_cart = jQuery.Deferred();

	var img_path = "http://preview.z9xxbdevr1itfbt9ee6gwt87u2138fr4spczigwh3tprpb9.box.codeanywhere.com/wp-content/themes/wordpress-bootstrap/";

	function hide_loader(){
		jQuery('.loading_products').hide('fast');
	}
	function display_loader(){
		jQuery('.loading_products').show('fast');
	}
	function display_cart(){
		jQuery('.class-table-cart').show('fast');
	}
	function hide_cart(){
		jQuery('.class-table-cart').hide();
	}
	function clean_cart(){
		jQuery('#product_list_ul').html('');
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


	function render_cart(){

		var total_cart_value = 0.00;
		var price = 0.00;
		var img;
		clean_cart();
		get_cart();
		deferred_get_cart.done(function(value) {	

			if (!value) {
				//Handle error
			} else {

				hide_loader();
				display_cart();

				var cart_itens = value.items;
				if (cart_itens.length > 0){
					jQuery.each( cart_itens, function( key, product_obj ) {
						jQuery.each( all_products, function( key, product ) {

							if (product_obj.product_id == product.id){
								img = product.image;
								price = product.price;
							}
						});
						jQuery("#product_list_ul").append( render_cart_li(product_obj, img, price) );
						total_cart_value = total_cart_value + parseFloat(product_obj.amount);
					});
				} else {
					jQuery("#product_list_ul").append( '<br><h4>Your cart is empty.</h4><br>' );
					jQuery("#btn-checkout").hide();
					jQuery("#total-price").html('');

				}
				jQuery('#total-cart-value').html(total_cart_value.toFixed(2));
				jQuery('#total-cart-value-mobile').html(total_cart_value.toFixed(2));
			}

		});

	}

	function render_product_li(product_obj){
		var res = '<li><div class="row"><div class="col-sm-9 col-xs-8">';
		res+= '<span class="img"><img src="'+ img_path + product_obj.image +'" alt="" ></span>';
		res+= '<span class="product clearfix"><span class="name">'+ product_obj.name +'</span></span>';
		res+= '</div><div class="col-sm-1 col-xs-3"><span class="product-price">$'+ product_obj.price +'</span></div><div class="col-sm-1"><form method="post" action="" id="ajax_form" class="form-inline add-to-cart" role="form" data-product-id="'+ product_obj.id +'"><input type="hidden" name="product_id" value="'+ product_obj.id +'" /><div class="form-group"><input type="hidden" name="quantity" value="1" /> <button type="submit" class="btn btn-success">Add to cart</button></div></form>';
		res+= '</a></div></div></li>';
		return res;
	}

	function render_cart_li(product_obj, img, price ){

		var res = '<tr><td><div class="row"><div class="col-sm-2 col-xs-4"><img src="'+ img_path + img +'" alt="'+ product_obj.product_name +'" class="img-responsive"/></div><div class="col-sm-10"><h4 class="nomargin">'+ product_obj.product_name +'</h4></div></div></td><td data-th="Price">$'+ price.toFixed(2) +'</td><td data-th="Quantity">';

		res+= '<form class="product-on-cart" id="'+ product_obj.id +'"><input type="number" name="quantity" class="form-control text-center" value="'+ product_obj.quantity +'"><input type="hidden" name="product_id" value="'+ product_obj.product_id +'" /></form>';

			res+= '</td><td data-th="Subtotal" class="text-center">$'+ product_obj.amount.toFixed(2) +'</td><td class="actions" data-th=""><button class="btn btn-info btn-sm update-btn" data-update="'+ product_obj.id +'" data-product="'+ product_obj.product_id +'"><i class="fa fa-refresh"></i></button><button class="btn btn-danger btn-sm remove-btn" data-remove="'+ product_obj.id +'"><i class="fa fa-trash-o"></i></td>></tr>';
		return res;
	}

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
				window.open("/cart","_self");
			},
			'error': function(jqXHR, textStatus, errorThrown) {
				 //handle error
			}

		});
	}

	function get_cart() {

		jQuery.ajax({
			'url' : 'http://shoppingcart-mcfadyenbrazil.rhcloud.com/api/shoppingcart',
			'type' : 'GET',
			'cache' : false,
			'xhrFields' : {
					withCredentials: true
			},
			'success' : function(data) {
				deferred_get_cart.resolve(data);
			},
			'error': function (jqXHR, status, err) {
				deferred_get_cart.resolve(false);
			}	
		});
	}

	function delete_item_cart(id) {

		jQuery.ajax({
			'url' : 'http://shoppingcart-mcfadyenbrazil.rhcloud.com/api/shoppingcart/items/' + id,
			'type' : 'DELETE',
			'xhrFields' : {
					withCredentials: true
			},
			'success' : function(data) {
				deferred_remove_item_cart.resolve(data);
			},
			'error': function (jqXHR, status, err) {
				deferred_remove_item_cart.resolve(false);
			}

		});
	}