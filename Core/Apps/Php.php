<?php

namespace Core\Apps;

use Core\Http;

class Php
{
	public static function getVersions()
	{
		$url = 'https://windows.php.net/downloads/releases/archives';
		$contents = Http::get($url);

		$urls = self::getIndexUrls($contents['body'], 'https://windows.php.net');

		$url = 'https://windows.php.net/downloads/releases';
		$contents = Http::get($url);

		$urls = array_merge($urls, self::getIndexUrls($contents['body'], 'https://windows.php.net'));

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

		if(!file_exists(__DIR__.'/../../Happ-App-Versions/'))
			mkdir(__DIR__.'/../../Happ-App-Versions/');

		if(!file_exists(__DIR__.'/../../Happ-App-Versions/PHP/'))
			mkdir(__DIR__.'/../../Happ-App-Versions/PHP/');

		return file_put_contents(__DIR__.'/../../Happ-App-Versions/PHP/PHP-versions.json', json_encode($output));
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

	/**
	 * Download all php versions and extract the php.ini files in one folder
	 */
	public static function getPHPINIFiles()
	{
		if(!file_exists(__DIR__.'/../../Content/'))
			mkdir(__DIR__.'/../../Content/');

		if(!file_exists(__DIR__.'/../../Content/phpini/'))
			mkdir(__DIR__.'/../../Content/phpini/');

		$files = scandirc(__DIR__.'/../../Content/phpini/');
		$versions = json_decode(file_get_contents(__DIR__.'/../../Happ-App-Versions/PHP/PHP-versions.json'), true);
		$keys = array_keys($versions);
		for($i = count($versions)-1; $i >=0; $i-- ){
			
			if(!file_exists(__DIR__.'/../../tmp/'))
				mkdir(__DIR__.'/../../tmp/');

			echo $keys[$i]."\r\n";

			if(
				in_array($keys[$i].'-development.ini', $files)
				&& in_array($keys[$i].'-production.ini', $files)
			){
				continue;
			}

			Http::download($versions[$keys[$i]]['url'], __DIR__.'/../../tmp/tmp.zip');
			self::unzip(__DIR__.'/../../tmp/tmp.zip', __DIR__.'/../../tmp/');

			
			if(
				file_exists(__DIR__.'/../../tmp/php.ini-development')
				&& file_exists(__DIR__.'/../../tmp/php.ini-production')
			){
				$dev = __DIR__.'/../../tmp/php.ini-development';
				$prod = __DIR__.'/../../tmp/php.ini-production';
				
			} else if(
				file_exists(__DIR__.'/../../tmp/php.ini-recommended')
				&& file_exists(__DIR__.'/../../tmp/php.ini-dist')
			){
				$dev = __DIR__.'/../../tmp/php.ini-recommended';
				$prod = __DIR__.'/../../tmp/php.ini-dist';
			} else {
				continue;
			}
				
			if(file_exists($dev)){
				rename($dev, __DIR__.'/../../Content/phpini/'.$keys[$i].'-development.ini');
			} else {
				echo 'php.ini dev Not found.';
				exit;
			}
			if(file_exists($prod)){
				rename($prod, __DIR__.'/../../Content/phpini/'.$keys[$i].'-production.ini');
				self::deleteFolder(__DIR__.'/../../tmp/');
			} else {
				echo 'php.ini prod Not found.';
				exit;
			}	
			
		}
	}

	public static function createPHPINIFile()
	{
		$dirs = scandirc(__DIR__.'/../../Content/phpini/');
		$sorted_dir = [];
		foreach($dirs as $dir){
			$version = strstr2($dir, '-', true);
			$expl = explode('.', $version);
			$str = '';
			foreach($expl as $e){
				$str .= self::prependChars($e, '0', 4);
			}
			$sorted_dir[(string)$str] = $dir;
		}
		// Sort $sorted_dir by key
		ksort($sorted_dir);
		
		$latest_version = false;
		$ini_data = [];
		$compact_file = [];
		foreach($sorted_dir as $dir){
			$file = file_get_contents(__DIR__.'/../../Content/phpini/'.$dir);
			$lines = explode("\n", $file);

			// Strip lines
			foreach($lines as $i => $line){
				if(empty(trim($line)))
					unset($lines[$i]);

				if(trim($line) === ';')
					unset($lines[$i]);

				if(substr($line, 0, 1) == '[')
					unset($lines[$i]);

				if(substr($line, 0, 2) == '; ')
					unset($lines[$i]);

				if(substr($line, 0, 2) == ';;')
					unset($lines[$i]);
			}

			$full_version = substr($dir, 0, strrpos($dir, '-'));
			$version = strstr2($dir, '-', true);
			$latest_version = $version;
			$current_option_list = [];
			$local_option_list = [];
			foreach($lines as $i => $line){
				$commented = false;
				if(substr($line, 0, 1) === ';')
					$commented = true;

				$data[0] = strstr($line, '=', true);
				$data[1] = substr($line, strpos($line, '=')+1);

				if(!isset($data[1])){
					continue;
				}




				$option = trim(str_replace(';', '', $data[0]));

				if(in_array($option, $local_option_list))
					continue;
				$local_option_list[] = $option;

				$current_option_list[] = $option;

				$ini_data[$option][$version][$full_version] = [
					'v' => trim($data[1]),
					'c' => $commented
				];
			}

			$local_option_list = [];
			foreach($lines as $line){

				

				$data[0] = strstr($line, '=', true);
				$data[1] = substr($line, strpos($line, '=')+1);

				if(!isset($data[1])){
					continue;
				}

				$option = trim(str_replace(';', '', $data[0]));

				if(in_array($option, $local_option_list))
					continue;
				$local_option_list[] = $option;

				$commented = false;
				if(substr($line, 0, 1) === ';')
					$commented = true;


				if(!isset($compact_file[$option])){
					$compact_file[$option] =[
						'versions' => [
							0 => [
								'from' => $version,
								'to' => false
							],
						],
						'default' => [
							0 => [
								'value' => trim($data[1]),
								'from' => $version,
								'to' => false,
								'end' => false
							],
						],
						'commented' => [
							0 => [
								'value' => $commented,
								'from' => $version,
								'to' => false,
								'end' => false
							],
						]
					];
				} else {
					$cnt = count($compact_file[$option]['versions']);
					// if(!isset($compact_file[$option]['versions'][$cnt-1]['to'])){
					// 	echo $cnt.'--';
					// 	print_r($compact_file[$option]['versions']);
					// 	exit;
					// }
					if($compact_file[$option]['versions'][$cnt-1]['to'] !== false){
						$compact_file[$option]['versions'][$cnt] = [
							'from' => $version,
							'to' => false
						];
					}

					$cnt = count($compact_file[$option]['default']);
					if(
						$compact_file[$option]['default'][$cnt-1]['value'] !== trim($data[1])
						|| $compact_file[$option]['default'][$cnt-1]['end'] === true
					){
						$compact_file[$option]['default'][$cnt] = [
							'from' => $version,
							'to' => false,
							'value' => trim($data[1]),
							'end' => false
						];
					} else {
						$compact_file[$option]['default'][$cnt-1]['to'] = $version;
					}
					$cnt = count($compact_file[$option]['commented']);

					if(
						$compact_file[$option]['commented'][$cnt-1]['value'] !== $commented
						|| $compact_file[$option]['commented'][$cnt-1]['end'] === true
					){
						$compact_file[$option]['commented'][$cnt] = [
							'from' => $version,
							'to' => false,
							'value' => $commented,
							'end' => false
						];
					} else {
						$compact_file[$option]['commented'][$cnt-1]['to'] = $version;
						
					}

				}

				// loop over already set lines
				foreach($compact_file as $opt => $v){
					$cnt = count($compact_file[$opt]['versions']);
					if(
						!in_array($opt, $current_option_list)
						&& $compact_file[$opt]['versions'][$cnt-1]['to'] === false
					){						
						$compact_file[$opt]['versions'][$cnt-1]['to'] = $version;
						$compact_file[$opt]['default'][$cnt-1]['end'] = true;
						$compact_file[$opt]['commented'][$cnt-1]['end'] = true;
					}
				}
			}
			echo '.';
		}

		foreach($compact_file as $k => $v){
			foreach($v as $kk => $vv){
				foreach($vv as $kkk => $vvv){
					if(isset($vvv['end']))
						unset($compact_file[$k][$kk][$kkk]['end']);
					if($vvv['to'] === false || $vvv['to'] == $latest_version)
						unset($compact_file[$k][$kk][$kkk]['to']);
					if(isset($vvv['value']) && $vvv['value'] === false || empty($vvv['value']))
						unset($compact_file[$k][$kk][$kkk]['value']);
				}
			}
		}

		if(!file_exists(__DIR__.'/../../Happ-App-Versions/'))
			mkdir(__DIR__.'/../../Happ-App-Versions/');

		if(!file_exists(__DIR__.'/../../Happ-App-Versions/PHP/'))
			mkdir(__DIR__.'/../../Happ-App-Versions/PHP/');

		file_put_contents(__DIR__.'/../../Happ-App-Versions/PHP/php.ini-compact.json', json_encode($compact_file));
		file_put_contents(__DIR__.'/../../Happ-App-Versions/PHP/php.ini.json', json_encode($ini_data));

		echo 'DONE!';

	}

	public static function prependChars($str, $chars, $totalLength)
	{
		$str = (string)$str;
		$chars = (string)$chars;
		$totalLength = (int)$totalLength;
		$strLength = strlen($str);
		$charsLength = strlen($chars);
		$diff = $totalLength - $strLength;
		$diff = $diff < 0 ? 0 : $diff;
		$prepend = '';
		for($i = 0; $i < $diff; $i++){
			$prepend .= $chars[$i % $charsLength];
		}
		return $prepend.$str;
	}


	/**
	 * Delete all files in folder recursively
	 */
	public static function deleteFolder($dir)
	{
		if(!file_exists($dir))
			return;
		$files = scandir($dir);
		foreach($files as $file){
			if($file == '.' || $file == '..')
				continue;
			if(is_dir($dir.'/'.$file))
				self::deleteFolder($dir.'/'.$file);
			else
				unlink($dir.'/'.$file);
		}
		rmdir($dir);
	}

	/**
	 * delete all contents in folder
	 */
	/*
	public static function deleteFolder($path)
	{
		$files = glob($path . '/*');
		foreach($files as $file){
			if(is_file($file))
				unlink($file);
			else
				self::deleteFolder($file);
		}
		rmdir($path);
	}
	*/

	/**
	 * unzip file
	 */
	public static function unzip($file, $destination)
	{
		$zip = new \ZipArchive;
		$res = $zip->open($file);
		if ($res === TRUE) {
			$zip->extractTo($destination);
			$zip->close();
			return true;
		} else {
			return false;
		}
	}


}