<?php
// Namespace
namespace Command;

/**
 * Sends the arguments to the channel, like say from a user.
 * arguments[0] == Channel or User to say message to.
 * arguments[1] == Message text.
 *
 * @package IRCBot
 * @subpackage Command
 * @author Daniel Siepmann <coding.layne@me.com>
 */
class Imdb extends \Library\IRC\Command\Base {
    /**
     * The number of arguments the command needs.
     *
     * @var integer
     */
    protected $numberOfArguments = -1;

    /**
     * Sends the arguments to the channel, like say from a user.
     *
     * IRC-Syntax: PRIVMSG [#channel]or[user] : [message]
     */
    public function command( ) {
		if( empty( $this->arguments[0] ) )
    		return;
		
		
		$query_string = urlencode( implode( ' ', $this->arguments ) );
        $query_url = 'https://ajax.googleapis.com/ajax/services/search/web?v=1.0&q=site:imdb.com%20' . $query_string;
        
        $ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $query_url );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		$query_return = curl_exec( $ch );
		curl_close( $ch );
	       
        $decoded = json_decode( $query_return, true );
        
        if( isset( $decoded['responseData']['results'][0]['title'] ) &&
	        isset( $decoded['responseData']['results'][0]['url'] ) ) {
	        $title 	= $decoded['responseData']['results'][0]['title'];
	        $url 	= $decoded['responseData']['results'][0]['url'];
	        
	        $title = str_replace( '<b>' , '', $title );
	        $title = str_replace( '</b>' , '', $title );
	        $title = htmlspecialchars_decode( $title );
	       	$title = urldecode( $title );	       	
	       	$url   = urldecode( $url );
	       	
	       	$movie_id = substr( $url, 0, -1 ); // Remove the last '/'
	       	$movie_id = substr( $movie_id, strripos( $movie_id, '/' ) + 1 ); // $movie_id = everything after the new last '/'
	       	
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
	        
	        $this->say( "1,8IMDb $movie_title ($movie_year) $movie_rating - $movie_plot $url" );
	    }
    }
}
?>
