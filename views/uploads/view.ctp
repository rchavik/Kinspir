<div class="box">
	<h4><?php echo $upload['Upload']['name']; ?></h4>
	<strong><?php echo $this->Html->link(__('Download', true), array('controller' => 'uploads', 'action' => 'view', $upload['Upload']['id'], 'download')); ?></strong>
		(<?php echo $this->Tools->formatBytes($upload['ActiveVersion']['filesize']); ?>)<br />
	Uploaded by <?php echo $this->Html->link($upload['ActiveVersion']['User']['name'], array('controller' => 'users', 'action' => 'view', $upload['ActiveVersion']['User']['id'])); ?>
		<?php echo $this->Time->timeAgoInWords($upload['ActiveVersion']['created']); ?>
	<br /><br />
	<h3>Upload Versions</h3>
	<ul>
		<?php foreach ($upload['UploadVersion'] as $version): ?>
			<li>
				(<?php echo $this->Tools->formatBytes($version['filesize']); ?>)
					Uploaded by <?php echo $this->Html->link($version['User']['name'], array('controller' => 'users', 'action' => 'view', $version['User']['id'])); ?>
					<?php echo $this->Time->timeAgoInWords($version['created']); ?>
					<?php echo $this->Html->link('Delete', array('controller' => 'uploads', 'action' => 'delete', $upload['Upload']['id'])); ?><br />
				<?php
					echo $this->Form->create('Upload', array('action' => 'edit'));
						echo $this->Form->hidden('id', array('value' => $upload['Upload']['id']));
						echo $this->Form->hidden('active_version_id', array('value' => $version['id']));
					echo $this->Form->end('Set as Active Version');
				?>
			</li>
		<?php endforeach; ?>
		</ul>
	<br />
	<strong>
		<?php echo $this->Javascript->toggle('Upload New Version', array(
			'url' => array('controller' => 'uploads', 'action' => 'edit', $upload['Upload']['id'])
		))?>
	</strong>
</div>