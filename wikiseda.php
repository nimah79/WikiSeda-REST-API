<?php

/*
 * WikiSeda REST API
 * By NimaH79
 * NimaH79.ir
*/

class wikiseda
{
    private $lang;

    private $host = 'https://getsongg.com/dapp/';

    public function __construct($lang)
    {
        $this->lang = $lang;
    }

    public function getNewCases($page = 1, $type = 'all')
    {
        if ($type == 'all') {
            $page = $this->curlRequest($this->host.'getnewcases', ['page' => $page, 'type' => $type, 'lang' => $this->lang]);
        } else {
            $page = $this->curlRequest($this->host, ['page' => $page, 'order' => $order, 'type' => $type, 'lang' => $this->lang]);
        }
        $page = json_decode($page, true);
        $results = [];
        foreach ($page as $item) {
            if (in_array($item['type'], ['song', 'album', 'artist'])) {
                $results[] = $item;
            }
        }

        return $results;
    }

    public function getTrackDetails($id, $lyric = 1)
    {
        $result = $this->curlRequest($this->host.'gettrackdetail?'.http_build_query(['id' => $id, 'lyric' => $lyric, 'lang' => $this->lang]));
        $result = json_decode($result);

        return $result;
    }

    public function getAlbumDetails($id)
    {
        $result = $this->curlRequest($this->host.'getalbumdetail?'.http_build_query(['id' => $id, 'lang' => $this->lang]));
        $result = json_decode($result);

        return $result;
    }

    public function getArtistDetails($id, $page = 1, $order = 'new')
    {
        $page = $this->curlRequest($this->host.'getnewcases?'.http_build_query(['signer_id' => $id, 'page' => $page, 'order' => $order, 'lang' => $this->lang]));
        $page = json_decode($page, true);
        if (isset($page['artist'])) {
            return ['artist' => $page['artist'][0], 'items' => $page['items']];
        }

        return [];
    }

    public function search($query, $page = 1, $order = 'top', $type = 'all')
    {
        $page = $this->curlRequest('https://getsongg.com/dapp/?'.http_build_query(['query' => urlencode($query), 'page' => $page, 'order' => $order, 'type' => $type, 'lang' => $this->lang]));
        $page = json_decode($page, true);
        $results = [];
        foreach ($page as $item) {
            if (in_array($item['type'], ['song', 'album', 'artist'])) {
                $results[] = $item;
            }
        }

        return $results;
    }

    private function curlRequest($url, $parameters = [])
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }
}
