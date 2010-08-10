<div class="milestones form">
	<h4><?php __('New Permission');?></h4>
	<?php echo $this->Form->create('Permissions');?>
		<fieldset>
			<?php
				echo $this->Form->input('user_id', array('label' => 'Add User'));
				//echo $this->Form->input('user_id', array('class' => 'autocomplete', 'label' => 'Add User'));
				echo $this->Form->hidden('type');
				echo $this->Form->hidden('foreign_key');
			?>
		</fieldset>
	<?php echo $this->Form->end(__('Submit', true)); ?>
	
</div>