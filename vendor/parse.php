<?php
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/connect.php';
?>
<?php
	function is_url($uri){
	    if(preg_match('/^(http|https):\\/\\/[a-z0-9_]+([\\-\\.]{1}[a-z_0-9]+)*\\.[_a-z]{2,5}'.'((:[0-9]{1,5})?\\/.*)?$/i', $uri)) {
	    	return $uri;
	    } else {
	        return false;
	    }
	}

	function get_title($url){
		$str = file_get_contents($url);
		if(strlen($str) > 0){
			$str = trim(preg_replace('/\s+/', ' ', $str)); // supports line breaks inside <title>
			preg_match("/\<title\>(.*)\<\/title\>/i", $str, $title); // ignore case
			return $title[1];
		}
	}

	function get_favicon($site) {
        $html = file_get_contents($site);
        $dom = new DOMDocument();
        @$dom->loadHTML($html);
        $links = $dom->getElementsByTagName('link');
        $fevicon = '';

        for($i = 0; $i < $links->length; $i ++ ) {
            $link = $links->item($i);
            if($link->getAttribute('rel') == 'icon' || $link->getAttribute('rel') == "Shortcut Icon" || $link->getAttribute('rel') == "shortcut icon" || $link->getAttribute('apple-touch-icon')) {
                $fevicon = $link->getAttribute('href');
            }
        }
        return  $fevicon;
	}
?>
<?php 
	$url = trim(mysqli_real_escape_string($connect, $_GET['url']));
?>
<?php 
	if (is_url($url)) {
		if (get_title($url) === '') {
			$title = get_title($url);
		} else {
			$title = get_title($url);
		}
		echo($title . '<br><br>');
		$cleared_url = parse_url($url, PHP_URL_SCHEME) . '://' . parse_url($url, PHP_URL_HOST);
		$favicon_url_result = $cleared_url . parse_url(get_favicon($url), PHP_URL_PATH);
		if (strpos(get_favicon($url), 'https://') or strpos(get_favicon($url), 'http://')) {
			$favicon_url_result = get_favicon($url);
		}
		echo($favicon_url_result . '<br>');
		echo('<img src="' . $favicon_url_result . '">' . '<br><br>');
		if (get_meta_tags($url)['description'] === '') {
			$description = get_meta_tags($cleared_url)['description'];
		} else {
			$description = get_meta_tags($url)['description'];
		}
		echo($description . '<br><br>');
		echo(get_meta_tags($cleared_url)['keywords'] . '<br><br>');
	} else {
		echo('URL Invalid!');
	}
?>