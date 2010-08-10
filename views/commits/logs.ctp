<?php
$script = '
$(document).ready(function(){
	$(".message").each(function () {
		$(this).html(converter.makeHtml(jQuery.trim($(this).text())))
	});
});
';
$html->scriptBlock($script, array('inline' => false));
?>

<div class="commits history index">
	<h4>
		<?php __('Logs') ?>
	</h4>
	<?php foreach ((array)$commits as $commit):?>

		<div class="commit">

			<h2>
				<?php echo $chaw->commit($commit['Repo']['revision'], (array)$CurrentProject);?>
			</h2>

			<p>
				<strong><?php __('Author') ?>:</strong> <?php echo $commit['Repo']['author'];?>
			</p>

			<p>
				<strong><?php __('Date') ?>:</strong> <?php echo $commit['Repo']['commit_date'];?>
			</p>

			<p class="message">
				<?php echo $commit['Repo']['message'];?>
			</p>

		</div>

	<?php endforeach;?>

</div>
<?php echo $this->element('layout/pagination'); ?>
