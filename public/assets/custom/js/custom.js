$(function(){
	$('.owl-carousel').owlCarousel({
		dots:true,
	    loop:true,
	    margin:10,
	    nav:true,
	    items:1,
	    autoplay: true,
	});


});

 $('.product-wrapper .thumbnail-product li').on('click', function(e) {
		e.preventDefault();
		var tab_id = $(this).attr('data-tab');
        $(this).parent().parent().parent().find('.large-product li.active').removeClass('active');
        $(this).addClass('active');
        $("#" + tab_id).addClass('active');
    });
  $('.product-color ul li').on('click', function(e) {
                e.preventDefault();
                $('.product-color ul li').removeClass('active');
                $(this).addClass('active');
            });


  $('.option-filter a').on('click', function(e) {
                e.preventDefault();
                $('.option-filter a').removeClass('active');
                $(this).addClass('active');
            })



		    $('.count').prop('disabled', true);
   			$(document).on('click','.plus',function(){
				$('.count').val(parseInt($('.count').val()) + 1 );
    		});
        	$(document).on('click','.minus',function(){
    			$('.count').val(parseInt($('.count').val()) - 1 );
    				if ($('.count').val() == 0) {
						$('.count').val(1);
					}
    	    	});
 		

 $('.thumbnail-slider.owl-carousel').owlCarousel({
    loop:true,
    margin:5,
    responsiveClass:true,
    responsive:{
        0:{
            items:5,
            nav:true
        },
        768:{
            items:5,
            nav:true,
            loop:false
        }
    }
})
 $(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
    
    /*--------------------------
        Product Zoom
	---------------------------- */
    /*$("#img_01").elevateZoom({
        gallery: "gal1",
        galleryActiveClass: "active",
        zoomWindowWidth: 300,
        zoomWindowHeight: 100,
        scrollZoom: true,
        zoomType: "inner",
        cursor: "crosshair"
    });*/
    //initiate the plugin and pass the id of the div containing gallery images
