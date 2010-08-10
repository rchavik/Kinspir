<div class="widgets index">
	<h4><?php __('Widgets');?></h4>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('name');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($widgets as $widget):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $widget['Widget']['id']; ?>&nbsp;</td>
		<td><?php echo $widget['Widget']['name']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $widget['Widget']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $widget['Widget']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $widget['Widget']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $widget['Widget']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
</div>
<?php echo $this->element('layout/pagination')?>
