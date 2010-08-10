<div class="dashboardSlots index">
	<h4><?php __('Dashboard Slots');?></h4>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('dashboard_id');?></th>
			<th><?php echo $this->Paginator->sort('widget_id');?></th>
			<th><?php echo $this->Paginator->sort('column');?></th>
			<th><?php echo $this->Paginator->sort('order');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($dashboardSlots as $dashboardSlot):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $dashboardSlot['DashboardSlot']['id']; ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($dashboardSlot['Dashboard']['name'], array('controller' => 'dashboards', 'action' => 'view', $dashboardSlot['Dashboard']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($dashboardSlot['Widget']['name'], array('controller' => 'widgets', 'action' => 'view', $dashboardSlot['Widget']['id'])); ?>
		</td>
		<td><?php echo $dashboardSlot['DashboardSlot']['column']; ?>&nbsp;</td>
		<td><?php echo $dashboardSlot['DashboardSlot']['order']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $dashboardSlot['DashboardSlot']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $dashboardSlot['DashboardSlot']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $dashboardSlot['DashboardSlot']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $dashboardSlot['DashboardSlot']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
	));
	?>	</p>

	<div class="paging">
		<?php echo $this->Paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
	 | 	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('next', true).' >>', array(), null, array('class' => 'disabled'));?>
	</div>
</div>