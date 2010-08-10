<?php
	echo $chaw->messages($messages);
?>
<div class="projects form">
 		<h4><?php echo $title_for_layout; ?></h4>
<?php echo $form->create(array('action' => $this->action,
		'url' => array('id' => false)
));?>
	<fieldset class="main">
	<?php
		echo $form->input('id');
		echo $form->hidden('url');
		echo $form->hidden('fork');
		echo $form->hidden('username');

		echo $form->input('repo_type', array(
			'label' => __('Repo Type', true),
			'disabled' => true,
		));

		echo $form->input('name', array(
			'disabled' => true
		));

		if ($form->value('private') == 0) {
			echo $form->input('ohloh_project', array(
				'label' => '<a href="https://www.ohloh.net">https://www.ohloh.net/</a>p/',
				'div' => 'inline'

			));
		}
		echo $form->input('description');
	?>
	</fieldset>
	<fieldset class="options">
 		<h2><?php __('Groups')?></h2>
 		<p><?php __('Comma seperated') ?></p>
		<?php
			echo $form->input('config.groups', array('label' => false, 'type' => 'textarea'));
		?>
<?php echo $form->end('Submit');?>
	</fieldset>
</div>