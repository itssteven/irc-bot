<?php
// Namespace
namespace Listener;

/**
 *
 * @package IRCBot
 * @subpackage Listener
 * @author Matej Velikonja <matej@velikonja.si>
 */
class Reddit extends \Library\IRC\Listener\Base {

    /**
     * Main function to execute when listen occurs
     */
    public function execute($data) {    
        $args = $this->getArguments( $data );
        
        $query = null;
        foreach( $args as $arg ) {
        	if( strstr( $arg, "reddit.com/" ) ) {
				$query = $query = $arg . '.json';
				break;
			}
		}
		
		if( $query !== null ) {
			$query_return = file_get_contents( $query );
			$decoded = json_decode( $query_return, true );
					
			if( isset( $decoded[0]['data']['children'][0]['data']['title'] ) )
			{
				$title = $decoded[0]['data']['children'][0]['data']['title'];
				$subreddit = $decoded[0]['data']['children'][0]['data']['subreddit'];
				
				$url = '';
				if( strncmp( $decoded[0]['data']['children'][0]['data']['domain'], 'self.', strlen( 'self.' ) ) )
					$url = ' - ' . $decoded[0]['data']['children'][0]['data']['url'];
				
			}
			if( ! empty( $title ) &&
				! empty( $subreddit ) ) {
				$this->say( "/r/$subreddit: $title$url", $args[2] );
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
        return array( 'reddit.com/' );
    }
}
