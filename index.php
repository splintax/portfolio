<!DOCTYPE html>
<html>

<head>
<title>Scott Young &mdash; Perth, Western Australia</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<link href='//fonts.googleapis.com/css?family=Bitter' rel='stylesheet' type='text/css'>
<link href="home.css" rel="stylesheet">
<link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">
<style type="text/css">
#header { <?
    $imgs = array('palm', 'stars', 'sunset'); //, 'narrows');
    $img = $imgs[array_rand($imgs)];
    echo "background-image: url('/home/images/$img.jpg');";
    echo "background-position: bottom right;";
    echo "background-repeat: no-repeat;";
    echo "background-size: cover;";
?> }
</style>
</head>

<body>

<div id="header">
<div id="spacer"></div>
<h1 id="name">Scott Young</h1>
<ul id="nav">
    <li><i class="fa fa-info"></i><a href="#about">about</a></li>
    <li><i class="fa fa-flask"></i><a href="#projects">projects</a></li>
    <li><i class="fa fa-globe"></i><a href="#contact">contact</a></li>
</ul>
<p id="tagline">is a programmer from Perth, Western Australia.</p>
</div>

<div id="sections">
    <div id="about">
        <img src="images/perth-small.jpg">
        <p>I'm <strong>S</strong>cott <strong>J.</strong>
        <strong>Y</strong>oung and <tt><strong>sjy</strong>.id.au</tt> is
        my part of the Internet.</p> <p>I
        <?
            echo 'am '; $now = new DateTime();
            echo $now->diff(new DateTime('1990-08-28'))->y;
            echo ' years old and';
        ?>
        live in <a href="http://en.wikipedia.org/wiki/Perth">Perth, Western
        Australia</a>. I have bachelor's degrees in law and 
        pure mathematics (<a href="http://uwa.edu.au"><em>W.Aust.</em></a>) and
        have worked as a law clerk and programmer.</p>
        <p>I'm a member of the <a href="http://ucc.asn.au">University Computer Club</a>.</p>
    </div>

    <div id="projects">
        <div class="half"><h3>computer programming</h3><dl>
            <dt><a href="http://uwa.edu.au/contact/map">Campus Map</a> (2013)</dt>
            <dd>Interactive map combining data from Google Maps with a searchable,
            locally-maintained database of geographical features. Significantly faster
            than the old map.</dd>
            <dd><strong>Tools</strong>: JavaScript, Google Maps API, jQuery, MySource
            Matrix, PHP, <a href="http://en.wikipedia.org/wiki/Shapefile">shapefile</a>, Apache</dd>

            <dt><a href="http://crawleyvillage.housing.uwa.edu.au">Crawley Village</a> (2012)</dt>
            <dd>Database for UWA-owned housing. Integrates with the real
            estate management software REST Professional and a mailing list.
            Maintained during active use since June 2012.</dd>
            <dd><strong>Tools</strong>: Python, HTML/CSS/JavaScript, Django, nginx</dd>
        </dl></div>
        <div class="half"><h3>legal writing</h3><dl>
            <dt>Federalism &amp; Treaty Interpretation</a> (2013)</dt>
            <dd>Dissertation completed for the requirements of <a
            href="http://units.handbooks.uwa.edu.au/units/laws/laws3347">LAWS3347</a>
            (Supervised Research I). Received a grade of 80 (high distinction).</dd>
            <dd><strong>Topics</strong>: Australian and American constitutional law,
            federalism, external affairs power, necessary and proper clause</dd>

            <dt><a href="mooting-submissions.pdf">Mooting Submissions</a> (2013)</dt>
            <dd>Written component of the 5<sup>th</sup> and final round of UWA's
            open mooting competition, which I was the overall winner of.</dd>
            <dd><strong>Topics</strong>: environmental law, administrative law,
            statutory construction, improper exercise of power, unreasonableness</dd>
        </dl></div>
    </div>

    <div id="contact">
    <ul>
        <li><i class="fa fa-envelope"></i>  <a href="mailto:scott@sjy.id.au">scott@sjy.id.au</a></li>
        <li><i class="fa fa-skype"></i>     scott.j.young</li>
        <li><i class="fa fa-facebook"></i>  <a href="http://facebook.com/scott.young">scott.young</a></li>
        <li><i class="fa fa-github"></i>    <a href="https://github.com/splintax">splintax</a></li>
        <li><i class="fa fa-instagram"></i>     <a href="https://instagram.com/splintax">splintax</a></li>
        <li><i class="fa fa-stack-exchange"></i>    <a href="http://stackexchange.com/users/1497308/sjy">sjy</a></li>
    </div>
</div>

<div id="ruler"><img src="images/ruler.svg"></div>

<script type="text/javascript" src="home.js"></script>

</body>
</html>
