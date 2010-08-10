<div class="dashboards form">
	<h4><?php echo (!empty($this->data['Dashboard']['name'])) ? $this->data['Dashboard']['name'] : 'New'; ?> <?php __('Dashboard');?></h4>
	<?php echo $this->Form->create('Dashboard');?>
		<fieldset>
			<?php
				echo $this->Form->input('id');
				echo $this->Form->input('name');
			?>
		</fieldset>
	<?php echo $this->Form->end(__('Submit', true)); ?>
</div>