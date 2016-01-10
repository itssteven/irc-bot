<?php
    /**
     * IRC Bot
     *
     * LICENSE: This source file is subject to Creative Commons Attribution
     * 3.0 License that is available through the world-wide-web at the following URI:
     * http://creativecommons.org/licenses/by/3.0/.  Basically you are free to adapt
     * and use this script commercially/non-commercially. My only requirement is that
     * you keep this header as an attribution to my work. Enjoy!
     *
     * @license http://creativecommons.org/licenses/by/3.0/
     *
     * @package IRCBot
     * @author Super3 <admin@wildphp.org>
     * @author Matej Velikonja <matej@velikonja.si>
     */
	
	

    define('ROOT_DIR', __DIR__);

    // Configure PHP
    //ini_set( 'display_errors', 'on' );

    // Make autoload working
    require 'Classes/Autoloader.php';

    if (file_exists(ROOT_DIR . '/config.local.php')) {
        $config = include_once(ROOT_DIR . '/config.local.php');
    } else {
        $config = include_once(ROOT_DIR . '/config.php');
    }

    spl_autoload_register( 'Autoloader::load' );

    // Create the bot.
    $bot = new Library\IRC\Bot();

    // Configure the bot.
    $bot->setServer( $config['server'] );
    $bot->setPort( $config['port'] );
    $bot->setChannel( $config['channels'] );
    $bot->setName( $config['name'] );
    $bot->setNick( $config['nick']);
    $bot->setMaxReconnects( $config['max_reconnects'] );
    $bot->setLogFile( $config['log_file'] );

    // Add commands to the bot.
    foreach ($config['commands'] as $commandName => $args) {
        $reflector = new ReflectionClass($commandName);

        $command = $reflector->newInstanceArgs($args);

        $bot->addCommand($command);
    }

    foreach ($config['listeners'] as $listenerName => $args) {
        $reflector = new ReflectionClass($listenerName);

        $listener = $reflector->newInstanceArgs($args);

        $bot->addListener($listener);
    }


    if (function_exists('setproctitle')) {
        $title = basename(__FILE__, '.php') . ' - ' . $config['nick'];
        setproctitle($title);
    }
    
    function utf8_urldecode( $str ) {
		$str = preg_replace("/%u([0-9a-f]{3,4})/i","&#x\\1;", urldecode( $str ) );
		return html_entity_decode( $str, null, 'UTF-8' );
	}
	
	function find_urls_in_string( $string ) {
		if( $string === null )
			return FALSE;
			
		$ret = preg_match_all( '#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', $string, $urls );
		
		if( $ret === FALSE ||
			$ret === 0 	   ) {
			return FALSE;
		}
		return $urls[0];
	}
	
	/*
	function google( $query ) {
		$query_string = urlencode( $query );
        $query_url = 'https://ajax.googleapis.com/ajax/services/search/web?v=1.0&q=' . $query_string;
        $query_return = file_get_contents( $query_url );        
        $decoded = json_decode( $query_return, true );
        
        if( ! isset( $decoded['responseData']['results'][0]['title'] ) ||
	        ! isset( $decoded['responseData']['results'][0]['url'] ) ) {
				
			echo "No results\n";
			return FALSE;
		}
		
		$title 	= $decoded['responseData']['results'][0]['title'];
		$url 	= $decoded['responseData']['results'][0]['url'];
		
		// Requires both for some reason
		$url = urldecode( $url );
		$url = utf8_urldecode( $url );
		// Fixes spaces
		$url = str_replace( ' ', '%20', $url );
		// decode title
		$title = str_replace( '<b>' , '', $title );
		$title = str_replace( '</b>' , '', $title );
		$title = htmlspecialchars_decode( $title );
		$title = html_entity_decode( $title, ENT_QUOTES );
		
		return array( 'title' => $title, 'url' => $url );
	}
	*/
	
	function google( $query ) {
		$curl = curl_init();
		curl_setopt_array( 
			$curl, 
			array(
				CURLOPT_TIMEOUT => 5,
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_SSL_VERIFYPEER => false,
				CURLOPT_USERAGENT => "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1",
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_URL => 'https://search.disconnect.me/searchTerms/search?query=' . urlencode( $query )
			)
		);
		echo "https://search.disconnect.me/searchTerms/search?query=" . urlencode( $query ), PHP_EOL;
		$resp = curl_exec($curl);
		$errno = curl_errno( $curl );
		curl_close($curl);
		
		if( $errno !== 0 ) {
			return FALSE;
		}

		$dom = new DOMDocument();
		@$dom->loadHTML( $resp );
		$results = $dom->getElementById('normal-results');
		$title = '';
		$url = '';
		if( isset( $results ) ) {
			foreach( $results->getElementsByTagName('li') as $li ) {
				foreach( $li->getElementsByTagName('a') as $a ) {
					$title = $a->nodeValue;
					$url = $a->getAttribute('href');
					
					if( ! strstr( $url, "/ads/" ) ) {
						break;
					}
				}
				break;
			}
		}
		if( empty( $title ) || empty( $url ) ) return FALSE;
		
		// Make that shit decent for irc text
		$title = str_replace( '<b>', '', $title );
		$title = str_replace( '</b>', '', $title );	        
		$title = htmlspecialchars_decode( $title );
		$title = html_entity_decode( $title, ENT_QUOTES );
		
		$url = urldecode( $url );
		$url = utf8_urldecode( $url );
		
		$url = str_replace( ' ', '%20', $url );	
		return array( 'title' => $title, 'url' => $url );
	}
	
	

    // Connect to the server.
    $bot->connectToServer();

    // Nothing more possible, the bot runs until script ends.
