<div class="workspaces form">
	<h4><?php echo (!empty($this->data['Workspace']['name'])) ? $this->data['Workspace']['name'] : 'New'; ?> <?php __('Workspace');?></h4>
	<?php echo $this->Form->create('Workspace');?>
		<fieldset>
			<?php
				echo $this->Form->input('id');
				echo $this->Form->input('name');
				echo $this->Form->input('description');
			?>
		</fieldset>
	<?php echo $this->Form->end(__('Submit', true)); ?>
</div>