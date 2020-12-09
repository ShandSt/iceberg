import Inputmask from "inputmask";

Inputmask({mask: '+7 (999) 999-99-99', placeholder: ' '}).mask('input[type="tel"]')

$('#city')
    .select2(
        {
            theme: "bootstrap",
            placeholder: 'Город',
            language: 'ru',
            minimumInputLength: 3
        }
    )
    .on('select2:select', function (e) {
        setStreets(e.params.data.id)
    })
$('#street').select2(
    {
        theme: "bootstrap",
        placeholder: 'Улица',
        language: 'ru',
        minimumInputLength: 3
    }
)

const setStreets = (cityId) => {
    let streets = $('#street').data('streets').find(city => city.id == cityId).streets.map(street => {
        return {
            id: street.id,
            text: street.name
        }
    })

    $('#street').select2('destroy').html('')
    $('#street').select2(
        {
            theme: "bootstrap",
            placeholder: 'Улица',
            language: 'ru',
            data: streets,
            minimumInputLength: 3
        }
    )
    if ($('#street').data('old-street')) {
        $('#street').val($('#street').data('old-street')).trigger('change');
    } else {
        $('#street').val(null).trigger('change');
    }
}

if ($('#city option:selected').val()) {
    setStreets($('#city option:selected').val())
}

const allowProceedCheckout = () => {
    $('#cart-limit-warning').hide()
    $('#checkout-form').show()
}

const denyProceedCheckout = () => {
    $('#cart-limit-warning').show()
    $('#checkout-form').hide()
}

const setCartProductData = (productId, cart) => {
    if (cart.items.length === 0) {
        window.location = '/'
    }
    $('#product-cart-amount').html(cart.amount)

    if (cart.allowProceedCheckout) {
        allowProceedCheckout()
    } else {
        denyProceedCheckout()
    }

    const item = cart.items[productId]
    if (!item) {
        return $('#product-' + productId).remove()
    }
    $('#product-'+productId+'-qty').html(item.qty)
    $('#product-'+productId+'-price').html(item.price)
    $('#product-'+productId+'-amount').html(item.amount)
}

const dec = (productId) => {
    $.ajax({
        type: 'POST',
        url: '/cart',
        data: {
            id: productId,
            count: -1
        },
        success: (result) => {
            const cart = JSON.parse(result)
            window.setCartInfo(cart.amount, cart.count);
            setCartProductData(productId, cart)
        }
    })
}

const inc = (productId) => {
    $.ajax({
        type: 'POST',
        url: '/cart',
        data: {
            id: productId,
            count: 1
        },
        success: (result) => {
            const cart = JSON.parse(result)
            window.setCartInfo(cart.amount, cart.count);
            setCartProductData(productId, cart)
        }
    })
}

const remove = (productId) => {
    $.ajax({
        type: 'POST',
        url: '/cart',
        data: {
            id: productId,
            count: parseInt($('#product-'+productId+'-qty').html()) * -1
        },
        success: (result) => {
            const cart = JSON.parse(result)
            window.setCartInfo(cart.amount, cart.count);
            setCartProductData(productId, cart)
        }
    })
}

window.cart = {
    dec,
    inc,
    remove
}

window.setStreetView = (state) => {
    if (state) {
        $('#street-select-input').hide();
        $('#street-text-input').show();
    } else {
        $('#street-select-input').show();
        $('#street-text-input').hide();
    }
}

$("input[name='accept_terms']").on('change', () => {
    if ($("#accept_terms > input[type='checkbox']").is(':checked')) {
        $('#accept_terms_container').removeClass('has-error')
        $('#accept_terms').removeClass('need-attention')
    } else {
        $('#accept_terms').addClass('need-attention')
    }
})

const commentField = $("textarea[name='comment']")
const commentLength = $('#comment-length small')
const maxCommentLength = commentField.attr('maxlength')
commentLength.html(`${commentField.val().length} / ${maxCommentLength}`)
commentField.keyup(() => {
    commentLength.html(`${commentField.val().length} / ${maxCommentLength}`)
})
