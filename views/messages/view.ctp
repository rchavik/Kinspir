<?php foreach ($messages as $message) :?>
	<div class="box">
		<h4><?php echo $message['Message']['title']; ?> from <?php echo $message['User']['name']; ?> sent <?php echo $this->Time->timeAgoInWords($message['Message']['created']); ?></h4>
		<?php echo $message['Message']['body']; ?>
	</div>
	
<?php endforeach; ?>
<?php
$this->Js->buffer(
	$this->Js->request(
		$this->Html->url(array('controller' => 'message', 'action' => 'reply', $message['Message']['id']), true),
		array(
			'update' => '#reply-panel',
			'complete' => 'afterLoad("Messages");'
		)
	)
);
?>

<div id="reply-panel"><?php echo $this->Html->image('spinner.gif');?></div>
