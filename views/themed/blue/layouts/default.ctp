<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $html->charset(); ?>
	<title>
		<?php __('kĭn-spīr\' - Project Management System:'); ?>
		<?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $html->meta('icon');
		echo $html->css('themed/blue/admin.css');
		echo $html->script('default/behavior', true);

		echo $html->script('prototype');
		echo $html->script('scriptaculous.js?load=effects');

		echo $html->script('modalbox');
		echo $html->css('modalbox');

		echo $html->script('cakemodalbox');
		echo $scripts_for_layout;
	?>

<script type="text/javascript"> 
	document.onclick=check; 
	function check(e){ 
		var target = (e && e.target) || (event && event.srcElement); 
		var obj = document.getElementById('view'); 
		if(target!=obj){obj.style.display='none'} 
	} 
</script> 
</head>
<!-- END html head -->

<body onClick"">
	<!-- start wrapper -->
	<div id="wrapper">
		<!-- start head -->
		<div id="head">
				<!-- start logo and user details -->
				<div id="logo_user_details">
					<h1 id="logo"><?php echo $html->link('kInspire Project Management', array('controller'=> 'users', 'action'=>'dashboard')); ?></h1>
					<?php if($session->check('Auth.User')) : ?>
						<!-- start user details -->
						<div id="user_details">
							<ul id="user_details_menu">
								<li>Welcome <strong><?php echo User::get('name'); ?></strong></li>
								
								<li>
									<ul id="user_access">
										<li class="first"><?php echo $html->link('Settings', array('controller'=>'users', 'action'=>'edit')); ?></li>
										<li class="last"><?php echo $html->link('Log out', array('controller'=> 'users', 'action'=>'logout')); ?></li>
									</ul>
								</li>
								<li><?php echo $html->link('My Messages (0)', array('controller'=> 'messages', 'action'=>'index'), array('class'=>'new_messages')); ?></li>
							</ul>
							<div id="server_details">
								<dl>
									<dt>Last login :</dt>
									<dd><?php echo User::get('last_ip'); ?></dd>
								</dl>
								<dl>
									<dt>@ </dt>
									<dd><?php echo $time->nice(User::get('last_login')); ?></dd>
								</dl>
							</div>
							<!-- start search -->
							<div id="search_wrapper">
								<form accept-charset="UNKNOWN" enctype="application/x-www-form-urlencoded" method="get">
								<div id="searchbox" style="position:relative;">
									<fieldset>
										<label>
											<input id="query" maxlength="2147483647" name="query" size="20" class="text" type="text" />
										</label>
									</fieldset>
									<div id="loading" style="display: none; position:absolute; background: #fff; margin-top: 5px;"><?php echo $html->image("spinner.gif"); ?></div>
									<?php
									$options = array(
										'update' => 'view',
										'url'    => '/searches/livesearch',
										'frequency' => 1,
										'loading' => "Element.hide('view');Element.show('loading')",
										'complete' => "Element.hide('loading');Effect.Appear('view')"
									);
									print $ajax->observeField('query', $options);
									?>
									<div id="view" style="position:absolute; background: #fff; margin-top: 5px;"><!-- Results will load here --></div>
								</div>
								</form>
								<ul id="search_wrapper_menu">
									<li class="first">Live Search</li>
									<li class="last"><a href="#">Advanced Search</a></li>
								</ul>
							</div>
							<!-- end search -->
						</div>
						
						<!-- end user details -->
					<?php endif; ?>
				</div>
			
			<!-- end logo end user details -->
			
			<?php if($session->check('Auth.User')) : ?>
				<!-- start menus_wrapper -->
				<div id="menus_wrapper">
					<div id="main_menu">
						<ul>

						</ul>
					</div>
					<?php if ($session->check('Menu.submenu')) : ?>
						<div id="sec_menu">
							<ul>

							</ul>
						</div>
					<?php endif; ?>
				</div>
				<!-- end menus_wrapper -->
			<?php endif; ?>
			
		</div>
		<!-- end head -->
		
		<!-- start content -->
		<div id="content">
			
			<!-- begin content -->
			<!-- start page -->
			<div id="page">
				<div class="inner">						
					
					<?php if ($session->check('Message.flash')): ?>
						<ul class="system_messages">
							<?php echo $session->flash(); ?>
						</ul><br>
					<?php endif; ?>
					
					<?php echo $content_for_layout;?>
				</div>
			</div>
			<!-- end page -->

			<?php if($session->check('Auth.User')) : ?>
				<!-- start sidebar -->
				<div id="sidebar">
					<div class="inner">

					</div>
				</div>
				<!-- end sidebar -->
			<?php endif; ?>
			
		</div>
		<!-- end content -->
		
	</div>
	<!-- end wrapper -->
	
	<!-- start footer -->
	<div id="footer">
		<div id="footer_inner">
		
		<dl class="copy">
			<dt><strong>kInspire Project Management</strong> <em>version 1.0</em></dt>
			<dd>&copy; 2009 exqSoft Solutions LLC.</dd>
		</dl>
		
		<!--
		<dl class="help_links">
			<dt><strong>Need Help ?</strong></dt>
			<dd>
				<ul>
					<li>
						Help
					</li>
					<li class="last"><a href="http://www.kinspir.com" target="_blank">kinspir.com</a></li>
				</ul>
			</dd>
		</dl>
		-->
		</div>
	</div>
	<!-- end footer -->

<script type="text/javascript">
var uservoiceOptions = {
  /* required */
  key: 'kinspir',
  host: 'kinspir.uservoice.com', 
  forum: '41358',
  showTab: true,  
  /* optional */
  alignment: 'right',
  background_color:'#f00', 
  text_color: 'white',
  hover_color: '#06C',
  lang: 'en'
};

function _loadUserVoice() {
  var s = document.createElement('script');
  s.setAttribute('type', 'text/javascript');
  s.setAttribute('src', ("https:" == document.location.protocol ? "https://" : "http://") + "cdn.uservoice.com/javascripts/widgets/tab.js");
  document.getElementsByTagName('head')[0].appendChild(s);
}
_loadSuper = window.onload;
window.onload = (typeof window.onload != 'function') ? _loadUserVoice : function() { _loadSuper(); _loadUserVoice(); };
</script>
</body>
</html>
