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
		$ret = google( "site:imdb.com $query_string" );
		
		if( $ret === FALSE ) {
			echo '('."imdb $query_string".') returned false...', PHP_EOL;
			return;
		}
		
		$url = $ret['url'];
	       	
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
?>
