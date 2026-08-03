function is_mobile() {
  return (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent))
}
jQuery(document).ready(function($) {
  var $loader = $('.wrapper-loader');
  if (!is_mobile()) {
    $loader.stop(true, true).fadeOut(300, function () {
      $(this).hide();
    });
  } else {
    $loader.hide();
  }
  // fallback: jQuery fade can stall under rAF throttling
  setTimeout(function () {
    $loader.hide();
  }, 500);
  if (!is_mobile()) {
    $('.dropdown-content').addClass('is-animation');
  }
  $(".fixed-menu_hamburger").on("click", function() {
    $(this).toggleClass('is-active');
    $('.head-nav').slideToggle(100);
    if (is_mobile()) {
      $('html').toggleClass('is-hidden');
    }
  });
  $(".tablet-hamburger").on("click", function() {
    $(this).toggleClass('is-active');
    $('.head-nav').slideToggle(100);
    if (is_mobile()) {
      $('html').toggleClass('is-hidden');
    }
  });
  $(window).resize(function() {
    if ($(window).width() > 991) {
      $('.head-nav').removeAttr('style');
      $(".hamburger").removeClass('is-active');
      $('html').removeClass('is-hidden');
      $('.catalog-menu').removeAttr('style');
      $('.catalog_sidebar-title').removeClass('is-active')
    }
  });
  $('.modal-city_list-unstyled li').on('click', function() {
    var text = $(this).text();
    $('.select-city_field').text(text);
    $('#citySelect').modal('hide');
  });
  if (!is_mobile()) {
    $('.text-section').parallax();
  }
  if (is_mobile()) {
    $('.head-menu_catalog-item > a').on('click', function() {
      if ($(this).siblings('.dropdown-content').length) {
        $(this).closest('.head-menu_catalog-item').toggleClass('is-active');
        return false;
      }
    });
    $('.dropdown-menu_item >a').on('click', function() {
      if ($(this).closest('.dropdown-menu_item').find('.dropdown-submenu').length) {
        var active = false;
        if ($(this).closest('.dropdown-menu_item').hasClass('is-active')) active = true;
        $('.dropdown-menu_item').removeClass('is-active');
        if (!active) $(this).closest('.dropdown-menu_item').toggleClass('is-active');
        return false;
      }
    });
    $('.fixed-panel_catalog-btn').on('click', function() {
      if ($(this).siblings('.dropdown-content').length) {
        $(this).closest('.fixed-menu_catalog').toggleClass('is-active');
        $('html').toggleClass('is-hidden');
        return false;
      }
    });
  }
  if (!is_mobile()) {
    // Hover-intent: держим меню при промахе курсора (L2↔L3 и кнопка↔панель)
    var MENU_CLOSE_DELAY = 450;

    function menuClearTimer($el) {
      var t = $el.data('menuCloseTimer');
      if (t) {
        clearTimeout(t);
        $el.removeData('menuCloseTimer');
      }
    }

    function menuScheduleClose($el, fn) {
      menuClearTimer($el);
      $el.data('menuCloseTimer', setTimeout(fn, MENU_CLOSE_DELAY));
    }

    function menuRoot($from) {
      return $from.closest('.head-menu_catalog-item, .fixed-menu_catalog, .head-menu_item');
    }

    /** L2: лёгкий дотяг влево только если L3 реально не влезает; не уезжать к логотипу */
    function menuAlignCatalogPanel($wrap) {
      var $root = $wrap.children('.dropdown-content');
      if (!$root.length) {
        return;
      }
      $root[0].style.setProperty('--menu-nudge-x', '0px');
      requestAnimationFrame(function() {
        var pad = 24;
        var l3Reserve = 280;
        var maxNudge = 80; // px — не больше небольшого сдвига от кнопки КАТАЛОГ
        var rootEl = $root[0];
        var rootRect = rootEl.getBoundingClientRect();
        var overflowRight = rootRect.right + l3Reserve - (window.innerWidth - pad);
        if (overflowRight <= 0) {
          return;
        }
        var maxLeft = Math.max(0, rootRect.left - pad);
        var nudge = -Math.min(overflowRight, maxLeft, maxNudge);
        rootEl.style.setProperty('--menu-nudge-x', Math.round(nudge) + 'px');
      });
    }

    /** L3 справа; если не влазит — сдвигаем L2 влево. Влево flip только если экран совсем узкий */
    function menuPlaceSubmenu($item) {
      var $panel = $item.children('.dropdown-submenu-content');
      if (!$panel.length) {
        return;
      }
      $panel.removeClass('is-flip-left');
      var $root = $item.closest('.dropdown-content');
      if (!$root.length) {
        return;
      }
      requestAnimationFrame(function() {
        var rootEl = $root[0];
        var rootRect = rootEl.getBoundingClientRect();
        var panelW = $panel.outerWidth() || 280;
        var gap = 12;
        var spaceRight = window.innerWidth - rootRect.right - gap;
        if (spaceRight >= panelW) {
          return;
        }
        var need = panelW - spaceRight;
        var current = parseFloat(getComputedStyle(rootEl).getPropertyValue('--menu-nudge-x')) || 0;
        var roomLeft = Math.min(80, Math.max(0, rootRect.left - gap));
        if (need <= roomLeft) {
          rootEl.style.setProperty('--menu-nudge-x', Math.round(current - need) + 'px');
          return;
        }
        // совсем узко — fallback влево
        if (rootRect.left - gap > spaceRight) {
          $panel.addClass('is-flip-left');
        }
      });
    }

    $(document).on('mouseenter', '.head-menu_catalog-item, .fixed-menu_catalog, .head-menu_item', function() {
      var $wrap = $(this);
      menuClearTimer($wrap);
      $wrap.addClass('is-hover');
      if ($wrap.hasClass('head-menu_catalog-item') || $wrap.hasClass('fixed-menu_catalog')) {
        menuAlignCatalogPanel($wrap);
      }
    });

    $(document).on('mouseleave', '.head-menu_catalog-item, .fixed-menu_catalog, .head-menu_item', function(e) {
      var $wrap = $(this);
      var to = e.relatedTarget;
      // остаёмся внутри того же корня — не закрываем
      if (to && $wrap[0].contains(to)) {
        return;
      }
      menuScheduleClose($wrap, function() {
        $wrap.removeClass('is-hover');
        $wrap.find('.dropdown-menu_item').removeClass('is-hover');
      });
    });

    // Любое движение внутри панели отменяет закрытие корня
    $(document).on('mouseenter', '.dropdown-content, .dropdown-submenu-content', function() {
      var $wrap = menuRoot($(this));
      if ($wrap.length) {
        menuClearTimer($wrap);
        $wrap.addClass('is-hover');
      }
    });

    // Любой пункт L2 (в т.ч. без детей): сразу гасим чужой is-hover,
    // иначе leaf + предыдущий has-submenu светятся оба (как «Заказные» + «Запорная»).
    $(document).on('mouseenter', '.dropdown-menu_item:not(.dropdown-menu_item--label)', function() {
      var $item = $(this);
      var $wrap = menuRoot($item);
      if ($wrap.length) {
        menuClearTimer($wrap);
        $wrap.addClass('is-hover');
      }
      $item.siblings('.dropdown-menu_item').each(function() {
        var $sib = $(this);
        menuClearTimer($sib);
        $sib.removeClass('is-hover');
      });
      if ($item.hasClass('has-submenu')) {
        menuClearTimer($item);
        $item.addClass('is-hover');
        menuPlaceSubmenu($item);
      }
    });

    $(document).on('mouseleave', '.dropdown-menu_item.has-submenu', function(e) {
      var $item = $(this);
      var to = e.relatedTarget;
      // уход в свой L3 — не гасим
      if (to && $item[0].contains(to)) {
        return;
      }
      // уход на соседний L2 — mouseenter соседа уже снимет is-hover
      if (to && $(to).closest('.dropdown-menu_item').parent()[0] === $item.parent()[0]) {
        return;
      }
      menuScheduleClose($item, function() {
        if (!$item.hasClass('is-hover')) return;
        if ($item.siblings('.dropdown-menu_item.is-hover').length) {
          $item.removeClass('is-hover');
          return;
        }
        var $wrap = menuRoot($item);
        if ($wrap.hasClass('is-hover') || $wrap.is(':hover')) {
          return;
        }
        $item.removeClass('is-hover');
      });
    });
  }

  $('.product-table').on('click', '.product-item_buy-btn', function () {
    $(this).closest('tr').find('.product-item_cart-btn').trigger('click');
  });
  $(".product-table").on("click", ".product-item_cart-btn", function() {
    var $btn = $(this);
    var $qty = $btn.closest('tr').find('.product-table-input[name="pieces"]');
    var qty = 1;
    if ($qty.length) {
      qty = parseFloat(String($qty.val()).replace(',', '.'));
      if (!isFinite(qty) || qty <= 0) {
        qty = 1;
      }
    }

    $btn.clone().css({
      'position': 'absolute',
      'z-index': '1000',
      'width': '57px',
      top: $btn.offset().top,
      left: $btn.offset().left
    }).appendTo("body").animate({
      opacity: 0.05,
      left: $(".head-cart").offset()['left'],
      top: $(".head-cart").offset()['top'],
      width: 20
    }, 700, function() {
      $(this).remove();
    });

    $.get("/ajax/", { component: "add_cart", id: $btn.attr('id'), quantity: qty }).done(function() {
      $.get("/ajax/", { component: "cart_small" }).done(function(cart) {
        $('.head-cart').html(cart);
      });
    });

    return false;
  });
  $(".product-item_buy-btn").on("click", function() {
    $(this).clone().css({
      'position': 'absolute',
      'z-index': '1000',
      'width': '120px',
      'minWidth': 'auto',
      top: $(this).offset().top,
      left: $(this).offset().left
    }).appendTo("body").animate({
      opacity: 0.05,
      left: $(".head-cart").offset()['left'],
      top: $(".head-cart").offset()['top'],
      width: 20
    }, 700, function() {
      $(this).remove();
    });
    return false;
  });

  $('.services-detailed_hide-table').on('click', function(){
   if ($(this).html() == 'Скрыть таблицу') {
      $('.services-detailed_table').slideUp(150);
      $(this).text('Показать таблицу');
    } else {
      $('.services-detailed_table').slideDown(150);
      $(this).text('Скрыть таблицу');
    }
    $(this).toggleClass('is-active');
  });

  function lazyLoad($content) {
    $content.find('img[data-src], source[data-src], audio[data-src], iframe[data-src]').each(function() {
      $(this).attr('src', $(this).data('src'));
      $(this).removeAttr('data-src');
      if ($(this).is('source')) {
        $(this).closest('video').get(0).load();
      }
    });
  }
  lazyLoad($('body'));
  $('.our-partners_slider').slick({
    slidesToShow: 5,
    slidesToScroll: 1,
    focusOnSelect: true,
    dots: true,
    responsive: [{
      breakpoint: 1200,
      settings: {
        slidesToShow: 4,
        slidesToScroll: 1,
      }
    }, {
      breakpoint: 992,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 1,
      }
    }, {
      breakpoint: 767,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1,
      }
    }, {
      breakpoint: 400,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 6000,
      }
    }, ]
  });
  $('.advantages-slider').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: false,
    dots: true,
    appendDots: $('.js-dots'),
    autoplay: true,
    autoplaySpeed: 6000
  });
  $('.main-slider').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: false,
    dots: true,
    autoplay: true,
    autoplaySpeed: 6000
  });
  // Старый слайдер (фон + текст): меняем background секции
  $('.main-slider:not(.main-slider--picture)').on('beforeChange', function(event, slick, currentSlide, nextSlide){
    var currentSlide = $(slick.$slides.get(nextSlide));
    var currentImage = currentSlide.find('.main-slide').data('background');
    if (currentImage) {
      currentSlide.closest('.main-section').css('background','url('+ currentImage +') no-repeat center top');
    }
  });
  // Картиночный слайдер: пересчёт ширины после загрузки/resize (без «пляски»)
  var $pictureSlider = $('.main-slider--picture');
  if ($pictureSlider.length) {
    var refreshPictureSlider = function() {
      $pictureSlider.slick('setPosition');
    };
    $pictureSlider.find('img').on('load', refreshPictureSlider);
    $(window).on('resize orientationchange', refreshPictureSlider);
  }
  $('.review_mobile-slider').slick({
    dots: true,
    infinite: false,
    responsive: [{
      breakpoint: 9999,
      settings: "unslick"
    }, {
      breakpoint: 767,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 3,
        autoplay: true,
        autoplaySpeed: 7000,
      }
    }, {
      breakpoint: 575,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 2,
      }
    }, {
      breakpoint: 400,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1,
      }
    }, ]
  });
  $('.wrapper_partner-item').slick({
    dots: true,
    responsive: [{
      breakpoint: 9999,
      settings: "unslick"
    }, {
      breakpoint: 767,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 2,
        autoplay: true,
        autoplaySpeed: 7000,
      }
    }, {
      breakpoint: 575,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 2,
      }
    }, {
      breakpoint: 400,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1,
      }
    }, ]
  });
  $(".wrapper_select-office .js-select").on("change", function() {
    var number = $(this).find('option:selected').index();

    $(this).closest('.tab-item').find('.contact-section-desc').removeClass('is-active').eq(number).addClass('is-active');
    $(this).closest('.container').find('.contact_right-column .contact-img.is-visible img.contact-section-desc').removeClass('is-active').eq(number).addClass('is-active');
  });

  $(".map-contact_box .js-select").on("change", function() {
    var number = $(this).find('option:selected').val();
    $(this).closest('.map-contact_box').find('.map-contact_list').fadeOut(1);
    $(this).closest('.map-contact_box').find('[data-id=' + number + ']').fadeIn(1);
  });

  if (!is_mobile()) {
    if ($('.digit-list').length) {
      var show = true;
      $(window).on("scroll load resize", function() {
        if (!show) return false;
        var w_top = $(window).scrollTop();
        var e_top = $('.digit-section').offset().top;
        if (w_top + 400 >= e_top) {
          $('.digit-list_item').each(function(index){
             var jthis = $(this);
             setInterval(function(){
               jthis.removeClass('fadein');
             },700*index);
           });
           $('.digit-item_circle').each(function(index){
            var jthis = $(this);
            setTimeout(function() {
               setInterval(function(){
                 jthis.addClass('anim-digit');
               },700*index);
            }, 3000);
              setInterval(function(){
                 jthis.spincrement({
                  from: 0,
                  // to:false,
                  decimalPlaces: 0,
                  decimalPoint:'.',
                  thousandSeparator:',',
                  duration: 3000,// ms; TOTAL length animation
                  leeway: 50,// percent of duraion
                  easing:'spincrementEasing',
                  // fade:true,
                  complete: true
                  });
               },700*index);
            setInterval(function(){
               jthis.addClass('fade');
             },700*index);
          });
           setTimeout(function() {
           $('.digit-list').removeClass('off');
            }, 3000);
          show = false;

      }
    });
  }
}
  if (is_mobile()) {
    $('.digit-list_item').removeClass('fadein')
  }
  $('.history-company_slider').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: false,
    // autoplay: true,
    autoplaySpeed: 10000
  });
  $('.history-company_slider').on('afterChange', function(event, slick, i) {
    var item = $('.history-company_slider').slick('slickCurrentSlide')
    $(".year-slider li").removeClass('is-active');
    $(".year-slider li").eq(item).addClass('is-active');
  });
  $(".year-slider li").click(function(e) {
    $(".year-slider li").removeClass('is-active');
    $(this).addClass('is-active');
    var slide = $(this).data('type');
    $('.history-company_slider').slick('slickGoTo', slide);
  });
  $('.history-company_slide-nav .slide-next').on('click', function() {
    $('.history-company_slider').slick("slickNext")
  });
  $('.history-company_slide-nav .slide-prev').on('click', function() {
    $('.history-company_slider').slick("slickPrev")
  });
  /*******COUNTER*********/
  $('.wrapper-counter-btn').each(function() {
    $(this).find('.product-count').on('input', function() {
      var rep = (/^0/);
      var value = $(this).val();
      if (rep.test(value)) {
        value = value.replace(rep, '');
        $(this).val(value);
      }
      var value2 = $(this).val();
      var rep2 = /[a-zA-Zа-яА-Я]/;
      if (rep2.test(value)) {
        value2 = value2.replace(rep, '');
        $(this).val(value2);
      }
      if ($(this).val() == '') {
        $(this).val(0);
      }
      var msg = $(this).val();
    });
  });
  $('.wrapper-counter-btn').each(function() {
    $(this).find('.counter-back').on("click", function(e) {
      var valPlus = $(this).siblings('.product-count').val();
      var result = parseInt(valPlus) - 1;
      if (result >= 1) {
        $(this).siblings('.product-count').val(result);
      }
      return false;
    });
  });
  $('.wrapper-counter-btn').each(function() {
    $(this).find('.counter-forward').on("click", function(e) {
      var valPlus = $(this).siblings('.product-count').val();
      var result = parseInt(valPlus) + 1;
      if (result >= 1) {
        $(this).siblings('.product-count').val(result);
      }
      return false;
    });
  });
  $(".fancybox").fancybox({
    afterLoad: function(instance, current) {
      if (!is_mobile()) {
        $('.fixed-menu').addClass('is-overflow');
        $('.scroll-to-top').addClass('is-hidden');
      }
    },
    afterClose: function(instance, current) {
      if (!is_mobile()) {
        $('.fixed-menu').removeClass('is-overflow');
        $('.scroll-to-top').removeClass('is-hidden');
      }
    }
  });

  $('.head-cart').on('click', 'a', function() {

    $.get("/ajax/", { component: "cart" }).done(function(data) {
      $('.cart-content > .cart-content_first').html(data);
      $('.cart-content').addClass('is-open');
      if (!is_mobile()) {
        $('html').addClass('is-hidden');
      }
    });

    return false;
  });

  $('.cart-content').on('click', '.js-checkout', function() {
    var comment = $(this).closest('.container').find('.cart-table_textarea').val();
    $.get("/ajax/", { component: "order" }).done(function(data) {
      $('.cart-content > .cart-content_second').html(data);
      $('.cart-content > .cart-content_second').find('textarea[name="COMMENT"]').val(comment);
      $('.cart-content_second').addClass('is-open');
    });
    return false;
  });


  $('.cart-content').on('click', '.cart-content_second .js_back-site', function() {
    $('.cart-content_second').removeClass('is-open');
    $('.cart-content_first').addClass('is-open');
    return false;
  });

  $('.cart-content').on('click', '.cart-close', function () {
    $('.cart-content').removeClass('is-open');
    $('.cart-content_second').removeClass('is-open');
    $('.cart-content_third').removeClass('is-open');
    $('html').removeClass('is-hidden');
  });

  $('.js-back-site_2').on('click', function() {
    $('.cart-content').removeClass('is-open');
    $('.cart-content_second').removeClass('is-open');
    $('.cart-content_third').removeClass('is-open');
    $('html').removeClass('is-hidden');
    return false;
  });
  if ($(window).width() < 575) {
    $('.category-item_other').on('click', function() {
      if ($(this).find('.category-item_other-list').length) {
        $(this).toggleClass('is-active');
        $(this).find('.category-item_other-list').slideToggle(150);
      }
    });
    $('.category-item_other-title').on('click', function() {
      if ($(this).closest('.category-item_other').find('.category-item_other-list').length) {
        $(this).closest('.category-item_other').toggleClass('is-active');
        $(this).closest('.category-item_other').find('.category-item_other-list').slideToggle(150);
        return false;
      }
    });
  }
  $('.catalog-menu_item').on('click', function() {
    if ($(this).find('.catalog-submenu').length) {
      $(this).children('a').toggleClass('is-active');
      $(this).find('.catalog-submenu').slideToggle(150);
    }
  });
  $('.catalog-menu_item > a').on('click', function() {
    if ($(this).closest('.catalog-menu_item').find('.catalog-submenu').length) {
      $(this).toggleClass('is-active');
      $(this).closest('.catalog-menu_item').find('.catalog-submenu').slideToggle(150);
      return false;
    }
  });
  $('.filter-box_title').on('click', function() {
    $('.filter-box_content').slideToggle(150);
    $(this).toggleClass('is-active');
  });
  if (is_mobile()) {
    $('.product-table').on('click', '.product-table_first-cell', function(e) {
      $(this).closest('tr').siblings('tr').find('.product-item_popup').fadeOut(100);
      if ($(e.target).closest(".product-item_popup").length == 0) $(this).find('.product-item_popup').fadeIn(100);
    });
    $('.product-table').on('click', '.product-item_popup-close', function() {
      $(this).closest('.product-item_popup').fadeOut(100);
    });
  }
  $('.catalog_sidebar-title').on('click', function(e) {
    $(this).toggleClass('is-active');
    $('.catalog-menu').slideToggle(150);
  });
  $('.vacancy-item_more-details').on('click', function(e) {
    if ($(this).html() == 'Свернуть') {
      $(this).closest('.vacancy-item').find('.vacancy-item_hidden').slideUp(150);
      $(this).text('Подробнее');
    } else {
      $(this).closest('.vacancy-item').find('.vacancy-item_hidden').slideDown(150);
      $(this).text('Свернуть');
    }
    $(this).toggleClass('is-active');
  });
  if (!is_mobile()) {
    $('.js-modal').on('show.bs.modal', function(event) {
      $('.fixed-menu').addClass('is-overflow');
      $('.scroll-to-top').addClass('is-hidden');
    });
    $('.js-modal').on('hidden.bs.modal', function(event) {
      $('.fixed-menu').removeClass('is-overflow');
      $('.scroll-to-top').removeClass('is-hidden');
    });
  }
  $('.js-select').selectric({
    maxHeight: 200,
    disableOnMobile: false,
    nativeOnMobile: false,
  });
  $('.tab-container').on('click', '.tab:not(.active)', function() {
    $(this).addClass('active').siblings().removeClass('active')
    $(this).closest('.tab-container').find('.tab-item').removeClass('is-visible').eq($(this).index()).addClass('is-visible');
    $(this).closest('.container').find('.contact_right-column .tab-item').removeClass('is-visible').eq($(this).index()).addClass('is-visible');

    $(this).closest('.tab-container').find('.tab-item').eq($(this).index()).find('.js-select').trigger('change');
  });
  var heightTopHead = $('.ui-header').outerHeight() || 120;
  function updateStickyHeader() {
    // пересчёт: админ-панель Bitrix / ресайз меняют высоту шапки
    if (!$('.ui-header').hasClass('fixed-menu')) {
      heightTopHead = $('.ui-header').outerHeight() || heightTopHead;
    }
    if ($(window).scrollTop() > Math.max(heightTopHead - 8, 40)) {
      $('.ui-header').addClass('fixed-menu');
      $('.global-wrapper').addClass('global-pad');
      // сразу выводим sticky (без 100ms «провала» за экран)
      $('.ui-header').addClass('scroll-transform');
    } else {
      $('.ui-header').removeClass('fixed-menu');
      $('.ui-header').removeClass('scroll-transform');
      $('.global-wrapper').removeClass('global-pad');
    }
  }
  jQuery(window).on("scroll load resize", function() {
    updateStickyHeader();
    if ($(window).scrollTop() > $(window).height()) {
      $('.scroll-to-top').addClass('scroll-to-top-visible');
    } else {
      $('.scroll-to-top').removeClass('scroll-to-top-visible');
    }
  });
  $('.scroll-to-top').on('click', function() {
    $('html, body').animate({
      scrollTop: 0
    }, 800);
    return false;
  });
  $('input[type="tel"]').inputmask("+7 (999) 999 99 99", {
    "clearIncomplete": true,
    showMaskOnHover: false,
  });

  $("#product-table").fancyTable({
    nColumns: Math.max($("#product-table thead tr:first-child th").length, 1),
    sortColumn: 1,
    sortable: false,
    searchable: false,
    globalSearch: true,
    pagination: false,
  });

  /**
   * Умный поиск над таблицей: фильтр по названию / марке / размерам (все строки раздела).
   */
  (function initProductTableSmartSearch() {
    var $table = $('#product-table');
    var $wrap = $('[data-product-table-search]');
    if (!$table.length || !$wrap.length) {
      return;
    }
    var $input = $wrap.find('.product-table-smart-search__input');
    var $clear = $wrap.find('.product-table-smart-search__clear');
    var $meta = $wrap.find('.product-table-smart-search__meta');
    var timer = null;

    function normalize(str) {
      return String(str || '')
        .toLowerCase()
        .replace(/ё/g, 'е')
        .replace(/[х×⁎﹡]/g, 'x')
        .replace(/,/g, '.')
        .replace(/\s+/g, ' ')
        .trim();
    }

    function getRows() {
      return $table.find('tbody tr');
    }

    function rowHaystack($row) {
      var cached = $row.data('searchText');
      if (typeof cached === 'string') {
        return cached;
      }
      var clone = $row.clone();
      clone.find('.product-item_popup, script, style, .product-availability-marker__tip, .product-availability').remove();
      cached = normalize(clone.text());
      $row.data('searchText', cached);
      return cached;
    }

    function applyFilter() {
      var $rows = getRows();
      var total = $rows.length;
      var q = normalize($input.val());
      $clear.prop('hidden', !q);
      var visible = 0;
      if (!q) {
        $rows.show();
        $meta.text('');
        return;
      }
      var tokens = q.split(' ').filter(Boolean);
      $rows.each(function() {
        var hay = rowHaystack($(this));
        var ok = tokens.every(function(token) {
          return hay.indexOf(token) !== -1;
        });
        $(this).toggle(ok);
        if (ok) {
          visible++;
        }
      });
      if (visible === 0) {
        $meta.text('Ничего не найдено');
      } else {
        $meta.text('Найдено: ' + visible + ' из ' + total);
      }
    }

    $input.on('input search', function() {
      clearTimeout(timer);
      timer = setTimeout(applyFilter, 120);
    });
    $input.on('keydown', function(e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        clearTimeout(timer);
        applyFilter();
      }
    });
    $clear.on('click', function() {
      $input.val('').trigger('input').focus();
    });
  })();

  $('#success_msg').modal('show');

  $(".to_order-main").stick_in_parent({
    offset_top: 150
  });

  $('#inStockCat').click(function(e){
    e.preventDefault();

    let self = $(this);
	self.css('cursor', 'no-drop');
    $.cookie(`in_stock_${self.data('cat_id')}`, true, { expires: 30, path: '/' });
	$.get("/ajax/cache.php");
	  
	 setTimeout(() => { window.location.reload(); }, 1000);
  });

  $('[data-text]').each(function(i, el){
    let self = $(el);
    self.html(self.data('text'));
  });

  $('.tag-slider').slick({
    dots: false,
    arrows: false,
    infinite: true,
    autoplay: true,
    variableWidth: true,
    centerMode: true,
    slidesToShow: 3,
    responsive: [
      {
        breakpoint: 767,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1
        }
      }
    ]
  });

  $(".subcategories .open").click(function(){
    let tags = $(this).closest('.tags-list');

    $(this).hide();
    tags.find(".subcategories .close").show();
    tags.find(".subcategories .sub-links-2").addClass("open");
    tags.find('.tag-slider').slick('unslick');
  });
  $(".subcategories .close").click(function(){
    let tags = $(this).closest('.tags-list');
    $(this).hide();
    tags.find(".subcategories .open").show();
    tags.find(".subcategories .sub-links-2").removeClass("open");
    tags.find('.tag-slider').slick({
      dots: false,
      arrows: false,
      infinite: true,
      autoplay: true,
      variableWidth: true,
      centerMode: true,
      slidesToShow: 3,
    });
  });

  $( "#accordion" ).accordion({
	active: false,
    collapsible: true,
    heightStyle: "content",
    icons: {
      "header": "none",
      "activeHeader": "none"
    }
  });

});
if ($('.map-container').length) {
  YaMapsShown = false;
  $(window).on("scroll load resize", function() {
    if (!YaMapsShown) {
      if ($(window).scrollTop() + $(window).height() > $('.map-container').offset().top - 500) {
        showYaMaps();
        YaMapsShown = true;
      }
    }
  });

  function showYaMaps() {
    var script = document.createElement("script");
    script.type = "text/javascript";
    script.src = "https://api-maps.yandex.ru/2.1/?lang=ru_RU";
    document.getElementById("map").appendChild(script);
    script.onload = function() {
      ymaps.ready(init);
      var myMap,
        myPlacemark;

      function init() {

        // Создание экземпляра карты.
        var myMap = new ymaps.Map('map', {
              center: $($(".js-select option").get(0)).data('center').split(','),
              zoom: 18,
              behaviors: ['default', 'scrollZoom'],
            }, {
              searchControlProvider: 'yandex#search'
            });
            myMap.behaviors.disable('scrollZoom');

        collection = new ymaps.GeoObjectCollection(null, { preset: "islands#redIcon" }),
        myMap.geoObjects.add(collection)

        var arPlacemark = [];
        $(".js-select option").each(function(indx, element){

          if($(element).data('center')){
            placemark = new ymaps.Placemark($(element).data('center').split(','), {
              balloonContent: $(element).text()
            }, {
              balloonAutoPan: false
            });
            collection.add(placemark)
            arPlacemark[$(element).val()] = placemark;
          }
        });

        $('.js-select').bind('change', function () {
          var id = $(this).find('option:selected').val();
          if(arPlacemark[id]){
            if (!arPlacemark[id].balloon.isOpen()) {
              arPlacemark[id].balloon.open();
              myMap.setCenter($(this).find('option:selected').data('center').split(','), 18);
            } else {
              arPlacemark[id].balloon.close();
            }
          }
          return false;
        });

      }
    }
  }
}
