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
class Tpb extends \Library\IRC\Command\Base {
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
        $query_url = 'http://thepiratebay.se/' . $query_string . '/0/7/0';
        
        $ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $query_url );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		$query_return = curl_exec( $ch );
		curl_close( $ch );
		
		
		$title = null;
		$magnet = null;
			
		$DOM = new DOMDocument;
		@$DOM->loadHTML( $html );
		$items = $DOM->getElementsByTagName( 'a' );
		for( $i = 0; $i < $items->length; $i++ ) {
			if( ! strcmp( $items->item($i)->getAttribute( 'class' ), 'detLink' ) ) {
				echo trim( $items->item($i)->nodeValue ), PHP_EOL;
				break;
			}
		}
		$items = $DOM->getElementsByTagName( 'a' );
		for( $i = 0; $i < $items->length; $i++ ) {
			if( ! strcmp( $items->item($i)->getAttribute( 'title' ), 'Download this torrent using magnet' ) ) {
				$magnet = trim( $items->item($i)->getAttribute( 'href' ) );
				break;
			}
		}		

		if( empty( $title ) || empty( $magnet ) ) {
			echo 'title ('.$title.') or magnet('.$magnet.') were emptty.',PHP_EOL;
			return;
		}

		$curl = curl_init();
		// Set some options - we are passing in a useragent too here
		curl_setopt_array( $curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => 'http://bojs.tk/m?magnet_link=' . urlencode( $magnet ),
		));
		$resp = curl_exec($curl);
		curl_close($curl);

		$result = trim( $resp );
		if( ! empty( trim($result) ) )
			$this->say( $title . ': ' . $result );
	}
}
?>
