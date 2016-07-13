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
class Y extends \Library\IRC\Command\Base {
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
    	
        $query_string = implode( ' ', $this->arguments );
		$ret = google( 'youtube.com ' . $query_string );
		
		if( $ret !== FALSE ) {
			echo $ret['url'], PHP_EOL;
			echo $ret['title'], PHP_EOL;
			$ret['title'] = substr( $ret['title'], 0, -1 * strlen( ' - YouTube' ) );
	        $this->say( "1,0You0,4Tube {$ret['title']} - {$ret['url']}" );
		}
    }
}
?>
