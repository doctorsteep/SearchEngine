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

	function getDescription($url) {
	    $tags = get_meta_tags($url);
	    return @($tags['description'] ? $tags['description'] : null);
	}
	function getKeywords($url) {
	    $tags = get_meta_tags($url);
	    return @($tags['keywords'] ? $tags['keywords'] : null);
	}

	function getTitle($url) {
	    $page = file_get_contents($url);
	    $title = preg_match('/<title[^>]*>(.*?)<\/title>/ims', $page, $match) ? $match[1] : null;
	    return $title;
	}
?>
<?php 
	$url = trim(mysqli_real_escape_string($connect, $_GET['url']));
	$keywords = trim(mysqli_real_escape_string($connect, $_GET['keywords']));
	$description = trim(mysqli_real_escape_string($connect, $_GET['description']));
?>
<?php
	if (is_url($url)) {
		// TRUE
		$serverTIME = date('Y-m-d H:i:s'); // 2020-01-01 12:00:00
		$time = time();
		$host = trim(mysqli_real_escape_string($connect, parse_url($url, PHP_URL_HOST)));
		$path = trim(mysqli_real_escape_string($connect, parse_url($url, PHP_URL_PATH)));
		$scheme = trim(mysqli_real_escape_string($connect, parse_url($url, PHP_URL_SCHEME)));
		$cleared_url = trim(mysqli_real_escape_string($connect, parse_url($url, PHP_URL_SCHEME) . '://' . parse_url($url, PHP_URL_HOST)));
		$favicon = trim(mysqli_real_escape_string($connect, 'https://www.google.com/s2/favicons?sz=64&domain_url=' . $host));
		$favicon_v2 = trim(mysqli_real_escape_string($connect, 'https://' . $_SERVER['HTTP_HOST'] . '/sites/favicons/' . $host));
		if (getDescription($url) != '' or getDescription($url) != null) {
			$description = trim(mysqli_real_escape_string($connect, getDescription($url)));
		} else {
			if (trim(getDescription($cleared_url)) !== '') {
				$description = trim(mysqli_real_escape_string($connect, getDescription($cleared_url)));
			}
		}
		if (getKeywords($url) != '' or getKeywords($url) != null) {
			$keywords = trim(mysqli_real_escape_string($connect, getKeywords($url)));
		} else {
			if (trim(getKeywords($cleared_url)) !== '') {
				$keywords = trim(mysqli_real_escape_string($connect, getKeywords($cleared_url)));
			}
		}
		$title = trim(mysqli_real_escape_string($connect, getTitle($url)));

		echo('TIMESTAMP: '.$serverTIME.'<br>');
		echo('TIME: '.$time.'<br>');
		echo('HOST: '.$host.'<br>');
		echo('PATH: '.$path.'<br>');
		echo('SCHEME: '.$scheme.'<br>');
		echo('CLEARED URL: '.$cleared_url.'<br>');
		echo('DESCRIPTION: '.$description.'<br>');
		echo('KEYWORDS: '.$keywords.'<br>');
		echo('TITLE: '.$title.'<br>');

		echo('FAVICON: '.$favicon.'<br>');
		echo('FAVICON_V2: '.$favicon_v2.'<br>');
		echo('<img src="' . $favicon . '">');
		$favicon_data = file_get_contents($favicon);
		$favicon_new = $_SERVER['DOCUMENT_ROOT'].'/sites/favicons/'.$host;
		file_put_contents($favicon_new, $favicon_data);

		if ($host == null or $host == '') {
			exit();
		} if ($scheme == null or $scheme == '') {
			exit();
		} if ($cleared_url == null or $cleared_url == '') {
			exit();
		} if ($favicon == null or $favicon == '') {
			exit();
		} if ($favicon_v2 == null or $favicon_v2 == '') {
			exit();
		} if ($description == null or $description == '') {
			exit();
		} if ($keywords == null or $keywords == '') {
			exit();
		} if ($title == null or $title == '') {
			exit();
		} if ($url == null or $url == '') {
			exit();
		}

		echo('<br><br><br><br>');

		$check_search = mysqli_query($connect, "SELECT * FROM `search` WHERE `host` = '$host' AND `path` = '$path'");
		if (mysqli_num_rows($check_search) > 0) {
			echo('UPD: ');
			if (mysqli_query($connect, "UPDATE `search` SET `host` = '$host', `path` = '$path', `scheme` = '$scheme', `cleared_url` = '$cleared_url', `description` = '$description', `keywords` = '$keywords', `title` = '$title', `favicon` = '$favicon', `favicon_v2` = '$favicon_v2', `url` = '$url', `time` = '$time' WHERE `path` = '$path' AND `host` = '$host'")) {
				echo('DONE');
			} else {
				echo('FAIL');
			}
		} else {
			echo('NEW: ');
			if (mysqli_query($connect, "INSERT INTO `search`(`host`, `path`, `scheme`, `cleared_url`, `description`, `keywords`, `title`, `favicon`, `favicon_v2`, `url`, `date_fix`, `time`) VALUES ('$host', '$path', '$scheme', '$cleared_url', '$description', '$keywords', '$title', '$favicon', '$favicon_v2', '$url', '$serverTIME', '$time')")) {
				echo('DONE');
			} else {
				echo('FAIL');
			}
		}
	} else {
		// FALSE
	}
?>