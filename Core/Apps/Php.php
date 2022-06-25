<?php

namespace Core\Apps;

use Core\Http;

class Php
{
	public static function getVersions()
	{
		/*
		$already_installed = [];
		foreach(scandir(__DIR__.'/../../../../PHP/') as $k => $v){
			if(in_array($v, ['.', '..', 'index.php', 'versions.json']))
				continue;
			$already_installed[] = $v;
		}
		*/




		$url = 'https://windows.php.net/downloads/releases/archives';
		$contents = Http::get($url);

		$urls = self::getIndexUrls($contents['body'], $url);

		$url = 'https://windows.php.net/downloads/releases';
		$contents = Http::get($url);

		$urls = array_merge($urls, self::getIndexUrls($contents['body'], $url));

		foreach ($urls as $key => $value) {
			if(!preg_match('/php-[0-9]+\.[0-9]+\.[0-9]+-(nts-)?Win32/', $value) || substr($value, -4) !== '.zip')
				unset($urls[$key]);
		}

		$output = [];
		foreach ($urls as $key => $value) {
			$version = strstr2($value, 'php-');
			$version = strstr2($version, '-', true);
			$x64 = strpos($value, 'x64') ? true : false;
			$ts = strpos($value, 'nts') ? false : true;

			$version_name = $version
				.'-'
				.'WIN32'
				.'-'
				.($x64 ? 'x64' : 'x86')
				
				.'-'
				.($ts ? 'TS' : 'NTS');

			$output[$version_name] = [
				'version' => $version,
				'os_api' => 'WIN32',
				'bits' => ($x64 ? 'x64' : 'x86'),
				'thread_safe' => ($ts ? true : false),
				'url' => $value,
				//'installed' => (in_array($version_name, $already_installed))
			];
		}

		ksort($output);

		return file_put_contents(__DIR__.'/../../Versions/PHP-versions.json', json_encode($output));
	}

	public static function getIndexUrls($src, $url)
	{
		$urls = [];
		do{
			$src = strstr2($src, 'HREF="');
			$u = $url.strstr2($src, '"', true);
			$urls[] = $u;
			$src = strstr2($src, '"');

		}
		while(strpos($src, 'HREF="'));
		return $urls;
	}
}