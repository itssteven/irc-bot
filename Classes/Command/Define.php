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
class Define extends \Library\IRC\Command\Base {
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
        $query_url = "http://www.dictionaryapi.com/api/v1/references/collegiate/xml/" . $query_string . "?key=" . KEY_DICK;
		
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $query_url );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		$query_return = curl_exec( $ch );
		curl_close( $ch );
		
		$DOM = new \DOMDocument;
		@$DOM->loadHTML( $query_return );
		
		$items = $DOM->getElementsByTagName( 'ew' );
		if( isset( $items ) ) {
			if( isset( $items->item(0)->nodeValue ) ) {
				$word = $items->item(0)->nodeValue;
				
				$items = $DOM->getElementsByTagName( 'dt' );
				if( isset( $items ) ) {
					if( isset( $items->item(0)->nodeValue ) ) {
						$this->say( '' . $word . ': ' . substr( trim( $items->item(0)->nodeValue ), 1 ) );
					}
				}
			}
		}
		
	}
}
?>
