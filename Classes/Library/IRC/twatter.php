<?php

require_once('TwitterAPIExchange.php');

$settings = array(
    'oauth_access_token' 		=> "2336824717-oGaswBJ2JJSMKBp3WBclhhB2cRW4wrXUPWGSDGm",
    'oauth_access_token_secret' => "Vn0jXmuCwPGeoY6cIcD1WI8uQIXsrFmNLwh6IUqjMHGH1",
    'consumer_key' 				=> "phxCxLTxlXADCTI9gLNRHg",
    'consumer_secret' 			=> "zauQyRQSHKaRf7ggkPXM3i98RDBIOelJL6vm3sac"
);

$twitter = new TwitterAPIExchange( $settings );

$url = 'https://api.twitter.com/1.1/statuses/home_timeline.json';
$getfield = '?include_rts=0';
$requestMethod = 'GET';

$id = null;


if( ! isset( $id ) ) {
	// Get starting id
	$reply = $twitter->setGetfield( $getfield . '&count=1' )->buildOauth( $url, $requestMethod )->performRequest();
	$decoded = json_decode( $reply, true );
	if( ! isset( $decoded[0]['id_str'] ) ) {
		echo "Error\n";
		return;
	}
	$id = $decoded[0]['id_str'];
}

$reply = $twitter->setGetfield( $getfield . '&count=1&since_id=' . $id )->buildOauth( $url, $requestMethod )->performRequest();
$decoded = json_decode( $reply, true );

if( isset( $decoded[0] ) ) {
	$id = $decoded[0]['id_str'];

	foreach( $decoded as $tweet ) { // there's only 1
		 $this->say( "{$tweet['user']['name']}: {$tweet['text']}" );
	}
}

?>
