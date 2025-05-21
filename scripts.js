$(document).ready(function(){
    // Section nav switch
    $('.nav-link').click(function(e){
        e.preventDefault();
        let target = $(this).data('section');
        $('.nav-link').removeClass('active').attr('aria-selected', 'false').attr('tabindex', -1);
        $(this).addClass('active').attr('aria-selected', 'true').attr('tabindex', 0);
        $('.section').removeClass('active').attr('aria-hidden', 'true');
        $('#' + target).addClass('active').attr('aria-hidden', 'false');
    });

    // Product filters
    $('.filter-btn').click(function(){
        $('.filter-btn').removeClass('active').attr('aria-selected', 'false').attr('tabindex', -1);
        $(this).addClass('active').attr('aria-selected', 'true').attr('tabindex', 0);
        let filter = $(this).data('filter');
        if(filter === 'all') {
            $('.product-card').show();
        } else {
            $('.product-card').hide().filter(`[data-type="${filter}"]`).show();
        }
    });

    // Slider logic
    let currentSlide = 0;
    const slides = $('.slider-images img');
    const slideCount = slides.length;

    function showSlide(index) {
        if(index >= slideCount) index = 0;
        if(index < 0) index = slideCount -1;
        $('.slider-images').css('transform', `translateX(${-index * 100}vw)`);
        currentSlide = index;
    }
    $('.slider-btn.next').click(() => showSlide(currentSlide + 1));
    $('.slider-btn.prev').click(() => showSlide(currentSlide - 1));
    setInterval(() => showSlide(currentSlide +1), 6000);

    // Cart modal toggle
    const cartModal = $('#cart-modal');
    $('#cart-button').click(() => {
        loadCartItems();
        cartModal.attr('aria-hidden', 'false').fadeIn(200);
    });
    $('#cart-close').click(() => cartModal.attr('aria-hidden', 'true').fadeOut(200));
    $(window).click(function(e){
        if($(e.target).is(cartModal)) {
            cartModal.attr('aria-hidden', 'true').fadeOut(200);
        }
    });

    // Logout popup toggle
    const logoutPopup = $('#logout-popup');
    $('#logout-button').click(() => logoutPopup.attr('aria-hidden', 'false').fadeIn(200));
    $('#cancel-logout').click(() => logoutPopup.attr('aria-hidden', 'true').fadeOut(200));
    $(window).click(e => {
        if($(e.target).is(logoutPopup)) logoutPopup.attr('aria-hidden', 'true').fadeOut(200);
    });

    // Checkout modal toggle
    const checkoutModal = $('#checkout-modal');
    $('#checkout-button').click(() => {
        checkoutModal.attr('aria-hidden', 'false').fadeIn(200);
        $('#cart-modal').attr('aria-hidden','true').fadeOut(200);
    });
    $('#checkout-close').click(() => checkoutModal.attr('aria-hidden', 'true').fadeOut(200));
    $(window).click(e => {
        if($(e.target).is(checkoutModal)) checkoutModal.attr('aria-hidden', 'true').fadeOut(200);
    });

    // Add to cart form submission with animation
    $('.add-to-cart-form').submit(function(e){
        e.preventDefault();
        let form = $(this);
        let btn = form.find('.add-cart-btn');
        btn.prop('disabled', true).text('Adding...');

        $.ajax({
            url: '',
            method: 'POST',
            data: form.serialize(),
            dataType: 'json',
            success: function(res){
                if(res.success){
                    animateAddToCart(form.closest('.product-card').find('.product-image'));
                    // Update cart count
                    let countElem = $('#cart-count');
                    let currentCount = parseInt(countElem.text()) || 0;
                    let addedQty = parseInt(form.find('input[name="quantity"]').val());
                    countElem.text(currentCount + addedQty);
                } else {
                    alert('Error adding to cart!');
                }
                btn.prop('disabled', false).text('Add to Cart');
            },
            error: function(){
                alert('Network Error while adding to cart!');
                btn.prop('disabled', false).text('Add to Cart');
            }
        });
    });

    function animateAddToCart(imageElem){
        let cartBtn = $('#cart-button');
        let imgClone = imageElem.clone()
            .css({
                position: 'absolute',
                top: imageElem.offset().top,
                left: imageElem.offset().left,
                width: imageElem.width(),
                height: imageElem.height(),
                borderRadius: '8px',
                opacity: 0.85,
                zIndex: 2000
            })
            .appendTo('body');
        imgClone.animate({
            top: cartBtn.offset().top + 10,
            left: cartBtn.offset().left + 10,
            width: 30,
            height: 30,
            opacity: 0
        }, 1000, () => imgClone.remove());
    }

    // Load cart items dynamically into modal
    function loadCartItems() {
        let container = $('#cart-items-container');
        container.empty();
        $.ajax({
            url:'cart.php',
            method:'GET',
            dataType: 'json',
            success: function(data){
                if(data.items.length === 0) {
                    container.html('<p>Your cart is empty.</p>');
                    $('#checkout-button').prop('disabled', true);
                } else {
                    $('#checkout-button').prop('disabled', false);
                    let html = '';
                    $.each(data.items, function(i, item){
                        html += `
                        <div class="cart-item">
                            <div class="cart-item-details">
                                <strong>${item.name}</strong><br/>
                                Color: ${item.color}, Size: ${item.size}<br/>
                                Quantity: <span class="cart-item-qty">${item.quantity}</span>
                            </div>
                            <div class="cart-price">
                                $${(item.price * item.quantity).toFixed(2)}
                            </div>
                        </div>`;
                    });
                    container.html(html);
                }
            },
            error: function(){
                container.html('<p>Error loading cart items.</p>');
            }
        });
    }

    // Checkout form submit
    $('#checkout-form').submit(function(e){
        e.preventDefault();
        const form = $(this);
        const messageDiv = form.find('.form-message');
        messageDiv.text('');
        const submitBtn = form.find('.checkout-submit-btn');
        submitBtn.prop('disabled', true).text('Placing order...');
        // Serialize form & add action param
        let data = form.serialize() + '&action=checkout_submit';

        $.ajax({
            url: '',
            method: 'POST',
            data: data,
            dataType: 'json',
            success: function(res){
                if(res.success){
                    messageDiv.css('color', 'green').text('Order placed successfully! Thank you.');
                    form[0].reset();
                    $('#cart-count').text('0');
                    $('#checkout-button').prop('disabled', true);
                    setTimeout(()=>{
                        $('#checkout-modal').attr('aria-hidden', 'true').fadeOut(300);
                        $('#cart-modal').attr('aria-hidden', 'true').fadeOut(300);
                    }, 2500);
                } else {
                    messageDiv.css('color', '#d86002').text(res.message || 'Error placing order.');
                }
                submitBtn.prop('disabled', false).text('Place Order');
            },
            error: function(){
                messageDiv.css('color', '#d86002').text('Network error, try again later.');
                submitBtn.prop('disabled', false).text('Place Order');
            }
        });
    });
});