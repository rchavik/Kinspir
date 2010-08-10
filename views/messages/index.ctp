<div class="box">
	<h4><?php echo $folder; ?> <?php __('Messages');?></h4>
	<?php if (!empty($messages)): ?>
		<table cellpadding="0" cellspacing="0" class="table-long">
			<thead>
				<tr>
					<td><?php echo $this->Paginator->sort('title');?></td>
					<td><?php echo $this->Paginator->sort('Started By', 'user_id');?></td>
					<td><?php echo $this->Paginator->sort('Last Replier', 'last_relier_id');?></td>
					<td><?php echo $this->Paginator->sort('last_reply');?></td>
					<td><?php __('Actions');?></td>
				</tr>
			</thead>
			<tbody>
			<?php
				$i = 0;
				foreach ($messages as $message):
				$class = null;
				if ($i++ % 2 == 0) {
					$class = ' class="altrow"';
				}
				
				if (isset($message['UnreadMessage'][0])) {
					$class = ' class="blue"';
				}
			?>
				<tr<?php echo $class;?>>
					<td class="col-second"><?php echo $this->Html->link($message['Message']['title'], array('controller'=>'message', 'action' => 'view', $message['RootMessage']['id'])); ?></td>
					<td class="col-first"><?php echo $message['User']['name']; ?></td>
					<td class="col-first"><?php echo $message['RootMessage']['Replier']['name']; ?></td>
					<td><?php echo $time->timeAgoInWords($message['RootMessage']['last_reply']); ?></td>
					<td class="row-nav">
						<?php
							if (!empty($message['DeletedMessage'])) {
								$linkText = 'Restore';
							} else {
								$linkText = 'Delete';
							}
							echo $this->Html->link(__($linkText, true), array('action' => strtolower($linkText), $message['Message']['id']), array('class'=>'table-delete-link'));
						?>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php else: ?>
		<?php __('No recent activity.');?>
	<?php endif; ?>
</div>
<?php echo $this->element('layout/pagination')?>

<?php $this->Layout->blockStart('sidebar')?>
<div class="box">
	<h4><?php __('Messages Menu');?></h4>
	<ul class="list-links">
		<li><?php echo $this->Javascript->link(__('Compose', true), array('action' => 'add'))?></li>
		<li><?php echo $this->Javascript->link(__('Inbox', true), array('controller' => 'messages','action'=>'index', 'inbox'), array('update' => '#content-main'))?></li>
		<li><?php echo $this->Javascript->link(__('Sent', true), array('controller' => 'messages','action'=>'index', 'sent'), array('update' => '#content-main'))?></li>
		<li><?php echo $this->Javascript->link(__('Trash', true), array('controller' => 'messages','action'=>'index', 'trash'), array('update' => '#content-main'))?></li>
		<li class="hidden"><?php echo $this->Javascript->link(__('New Folder', true), array('controller' => 'message_folders', 'action'=>'add'))?></li>
		<li class="hidden"><?php echo $this->Javascript->link(__('Manage Folders', true), array('controller' => 'message_folders', 'action'=>'index'), array('update' => '#content-main'))?></li>
	</ul>
</div>
<?php if (!empty($messageFolders)) : ?>
<div class="box">
	<h4><?php __('Folders');?></h4>
	<ul class="list-links">
	<?php foreach ($messageFolders as $folder):?>
		<li><?php echo $this->Javascript->link(__(Inflector::humanize($folder), true), array('controller' => 'messages','action'=>'index', $folder), array('update' => '#content-main')); ?></li>
	<?php endforeach;?>
	</ul>
</div>
<?php endif; ?>
<?php $this->Layout->blockEnd()?>