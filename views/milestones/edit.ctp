<div class="milestones form">
<h4><?php echo (!empty($this->data['Milestone']['name'])) ? $this->data['Milestone']['name'] : 'New'; ?> <?php __('Milestone');?></h4>
	<?php echo $this->Form->create('Milestone');?>
		<fieldset id="milestone">
			<?php
				echo $this->Form->input('id');
				echo $this->Form->hidden('workspace_id');
				echo $this->Form->input('name');
				echo $this->Form->input('due', array('label' => 'Due (Date and Time)', 'type' => 'text', 'class' => 'datepicker'));
				echo $this->Form->input('description');
			?>
		</fieldset>
	<?php echo $this->Form->end(__('Submit', true)); ?>
</div>