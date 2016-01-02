// Scroll animations/Parallax
$(window).scroll(function () {
    var sc = $(window).scrollTop();
    var sc_para = sc / 3.5;

    if (sc > $('.ism-slider').height() / 2) {
      $('.container-1').addClass('fade-up');
    }

    if (sc > $('.ism-slider').height() / 2 + $('.container-1').height() / 3) {
      $('.container-2').addClass('fade-up');
    }

    if (sc > $('.ism-slider').height() / 2 + $('.container-1').height() / 2 + $('.container-2').height() / 2) {
      $('.container-3').addClass('fade-up');
    }

    if (sc > $('.ism-slider').height() / 2 + $('.container-1').height() / 2 + $('.container-2').height() / 2 + $('.container-3').height() ) {
      $('.container-4').addClass('fade-up');
    }


    var footerDisplay = $('.pageMiddle').innerHeight() / 2;
    console.log(footerDisplay);

    if (sc > footerDisplay) {
      $('footer').addClass('footer-display');
    } else {
      $('footer').removeClass('footer-display');
    }

});




// FUNCTION RESIZE
$(function() {
  responsive();

  function responsive() {
    windowWidth = $(window).width();
    windowHeight = $(window).height();
    headerHeight = $('header').height();

    $('.parallax').css({'padding-top' : headerHeight + 10});

    if(windowWidth < 685) {
      $('.media-remove1').css('display', 'none');
      $('.media-remove2').css('display', 'none');
      $('.media-show').css('display', 'inline');
      $('header').css('justify-content', 'space-between');
    } else {
      $('.media-remove1').css('display', 'inline');
      $('.media-remove2').css('display', 'inline');
      $('.media-show').css('display', 'none');
      $('header').css('justify-content', 'space-around');
    }

    var textboxWidth = $('.postarea').width() - $('.postimg').width() - 21;
    $('.textbox').css({'width': textboxWidth});

    if(windowWidth < 800 && windowWidth > 490) {
      var pageMiddle = $('.pageMiddle').width();
      $('.postarea').css({'width': windowWidth - 50});

      var textboxWidth = ($('.postarea').width() - $('.postimg').width() - 21) * 2;
      $('.textbox').css({'width': textboxWidth});

    } else if (windowWidth <= 490) {
      var textboxWidth = ($('.postarea').width() - $('.postimg').width() - 21) * 3;
      $('.textbox').css({'width': textboxWidth});

    }

    $('.search-slider').css({'top': headerHeight});
  }

  responsive();
  $(window).resize(function() {
    responsive();
  });


  $('.media-show').click(function() {
    $('.menu').animate({
      left: '0px'
    }, 200);

    $('body, .ism-slider').animate({
    left: '287px'
    }, 200);

    $('.menu-close-body').css('display', 'block');

    $('.menu-close').click(function() {
        $('.menu').animate({ left: '-2878px'}, 200);
        $('body, .ism-slider ').animate({ left: '0px'}, 200);
        $('.menu-close-body').css('display', 'none');
    });

  });

});



// CHECK FOR BREADCRUMBS
$(function() {
  var breadCrumb = $('body').attr("data-pageName");
  var breadCrumbChild = $('body').attr("data-childPageName");

  $('.'+breadCrumb).addClass('active');
  $('.'+breadCrumbChild).addClass('active');

  $('.search-slide').click(function() {
    $('.search-slider').slideToggle(300);
    $('.search-toggle').toggleClass('active');
  });
});




// CUSTOM FUNCTIONS FOR VALIDATION
function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
    return pattern.test(emailAddress);
};

function isNumeric(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}




// Form Signup Design & validation ONLY
$(function() {


  $('.fancy-text-form input, .fancy-text-form select, .fancy-text-form textarea, .transparent-blue-textarea, .transparent-blue-box').focusout(function() {

    var focusFancy = $(this).val();

    if (focusFancy === "") {

      $(this).removeClass('focusFancy');

    } else {

      $(this).addClass('focusFancy');
      $(this).removeClass('error');

    }

  });

  $('.submit-login').on('click', function() {
    $('.login-required').each(function() {
      var $this = $(this);

      if ($this.val().length === 0) {
        $this.addClass('error');
      }
    })
  });
  $('.submit-contact').on('click', function() {
    $('.contact-required').each(function() {
      var $this = $(this);

      if ($this.val().length === 0) {
        $this.addClass('error');
      }
    })
  });

  $('.submit-feedback').on('click', function() {
    $('.feedback-required').each(function() {
      var $this = $(this);

      if ($this.val().length === 0) {
        $this.addClass('error');
      }
    })
  });

  $('.submit-password').on('click', function() {
    $('.password-required').each(function() {
      var $this = $(this);

      if ($this.val().length === 0) {
        $this.addClass('error');
      }
    })
  });

  $('.submit-setting').on('click', function() {
    $('.setting-required').each(function() {
      var $this = $(this);

      if ($this.val().length === 0) {
        $this.addClass('error');
      }
    })
  });

  $('.submit-post').on('click', function() {
    $('.post-required').each(function() {
      var $this = $(this);

      if ($this.val().length === 0) {
        $this.addClass('error');
      }
    })
  });


  $('.signup-launch').on('click', function() {
    $('.pop-up-background').fadeIn( 500 );
    $('.signup-form-pop').fadeIn( 500 );
  });

  $('.feedback-text').on('click', function() {
    $('.pop-up-background').fadeIn( 500 );
    $('.feedback-pop').fadeIn( 500 );
  });


  $('body').on('click','.exit', function() {
    $('.pop-up-background').fadeOut( 500 );
    $('.signup-form-pop, .login-form, .feedback-pop').fadeOut( 500 );
  });
});
