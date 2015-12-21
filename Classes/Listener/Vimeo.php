<?php
// Namespace
namespace Listener;

/**
 *
 * @package IRCBot
 * @subpackage Listener
 * @author Matej Velikonja <matej@velikonja.si>
 */
class Vimeo extends \Library\IRC\Listener\Base {

    /**
     * Main function to execute when listen occurs
     */
    public function execute($data) {
        $args = $this->getArguments( $data );
        
        foreach( $args as $arg ) {
        	if( $url = strstr( $arg, 'vimeo.com/' ) ) {
        		$id = substr( $url, strlen( 'vimeo.com/' ) );
        		
        		$i;
        		for( $i = 0; ctype_digit( $id[$i] ), $i < strlen( $id ) ; $i++ );
        		$id = substr( $id, 0, $i );
        		
        		$query = 'https://vimeo.com/api/v2/video/' . $id . '.json';
        	}
        }
        
        $query_return = file_get_contents( $query );
		$decoded = json_decode( $query_return, true );
			
		if( isset( $decoded[0]['title'] ) ) {
			$title = $decoded[0]['title'];
			$this->say( "0,10Vimeo $title", $args[2] );
		}
    }

    private function getCommandsName( ) {
        $commands = $this->bot->getCommands( );

        $names = array();
        /* @var $command \Library\IRC\Command\Base */
        foreach ($commands as $name => $command) {
            $names[] = $this->bot->getCommandPrefix() . $name;
        }

        return implode(", ", $names);
    }

    private function getUserNickName($data) {
        $result = preg_match('/:([a-zA-Z0-9_]+)!/', $data, $matches);

        if ($result !== false) {
            return $matches[1];
        }

        return false;
    }

    /**
     * Returns keywords that listener is listening to.
     *
     * @return array
     */
    public function getKeywords() {
        return array( 'vimeo.com/' );
    }
}
