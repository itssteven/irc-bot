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
 
class Imdb extends \Library\IRC\Listener\Base {

    /**
     * Main function to execute when listen occurs
     */
    public function execute($data) {    
        $args = $this->getArguments( $data );
        
        foreach( $args as $arg ) {
        	if( $listenee = strstr( $arg, "imdb.com/title/" ) ) {
				
				// Google search the url
				$query_url = 'https://ajax.googleapis.com/ajax/services/search/web?v=1.0&q=' . $arg;
        
				$ch = curl_init();
				curl_setopt( $ch, CURLOPT_URL, $query_url );
				curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
				$query_return = curl_exec( $ch );
				curl_close( $ch );
				$decoded = json_decode( $query_return, true );
				
				if( isset( $decoded['responseData']['results'][0]['title'] ) &&
					isset( $decoded['responseData']['results'][0]['url'] ) ) {
					$url 	= $decoded['responseData']['results'][0]['url'];
					$url   = urldecode( $url );
					
					// Get movie id from url
					$parsed_url = parse_url( $url );
					$movie_id = substr( $parsed_url['path'], strlen( '/title/' ) );
					$movie_id = substr( $movie_id, 0, stripos( $movie_id, '/' ) );
					
					// Get movie info via omdb's API
					$query = 'http://www.omdbapi.com/?i=' . $movie_id;	       	
					$ch = curl_init();
					curl_setopt( $ch, CURLOPT_URL, $query );
					curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
					$query_ret = curl_exec( $ch );
					curl_close( $ch );
					
					$decoded = json_decode( $query_ret, true );
					
					if( isset( $decoded['Error'] ) ) 
						return;
					
					$movie_title 	= $decoded['Title'];
					$movie_year 	= $decoded['Year'];
					$movie_plot 	= $decoded['Plot'];
					$movie_rating 	= $decoded['imdbRating'];
					
					$this->say( "1,8IMDb $movie_title ($movie_year) $movie_rating - $movie_plot $url", $args[2] );
					break;
				}
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
        return array( 'imdb.com/title/' );
    }
}
