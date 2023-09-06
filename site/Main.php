<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<link rel="icon" href="/assets/img/favicon.png" type="image/x-icon"/>
	<link href="/assets/css/main.css" rel="stylesheet">
	<title>
		<?php
			// get app title 
			$app_title = $config->getValue("app-name")." ".$config->getValue("version"); 
			echo $app_title;
		?>
	</title>
</head>
<body>
	<main>
		<p class="title-msg"><strong>Becwork</strong></p>
		<p class="doc-msg">click -> <a href="/example">examples</a></p>
		<p class="doc-msg">The documentation is available <a href="https://github.com/lordbecvold/becwork/blob/d0401f1b6bdc99996c9009876d1e656d91de95bc/DOC.MD" target="_blank">here</a></p>
	</main>
</body>
</html>