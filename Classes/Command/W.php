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
class W extends \Library\IRC\Command\Base {
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
    	
    	$ret = google( "wikipedia $query_string" );
		if( $ret === FALSE ) {
			echo '('."wikipedia $query_string".') returned false...', PHP_EOL;
			return;
		}
	       	
		// Cut off the ugly part of the title
		$title = substr( $ret['title'], 0, stripos( $ret['title'], ' - Wikipedia,' ) );	
			
		$this->say( "1,0Wikipedia $title - {$ret['url']}" );
    }
}
?>












