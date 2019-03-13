<?php

/**
 *  Class to retrieve all videos of a certain channel and return them as an array
 *  YouTube API v 3.0
 */
class Videos
{
    private $apiBaseURL     = "https://www.googleapis.com/youtube/v3/";
    private $apiKey         = NULL;
    private $channelID      = NULL;
    private $channelName    = NULL;
    private $pid            = NULL;
    private $totalVids      = NULL;
    private $videos         = array();

    public function init(){
        if ($this->channelName !== NULL  || $this->channelID !== NULL) {
            if($this->channelName !== NULL){
                $apiString = "channels?part=contentDetails&forUsername=" . $this->channelName;
            }else{
                $apiString = "channels?part=contentDetails&id=" . $this->channelID;
            }
            $this->pid = $this->get($apiString)->items[0]->contentDetails->relatedPlaylists->uploads;
        } else {
            die("Error: No Channel specified");
        }
    }

    public function getVideos(){
        if(!isset($this->pid)) return false;
        $apiString = "playlistItems?part=snippet&playlistId=".$this->pid."&key=".$this->apiKey;
        $data = $this->get($apiString);
        $this->totalVids = $data->pageInfo->totalResults;
        $nextPageToken = $data->nextPageToken;
        foreach($data->items as $value){
            array_push($this->videos, array(
                'videoID'       => $value->snippet->resourceId->videoId,
                'publishedAt'   => $value->snippet->publishedAt,
                'title'         => $value->snippet->title,
                'description'   => $value->snippet->description,
                'thumbnail'     => $value->snippet->thumbnails->default,
            ));
        }
        while(count($this->videos) < $this->totalVids){
            $apiString = "playlistItems?part=snippet&playlistId=".$this->pid."&key=".$this->apiKey."&pageToken=".$nextPageToken;
            $data = $this->get($apiString);
            foreach($data->items as $value){
                array_push($this->videos, array(
                    'videoID'       => $value->snippet->resourceId->videoId,
                    'publishedAt'   => $value->snippet->publishedAt,
                    'title'         => $value->snippet->title,
                    'description'   => $value->snippet->description,
                    'thumbnail'     => $value->snippet->thumbnails->default,
                ));
            }
            if(!isset($data->nextPageToken)){ break; }
        }
        return $this->videos;
    }

    private function get($apiString){
        $response = file_get_contents($this->apiBaseURL . $apiString . "&key=" . $this->apiKey);
        if ($response === false) {
            die("Error: Couldn't get file contents");
        }
        return json_decode($response);
    }

    public function getPID(){
        return $this->pid;
    }

    public function setAPIKey($key){
        $this->apiKey = $key;
        return true;
    }

    public function setChannelName($channel){
        $this->channelName = $channel;
        return true;
    }

    public function setChannelID($channel){
        $this->channelID = $channel;
        return true;
    }

    public function setApiBaseURL($url){
        $this->apiBaseURL = $url;
        return true;
    }

}



