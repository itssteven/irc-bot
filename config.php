<?php
return array(
    'server'   => 'irc.freenode.org',
    'port'     => 6667,
    'name'     => 'bojs',
    'nick'     => 'bojs',
    'channels' => array(
        '#bojs',
    ),
    'max_reconnects' => 1,
    'log_file',
    'commands' => array(
		'Command\F'      => array(), // Random Fapper
        'Command\Y'      => array(), // YouTube
        'Command\Imdb'	 => array(), // IMDb Searcher
        'Command\W'		 => array(), // Wikipedia
        'Command\G'		 => array(), // Google Search
//        'Command\Gi'	 => array(), // Google Image Search
        'Command\Urb'	 => array(), // Urban Dictionary
//        'Command\Wa'	 => array(), // wolfram alpha
 //       'Command\B'		 => array(), // 4chan rand image
//        'Command\Define' => array(), // dictionary define
        
        // Shit that gets called every ping
    //    'Command\Twitter' => array(),
    ),
    'listeners' => array(
#        'Listener\YouTube' 		=> array(),
		'Listener\Fapper' 		=> array(),
        'Listener\Reddit' 		=> array(),
        'Listener\DailyMotion' 	=> array(),
        'Listener\Vimeo'		=> array(),
        'Listener\Imgur'		=> array(),
        'Listener\Instagram' 	=> array(),
        'Listener\Titles' 		=> array(),
        'Listener\Imdb' 		=> array(),
        'Listener\Gfycat' 		=> array(),
    ),
);
