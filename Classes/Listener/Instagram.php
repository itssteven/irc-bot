<?php
// Namespace
namespace Listener;

/**
 *
 * @package IRCBot
 * @subpackage Listener
 * @author Matej Velikonja <matej@velikonja.si>
 */
class Instagram extends \Library\IRC\Listener\Base {

    /**
     * Main function to execute when listen occurs
     */
    public function execute($data) {
        $args = $this->getArguments( $data );
        //https://api.instagram.com/oembed?url=http://instagr.am/p/
        
        $title = '';
        foreach( $args as $arg ) {
        	$bfound = false;
        
        	if( ($url = strstr( $arg, 'instagram.com/p/' )) ) {
        		$bfound = true;
        		$id = substr( $url, strlen( 'instagram.com/p/' ) );
        	}
        	else
        	if( ($url = strstr( $arg, 	'instagr.am/' )) ) {
        		$bfound = true;
        		$id = substr( $url, strlen( 'instagr.am/p/' ) );
        	}
        	
        	if( $bfound ) {
        		$i;
        		for( $i = 0; $i < strlen( $id ) ; $i++ ) {
        			if( ! ctype_alnum( $id[$i] ) &&
        				$id[$i] !== '-' &&
        				$id[$i] !== '_' ) 
        				break;
        		}
        		$id = substr( $id, 0, $i );
        		
        		$query = 'https://api.instagram.com/oembed?url=http://instagr.am/p/' . $id;
        	
		    	$query_return = file_get_contents( $query );
				$decoded = json_decode( $query_return, true );
				
				if( isset( $decoded['author_name'] ) &&
					isset( $decoded['title'] ) ) {
					$title = "{$decoded['author_name']}: {$decoded['title']}";
					break; // dont look for any more instagram links, we gots 1			
				}
        	}
        }
    	if( ! empty( $title ) )
			$this->say( "0,11Instagram $title", $args[2] );
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
        return array( 'instagram.com/p/', 'instagr.am/p/' );
    }
}
