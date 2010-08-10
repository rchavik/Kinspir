<div class="uploads form">
	<h4>
		<?php 
			echo (!empty($this->data['Upload']['name'])) ? $this->data['Upload']['name'] : ' ';
			// if true we are editing, not adding
			if (isset($this->data['Upload']['id'])) {
				__('New Version');
			} else {
				__('New Upload');
			}
		?>
	</h4>
	<?php echo $this->Form->create('Upload', array('type' => 'file'));?>
		<fieldset id="upload">
			<?php
				echo $this->Form->input('id');
				echo $this->Form->hidden('workspace_id');
				echo $this->Form->input('name');
				echo $this->Form->input('UploadVersion.filename', array('type' => 'file'));
				if (!$this->Session->check('Stack')) {
					echo $this->Form->input('stack_id', array('label' => 'Move To Stack', 'empty' => 'None', 'class' => 'autocomplete'));
				}
				if (!$this->Session->check('Milestone')) {
					echo $this->Form->input('milestone_id', array('empty' => 'None', 'class' => 'autocomplete'));
				}
			?>
			<div class="clearFix"></div>
			<?php
				echo $this->Form->input('description', array('class' => 'hidden',
					'label' => $this->Javascript->toggle('Description', array('update' => '#UploadDescription', 'div' => false))
				));
			?>
		</fieldset>
	<?php
		echo $this->Form->end(__('Submit', true)); 
	?>
</div>