<?php
// Namespace
namespace Listener;

/**
 *
 * @package IRCBot
 * @subpackage Listener
 * @author Matej Velikonja <matej@velikonja.si>
 */
class YouTube extends \Library\IRC\Listener\Base {

    /**
     * Main function to execute when listen occurs
     */
    public function execute( $data ) {
        $args = $this->getArguments( $data );
        
        $title = null;
        
        // array_slice = Skip over host, channel, msgtype
        $message_string = implode( ' ', array_slice( $args, 3 ) );
        
		// find all urls in each 'word'			
		$urls = find_urls_in_string( $message_string );
		if( $urls === FALSE ) {
			echo 'didnt find any urls in string', PHP_EOL;
			return;
		}
			
		$youtube_video_id = null;
		foreach( $urls as $url ) {
			$url_parsed = parse_url( $url );
			if( $url_parsed !== FALSE 			&&
				isset( $url_parsed['host'] ) 	) {
				if( strstr( $url_parsed['host'], 'youtube.com' ) ||
					strstr( $url_parsed['host'], 'youtu.be' ) ) {
					$youtube_video_id = $url;
					break;
				}
			}
		}
		
		if( $youtube_video_id === null ) {
			echo 'youtube_video_id was null', PHP_EOL;
			return;
		}
		
		echo $youtube_video_id, PHP_EOL;
		echo urlencode( $youtube_video_id ), PHP_EOL;
		
        $ret = google( $youtube_video_id );		
		if( $ret === FALSE ) {
			echo "google() error\n";
		}
		else {
			$ret['title'] = $title = substr( $title, 0, stripos( $title, ' - YouTube' ) );
	        $this->say( "1,0You0,4Tube {$ret['title']} - {$ret['url']}", $args[2]  );
	    }
    }

    private function getCommandsName() {
        $commands = $this->bot->getCommands();

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
        return array( 'youtu.be/', 'youtube.co' );
    }
}
