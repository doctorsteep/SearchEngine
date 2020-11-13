<?php 
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/connect.php';
?>
<?php 
	echo('<link rel="stylesheet" type="text/css" href="/assets/css/text.css?v=' . time() . '">'); 
?>
<?php
	$search = $search;
?>
<script type="text/javascript">
	if(document.readyState === 'complete') {
	    loadedPageText();
	} else {
	    if (window.addEventListener) {  
	        window.addEventListener('load', loadedPageText, false);
	    } else {
	        window.attachEvent('onload', loadedPageText);
	    }
	}

	function loadedPageText() {
		document.getElementById('container-content-search-full-page').style.marginTop = document.getElementById('top-bar-serach-home-container').offsetHeight + "px";
	}
</script>

<script type="text/javascript">
	var searchValueSmall = "<?php echo mb_strimwidth($search, 0, 40, "..."); ?>";
	var searchValueFull = "<?php echo $search; ?>";
</script>

<!-- <h2 class="message-search-value-text-content" id="message-search-value-text-content"></h2>

<script type="text/javascript">
	document.getElementById('message-search-value-text-content').textContent = 'Поисковой запрос: ' + searchValueSmall;
</script> -->
<?php
	$search_query = mysqli_query($connect, "SELECT * FROM search WHERE keywords LIKE '%$search%' OR description LIKE '%$search%' OR title LIKE '%$search%' OR path LIKE '%$search%' OR url LIKE '%$search%' OR host LIKE '%$search%' ORDER BY view ASC LIMIT 20");
?>
	<h2 class="message-search-value-text-content" id="message-search-row-text-content">Поисковой запрос: <?php echo mb_strimwidth($search, 0, 14, "..."); ?> | Результат поиска: <?php echo(mysqli_num_rows($search_query)); ?> | Developer by. DoctorSteep</h2>
<?php 
	if (mysqli_num_rows($search_query) > 0) { ?>
		<div class="container-centered-search-result">
			<div class="container-centered-search-content-result-list<?php if ($detect_device->isMobile()) { echo('-mobile'); }?>">
				<?php while($row = mysqli_fetch_assoc($search_query)) { // рисуем список :) ?>
					<?php $id_se = $row['id']; ?>
					<?php $se_check = mysqli_query($connect, "SELECT * FROM `search` WHERE `id` = '$id_se'"); ?>
					<?php if (mysqli_num_rows($se_check) > 0) { ?>
						<?php $se = mysqli_fetch_assoc($se_check); ?>
						<div class="container-item-search-result<?php if ($detect_device->isMobile()) { echo('-mobile'); }?>" id="container-item-search-result-id<?php echo($id_se); ?>">
							<div class="top-container-content-title-site-search-result<?php if ($detect_device->isMobile()) { echo('-mobile'); }?>">
								<img src="<?php echo($se['favicon_v2']); ?>" class="image-site-resulr-search-favicon" draggable="false">
								<a class="a-href-url-result-search" href="<?php echo($se['url']); ?>"><?php echo($se['url']); ?></a>
							</div>
							<hr class="divider-search-result">
							<div class="container-bottom-content-search-result<?php if ($detect_device->isMobile()) { echo('-mobile'); }?>">
								<h2 class="title-result-search"><?php echo($se['title']); ?></h2>
								<h2 class="description-result-search"><?php echo($se['description']); ?></h2>
							</div>
							<hr class="divider-search-result">
							<div class="top-container-content-title-site-search-result<?php if ($detect_device->isMobile()) { echo('-mobile'); }?>">
								<h2 class="date-last-update-search-result">Последнее обновление: <?php echo($se['date']); ?></h2>
							</div>
						</div>
					<?php } ?>
				<?php } ?>
			</div>
		</div>
	<?php } else { ?>
		<h2 class="title-search-unknown">По запросу<b class="tt">"<?php echo $search; ?>"</b>ничего не найдено!</h2>
	<?php }
?>