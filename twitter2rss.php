<?php
	$title = "Twitter PHP News";
	$description = "A RSS feed for PHP News on Twitter";
	$link = "http://www.php.com/";
	$language = "us";
	$copyright = "";


	$APIKey = "Your API KEY";
	$APISecret = "Your API Secret";


	$search = "#PHP"; //the search the script will perform.
	$count = 20; // the  number of items to return.


//Request Access Token;

	$url = 'https://api.twitter.com/oauth2/token';
	$data = "grant_type=client_credentials"; 
	$auth = base64_encode($APIKey.":".$APISecret);
	
	$options = array(
		'http' => array(
			'header'  => "Content-type: application/x-www-form-urlencoded;charset=UTF-8\r\n"."Authorization: Basic ".$auth,
			'method'  => 'POST',
			'content' => $data
		)
	);
	$context  = stream_context_create($options);
	$jsonStr = file_get_contents($url, false, $context);	
	$result = json_decode($jsonStr, true); 
	
	
//Perform Search

	$url = "https://api.twitter.com/1.1/search/tweets.json?q=".urlencode($search)."&count=".$count;
	
	
	$auth=$result["access_token"];

	$options = array(
		'http' => array(
			'header'  => "Authorization: Bearer ".$auth,
			'method'  => 'GET',
			'ignore_errors' => true
		)
	);
	
	$context  = stream_context_create($options);
	$jsonStr = ""; file_get_contents($url, false, $context);
	
	try {
		$jsonStr = file_get_contents($url, false, $context);
	}catch (Exception $e) {
		echo $e->getMessage();
	}

	$result = json_decode($jsonStr, true);	

//build RSS
	
	$userPattern = '/(@([A-Za-z0-9_]{1,15}))/';
	$hashTagPattern = '/(#([A-Za-z0-9_]{1,140}))/';
	$urlPattern = '/(http(s|):\\/\\/[^\\s\\\\]*)/';

    header("Content-Type: application/rss+xml; charset=ISO-8859-1");
 
    $rssfeed = '<?xml version="1.0" encoding="ISO-8859-1"?>';
    $rssfeed .= '<rss version="2.0">';
    $rssfeed .= '<channel>';
    $rssfeed .= '<title>'.$title.'</title>';
    $rssfeed .= '<link>'.$link.'</link>';
    $rssfeed .= '<description>'.$description.'</description>';
    $rssfeed .= '<language>'.$language.'</language>';
    $rssfeed .= '<copyright>'.$copyright.'</copyright>';


	
	for ($i = 0; $i < count($result["statuses"]); $i++){
		$status = $result["statuses"][$i];
		$text = $status["text"];
		$text = preg_replace($urlPattern, "<a href='$1'>$1</a>",$text);
		$text = preg_replace($userPattern, "<a href='https://twitter.com/$1'>$1</a>",$text);
		$text = preg_replace($hashTagPattern, "<a href='https://twitter.com/$1'>$1</a>",$text);
		//echo $text."\r\n";
		
		$rssTitle = $status["user"]["name"]." @".$status["user"]["screen_name"];		
		$rssLink = "https://twitter.com/statuses/".$status["id_str"];
			
		$rssDate =  date('r', strtotime($status["created_at"]));
		
        $rssfeed .= '<item>';
        $rssfeed .= '<title>' . $rssTitle . '</title>';
        $rssfeed .= '<description>' . $text . '</description>';
        $rssfeed .= '<link>' . $rssLink . '</link>';
        $rssfeed .= '<pubDate>' . $rssDate . '</pubDate>';
        $rssfeed .= '</item>';				
	}
	
	
    $rssfeed .= '</channel>';
    $rssfeed .= '</rss>';
 
    echo $rssfeed;
?>
