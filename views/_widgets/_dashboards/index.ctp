<div class="dashboards index">
	<h4><?php __('Dashboards');?></h4>
	<table cellpadding="0" cellspacing="0" class="table-long">
	<tr>
			<th><?php echo $this->Paginator->sort('name');?></th>
			<th><?php echo $this->Paginator->sort('order');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($dashboards as $dashboard):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $dashboard['Dashboard']['name']; ?>&nbsp;</td>
		<td><?php echo $dashboard['Dashboard']['order']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $dashboard['Dashboard']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $dashboard['Dashboard']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $dashboard['Dashboard']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
</div>
<?php echo $this->element('layout/pagination')?>

<?php $this->Layout->blockStart('sidebar')?>
<div class="box">
	<h4><?php __('Dashboards Menu');?></h4>
	<ul class="list-links">
		<li><?php echo $this->Javascript->link(__('Dashboards', true), array('action'=>'index'), '#content-main')?></li>
		<li><?php echo $this->Javascript->link(__('New Dashboard', true), array('action'=>'add'), '#content-main')?></li>
		<li><?php echo $this->Javascript->link(__('Widgets', true), array('controller'=>'widgets', 'action'=>'index'), '#content-main')?></li>
		<li><?php echo $this->Javascript->link(__('New Widget', true), array('controller'=>'widgets','action'=>'add'), '#content-main')?></li>
	</ul>
</div>
<?php $this->Layout->blockEnd()?>