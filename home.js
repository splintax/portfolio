$(document).ready(function() {
    $.easing.easeOutBack = function (x, t, b, c, d, s) {
        if (s == undefined) s = 1.70158;
        return c*((t=t/d-1)*t*((s+1)*t + s) + 1) + b;
    };

    // wrap individual letters in <span> for letterwise animation
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
    var animToggle = false; // don't animate by default
    var showBigPic = function() {
        $('h1').addClass('active');
        $('#nav a').removeClass('active')

        // toggle content visibility
        $('#sections, #ruler').hide();
        $('#expand').fadeOut();
        $('#tagline').fadeIn();

        // slide down
        $('#header').animate({height: '100%'}, animToggle*800);
        $('#spacer').animate({height: '60%'}, animToggle*800);

        // always animate after the first time
        animToggle = true;
    };
    var showContent = function() {
        // toggle content visibility
        $('#sections, #ruler').show();
        $('#expand').fadeIn();
        $('#tagline').hide();

        // slide up
        $('#header').animate({height: '30%'}, animToggle*800);
        $('#spacer').animate({height: '40%'}, animToggle*800)

        // always animate after the first time
        animToggle = true;
    }

    $('h1').click(function() {
        if ($(this).hasClass('active')) {
            $('a[href="#about"]').click();
        } else {
            window.location.hash = '';
            showBigPic();
        }
    });

    $.fn.target = function() {return $(this.attr('href'));}
    // content view: one section expanded
    $('#nav a[href^="#"]').click(function(event) {
        // add hash and show content
        event.preventDefault();
        window.location.hash = $(this).attr('href');
        $(this).target().fadeIn().siblings().hide();

        // add classes
        $('#nav a, h1').removeClass('active');
        $(this).addClass('active');

        showContent();
    });

    // load subpage when loaded from query string
    var clickedLink = $('a[href="' + window.location.hash + '"]');
    if (clickedLink.length === 1)
        $('a[href="' + window.location.hash + '"]').click()
    else
        showBigPic()

    $('#expand').click(showBigPic);

    // external link icons
    $('a[href$="pdf"]').after(' <i class="fa fa-file-o"></i>');

    var cycleBackgrounds = function() {
        $('#header').css({opacity: 0});

        // swap image
        var img = window.BG_IMAGES.shift();
        var url = 'url("http://sjy.id.au/home/backgrounds/' + img.name + '.jpg")';
        $('#header').css('backgroundImage', url);
        $('#caption').html(img.desc + ' — ' + img.date);
        window.BG_IMAGES.push(img);

        $('#header').animate({opacity: 1}, 500);
    }
    $('#refresh').click(cycleBackgrounds);

});
