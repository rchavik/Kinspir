<?php
	echo $chaw->messages($messages);
?>
<div class="projects form">
	<h4>New Repository</h4>
<?php echo $form->create(array('action' => $this->action));?>
	<fieldset class="main">
	<?php
		echo $form->input('id');
		echo $form->input('repo_type',array('label' => __('Repo Type',true)));
		echo $form->input('name', array(
			'error' => array(
				'minimum' => __('The project name must be at least 5 characters',true),
				'unique' => __('The project name must be unique.',true)
			)
		));
		echo $form->input('description');

		echo $form->hidden('private');

		if (!empty($this->passedArgs[0]) && $this->passedArgs[0] == 'public'){
			echo $form->input('ohloh_project', array(
				'label' => '<a href="https://www.ohloh.net">https://www.ohloh.net/</a>p/',
				'div' => 'inline'
			));
		}
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