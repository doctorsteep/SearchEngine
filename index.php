<?php
	header('Access-Control-Allow-Methods: GET');

	include $_SERVER['DOCUMENT_ROOT'].'/vendor/connect.php';

	include $_SERVER['DOCUMENT_ROOT'].'/vendor/manifest.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/libs/Mobile_Detect.php';

	$detect_device = new Mobile_Detect;
?>
<?php
	function ParseXmlToJson($url) {
        $fileContents= file_get_contents($url, 'UTF-8');
        $fileContents = str_replace(array("\n", "\r", "\t"), '', $fileContents);
        $fileContents = trim(str_replace('"', "'", $fileContents));
        $simpleXml = simplexml_load_string($fileContents);
        $json = json_encode($simpleXml, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);

        return $json;
    }
?>
<?php
	// $bank = ParseXmlToJson('https://belapb.by/CashExRatesDaily.php?ondate=' . date('d/m/Y'));

	// echo($bank);

	function _isCurl(){ 
	  return function_exists('curl_version'); 
	  }

	function get_course($curr){
		if (_iscurl()){ //curl is enabled 
			$url = "https://api.privatbank.ua/p24api/pubinfo?json&exchange&coursid=5"; 
			$ch = curl_init(); 
			curl_setopt($ch, CURLOPT_URL, $url); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
			$output = curl_exec($ch); 
			curl_close($ch); 
			// print_r($output); // Curl operations finished 
			if(!$output) return false;


			$courses = json_decode($output, true);
			$course_curr = false;
		} else { 
			echo "CURL is disabled"; 
		}

		foreach($courses as $course){
		    if($course['ccy'] == $curr){
		        echo('' .
		        	'<div class="container-item-curr-home">'.
		        		'<h2 class="curr-title-ccy">'.$course['ccy'].'</h2>'.
		        		'<h2 class="curr-title-base-ccy">'.$course['base_ccy'].'</h2>'.
		        		'<h2 class="curr-title-buy">'.trim(number_format($course['buy'], 2, '.', '')).'</h2>'.
		        		'<h2 class="curr-title-sale">'.trim(number_format($course['sale'], 2, '.', '')).'</h2>'.
		        	'</div>'.
		        	'');
		    }
		}
	}

	function convertCurrency($amount, $from = 'EUR', $to = 'USD'){
	    if (empty($_COOKIE['exchange_rate'])) {
	        $Cookie = new Cookie($_COOKIE);
	        $curl = file_get_contents_curl('http://api.fixer.io/latest?symbols='.$from.','.$to.'');
	        $rate = $curl['rates'][$to];
	        $Cookie->exchange_rate = $rate;
	    } else {
	        $rate = $_COOKIE['exchange_rate'];
	    }
	    $output = round($amount * $rate);

	    return $output;
	}
?>
<?php
	$search = trim(mysqli_real_escape_string($connect, $_GET['q']));
	$type = trim(mysqli_real_escape_string($connect, $_GET['t']));

	$maxInputLenght = 500;

	$go_search = '?q=';
	$go_search_two = '&t=text';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<title><?php echo $titlePage; ?></title>
	<link rel="stylesheet" type="text/css" href="/assets/css/tooltip.css">
	<link rel="stylesheet" type="text/css" href="/assets/css/style.css?v=<?php echo(time()); ?>">
	<link rel="shortcut icon" href="/assets/images/logo/logo-128.png" type="image/png">
	<meta charset="utf-8">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="keywords" content="док, доктор, стееп, стип, докторстееп, докторстип, докторстееп, поиск, поисковик, дс, серч, сёрч, искать, найти, Беларуско-Укринский, беларуский, украинский, ds, search, brlarus, ukaraine, by, ua, btc, usd, rur, rub, uah, doc, doctor, steep, doctorsteep">
	<meta name="description" content="Универсальный Беларуско-Укринский поисковик! Возможность добавить свой сайт, никогда не было так легко.">
