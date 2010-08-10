<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $html->charset(); ?>
	<title>
		<?php __('Kinspir:'); ?>
		<?php echo $title_for_layout; ?>
	</title>
	<!--[if IE 6]>
    <style type="text/css">
    label.remember {width: 200px; margin-left:40px;}
    label.txt-field {margin-right: 5px}
    </style>
    <![endif]-->
	<?php
		echo $html->meta('icon');
		echo $html->css('login');
		echo $scripts_for_layout;
	?>	
</head>
<body>
<div id="spacer"></div>
<div id="container">
	<h1>Kinspir Beta <?php echo $title_for_layout; ?></h1>
	<?php if ($session->check('Message.auth')) : ?>
		<p class="error"><strong>Access Denied</strong> | Please login to proceed.</p>
	<?php endif; ?>
	<?php echo $content_for_layout; ?>
	<br /><br />
	<h1><a href="http://about.kinspir.com">What is Kinspir?</a></h1>
</div>
</body>
</html>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-5187184-5']);
  _gaq.push(['_setDomainName', '.kinspir.com']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>