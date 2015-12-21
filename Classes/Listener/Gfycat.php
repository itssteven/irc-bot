<?php
// Namespace
namespace Listener;

/**
 *
 * @package IRCBot
 * @subpackage Listener
 * @author Matej Velikonja <matej@velikonja.si>
 */
 
 
/*
 * 
 * This is a template file.
 * 
 * You need to change:
 * 		1. The class name
 * 		2. The elements in the getKeywords() function
 * 
 */
 
class Gfycat extends \Library\IRC\Listener\Base {

    /**
     * Main function to execute when listen occurs
     */
    public function execute( $data ) {    
        $args = $this->getArguments( $data );
        
        // Check we're in a channel
        if( $args[2][0] !== '#' )
			return;        
        
		$args_string = implode( ' ', array_slice( $args, 3 ) );
		
		
        $urls = find_urls_in_string( $args_string );
        if( $urls === FALSE )
			return;
        
        $titles = '';
		
		$parsed_url = parse_url( $urls[0] );
		
		// if (imgur && not gifv/webm)...
		if( ! strstr( $parsed_url['host'], 'imgur.com' ) &&
			! strcmp( substr( $urls[0], -4 ), '.gif' ) ) {
			
			echo 'url passed imgur/gif test: ' . $url[0], PHP_EOL;
			
			$gfy_url = 'http://upload.gfycat.com/transcode?fetchUrl=';
			$url = $gfy_url . $urls[0];

			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_URL => $url,
			));
			$result = curl_exec($curl);

			curl_close($curl);

			$result = json_decode( $result, true );

			if( isset( $result['webmUrl'] ) )
				$this->say( '3,0Gfy1,0cat ' . $result['webmUrl'], $args[2] );
		}
		else {
			echo 'url[0]: ' . $urls[0], PHP_EOL;
			echo '$parsed_url[\'host\']: ' . $parsed_url['host'], PHP_EOL;
		}
    }

    private function getCommandsName( ) {
        $commands = $this->bot->getCommands( );

        $names = array();
        /* @var $command \Library\IRC\Command\Base */
        foreach ($commands as $name => $command) {
            $names[] = $this->bot->getCommandPrefix() . $name;
        }

        return implode(", ", $names);
    }

    private function getUserNickName($data) {
        $result = preg_match('/:([a-zA-Z0-9_]+)!/', $data, $matches);

        if ($result !== false) {
            return $matches[1];
        }

        return false;
    }

    /**
     * Returns keywords that listener is listening to.
     *
     * @return array
     */
    public function getKeywords() {
        return array( 'http' );
    }
}
