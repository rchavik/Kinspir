<div class="box">
	<h4>Notifications</h4>
	<?php if (empty($notifications)) { echo 'No recent activity'; } ?>
	<?php foreach ($notifications as $notification) : ?>
		<p>
			<?php
				$not_read = $notification['Notification']['is_read'] != 1;
				if ($not_read) {
					echo '<strong>';
				}
				
				echo $this->Tools->insertVars(
					$notification['NotificationType']['text'],
					array(
						$this->Html->link($notification['User']['name'], array('controller' => 'users', 'action' => 'view', $notification['User']['id'])),
						$this->Html->link($notification[$notification['Notification']['class']]['display_field'], array('controller' => Inflector::underscore(Inflector::tableize($notification['Notification']['class'])), 'action' => 'view', $notification[$notification['Notification']['class']]['id'])),
						$time->timeAgoInWords($notification['Notification']['created'])
					)
				);
				
				if ($not_read) {
					echo '</strong>';
				}
			 ?>
		</p>
	<?php endforeach; ?>
</div>
<?php echo $this->element('layout/pagination')?>
