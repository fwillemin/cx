$( window ).on('load',function() {
    $(document).on('click touchstart', '.navbar .dropdown-menu', function(e) {
      e.stopPropagation();
    })
  });

$( document ).ready(function() {

    var navHeight = $('#navHeader').offset().top;
    FixMegaNavbar(navHeight);
    $(window).bind('scroll', function() {FixMegaNavbar(navHeight);});

    function FixMegaNavbar(navHeight) {
      if (!$('#navHeader ').hasClass('navbar-fixed-bottom')) {
        if ($(window).scrollTop() > navHeight) {
          $('#navHeader ').addClass('navbar-fixed-top')
          $('body').css({'margin-top': $('#navHeader ').height()+'px'});

          if ($('#navHeader ').parent('div').hasClass('container')) $('#navHeader ').children('div').addClass('container').removeClass('container-fluid');
          else if ($('#navHeader ').parent('div').hasClass('container-fluid')) $('#navHeader ').children('div').addClass('container-fluid').removeClass('container');
        }
        else {
            $('#navHeader ').removeClass('navbar-fixed-top');
            $('#navHeader ').children('div').addClass('container-fluid').removeClass('container');
            $('body').css({'margin-top': ''});
        }
      }
    }

  });