<script type="text/javascript">
$(document).ready(function(){
	$('.widget a').live('click', function(){
		$(this).closest('.widget').load(this.href);
		return false;
	});
});
</script>
<?php $column1 = Set::extract('/DashboardSlot[column=1]', $dashboard); ?>
<?php $column2 = Set::extract('/DashboardSlot[column=2]', $dashboard); ?>
<?php $column3 = Set::extract('/DashboardSlot[column=3]', $dashboard); ?>

<?php if ($dashboard['Dashboard']['id'] != 1) :?>
	<div id="content-top">
		<h2><?php echo $dashboard['Dashboard']['name']; ?></h2>
		<?php if (false):?>
			<?php echo $this->Html->link('Manage Dashboards', array('controller' => 'dashboards', 'action' => 'index'), array('id' => 'topLink'))?>
		<?php endif; ?>
	</div>
<?php endif; ?>

<div id="left-col">
	<?php foreach ($column1 as $widget): ?>
		<div id="widget<?php echo $widget['DashboardSlot']['id']?>" class="box loading" style="display: block">
			<h4>Loading <?php echo $widget['DashboardSlot']['Widget']['name']; ?>...</h4>
			<?php echo $this->Javascript->load(array('controller' => $widget['DashboardSlot']['Widget']['controller'], 'action' => $widget['DashboardSlot']['Widget']['action'], $widget['DashboardSlot']['Widget']['params']), '#widget' . $widget['DashboardSlot']['id'], true)?>
		</div>
	<?php endforeach; ?>
</div>

<?php if ($dashboard['Dashboard']['columns'] >= 2):?>
	<?php
		$class = null;
		if ($dashboard['Dashboard']['columns'] == 2) {
			$class = 'class="full-col"';
		}
	?>
	<div id="mid-col" <?php echo $class; ?>>			
		<?php foreach ($column2 as $widget): ?>
			<div id="widget<?php echo $widget['DashboardSlot']['id']?>" class="box loading" style="display: block">
				<h4>Loading <?php echo $widget['DashboardSlot']['Widget']['name']; ?>...</h4>
				<?php echo $this->Javascript->load(array('controller' => $widget['DashboardSlot']['Widget']['controller'], 'action' => $widget['DashboardSlot']['Widget']['action'], $widget['DashboardSlot']['Widget']['params']), '#widget' . $widget['DashboardSlot']['id'], true)?>
			</div>
		<?php endforeach; ?>
	</div>
<?php endif; ?>

<?php if ($dashboard['Dashboard']['columns'] == 3):?>
	<div id="right-col">
		<?php foreach ($column3 as $widget): ?>
			<div id="widget<?php echo $widget['DashboardSlot']['id']?>" class="box loading" style="display: block">
				<h4>Loading <?php echo $widget['DashboardSlot']['Widget']['name']; ?>...</h4>
				<?php echo $this->Javascript->load(array('controller' => $widget['DashboardSlot']['Widget']['controller'], 'action' => $widget['DashboardSlot']['Widget']['action'], $widget['DashboardSlot']['Widget']['params']), '#widget' . $widget['DashboardSlot']['id'], true)?>
			</div>
		<?php endforeach; ?>
	</div>
<?php endif; ?>



