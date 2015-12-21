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
class B extends \Library\IRC\Command\Base {
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
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => 'https://a.4cdn.org/b/threads.json',
		));
		$result = curl_exec($curl);
		curl_close($curl);
		$threads_array = json_decode( $result, true );

start:
		// Choose thread
		$thread = $threads_array[(rand() % 10)]['threads'][(rand() % 14)]['no'];
		
		
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => 'http://a.4cdn.org/b/thread/' . $thread . '.json',
		));
		$result = curl_exec($curl);
		curl_close($curl);
		$thread_array = json_decode( $result, true );

		// Create a new array with only posts which include images
		$count = array();
		foreach( $thread_array['posts'] as $post )
			if( isset( $post['ext'] ) )
				array_push( $count, $post );
				
		if( count( $count ) < 1 )
			goto start;

		$rand = rand() % count( $count );
		$this->say( '/b/ http://i.4cdn.org/b/' . $count[$rand]['tim'] . $count[$rand]['ext'] );
    }
}
?>
