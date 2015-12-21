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
class Wa extends \Library\IRC\Command\Base {
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
        $query_url = "http://api.wolframalpha.com/v2/query?appid=" . KEY_WOLFRAM . "&input=" . $query_string;
		
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $query_url );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		$query_return = curl_exec( $ch );
		curl_close( $ch );
		
		
		$DOM = new \DOMDocument;
		@$DOM->loadHTML( $query_return ); 

		$items = $DOM->getElementsByTagName( 'pod' );
		for( $i = 0; $i < $items->length; $i++ ) {
			if( $items->item($i)->getAttribute( 'primary' ) ) {
				$utf8string = html_entity_decode(preg_replace("/U\+([0-9A-F]{4})/", "&#x\\1;", trim( $items->item($i)->nodeValue ) ), ENT_NOQUOTES, 'UTF-8');
				$this->say( $utf8string );
				return;
			}
		}	
    }
}
?>
