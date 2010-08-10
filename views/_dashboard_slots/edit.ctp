<div class="dashboardSlots form">
	<h4><?php printf(__('Edit %s', true), __('Dashboard Slot', true)); ?></h4>
<?php echo $this->Form->create('DashboardSlot');?>
	<fieldset>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('dashboard_id', array('type' => 'hidden'));
		echo $this->Form->input('widget_id');
		echo $this->Form->input('column');
		echo $this->Form->input('order');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>