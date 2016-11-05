<?php
// Namespace
namespace Listener;

include_once "Keys.php";

/**
 *
 * @package IRCBot
 * @subpackage Listener
 * @author Matej Velikonja <matej@velikonja.si>
 */
class Imgur extends \Library\IRC\Listener\Base {

    /**
     * Main function to execute when listen occurs
     */
    public function execute($data) {
        $args = $this->getArguments( $data );
        
        $title = '';
        $webm = '';
        foreach( $args as $arg ) {
        	if( ($url = strstr( $arg, 'imgur.com/' )) ) {   
				$url = $arg;
				$url_parsed = parse_url( $url );
		
				$id = '';
				$image = '';
				$gallery = '';
				if( ! strncmp( $url_parsed['path'], '/gallery/', strlen( '/gallery/' ) ) ) {
					$gallery = '/gallery/album';
		
					$id = substr( $url_parsed['path'], strlen( '/gallery' ) );
				}
				else
				if( ! strncmp( $url_parsed['path'], '/a/', strlen( '/a/' ) ) ) {
					$gallery = '/album';
		
					$id = substr( $url_parsed['path'], strlen( '/a' ) );
				}
				else {
					$id = $url_parsed['path'];
					$end = strripos( $id, '.' );
					$id = substr( $id, 0, $end );
					$image = '/image';
				}
	
				if( empty( $id ) ) 
					return;
	
				$query = "https://api.imgur.com/3$image$gallery$id";
				echo 'query: ' . $query, PHP_EOL;

				$ch = curl_init( $query );
				curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
				curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'Authorization: Client-ID ' . KEY_IMGUR ) );
				$data = curl_exec( $ch );
				curl_close( $ch );

				$decoded = json_decode( $data, true );
				print_r( $decoded );
	
				if( $decoded['success'] === true ) {
					if( ($temptitle = $decoded['data']['title']) !== null ) {
						if( ! empty( $title )  )
							$title .= ', ';
						$title .= $temptitle;
					}
				}
				else echo $data, PHP_EOL;
				
				break; // only do 1 img per line
        	}
        }
        
        // Give the html5 link if we can 
        $gifv = '';
        if( strcmp( substr( $url, -5 ), '.gifv' ) &&
			strcmp( substr( $url, -5 ), '.mp4' ) &&
			strcmp( substr( $url, -5 ), '.webm' ) ) {
			if( isset( $decoded['data']['gifv'] ) ) {
				if( ! empty( $decoded['data']['gifv'] ) ) {
					$gifv = ' - ' .$decoded['data']['gifv'];
				}
			}
			else
			if( isset( $decoded['data']['webm'] ) ) {
				if( ! empty( $decoded['data']['webm'] ) ) {
					$gifv = ' - ' .$decoded['data']['webm'];
				}
			}
		}
        
        if( ! empty( $title ) )
        	$this->say( "9,1I0,1mgur $title$gifv", $args[2] );
        else {
			$api = 'http://www.reddit.com/submit.json?url=';

			$html = file_get_contents( $api . urlencode( $url ) );
			$json = array();
			$json = json_decode( $html, true );
			
			if( is_array( $json ) &&
				isset( $json['data']['children'][0]['data']['title'] ) ) {				
				$title = $json['data']['children'][0]['data']['title'];
				if( ! empty( $title ) )
					$this->say( "9,1R0,1eddit $title$gifv", $args[2] );
			}
			else
			if( ! empty( $gifv ) ) {
				// check it's not already gifv/webm
				$this->say( "9,1F0,1uck gifs $title$gifv", $args[2] );
			}
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
        return array( 'imgur.com/' );
    }
}
