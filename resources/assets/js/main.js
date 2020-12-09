$(document).ready(function(){

  //Плавный скролл
  $("a.scrollto").click(function () {
      var elementClick = '#'+$(this).attr("href").split("#")[1]
      var destination = $(elementClick).offset().top;
      jQuery("html:not(:animated),body:not(:animated)").animate({scrollTop: destination}, 1000);
      return false;
  });

  $(window).on('resize', function() {
    if($(window).width < 992) {
        $("html, body, .mfp-zoom-out-cur").data("niceScroll").destroy();
    }
  });

    //Header
    $(window).scroll(function(event) {
        action_position(0);
        $(window).resize(function () {
            if ($(this).width() < 1199) {
                action_position(530);
            }
            if ($(this).width() < 991) {
                action_position(730);
            }
            if ($(this).width() < 991) {
                action_position(0);
            }
        });
    })

  $(function (){
    var location = window.location.href;
    var cur_url = location.split('/').pop();
    if (cur_url == 'want.html'){
      $('header#header').addClass('active');
    }
  });

  //Maskedinput
  $("#tel, #tel1").mask("7 (999) 999-99-99");

    $action_product = $('section.wish .action-product-slider .action-product')
    $action_product.matchHeight(
        {
            byRow: true,
            property: 'height',
            remove: false
        }
    );

  //Гамбургер меню
  var forEach=function(t,o,r){if("[object Object]"===Object.prototype.toString.call(t))for(var c in t)Object.prototype.hasOwnProperty.call(t,c)&&o.call(r,t[c],c,t);else for(var e=0,l=t.length;l>e;e++)o.call(r,t[e],e,t)};
    var hamburgers = document.querySelectorAll(".hamburger");
    if (hamburgers.length > 0) {
      forEach(hamburgers, function(hamburger) {
        hamburger.addEventListener("click", function() {
          this.classList.toggle("is-active");
        }, false);
      });
    }

  $(".hamburger").click(function(){
    $("#header .header-menu-wrapper").slideToggle(300);
    $("#header .header-menu-wrapper").toggleClass('active');
    if($('#header .header-menu-wrapper').hasClass('active')){
      $('#header .header-form input[type="submit"]').css('z-index','2');
    }
    $('section.shop .left-sidebar-wrapper').removeClass('active');
  });

  $('#header .header-menu li a').click(function(){
      if($(window).width() <= 991){
          $('#header .header-menu-wrapper').slideUp(300);
      }
    $(".hamburger").removeClass("is-active");
  });

    $(window).resize(function(){
        if($(this).width() > 991){
            $('#header .header-menu-wrapper').css('display','block');
        } else {
            $('#header .header-menu-wrapper').css('display','none');
        }
    });

  //Анимация гамбургер меню
  $(function() {
    $("header nav #hamburger__sidebar .hamburger__navigation__items, #first div.btn_transperent").animated("fadeInLeft");
    $("#hamburger__sidebar .btn__blue").animated("fadeInUp")
  });

  //Header search forma
  $('#header .header-form input[type="text"]').hover(
    function(){
      $('#header .header-form input[type="submit"]').css('z-index','7');
    }
  );

  $('#header .header-form input[type="text"]').focus(
    function(){
      $('#header .header-form input[type="submit"]').css('z-index','7');
    }
  );

  $('.categoy-all').on('click', function(){
    $('section.shop .left-sidebar-wrapper').toggleClass('active');
  });

  $('section.shop .left-sidebar .category-caption i').on('click', function(){
    $('section.shop .left-sidebar-wrapper').toggleClass('active');
  });

  $(window).scroll(function(){
    if($(this).scrollTop() > $(this).height()){
      $('.top').addClass('top__active');
    }else{
      $('.top').removeClass('top__active');
    }
  });

  //Footer dropdown
  $('#footer .footer-menu-caption').on('click', function(){
    $(this).parent().find('.footer-menu').toggleClass('active');
  });

  $('.top').click(function(){
    $('html, body').stop().animate({scrollTop:0},"slow","swing");
  });

  const currentTagIndex = $('.categoy-box.curent').parent().index();
  $('.categoy-box-slider').slick({
    dots: true,
    infinite: true,
    arrows: true,
    slidesToShow: 7,
    slidesToScroll: 4,
    prevArrow: '<i class="fa reviews__arrows_prev fa-angle-left" aria-hidden="true"></i>',
    nextArrow: '<i class="fa reviews__arrows_next fa-angle-right" aria-hidden="true"></i>',
    responsive: [
    {
      breakpoint: 1920,
      settings: {
      arrows: true,
      slidesToShow: 12,
      slidesToScroll: 4,
     }
    },
    {
      breakpoint: 1399,
      settings: {
      arrows: true,
      slidesToShow: 7,
      slidesToScroll: 4,
     }
    },
    {
      breakpoint: 1199,
      settings: {
      arrows: false,
      slidesToShow: 7,
      slidesToScroll: 4,
      }
     },
     {
        breakpoint: 767,
        settings: {
        arrows: false,
        slidesToShow: 7,
        slidesToScroll: 4,
      }
     },
     {
        breakpoint: 620,
        settings: {
        arrows: false,
        slidesToShow: 5,
        slidesToScroll: 4,
      }
     },
     {
        breakpoint: 520,
        settings: {
        arrows: false,
        slidesToShow: 4,
        slidesToScroll: 4,
      }
     },
     {
        breakpoint: 435,
        settings: {
        dots: false,
        arrows: false,
        slidesToShow: 3,
        slidesToScroll: 2,
        initialSlide: currentTagIndex,
      }
     },
    ]
  });

  //Product
  $product_sale = $('.product-sale');
  if ($product_sale.html() == ''){
    $product_sale.css('padding', '0');
  }

  $('.pm-widget').pmWidget();

  $product_item = $('.product-item > a')
  $product_item.matchHeight(
    {
      byRow: true,
      property: 'height',
      remove: false
    }
  );

  $('.action-product-slider').slick({
    init: true,
    dots: true,
    infinite: true,
    arrows: true,
    slidesToShow: 1,
    initialSlide: 0,
    slidesToScroll: 1,
    prevArrow: '<i class="fa reviews__arrows_prev fa-angle-left" aria-hidden="true"></i>',
    nextArrow: '<i class="fa reviews__arrows_next fa-angle-right" aria-hidden="true"></i>',
    responsive: [
    {
       breakpoint: 1399,
       settings: {
       arrows: true,
       dots: false,
       slidesToShow: 1,
       slidesToScroll: 1,
     }
    },
    {
        breakpoint: 1199,
        settings: {
        arrows: true,
        dots: false,
        slidesToShow: 1,
        slidesToScroll: 1,
      }
     },
     {
        breakpoint: 767,
        settings: {
        arrows: false,
        dots: false,
        slidesToShow: 1,
        slidesToScroll: 1,
      }
     },
     {
        breakpoint: 630,
        settings: {
        arrows: false,
        dots: false,
        slidesToShow: 1,
        slidesToScroll: 1,
        centerMode: true,
        variableWidth: true,
      }
     },
    ]
  });

  $('section.wish .action-product-hint').hover(
    function(){
      $(this).find('.grey').css('display', 'none');
      $(this).find('.blue').css('display', 'block');
      $(this).parent().find('.action-product-hint__block').css('display', 'block');
    },
    function(){
      $(this).find('.grey').css('display', 'block');
      $(this).find('.blue').css('display', 'none');
      $(this).parent().find('.action-product-hint__block').css('display', 'none');
    }
  );

  //Animation product to add in basket
  enableBuyButtons($(".product-item .in-cart .btn__yellow, .baner-wrapper .in-cart .btn__yellow"));

  $(".baner-wrapper .in-cart .btn__yellow").on("click",function(){
    $(this).parent().parent().parent().find('.easy-order-img img')//Картинка товара
      .clone()
      .css({
        'position' : 'absolute',
        'z-index' : '11100',
        top: $(this).offset().top-210,
        left:$(this).offset().left-130})
      .appendTo("body")
      .animate({opacity: 0.05,
        left: $(".cart-info__icon").offset()['left'],
        top: $(".cart-info__icon").offset()['top'],
        width: 20}, 1000, function() {
        $(this).remove();
      });
  });

    $('.baner-left-slider').slick({
        dots: false,
        infinite: true,
        arrows: true,
        slidesToShow: 1,
        initialSlide: 0,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 4000,
        adaptiveHeight: false,
        prevArrow: '<i class="fa reviews__arrows_prev fa-angle-left" aria-hidden="true"></i>',
        nextArrow: '<i class="fa reviews__arrows_next fa-angle-right" aria-hidden="true"></i>',
    });

    var sidebar_m_height = $('section.shop .left-sidebar-wrapper').height();
    $('.shop .row.shop-wrapper').css('min-height', sidebar_m_height);
    $('section.shop .left-sidebar .see-more').on('click', function(){
        var sidebar_m_height = $('section.shop .left-sidebar-wrapper').height();
        $('.shop .row.shop-wrapper').css('min-height', sidebar_m_height);
    });
});

