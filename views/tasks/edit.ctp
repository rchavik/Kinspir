<div class="tasks form">
<h4><?php echo (!empty($this->data['Task']['name'])) ? $this->data['Task']['name'] : 'New'; ?> <?php __('Task');?><?php if (isset($taskGroupName)) { echo ' Under ' . $taskGroupName; } ?></h4>
	<?php echo $this->Form->create('Task');?>
		<fieldset id="task-group-<?php echo $this->data['Task']['task_group_id']?>">
			<?php
				echo $this->Form->input('id');
				echo $this->Form->hidden('task_group_id');
				echo $this->Form->hidden('workspace_id');
				echo $this->Form->hidden('order');
				echo $this->Form->input('name', array('label' => 'Task'));
				echo $this->Form->input('assigned_to_id', array('label' => 'Assign To', 'empty' => 'None', 'class' => 'autocomplete'));
				if (!$this->Session->check('Stack')) {
					echo $this->Form->input('stack_id', array('label' => 'Move To Stack', 'empty' => 'None', 'class' => 'autocomplete'));
				}
				if (!$this->Session->check('Milestone')) {
					echo $this->Form->input('milestone_id', array('empty' => 'None', 'class' => 'autocomplete'));
				}
			?>
			<div class="clearFix"></div>
			<label class="toggle">
				<?php
					echo $this->Javascript->toggle('Due (Date and Time)', array('update' => '#TaskDueContainer', 'div' => false));
				?>
			</label>
			<label class="toggle">
				<?php
					echo $this->Javascript->toggle('Description', array('update' => '#TaskDescriptionContainer', 'div' => false));
				?>
			</label>
			<div class="clearFix"></div>
			<div id="TaskDueContainer" class="hidden">
				<?php
					echo $this->Form->input('due', array('type' => 'text', 'class' => 'datepicker'));
				?>
			</div>
			<div id="TaskDescriptionContainer" class="hidden">
				<?php
					echo $this->Form->input('description');
				?>
			</div>
		</fieldset>
	<?php echo $this->Form->end(__('Submit', true)); ?>
</div>