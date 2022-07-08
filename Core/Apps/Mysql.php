<?php

namespace Core\Apps;

use Core\Http;

class Mysql
{
	private static function get($url)
	{
		echo 'Checking: '.$url;
		$src = Http::ping($url);
		if($src){
			$urls[$i.'.'.$ii.'.'.$iii.'-'.$win.'-'.strtoupper($vs)] = [
				'version' => $i.'.'.$ii.'.'.$iii,
				'bits' => $win,
				'red' => strtoupper($vs),
				'url' => $url,
			];
			echo ' FOUND'."\r\n";
		} else {
			echo ' empty'."\r\n";
		}
	}

	public static function getVersions()
	{

		set_time_limit(0);


		$versions = [
			'httpd-2.4.54-o111p-x86-vs17.zip',
			'httpd-2.4.53-o111o-x86-vs17.zip',
			'httpd-2.4.52-o111m-x86-vc15.zip',
			'httpd-2.4.51-o111l-x86-vc15.zip',
			'httpd-2.4.54-o111p-x64-vs17.zip',
			'httpd-2.4.53-o111o-x64-vs17.zip',
			'httpd-2.4.52-o111m-x64-vc15.zip',
			'httpd-2.4.51-o111l-x64-vc15.zip',
			'httpd-2.4.54-o111p-x86-vs16.zip',
			'httpd-2.4.53-o111o-x86-vs16.zip',
			'httpd-2.4.52-o111m-x86-vs16.zip',
			'httpd-2.4.51-o111l-x86-vs16.zip',	
			'httpd-2.4.54-o111p-x64-vs16.zip',
			'httpd-2.4.53-o111o-x64-vs16.zip',
			'httpd-2.4.52-o111m-x64-vs16.zip',
			'httpd-2.4.51-o111l-x64-vs16.zip',
			'httpd-2.4.54-lre353-x86-vs16.zip',
			'httpd-2.4.53-lre352-x86-vs16.zip',
			'httpd-2.4.52-lre342-x86-vs16.zip',
			'httpd-2.4.51-lre341-x86-vs16.zip',
			'httpd-2.4.54-lre353-x64-vs16.zip',
			'httpd-2.4.53-lre352-x64-vs16.zip',
			'httpd-2.4.52-lre342-x64-vs16.zip',
			'httpd-2.4.51-lre341-x64-vs16.zip',
		];

		$mods = [
			'mod_antiloris-0.6.0-2.4.x-x86-vs17.zip',
			'mod_antiloris-0.6.0-2.4.x-x64-vs17.zip',
			'mod_authn_ntlm-1.0.8-x86-vs17.zip',
			'mod_authn_ntlm-1.0.8-x64-vs17.zip',
			'mod_bw-0.92-2.4.x-x86-vs17.zip',
			'mod_bw-0.92-2.4.x-x64-vs17.zip',
			'mod_fcgid-2.3.9a-2.4.x-x86-vs17.zip',
			'mod_fcgid-2.3.9a-2.4.x-x64-vs17.zip',
			'mod_limitipconn-0.24-2.4.x-x64-vs17.zip',
			'mod_limitipconn-0.24-2.4.x-x86-vs17.zip',
			'mod_log_rotate-1.0.2-2.4.x-x86-vs17.zip',
			'mod_log_rotate-1.0.2-2.4.x-x64-vs17.zip',
			'mod_maxminddb-1.2.0.160-2.4.x-x86-vs17.zip',
			'mod_maxminddb-1.2.0.160-2.4.x-x64-vs17.zip',

			'mod_antiloris-0.6.0-2.4.x-x86-vs16.zip',
			'mod_antiloris-0.6.0-2.4.x-x64-vs16.zip',
			'mod_authn_ntlm-1.0.8-x86-vs16.zip',
			'mod_authn_ntlm-1.0.8-x64-vs16.zip',
			'mod_bw-0.92-2.4.x-x86-vs16.zip',
			'mod_bw-0.92-2.4.x-x64-vs16.zip',
			'mod_fcgid-2.3.9a-2.4.x-x86-vs16.zip',
			'mod_fcgid-2.3.9a-2.4.x-x64-vs16.zip',
			'mod_limitipconn-0.24-2.4.x-x64-vs16.zip',
			'mod_limitipconn-0.24-2.4.x-x86-vs16.zip',
			'mod_log_rotate-1.0.2-2.4.x-x86-vc16.zip',
			'mod_log_rotate-1.0.2-2.4.x-x64-vc16.zip',
			'mod_maxminddb-1.2.0.160-2.4.x-x86-vs16.zip',
			'mod_maxminddb-1.1.0.132-2-x86-vs16.zip',
			'mod_maxminddb-1.2.0.160-2.4.x-x64-vs16.zip',
			'mod_maxminddb-1.1.0.132-2-x64-vs16.zip',
			'mod_security2-2.9.5-2.4.x-x86-vc16.zip',
			'mod_security2-2.9.3-2.4.x-x86-vc16.zip',
			'mod_security2-2.9.5-2.4.x-x64-vc16.zip',
			'mod_security2-2.9.3-2.4.x-x64-vc16.zip',

			'mod_antiloris-0.6.0-2.4.x-vc15-x86.zip',
			'mod_antiloris-0.6.0-2.4.x-vc15-x64.zip',
			'mod_authn_ntlm-1.0.8-x86-vc15.zip',
			'mod_authn_ntlm-1.0.8-x64-vc15.zip',
			'mod_bw-0.92-2.4.x-x86-vc15.zip',
			'mod_bw-0.92-2.4.x-x64-vc15.zip',
			'mod_fcgid-2.3.9a-2.4.x-x86-vc15.zip',
			'mod_fcgid-2.3.9a-2.4.x-x64-vc15.zip',
			'mod_limitipconn-0.24-2.4.x-x86-vc15.zip',
			'mod_limitipconn-0.24-2.4.x-x64-vc15.zip',
			'mod_log_rotate-1.0-2.4.x-x86-vc15.zip',
			'mod_maxminddb-1.1.0.132-x86-vc15.zip',
			'mod_maxminddb-1.1.0.132-x64-vc15.zip',
			'mod_security2-2.9.5-2.4.x-x86-vc15.zip',
			'mod_security2-2.9.5-2.4.x-x64-vc15.zip',

			//VC15
			'mod_security2-2.9.3-2.4.x-x86.zip',
			'mod_security2-2.9.3-2.4.x-x64.zip'
		];


		if(!file_exists(__DIR__.'/../../Happ-App-Versions/Apache/'))
			mkdir(__DIR__.'/../../Happ-App-Versions/Apache/');
		foreach ($versions as $key => $value) {
			file_put_contents(__DIR__.'/../../Happ-App-Versions/Apache/'.$value, file_get_contents('https://de.apachehaus.com/downloads/'.$value));
		}

		if(!file_exists(__DIR__.'/../../Happ-App-Versions/Apache-Mods/'))
			mkdir(__DIR__.'/../../Happ-App-Versions/Apache-Mods/');
		foreach ($mods as $key => $value) {
			file_put_contents(__DIR__.'/../../Happ-App-Versions/Apache-Mods/'.$value, file_get_contents('https://de.apachehaus.com/downloads/'.$value));
		}


		die();

		$options = [
			[
				'x86',
				'x64'
			],
			[
				'',
				'-vc1',
				'-vc2',
				'-vc3',
				'-vc4',
				'-vc5',
				'-vc6',
				'-vc7',
				'-vc8',
				'-vc9',
				'-vc10',
				'-vc11',
				'-vc12',
				'-vc13',
				'-vc14',
				'-vc15',
				'-vc16',
				'-vc17',
				'-vc18',
				'-vc19',
				'-vc20',
				'-vs1',
				'-vs2',
				'-vs3',
				'-vs4',
				'-vs5',
				'-vs6',
				'-vs7',
				'-vs8',
				'-vs9',
				'-vs10',
				'-vs11',
				'-vs12',
				'-vs13',
				'-vs14',
				'-vs15',
				'-vs16',
				'-vs17',
				'-vs18',
				'-vs19',
				'-vs20',
			],
			[
				'',
				'-ssl',
				'-o111',
				'-lre',
				'-ssl-sni'
			]
		];

		$start = [
			2,
			2,
			0
		];

		$max = [
			2,
			4,
			56
		];

		$urls = [];

		$alph = ' abcdefghijklmnopqrstuvwxyz';

		for ($i=$start[0]; $i <= $max[0]; $i++) { 
			for ($ii=$start[1]; $ii <= $max[1]; $ii++) { 
				for ($iii=$start[2]; $iii <= $max[2]; $iii++) { 
					foreach ($options[0] as $win) {
						foreach ($options[1] as $vs) {
							foreach ($options[2] as $v) {
								if($v === '-o111'){
									for ($a=0; $a < strlen($alph); $a++) {
										if(substr($alph, $a, 1) === ' ')
											$ver = $v;
										else
											$ver = $v.substr($alph, $a, 1);

										echo $ver;

										self::get($url = 'https://de.apachehaus.com/downloads/httpd-'.$i.'.'.$ii.'.'.$iii.$ver.'-'.$win.''.$vs.'.zip');
									}
								} else if($v === '-lre'){
									for ($a=0; $a < 360; $a++) { 
										if($a === 0)
											$ver = $v;
										else
											$ver = $v.$a;
										self::get($url = 'https://de.apachehaus.com/downloads/httpd-'.$i.'.'.$ii.'.'.$iii.$ver.'-'.$win.''.$vs.'.zip');
									}
								} else {
									self::get($url = 'https://de.apachehaus.com/downloads/httpd-'.$i.'.'.$ii.'.'.$iii.$v.'-'.$win.''.$vs.'.zip');
								}

								
							}
						}
					}
				}
			}
		}


		return file_put_contents(__DIR__.'/../../Happ-App-Versions/Apache-versions.json', json_encode($urls));

		print_r($urls);
	}

}