//pm-widget
$.fn.pmWidget = function (inc) {
  var multiEl = $(this);
  var incr = parseFloat(inc)?inc:1;
  multiEl.each(function(){
    var el = $(this),
      input = el.find('input');
    el.prepend('<div class="dec button">-</div>');
    el.append('<div class="inc button">+</div>');
    var buttons = el.find('.button');
    buttons.each(function(){
      $(this).on('click', function(){
        var oldValue = input.val(),
          newVal;
        if($(this).hasClass('inc')){
           newVal = parseFloat(oldValue) + incr;
           if (isNaN(newVal)) {
               newVal = 1;
           }
           if ((input.data('product-catalog-id') == 1 || input.data('banner') == true || (input.data('modal') == true && input.data('main') == true) || input.data('want-full') == true) && newVal > 2) {
               newVal = 2;
           }
        }
        if($(this).hasClass('dec')){
          if (oldValue > 0) {
            newVal = parseFloat(oldValue) - incr;
            newVal = newVal<1?1:newVal;
          } else {
            newVal = 1;
          }

            if (isNaN(newVal)) {
                newVal = 1;
            }
        }

        if (input.data('product-catalog-id') == 1) {
            const price = parseInt($('#product-price-1').data('price'));
            if (newVal == 1) {
                $('#product-price-1').html(price + 10 + '');
            } else {
                $('#product-price-1').html(price);
            }
        }

        if (input.data('banner') == true) {
            const price = parseInt($('#product-price-banner').data('price'));
            if (newVal == 1) {
                $('#product-price-banner').html(price + 10 + '')
            } else {
                $('#product-price-banner').html(price)
            }
        }

          if (input.data('modal') == true && input.data('main') == true) {
              const price = parseInt($('#product-price-modal').data('price'));
              if (newVal == 1) {
                  $('#product-price-modal').html(price + 10 + '');
              } else {
                  $('#product-price-modal').html(price);
              }
          }

          if (input.data('want-full') == true) {
              const price = parseInt($('#product-want-full-price').data('price'));
              if (newVal == 1) {
                  $('#product-want-full-price').html(price + 10 + '');
              } else {
                  $('#product-want-full-price').html(price);
              }
          }

        input.val(newVal);

          if(input.data('want') == true) {
              wantSummary();
          }
      });
    });
  });
};

function action_position (t){
  $window = $(window);
  var st = $window.scrollTop();
  var tp = (st + $('section.wish .action-product-wrapper.active').height()  );
  var pos_el = $('footer#footer').offset()['top'];
  if((st + $('section.wish .action-product-wrapper.active').height() - t  ) < pos_el){
    $('.action-product-total').addClass('active');
    $('footer#footer').addClass('active');
  }
  else{
    $('.action-product-total').removeClass('active');
    $('footer#footer').removeClass('active');
  }
}
