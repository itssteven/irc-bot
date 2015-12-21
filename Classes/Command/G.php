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
class G extends \Library\IRC\Command\Base {
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
    	
		$ret = google( implode( ' ', $this->arguments ) );
		
		if( $ret === FALSE ) {
			echo "google() error\n";
		}
		else {		
	        $this->say( "2,0G4,0o8,0o2,0g9,0l4,0e {$ret['title']} - {$ret['url']}" );
	    }
    }
}
?>
