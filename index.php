<!DOCTYPE html>
<html>

<head>
<title>Scott Young &mdash; Perth, Western Australia</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="/home/lib/jquery.min.js"></script>
<link rel="openid.server" href="https://secure.ucc.asn.au/openid/" />
<link href='//fonts.googleapis.com/css?family=Bitter' rel='stylesheet' type='text/css'>
<link href="/home/home.css" rel="stylesheet">
<link href="/home/lib/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<style type="text/css">
#header { <?
    $BG_IMAGES = [
        [
            'name' => 'hang',
            'desc' => 'Flying at <a href="http://dynamicflight.com.au">Dynamic Flight Hang Gliding School</a>',
            'date' => '23 April 2014'
        ],
        [
            'name' => 'sunset',
            'desc' => 'Sunset in the <a href="https://en.wikipedia.org/wiki/Blue_Mountains_(New_South_Wales)">Blue Mountains</a>',
            'date' => '10 August 2006'
        ],
        [
            'name' => 'nyc',
            'desc' => 'Sunset from the <a href="https://en.wikipedia.org/wiki/Staten_Island_Ferry">Staten Island Ferry</a>',
            'date' => '26 September 2013'
        ],
        [
            'name' => 'dcmetro',
            'desc' => '<a href="https://en.wikipedia.org/wiki/Metro_Center_(Washington_Metro)">Metro Center</a> station in Washington, D.C.',
            'date' => '2 October 2013'
        ],
    ];

    $img = $BG_IMAGES[array_rand($BG_IMAGES)];
    echo "background-image: url('/home/backgrounds/".$img['name'].".jpg');"; ?>
}
</style>
<script type="text/javascript">
window.BG_IMAGES = <?
    // preserve ordering for cycling backgrounds later
    echo json_encode($BG_IMAGES) ?>
</script>
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
    <p id="tagline">Perth, Western Australia</p>
    <i id="expand" class="fa fa-arrows-v"></i>
    <i id="refresh" class="fa fa-refresh"></i>
    <p id="caption"><?= $img['desc'] ?></p>
</div><!-- end #header -->

<div id="sections">

<div id="about">
    <img src="/home/perth-small.jpg" class="photo" alt="perth's skyline">
    <p>I'm Scott and <code>sjy.id.au</code> is my part of the Internet.</p>
    <p>I <?
        echo 'am '; $now = new DateTime();
        echo $now->diff(new DateTime('1990-08-28'))->y;
        echo ' years old and';
    ?> live in <a href="http://en.wikipedia.org/wiki/Perth">Perth, Western
    Australia</a>. I studied law and pure mathematics at <a
    href="http://uwa.edu.au">UWA</a> and currently work as a programmer at
    <a href="http://ii.net">iiNet</a>. I also teach calculus at 
    <a href="http://pibt.wa.edu.au/">PIBT</a>.</p>

    <div id="books">
    <? include('goodreads.html'); ?>
    </div><!-- end #books -->
</div><!-- end #about -->
<? include('projects.html') ?>

<div id="contact">
<ul>
    <li><i class="fa fa-envelope"></i>  <a href="mailto:scott@sjy.id.au">scott@sjy.id.au</a></li>
    <li><i class="fa fa-skype"></i>     scott.j.young</li>
    <!--<li><i class="fa fa-facebook"></i>  <a href="http://facebook.com/scott.young">scott.young</a></li>-->
    <li><i class="fa fa-github"></i>    <a href="https://github.com/splintax">splintax</a></li>
    <li><i class="fa fa-instagram"></i>     <a href="https://instagram.com/splintax">splintax</a></li>
    <li><i class="fa fa-stack-exchange"></i>    <a href="http://stackexchange.com/users/1497308/sjy">sjy</a></li>
</ul>
</div><!-- end #contact -->

</div><!-- end #sections -->

<div id="ruler"><img src="/home/ruler.svg" alt="space-filling curve"></div>

<script type="text/javascript" src="/home/home.js"></script>

</body>
</html>
