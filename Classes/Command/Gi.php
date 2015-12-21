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
class Gi extends \Library\IRC\Command\Base {
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
        if( empty($this->arguments[0] ) )
    		return;
    	
        $query_string = urlencode( implode( ' ', $this->arguments ) );
        $query_url = 'https://ajax.googleapis.com/ajax/services/search/images?v=1.0&q=' . $query_string;
        $query_return = file_get_contents( $query_url );        
        $decoded = json_decode( $query_return, true );
        
        if( isset( $decoded['responseData']['results'][0]['width'] ) &&
	        isset( $decoded['responseData']['results'][0]['height'] ) &&
	        isset( $decoded['responseData']['results'][0]['url'] ) ) {
	        $width 	= $decoded['responseData']['results'][0]['width'];
	        $height = $decoded['responseData']['results'][0]['height'];
	        $url 	= $decoded['responseData']['results'][0]['url'];
	        
	        $url = urldecode( $url );
	        	        
	        $this->say( "2,0G4,0o8,0o2,0g9,0l4,0e $url ({$width}x{$height})" );
	    }
    }
}
?>
