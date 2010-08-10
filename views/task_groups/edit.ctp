<div class="taskGroups form">
	<h4><?php echo (!empty($this->data['TaskGroup']['name'])) ? $this->data['TaskGroup']['name'] : 'New'; ?> <?php __('Task Group');?></h4>
	<?php echo $this->Form->create('TaskGroup');?>
		<fieldset id="taskgroup">
			<?php
				echo $this->Form->input('id');
				echo $this->Form->hidden('workspace_id');
				echo $this->Form->input('name');
				echo $this->Form->input('description');
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
					echo $this->Javascript->toggle('Due (Date and Time)', array('update' => '#TaskGroupDueContainer', 'div' => false));
				?>
			</label>
			<label class="toggle">
				<?php
					echo $this->Javascript->toggle('Sorting Priority', array('update' => '#TaskGroupOrderContainer', 'div' => false));
				?>
			</label>
			<div class="clearFix"></div>
			<div id="TaskGroupDueContainer" class="hidden">
				<?php
					echo $this->Form->input('due', array('type' => 'text', 'class' => 'datepicker'));
				?>
			</div>
			<div id="TaskGroupOrderContainer" class="hidden">
				<?php
					echo $this->Form->input('order');
				?>
			</div>
		</fieldset>	
	<?php echo $this->Form->end(__('Submit', true)); ?>
</div>