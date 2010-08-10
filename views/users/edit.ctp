<div class="users form">
	<h4><?php __('Account Settings'); ?></h4>
	<?php echo $this->Form->create('User');?>
			<fieldset>
				<?php
					echo $this->Form->input('id');
					echo $this->Form->input('name', array('label' => 'Your First and Last Name'));
					echo $form->hidden('username');
					echo $form->input('username', array(
						'disabled' => (!empty($this->data['User']['username']) ? true : false),
						'label'=> __('Nickname (https://www.kinspir.com/Nickname)',true)
					));
					//echo $this->Form->input('username', array('label' => 'Nickname'));
					//echo $this->Form->input('email');
					echo $timeZone->select('timezone', 'Time Zone:');
					echo $this->Form->input('EmailSettings', array('label' => 'Don\'t receive a notification email', 'type' => 'select', 'multiple' => 'checkbox'));
				?>
				<div class="clearFix"></div>
				<?php
					echo $this->Form->submit('Save');
				?>
			</fieldset>
 		<h4><?php __('Ssh Keys');?></h4>
	<fieldset>
		<?php

			foreach ((array)$sshKeys as $type => $keys) {
				$spans = $fields = null;

				foreach ((array)$keys as $i => $sshKey) {

					$fields = $form->checkbox("Key.{$type}.{$i}.chosen", array(
						'value' => 1,
					));

					$fields .= $form->text("Key.{$type}.{$i}.content", array(
						'value' => $sshKey,
						'disabled' => false,
						'class' => 'text'
					));

					$spans .= $html->tag('span', $fields, array('class' => 'checkbox'));
				}
				$legend = $html->tag('legend', $type);

				if ($fields !== null) {
					echo $html->tag('fieldset',
						$legend .
						$html->tag('div', $spans, array('class' => 'checkbox')) .
						$form->submit(__('delete',true))
					);
				}
			}
		?>

		<fieldset>
	 		<legend><?php __('New');?></legend>
			<?php
				echo $form->input('SshKey.type', array(
					'label' => false,
					'after' => $form->text('SshKey.content', array('type' => 'text', 'class' => 'text'))
				));
			?>
		</fieldset>
	<?php echo $this->Form->end(__('Add', true));?>
	<br />
	<h4><?php __('Facebook Settings'); ?></h4>
	<div class="form">
		<div class="input">
			<?php echo $this->Facebook->login(); ?>
		</div>
	</div>
	<div class="clearFix">&nbsp;</div>
</div>