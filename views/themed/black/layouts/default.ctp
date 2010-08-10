<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php echo $this->Facebook->html(); ?>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php __('Kinspir'); ?> <?php echo $title_for_layout; ?></title>
	<?php echo $this->element('layout/head_meta')?>
	<?php echo $scripts_for_layout; ?>
</head>
<body>
	<div id="container">
		<div id="header">
			<?php echo $this->element('layout/header')?>
		</div>
		<div id="content">
			<?php echo $notify->flash(); ?>
			<?php //echo (isset($header_for_layout) ? $this->element('layout/page_title', array('title' => $header_for_layout)) : null); ?>
			<div id="content-main">
				<?php echo $content_for_layout; ?>
			</div>
			<div id="content-side">
				<?php if ($this->Session->check('Admin')) : ?>
					<div class="box">
						<h4>Administration</h4>
						<?php echo $this->Form->create('User', array('controller' => 'users', 'action' => 'admin_view_as_user')); ?>
							<?php echo $this->Form->input('view_as_user_id'); ?>
							<div class="clearFix"></div>
						<?php echo $this->Form->end(__('Switch', true)); ?>
					</div>
				<?php endif; ?>
				<?php echo $this->element('layout/sidebar')?>
			</div>
		</div>
	</div>
	<div id="footer">
		<?php echo $this->element('layout/footer')?>
	</div>
	<?php echo $this->element('layout/notifications')?>
	<?php echo $this->element('layout/uservoice')?>
	<?php echo $this->element('layout/analytics')?>
	<?php //echo $this->element('layout/tweets')?>
	<?php echo $this->Js->writeBuffer(); ?>
	<?php echo $this->Facebook->init(); ?>
</body>
</html>