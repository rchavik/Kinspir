<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
	<title>Kinspir</title>
</head>
<body>
<div style="padding: 10px 15px; width: 526px; margin-left: auto; margin-right: auto; margin-bottom: 50px;">
	<div style='background: #4D5558'>
	<?php
		$kinspirSiteUrl = Configure::read('Kinspir.Site.Url');
		$supportEmail = Configure::read('Kinspir.Email.Address.support');
		echo "<center>";
		echo $html->link(
				$html->image($kinspirSiteUrl . "/img/kinspir_logo.png", array(
					"alt" => "Kinspir",
					"height" => "50px",
					"background-color" => "#4D5558",
					)),
				$kinspirSiteUrl,
				array(
					'target' =>'_blank',
					'escape' => false
				)
			);
		echo "</center>";
	?>
	</div>