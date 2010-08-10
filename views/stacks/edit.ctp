<div class="stacks form">
<h4><?php echo (!empty($this->data['Stack']['name'])) ? $this->data['Stack']['name'] : 'New'; ?> <?php __('Stack');?></h4>
	<?php echo $this->Form->create('Stack');?>
		<fieldset id="stack">
			<?php
				echo $this->Form->input('id');
				echo $this->Form->hidden('workspace_id');
				echo $this->Form->input('name');
				echo $this->Form->input('description');
				echo $this->Form->input('assigned_to_id', array('label' => 'Assign To', 'empty' => 'None', 'class' => 'autocomplete'));
				if (!$this->Session->check('Milestone')) {
					echo $this->Form->input('milestone_id', array('empty' => 'None', 'class' => 'autocomplete'));
				}
			?>
			<div class="clearFix"></div>
			<label class="toggle">
				<?php
					echo $this->Javascript->toggle('Due (Date and Time)', array('update' => '#StackDueContainer', 'div' => false));
				?>
			</label>
			<div class="clearFix"></div>
			<div id="StackDueContainer" class="hidden">
				<?php
					echo $this->Form->input('due', array('type' => 'text', 'class' => 'datepicker'));
				?>
			</div>
		</fieldset>
	<?php echo $this->Form->end(__('Submit', true)); ?>
</div>