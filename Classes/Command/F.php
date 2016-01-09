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
class F extends \Library\IRC\Command\Base {
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
    	
        $json = file_get_contents( 'http://www.pornmd.com/getliveterms?orientation=s' );
		$json = json_decode( $json, true );
		$random_fap = rand() % count( $json );
		$title = $json[$random_fap]['keyword'];
		$search = str_replace( ' ', '+', $title );
		$url = 'http://www.pornmd.com/straight/' . $search;
		
		$this->say( "0,1FAP FAP FAP $title: $url" );
    }
}
?>
