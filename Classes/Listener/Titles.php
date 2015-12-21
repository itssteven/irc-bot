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
 
class Titles extends \Library\IRC\Listener\Base {

    /**
     * Main function to execute when listen occurs
     */
    public function execute( $data ) {    
        $args = $this->getArguments( $data );
        
        // Check we're in a channel
        if( $args[2][0] !== '#' )
			return;        
        
		$args_string = implode( ' ', array_slice( $args, 3 ) );        
//		preg_match_all( '#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', $args_string, $match );
        
        $urls = find_urls_in_string( $args_string );
        if( $urls === FALSE )
			return;
        
        $titles = '';
		
		foreach( $urls as $url ) {
			$title = '';			
			
			if( strstr( $url, 'reddit.com' 		) ||
//				strstr( $url, 'youtu.be' 		) ||
//				strstr( $url, 'youtube.com' 	) ||
				strstr( $url, 'imgur.com' 		) ||
				strstr( $url, 'imdb.com' 		) ||
				strstr( $url, 'instagr.am' 		) ||
				strstr( $url, 'instagram.com'	) ||
				strstr( $url, 'dailymotion.com' ) ||
				strstr( $url, 'vimeo.com' 		) ||
				strstr( $url, 'instagram.com' 	) ||
				strstr( $url, 'gfycat.com' 		) )
				break; // let the other listeners handle it, we dont want multiple outputs per input
			
			// Download linked webpage
			$html = file_get_contents( $url );
			if( FALSE === $html ) {
				echo "\tWarning: file_get_contents fail ($url)\n";
				return;
			}
			
			$start = substr( strstr( $html, '<title>' ), strlen( '<title>' ) );
			$title = substr( $start, 0, stripos( $start, '</title>' ) );
							
			if( !empty( $title ) ) {
				if( strlen( $titles ) )
					$titles .= ', ';
				$title = trim( html_entity_decode( $title, ENT_QUOTES ) );
				$titles .= "$title";
			}
		}

		echo "Outputting title: $titles\n";
		if( ! empty( $titles ) ) {
			$this->say( $titles, $args[2] );
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