</head>
<body>

	<?php if ($search == null) { ?>
		<script type="text/javascript">
			var titlePage = "<?php echo $titlePage; ?>";

			document.title = titlePage;
		</script>

		<div class="container-home-ds-search">
			<div class="container-curr-home-top-content">
				<div class="top-title-container-curr-home">
					<div style="width: 100%;"></div>
					<div class="top-title-container-curr-home-item">
						<h2 class="title-top-text-curr-home">Покупка</h2>
					</div>
					<div class="top-title-container-curr-home-item">
						<h2 class="title-top-text-curr-home">Продажа</h2>
					</div>
				</div>
				<div class="container-curr-home-top">
					<?php echo(get_course('USD')); ?>
					<?php echo(get_course('EUR')); ?>
					<?php echo(get_course('RUR')); ?>
					<?php echo(get_course('BTC')); ?>
				</div>
			</div>

			<div class="container-content-home-ds-search">
				<center style="margin-bottom: 80px;">
					<img class="logo-home-ds-search<?php if ($detect_device->isMobile()) { echo('-mobile'); }?>" src="/assets/images/logo/logo-<?php if ($detect_device->isMobile()) { echo('512.png'); } else { echo('256.png'); } ?>" draggable="false" onclick="return false;" ondragstart="return false;" ondragover="return false;">
				</center>
				<div class="container-content-input-home-ds-search<?php if ($detect_device->isMobile()) { echo('-mobile'); }?>">
					<input type="text" name="q" class="input-home-search" id="input-home-search" placeholder="Введите запрос" autocomplete="off" maxlength="<?php echo($maxInputLenght); ?>">
					<div class="container-input-content-action" id="container-input-content-action" style="display: none;">
						<img src="/assets/icons/Clear-128.png" class="action-input-clear" id="action-input-clear" title="Очистить">
						<img src="/assets/icons/Search-128.png" class="action-input-search" id="action-input-search" title="Искать">
					</div>
				</div>
			</div>
		</div>

		<script type="text/javascript">
			document.getElementById('input-home-search').setAttribute('oninput', 'searchFunInputChange(this.value)');
			document.getElementById('input-home-search').setAttribute('onkeypress', 'return keyScriptInputSearch(event)');

			function searchFunInputChange(text) {
				if (text == "" || text == null) {
					document.getElementById('container-input-content-action').style.display = 'none';
				} else {
					document.getElementById('container-input-content-action').style.display = 'flex';
				}
			}

			function goSearch() {
				window.location = '<?php echo $go_search; ?>' + document.getElementById('input-home-search').value + '<?php echo $go_search_two; ?>';
			}

			document.getElementById("action-input-clear").addEventListener("click", function() {
		       document.getElementById('input-home-search').value = '';
		       searchFunInputChange('');
		    });

		    document.getElementById("action-input-search").addEventListener("click", function() {
		       goSearch();
		    });

		    function keyScriptInputSearch(e) {
			    if (e.keyCode == 13) {
			        goSearch();
			        return false;
			    }
			}
		</script>
	<?php } else { ?>
		<script type="text/javascript">
			var titlePageSmall = "<?php echo mb_strimwidth($search, 0, 20, "..."); ?>";
			var titlePageFull = "<?php echo $search; ?>";
			var titlePage = "Поиск - " + titlePageSmall;

			document.title = titlePage;
		</script>

		<nav class="top-bar-serach-home-container" id="top-bar-serach-home-container">
			<div class="container-home-search-content-top-search">
				<?php if (!$detect_device->isMobile()) { ?>
					<img class="logo-home-ds-search-top-bar" src="/assets/images/logo/logo-256.png" draggable="false" onclick="return false;" ondragstart="return false;" ondragover="return false;">
				<?php } ?>
				<div class="container-home-search-content-top<?php if ($detect_device->isMobile()) { echo('-mobile'); }?>">
					<input type="text" name="q" class="input-home-search top" id="input-home-search" placeholder="Введите запрос" autocomplete="off" maxlength="<?php echo($maxInputLenght); ?>" value="<?php echo $search; ?>">
					<img src="/assets/icons/Clear-128.png" class="action-input-clear" id="action-input-clear" title="Очистить" style="visibility: visible;">
					<img src="/assets/icons/Search-128.png" class="action-input-search" id="action-input-search" title="Искать">
				</div>
			</div>
		</nav>

		<div class="container-content-search-full-page" id="container-content-search-full-page">
			<?php if ($type == 'text') { ?>
				<?php include $_SERVER['DOCUMENT_ROOT'].'/assets/search/text.php'; ?>
			<?php } else { ?>
				<h2 class="title-search-unknown">Не известный тип запроса!</h2>
			<?php } ?>
		</div>

		<script type="text/javascript">
			document.getElementById('input-home-search').setAttribute('oninput', 'searchFunInputChange(this.value)');
			document.getElementById('input-home-search').setAttribute('onkeypress', 'return keyScriptInputSearch(event)');

			document.getElementById("input-home-search").addEventListener("focusin", function() {
		       	searchFunInputChange(document.getElementById("input-home-search").value);
		    });
		    document.getElementById("input-home-search").addEventListener("focusout", function() {
		    	searchFunInputChange(document.getElementById("input-home-search").value);
		    });

		    function searchFunInputChange(text) {
				if (text == "" || text == null) {
					document.getElementById('action-input-clear').style.visibility = 'hidden';
				} else {
					document.getElementById('action-input-clear').style.visibility = 'visible';
				}
			}

			function goSearch() {
				window.location = '<?php echo $go_search; ?>' + document.getElementById('input-home-search').value + '<?php echo $go_search_two; ?>';
			}

			document.getElementById("action-input-clear").addEventListener("click", function() {
		       document.getElementById('input-home-search').value = '';
		       searchFunInputChange('');
		    });

		    document.getElementById("action-input-search").addEventListener("click", function() {
		       goSearch();
		    });

		    function keyScriptInputSearch(e) {
			    if (e.keyCode == 13) {
			        goSearch();
			        return false;
			    }
			}
		</script>
	<?php } ?>

</body>
</html>