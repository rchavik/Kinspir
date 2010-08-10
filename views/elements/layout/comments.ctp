<hr />
<?php foreach ($comments as $comment) :?>
<div class="box">
	<h4><?php echo $this->Html->link($comment['User']['name'], array('controller' => 'users', 'action' => 'view', $comment['User']['id'])); ?> : <?php echo $this->Time->timeAgoInWords($comment['created']); ?></h4>
	<?php echo $comment['body']; ?>
</div>
<?php endforeach; ?>
<?php echo $this->Javascript->toggle('New Comment', array('url' => array('controller' => 'comments', 'action' => 'add', $this->params['models'][0] => $this->params['pass'][0]), 'class' => 'right button', 'update' => 'new-comment-panel'));?>