<?php

    $link = mysqli_connect("shareddb-g.hosting.stackcp.net","vastukosh-32353f7f","password98@","vastukosh-32353f7f");

    $words = Array('ability', 'able', 'about', 'above', 'behavior', 'behind', 'believe', 'benefit', 'card', 'care', 'career', 'carry', 'democratic', 'describe', 'design', 'despite', 'eight', 'either', 'election', 'else', 'fear', 'federal', 'feel', 'feeling', 'goal', 'good', 'government', 'great', 'herself', 'high', 'him', 'himself', 'including', 'increase', 'indeed', 'indicate', 'job', 'join', 'just', 'juggle', 'kind', 'kitchen', 'know', 'knowledge', 'lead', 'leader', 'learn', 'least', 'maintain', 'major', 'majority', 'make', 'national', 'natural', 'nature', 'near', 'open', 'operation', 'opportunity', 'option', 'particularly', 'partner', 'party', 'pass', 'quality', 'question', 'quickly', 'quite', 'reach', 'read', 'ready', 'real', 'scientist', 'score', 'sea', 'season', 'third', 'this', 'those', 'though', 'understand', 'unit', 'until', 'up', 'victim', 'view', 'violence', 'visit', 'want', 'war', 'watch', 'water', 'yeah', 'year', 'yes', 'yet');

    $sentence = "";

    for($i=10074;$i<=10087;$i++) {
        $r = rand(2,8);
        $query = "UPDATE `items` SET `count` = '".$r."' WHERE `iid` = '".$i."'";
        mysqli_query($link, $query);
    }

?>