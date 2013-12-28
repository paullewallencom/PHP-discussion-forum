<?php
include("config.php");
if(isset($_GET['id']) == TRUE) {
    if(is_numeric($_GET['id']) == FALSE) {
        header("Location: " . $config_basedir);
    }
    $validforum = $_GET['id'];
}
else {
    header("Location: " . $config_basedir);
}
require("header.php");
$forumsql = "SELECT * FROM forums WHERE id = " . $validforum . ";";
$forumresult = mysql_query($forumsql);
$forumrow = mysql_fetch_assoc($forumresult);
echo "<h2>" . $forumrow['name'] . "</h2>";
echo "<a href='index.php'>" . $config_forumsname
        . " forums</a><br /><br />";
echo "[<a href='newtopic.php?id=" . $validforum . "'>New Topic</a>]";
echo "<br /><br />";
$topicsql = "SELECT MAX( messages.date ) AS maxdate,
topics.id AS topicid, topics.*, users.*
FROM messages, topics, users WHERE messages.topic_id
= topics.id AND topics.user_id = users.id AND
topics.forum_id = " . $validforum . " GROUP BY
messages.topic_id ORDER BY maxdate DESC;";
$topicresult = mysql_query($topicsql);
$topicnumrows = mysql_num_rows($topicresult);
if($topicnumrows == 0) {
    echo "<table width='300px'><tr><td>No topics!</td></tr></table>";
}
else {
    echo "<table class='forum'>";
    echo "<tr>";
    echo "<th>Topic</th>";
    echo "<th>Replies</th>";
    echo "<th>Author</th>";
    echo "<th>Date Posted</th>";
    echo "</tr>";
    while($topicrow = mysql_fetch_assoc($topicresult)) {
        $msgsql = "SELECT id FROM messages WHERE
topic_id = " . $topicrow['topicid'];
        $msgresult = mysql_query($msgsql);
        $msgnumrows = mysql_num_rows($msgresult);
        echo "<tr>";
        echo "<td>";
        echo "<strong>
<a href='viewmessages.php?id="
                . $topicrow['topicid'] . "'>"
                . $topicrow['subject'] . "</a></strong></td>";
        echo "<td>" . $msgnumrows . "</td>";
        echo "<td>" . $topicrow['username'] . "</td>";
        echo "<td>" . date("D jS F Y g.iA", strtotime($topicrow['date']))
                . "</td>";
        echo "<tr>";
    }
    echo "</table>";
}
require("footer.php");
?>