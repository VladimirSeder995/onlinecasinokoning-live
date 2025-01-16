jQuery(document).ready(function ($) {
  var Site = {
    $dom: {
      readMoreButtons: $(".section-summary-read-more"),
      html: $("html, body"),
      window: $(window),
      header: $("header"),
      body: $("body"),
      nav: $(".tg-header-container"),
    },
    vars: {
      didScroll: false,
      lastScrollTop: 0,
      delta: 0,
      heightFromTop: $(".tg-site-header-bottom").outerHeight(),
      navbarHeight: $("header").outerHeight(),
    },

    init: function () {
      this.bind();
    },

    toggleHeaderVisibility: function () {
      var st = $(window).scrollTop();
      if (st < 5) {
        $(".tg-header-container").addClass("inactive");
      } else {
        $(".tg-header-container").removeClass("inactive");
        $(".tg-header-container").addClass("active");
      }
      if (Math.abs(Site.vars.lastScrollTop - st) <= Site.vars.delta) return;
      if (st > Site.vars.lastScrollTop && st > Site.vars.navbarHeight) {
        Site.$dom.header.removeClass("nav-down").addClass("nav-up");
      } else {
        if (st + $(window).height() < $(document).height()) {
          Site.$dom.header.removeClass("nav-up").addClass("nav-down");
        }
      }
      Site.vars.lastScrollTop = st;
    },
    debounce: function (fn, delay) {
      let timer;
      return function () {
        clearTimeout(timer);
        timer = setTimeout(function () {
          fn();
        }, delay);
      };
    },
    bind: function () {
      this.$dom.readMoreButtons.on("click", Site.toggleReadMore);
      this.$dom.window.on(
        "scroll",
        Site.debounce(Site.toggleHeaderVisibility, 10)
      );
    },

    toggleReadMore: function () {
      var _this = this;
      $(this).find(".section-summary-read-more-close").toggle(0);
      $(this).find(".section-summary-read-more-open").toggle(0);
      $(this)
        .prev()
        .slideToggle({
          duration: 0,
          progress: function (el, step, a) {
            $("html, body").stop().animate(
              {
                scrollTop: Site.vars.lastScrollTop,
              },
              0
            );
          },
        });
    },
  };

  Site.init();
});

// mobile search autosuggest
jQuery('.mobile-search-wrap input').on('keyup', function(){
  var thisEl = jQuery(this);
  var val = thisEl.val();

  if( val.length > 2 ){
    jQuery.ajax({
			url: theme.ajaxurl,
			type: 'POST',
			dataType: 'HTML',
			data: {
				action : 'GlobalSearchCustomEndpoint',
				val : val,
			},
		}).done(function(data) {
      jQuery('.mobile-search-results').html(data);
		});

  }
});

jQuery('.mobile-search-submit').on('click', function(){
  jQuery('.mobile-search-wrap form').trigger('submit');
});

jQuery(document).on('mouseup', function(e) 
{
    var container = jQuery(".desktop-search-wrap, .mobile-search-wrap, .main-navigation li.tg-menu-item-search i, .mobile-search-trigger i");

    if (!container.is(e.target) && container.has(e.target).length === 0) 
    {
      jQuery('.mobile-search-results').html('');
      jQuery('.mobile-search-wrap').addClass('hide');
      jQuery('body').removeClass('asearch-open');
      jQuery('.mobile-search-trigger i.tg-icon-close').removeClass('tg-icon-close').addClass('tg-icon-search').attr('type','search');

      jQuery('.desktop-search-results').html('');
      jQuery('body').removeClass('asearch-open');
      jQuery('.main-navigation li.tg-menu-item-search i').removeClass('tg-icon-close').addClass('tg-icon-search').attr('type','search');
    }
});

// desktop search autosuggest
jQuery('.desktop-search-wrap input').on('keyup', function(){
  var thisEl = jQuery(this);
  var val = thisEl.val();

  if( val.length > 2 ){
    jQuery.ajax({
			url: theme.ajaxurl,
			type: 'POST',
			dataType: 'HTML',
			data: {
				action : 'GlobalSearchCustomEndpoint',
				val : val,
			},
		}).done(function(data) {
      jQuery('.desktop-search-results').html(data);
		});

  }
});

jQuery('body').on('click', '.mobile-search-trigger i', function(){
  var thisEl = jQuery(this);

  if( thisEl.attr('type') == 'close' ){
    thisEl.attr('type','search');
    thisEl.removeClass('tg-icon-close').addClass('tg-icon-search');
    jQuery('body').removeClass('asearch-open');
    jQuery('.mobile-search-wrap').addClass('hide');
    jQuery('.mobile-search-results').html('');
  }else{
    thisEl.attr('type','close');
    thisEl.removeClass('tg-icon-search').addClass('tg-icon-close');
    jQuery('body').addClass('asearch-open');
    jQuery('.mobile-search-wrap').removeClass('hide');
  }
});

jQuery('body').on('click', '.main-navigation li.tg-menu-item-search i', function(){
  var thisEl = jQuery(this);

  if( thisEl.attr('type') == 'close' ){
    thisEl.attr('type','search');
    thisEl.removeClass('tg-icon-close').addClass('tg-icon-search');
    jQuery('.desktop-search-results').html('');
  }else{
    thisEl.attr('type','close');
    thisEl.removeClass('tg-icon-search').addClass('tg-icon-close');
  }
});