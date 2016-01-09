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
class P extends \Library\IRC\Command\Base {
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
    	
		$ret = google( 'site:xhamster.com/movies ' . implode( ' ', $this->arguments ) );
		
		if( $ret === FALSE ) {
			echo "google() error\n";
		}
		else {		
	        $this->say( "{$ret['title']} - {$ret['url']}" );
	    }
    }
}
?>
