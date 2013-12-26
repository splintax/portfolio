$(document).ready(function() {
    $.easing.easeOutBack = function (x, t, b, c, d, s) {
        if (s == undefined) s = 1.70158;
        return c*((t=t/d-1)*t*((s+1)*t + s) + 1) + b;
    };

    // one-off DOM manipulation (wrapping individual letters in <span>);
    // allows letter-by-letter animation
    var dir = 'top';
    if (window.location.hash.length > 1)
        dir = 'bottom'; // flip animation if we're already on sub-page
    var dirDict = {}
    dirDict[dir] = '0px'; // .animate() resets top or bottom offset to zero
        
    $('h1').html
        ( '<span>' + $('h1').text().split('')
          .join('</span><span>') + '</span>'  )
        .children().css(dir, '1000px')
        .each(function(i, el) {
            $(el).css('position', 'relative')
                .delay(i*50).animate(dirDict, 500, 'easeOutBack');
        });

    // default view: sections hidden, heading at the bottom of the page

    // skip some animations if
    // the user is loading the homepage directly
    var animToggle = false; // don't animate by default
    var showDefaultView = function() {
        // add classes
        $('h1').addClass('active');
        $('#nav a').removeClass('active')

        // toggle content visibility
        $('#sections, #ruler').hide();
        $('#tagline').fadeIn();

        // slide down
        $('#header').animate({height: '100%'}, animToggle*800);
        $('#spacer').animate({height: '60%'}, animToggle*800);

        // always animate after the first time
        animToggle = true;
    };

    $('h1').click(function() {
        if ($(this).hasClass('active')) {
            $('a[href="#about"]').click();
        } else {
            window.location.hash = '';
            showDefaultView();
        }
    });

    // helpful jquery extension for <a href="foo">
    $.fn.target = function() {
        return $(this.attr('href'));
    }

    // content view: one section expanded
    $('#nav a[href^="#"]').click(function(event) {
        // handle link-clicking
        event.preventDefault();
        window.location.hash = $(this).attr('href');

        // add classes
        $('#nav a, h1').removeClass('active');
        $(this).addClass('active');

        // toggle content visibility
        $('#sections, #ruler').show();
        $('#tagline').hide();

        $('#header').animate({height: '30%'}, animToggle*800);
        $('#spacer').animate({height: '50%'}, animToggle*800)

        var target = $(this).target();
        $(this).target().fadeIn().siblings().hide();

        // always animate after the first time
        animToggle = true;
    });

    // load subpage when loaded from query string
    var clickedLink = $('a[href="' + window.location.hash + '"]');
    if (clickedLink.length === 1)
        $('a[href="' + window.location.hash + '"]').click()
    else
        showDefaultView()

    // external link icons
    $('a[href^="http"]').after(' <i class="fa fa-external-link"></i>');
    $('a[href$="pdf"]').after(' <i class="fa fa-file-o"></i>');

    var loadGoodreadsData = function() {
        
    }

});
