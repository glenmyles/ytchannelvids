<pre>
<?php
require 'Classes/Videos.class.php';

$videos = new Videos;
$videos->setAPIKey("AIzaSyA9qwDtn_QWUmr4HzQee_vL96T1a-ilcPA");
$videos->setChannelName("ActualDannyGonzalez");
$videos->init();
var_dump($videos->getVideos());


