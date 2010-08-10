<div class="box" id="uploads">
	<h4>Uploads</h4>
<?php if (empty($uploads)): ?>
	There are no uploads.
<?php else: ?>
	<ul id="uploads" class="order-list upload">
		<?php foreach ($uploads as $upload): ?>
			<li id="upload_<?php echo $upload['Upload']['id']; ?>" class="with-link-actions">
				<div class="handle">&nbsp;</div>
				<?php if (!empty($upload['comment_count'])) : ?>
					<div class="comment-icon">
						<?php echo $this->Javascript->toggle(null, array('url' => array('controller' => 'comments', 'action' => 'add', 'Upload' => $upload['Upload']['id']), 'class' => 'ui-icon ui-icon-comment', 'title' => 'Comment', 'update' => '#comment-upload-panel' . $upload['Upload']['id'], 'div' => false)); ?>
					</div>
				<?php endif; ?>
				<div class="hidden-link-actions right">
					<?php if (!empty($upload['Upload']['description'])) : ?>
						<?php echo $this->Javascript->toggle(null, array('title' => 'Show Description', 'class' => 'ui-icon ui-icon-folder-open', 'update' => '#upload-description' . $upload['Upload']['id'], 'div' => false));?>
					<?php endif; ?>
					<?php echo $this->Javascript->toggle(null, array('url' => array('controller' => 'uploads', 'action' => 'edit', $upload['Upload']['id']), 'class' => 'ui-icon ui-icon-pencil', 'title' => 'Edit this upload', 'update' => '#edit-upload-panel' . $upload['Upload']['id'], 'div' => false)); ?>
					<?php echo $this->Html->link(null, array('controller' => 'uploads', 'action' => 'delete', $upload['Upload']['id']), array('class' => 'ui-icon ui-icon-trash', 'title' => 'Delete this upload'), sprintf(__('Are you sure you want to delete %s?', true), $upload['Upload']['name'])); ?>
				</div>
				<strong style="margin-left:0"><?php echo $this->Html->link($upload['Upload']['name'], array('controller' => 'uploads', 'action' => 'view', $upload['Upload']['id'])); ?></strong>
				<?php if (!empty($upload['User'])) : ?>
					<div class="uploaded-by">
						Uploaded by <?php echo $this->Html->link($upload['User']['name'], array('controller' => 'users', 'action' => 'view', $upload['User']['id'])); ?>
					</div>
				<?php endif; ?>
				<?php if (!empty($upload['Upload']['description'])) : ?>
					<div id="upload-description<?php echo $upload['Upload']['id']; ?>" class="item-description">
						<?php echo $upload['Upload']['description']; ?>
					</div>
				<?php endif; ?>
			<div id="edit-upload-panel<?php echo $upload['Upload']['id']; ?>" class="loading"></div>
				<div id="comment-upload-panel<?php echo $upload['Upload']['id']; ?>" class="loading"></div>
			</li>
		<?php endforeach; ?>
	</ul>
<?php endif;?>
</div>
<?php echo $this->element('layout/pagination'); ?>