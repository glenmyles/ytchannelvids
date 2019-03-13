<pre>
<?php
require 'Classes/Videos.class.php';

$videos = new Videos;
$videos->setAPIKey("{__GOOGLE_API_KEY__}");
$videos->setChannelName("{_YOUTUBE_CHANNEL_}");
$videos->init();
var_dump($videos->getVideos());


