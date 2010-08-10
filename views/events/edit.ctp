<div class="events form">
<h4><?php echo (!empty($this->data['Event']['name'])) ? $this->data['Event']['name'] : 'New'; ?> <?php __('Event');?></h4>
	<?php echo $this->Form->create('Event'); ?>
		<fieldset id="event">
			<?php
				echo $this->Form->input('id');
				echo $this->Form->hidden('workspace_id');
				echo $this->Form->input('name');
				echo $this->Form->input('starts', array('label' => 'Event Starts At (Date and Time)', 'type' => 'text', 'class' => 'datepicker'));
				echo $this->Form->input('ends', array('label' => 'Event Ends At (Date and Time)', 'type' => 'text', 'class' => 'datepicker'));
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
					'label' => $this->Javascript->toggle('Description', array('update' => '#EventDescription', 'div' => false))
				));
			?>
		</fieldset>
	<?php echo $this->Form->end(__('Submit', true)); ?>
</div>