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
class Urb extends \Library\IRC\Command\Base {
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
		$api = 'https://api.urbandictionary.com/v0/define?term=';
				
		$decoded = json_decode( file_get_contents( $api . $query_string ), true );
				
		if( ! isset( $decoded['list'][0] ) )
			return;
		
		$word 		= $decoded['list'][0]['word'];
		$definition = $decoded['list'][0]['definition'];
		$example 	= $decoded['list'][0]['example'];
		$permalink 	= $decoded['list'][0]['permalink'];
		
		$i;
		for( $i = 0; $i < strlen( $definition ); $i++ ) {
			if( substr( $definition, $i, 2 ) == "\r\n" )
				break;
		}
		$definition = substr( $definition, 0, $i );
		
		for( $i = 0; $i < strlen( $example ); $i++ ) {
			if( substr( $example, $i, 2 ) == "\r\n" )
				break;
		}
		$example = substr( $example, 0, $i );
		
		$this->say( "0,12Urban0,1Dictionary $word: $definition ($example) - $permalink" );
    }
}
?>
