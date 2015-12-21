<?php
// Namespace
namespace Listener;

/**
 *
 * @package IRCBot
 * @subpackage Listener
 * @author Matej Velikonja <matej@velikonja.si>
 */
 
 
/*
 * 
 * This is a template file.
 * 
 * You need to change:
 * 		1. The class name
 * 		2. The elements in the getKeywords() function
 * 
 */
 
class ClassName extends \Library\IRC\Listener\Base {

    /**
     * Main function to execute when listen occurs
     */
    public function execute($data) {    
        $args = $this->getArguments( $data );
        
        foreach( $args as $arg ) {
        	if( $listenee = strstr( $arg, "butt" ) ) {
				$this->say( "lol u said butt", $args[2] );
				break;
        	}
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
        return array( 'youtube.com' );
    }
}
