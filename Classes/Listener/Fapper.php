<?php
// Namespace
namespace Listener;

/**
 *
 * @package IRCBot
 * @subpackage Listener
 * @author Matej Velikonja <matej@velikonja.si>
 */
class Fapper extends \Library\IRC\Listener\Base {

    /**
     * Main function to execute when listen occurs
     */
    public function execute($data) {
        $args = $this->getArguments( $data );
        
        $json = file_get_contents( 'http://www.pornmd.com/getliveterms?orientation=s' );
		$json = json_decode( $json, true );
		$random_fap = rand() % count( $json );
		$title = $json[$random_fap]['keyword'];
		
				
		$this->say( "$title", $args[2] );
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
        return array( 'ffs', 'zeusey', 'stumo' );
    }
}
