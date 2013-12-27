<div id="about">
    <img src="/home/perth-small.jpg" class="photo" alt="perth's skyline">
    <p>I'm Scott and <code>sjy.id.au</code> is my part of the Internet.</p>
    <p>I <?
        echo 'am '; $now = new DateTime();
        echo $now->diff(new DateTime('1990-08-28'))->y;
        echo ' years old and';
    ?> live in <a href="http://en.wikipedia.org/wiki/Perth">Perth, Western
    Australia</a>. I completed bachelor's degrees in law and 
    pure mathematics at <a href="http://uwa.edu.au">UWA</a> and
    have worked as a law clerk and programmer.</p>

    <div id="books">
    <?  $json = json_decode(exec('/usr/bin/python goodreads.py'));
        $imageToggle = false;
        $paintBook = function($book, $type) use (&$imageToggle) { /*
            <img src="<?= $book->image.($imageToggle ? '" class="alt' : '') ?>">
            <? */ $imageToggle = !$imageToggle; ?>
            <p> <? // title ?>
                <a href="<?= $book->link ?>">
                    <?= $book->title ?></a>
            </p><? // author ?>
            <p><span class="author"><?= $book->author ?></span><?
            if ($type === 'current') {
                ?><span class="rating"><?
                    if ($book->percent)
                        $pct = $book->percent;
                    else
                        $pct = $book->pages_done / $book->pages_total * 100;
                    
                    echo '<span class="done" style="width: ';
                    echo $pct/25; echo 'em"></span>';
                    echo '<span class="left" style="width: ';
                    echo 4 - $pct/25; echo 'em"></span>';
                    echo '('.$pct.'%)';
                ?></span><span class="finished">last read on <?
                    $date = new DateTime($book->last_updated);
                    echo $date->format('d M Y');
                ?></span><?
            } else if ($type === 'recent') { ?>
                <span class="rating"><?
                    for ($i=0; $i<$book->rating; $i++) echo '<i class="fa fa-star"></i>';
                    for ($i=$book->rating; $i<5; $i++) echo '<i class="fa fa-star-o"></i>';
                ?></span><span class="finished">finished on <?
                    $date = new DateTime($book->read_at);
                    echo $date->format('d M Y');
                ?></span><?
            } ?>
            </p><?
        } // end paintBook()
    ?>
    <h3>I'm currently reading...</h3>
    <div class="book">
    <? $paintBook($json->current, 'current') ?>
    </div><!-- end .book -->

    <h3>I recently finished...</h3>
    <? foreach ($json->recent as $book) { ?>
    <div class="book">
    <? $paintBook($book, 'recent') ?>
    </div><!-- end .book -->
    <? } ?>
    </div><!-- end #books -->
</div><!-- end #about -->
