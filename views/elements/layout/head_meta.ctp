<script type="text/javascript">
//<![CDATA[
	// setup our global basepath for the application for use in javascript
	var basePath = "<?php echo Configure::read('Kinspir.basePath'); ?>";
	// create a global js variable that is the wiki's markdown parser url (used in the markdown script)
	var parser_url = basePath + "<?php echo $this->Html->url(array('controller' => 'wiki_pages', 'action' => 'parse', 'base' => false),false); ?>";
	<?php if ($this->Session->check('Workspace.id')) : ?>
		var workspaceId = <?php echo $this->Session->read('Workspace.id'); ?>;
	<?php endif; ?>
//]]>
</script>

<?php
echo $this->Html->css(array(
	'reset',
	'layout',
	'icons',
	'jqueryui/default',
	'colorbox/colorbox',
	'fcbk/style',
	'notify/ui.notify',
	'fullcalendar/fullcalendar',
	'markitup/markitup',
	'markitup/markdown',
	'elements',
)); ?>
<!--[if IE 6]><?php echo $this->Html->css('ie')?><![endif]-->

<?php echo $this->Html->script(array(
	'https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js',
	'https://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.js',
	'colorbox/jquery.colorbox-min',
	'fcbk/jquery.fcbkcomplete',
	'notify/jquery.notify',
	'fullcalendar/fullcalendar.min',	
	'markitup/jquery.markitup.pack',
	'markitup/markdown',
	//'jquery.corners.min',
	'jquery.corner',
	'bg.pos',
	'kinspir'
)); ?>
	<?php
		echo '<meta name="ROBOTS" content="INDEX, NOFOLLOW">';
		echo $html->meta('icon');
		if (isset($rssFeed)) {
			echo $html->meta('rss', $html->url($rssFeed, true));
		}
		echo $html->css(array('chaw'));

		/*
		if (!empty($this->params['admin'])) {
			echo $html->css(array('chaw.admin'));
		}
		*/
		if (isset($showdown)):
			echo $html->script('gshowdown.min');
			echo $html->scriptBlock('
				var converter = new Showdown.converter("' . $chaw->base() . '");
				$(document).ready(function(){
					$(".wiki-text").each(function () {
						$(this).html(converter.makeHtml(jQuery.trim($(this).text())))
					});
				});
			');
		endif;
		//echo $scripts_for_layout;
	?>
<!--[if lte IE 6]>
	<?php //echo $this->Html->script('slide/pngfix/supersleight-min.js')?>
<![endif]-->