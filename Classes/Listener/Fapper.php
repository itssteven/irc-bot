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
        
        
        if( rand() % 2 == 0 )
			$url = 'http://www.pornmd.com/getliveterms?orientation=s&country=us';
		}
		else {
			$url = 'http://www.pornmd.com/getliveterms?orientation=g&country=us'
		}
        
        $user = getUserNickName($data) 
        if( $user === 'jetson' ) {
			$url = 'http://www.pornmd.com/getliveterms?orientation=t&country=us'
		}
        
        $json = file_get_contents( $url );        
        file_put_contents( 'fapper.html', $json );
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
        return array( 'stumo', '!' );
    }
}
