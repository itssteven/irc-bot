<?php
// Namespace
namespace Listener;

/**
 *
 * @package IRCBot
 * @subpackage Listener
 * @author Matej Velikonja <matej@velikonja.si>
 */
class DailyMotion extends \Library\IRC\Listener\Base {

    /**
     * Main function to execute when listen occurs
     */
    public function execute($data) {    
        $args = $this->getArguments( $data );
        
        foreach( $args as $arg ) {
        	if( $url = strstr( $arg, "dailymotion.com/video/" ) ) {        		
        		$wat = explode( '_', $url );        		
        		$query = 'https://api.' . $wat[0] . '?fields=title';
        	}
        }
        
        $query_return = file_get_contents( $query );
		$decoded = json_decode( $query_return, true );
		
		if( isset( $decoded['title'] ) ) {
			$title = $decoded['title'];
			$this->say( "0,12DailyMotion $title", $args[2] );
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
        return array( 'dailymotion.com/video/' );
    }
}
