<?php

namespace Core;


class Http
{
	/**
	 * @param $url string 		The url
	 * @param $cookies array 	Array of cookies 
	 * @param $custom_headers 	Array of headers
	 */		
	public static function get($url, $cookies = [], $custom_headers = false)
	{
		
		if(empty($url))
			die('Empty URL given in Http::get');

		$pos = strpos($url, '/', strpos($url, '://')+3);

        if($pos > 0){
            $path = substr($url, $pos);

            if(substr($path, -1) !== '/')
                $path = $path.'/';

            $p2 = strpos($url, '://')+3;
            $authority = substr($url, $p2, $pos-$p2);
        } else {
            $path = '/';
            $authority = substr($url, strpos($url, '://')+3);
        }

        $http = 'http';
        if(substr($url, 0, 5) === 'https')
            $http = 'https';
    

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_ENCODING, '');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        if($custom_headers === false){
            $user_agents = [
                'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/99.0.4844.51 Safari/537.36',
                'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.135 Safari/537.36 Edge/12.246',
                'Mozilla/5.0 (X11; CrOS x86_64 8172.45.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.64 Safari/537.36',
                'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_2) AppleWebKit/601.3.9 (KHTML, like Gecko) Version/9.0.2 Safari/601.3.9',
                'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.111 Safari/537.36',
                'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:15.0) Gecko/20100101 Firefox/15.0.1'
            ];

            $user_agent = $user_agents[rand(0, count($user_agents)-1)];

            $headers = [
                //':authority: '.$authority,
                //':method: GET',
                //':path: '.$path,
                //':scheme: '.$http,
                'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
                //'accept-encoding: gzip, deflate, br',
                'accept-language: nl-NL,nl;q=0.9',
                'sec-ch-ua: " Not A;Brand";v="99", "Chromium";v="99", "Google Chrome";v="99"',
                'sec-ch-ua-mobile: ?0',
                'sec-ch-ua-platform: "Windows"',
                'sec-fetch-dest: document',
                'sec-fetch-mode: navigate',
                'sec-fetch-site: none',
                'sec-fetch-user: ?1',
                'upgrade-insecure-requests: 1',
                'user-agent: '.$user_agent
            ];
        } else {
            $headers = $custom_headers;
        }
        if(!empty($cookies)){
            $t = 'cookie: ';
            foreach ($cookies as $key => $value) {
                $t.= $key.'='.$value.';';
            }
            $headers[] = substr($t, 0, -1);
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $output = curl_exec ($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close ($ch);
        return [
            'status' => (int)$httpcode,
            'body' => $output,
            'headers_sent' => $headers
        ];
	}

}