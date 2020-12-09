window.setCartInfo = (amount, count) => {
    $('.cart-info .cart-info__price').text(amount + 'р')
    $('.cart-info .quantity').text(count)
}

window.addToCart = (productId, count) => {
    if (productId == 1 && count > 2) {
        count = 2;
    }
    $.ajax({
        type: 'POST',
        url: '/cart',
        data: {
            id: productId,
            count: count
        },
        success: (result) => {
            const cart = JSON.parse(result)
            window.setCartInfo(cart.amount, cart.count)
            $('.product-' + productId).hide()
            $('.product-in-cart-' + productId).show()
        }
    })
}

window.enableBuyButtons = (buttons) => {
    buttons.on("click", function () {
        $(this).parent().parent().find('.product-img')//Картинка товара
            .clone()
            .css({
                'position': 'absolute',
                'z-index': '11100',
                top: $(this).offset().top - 330,
                left: $(this).offset().left
            })
            .appendTo("body")
            .animate({
                opacity: 0.05,
                left: $(".cart-info__icon").offset()['left'],
                top: $(".cart-info__icon").offset()['top'],
                width: 20
            }, 1000, function () {
                $(this).remove();
            });

        const productId = $(this).data('product-id')
        const count = $(this).parent().find('.product-count').val()
        addToCart(productId, count)
    });
}

window.showProductDetails = (productId) => {
    $('#productDetailModal').remove()
    $('.modal-backdrop').remove()
    $.ajax({
        type: 'GET',
        url: '/product/' + productId,
        success: (result) => {
            $('body').append(result)
            $('.modal .pm-widget').pmWidget();
            enableBuyButtons($(".modal .in-cart .btn__yellow"));
            $('#productDetailModal').modal('show').on('hidden.bs.modal', function () {
                $(this).remove()
            }).on('shown.bs.modal', function () {
                initModalSliders()
                const product_item_a = $('.modal.product .modal-product-recommended-slider .product-item > a');
                product_item_a.matchHeight(
                    {
                        byRow: false,
                        property: 'height',
                        remove: false
                    }
                );
                $('.modal-product-slider, .modal-product-recommended-slider').slick('refresh');
            })
        }
    })
}

window.wantSummary = () => {
    const fullPrice = parseInt($('#product-want-full-price').html())
    const fullCount = parseInt($('#product-want-full-count').val())
    const fullAmount = fullPrice * fullCount
    $('#product-want-full-amount').html(fullAmount)

    const emptyPrice = parseInt($('#product-want-empty-price').html())
    const emptyCount = fullCount
    $('#product-want-empty-count').html(emptyCount+' шт')
    const emptyAmount = emptyPrice * emptyCount
    $('#product-want-empty-amount').html(emptyAmount)

    const currentEquipment = $('.action-product.slick-active')
    const equipmentPrice = parseInt($(currentEquipment).children().find('.product-want-equipment-price').html())
    const equipmentCount = parseInt($(currentEquipment).children().find('.product-want-equipment-count').val())
    const equipmentAmount = equipmentPrice * equipmentCount
    $(currentEquipment).children().find('.product-want-equipment-amount').html(equipmentAmount)

    $('#product-want-amount').html(fullAmount + emptyAmount + equipmentAmount)
    $('#product-want-count').html(fullCount + emptyCount + equipmentCount)
    $('#product-want-empty').html(emptyAmount)

    $('input[name="full_count"]').val(fullCount)
    $('input[name="empty_count"]').val(emptyCount)
    $('input[name="equipment_count"]').val(equipmentCount)
    $('input[name="equipment_id"]').val($(currentEquipment).data('equipment-id'))
}

$('.action-product-slider').on('afterChange', () => {
    wantSummary()
})

const initModalSliders = () => {
    $('.modal-product-slider').slick({
        dots: false,
        infinite: true,
        arrows: true,
        initialSlide: 0,
        slidesToScroll: 1,
        asNavFor: '.modal-product-nav-slider',
        prevArrow: '<i class="fa reviews__arrows_prev fa-angle-left" aria-hidden="true"></i>',
        nextArrow: '<i class="fa reviews__arrows_next fa-angle-right" aria-hidden="true"></i>',
    });

    $('.modal-product-nav-slider').slick({
        dots: false,
        infinite: true,
        arrows: false,
        slidesToShow: 3,
        slidesToScroll: 2,
        init: true,
        asNavFor: '.modal-product-slider',
        prevArrow: '<i class="fa reviews__arrows_prev fa-angle-left" aria-hidden="true"></i>',
        nextArrow: '<i class="fa reviews__arrows_next fa-angle-right" aria-hidden="true"></i>',
    });

    $('.modal-product-nav-slider__item').on('click', function(){
        var number_slide_product = $(this).attr('data-slick-index');
        $('.modal-product-slider').slick('slickGoTo', number_slide_product);
    });

    $('.modal-product-recommended-slider').slick({
        dots: false,
        infinite: true,
        arrows: true,
        slidesToShow: 5,
        slidesToScroll: 3,
        prevArrow: '<i class="fa reviews__arrows_prev fa-angle-left" aria-hidden="true"></i>',
        nextArrow: '<i class="fa reviews__arrows_next fa-angle-right" aria-hidden="true"></i>',
        responsive: [
            {
                breakpoint: 1399,
                settings: {
                    arrows: true,
                    dots: false,
                    slidesToShow: 5,
                    slidesToScroll: 3,
                }
            },
            {
                breakpoint: 1199,
                settings: {
                    arrows: true,
                    dots: false,
                    slidesToShow: 4,
                    slidesToScroll: 3,
                }
            },
            {
                breakpoint: 767,
                settings: {
                    arrows: false,
                    dots: false,
                    slidesToShow: 4,
                    slidesToScroll: 3,
                }
            },
            {
                breakpoint: 630,
                settings: {
                    arrows: false,
                    dots: false,
                    slidesToShow: 3,
                    slidesToScroll: 3,
                    centerMode: true,
                    variableWidth: true,
                }
            },
            {
                breakpoint: 520,
                settings: {
                    arrows: false,
                    dots: false,
                    slidesToShow: 3,
                    slidesToScroll: 3,
                    centerMode: true,
                    variableWidth: true,
                }
            },
        ]
    });
}
