<div id="wiki" class="loading" style="display: block">
	<h4><?php __('Wiki Pages');?></h4>
	<?php echo $this->Javascript->load(array('action' => 'view', array_shift(array_keys($wikiPages))), '#wiki')?>
</div>

<?php echo $this->Javascript->toggle('New Wiki Page', array(
	'url' => array('controller' => 'wiki_pages', 'action' => 'add'), 'class' => 'button'
))?>

<?php $this->Layout->blockStart('sidebar')?>
<div class="box">
	<h4>Pages</h4>
	<ul class="list-links">
	<?php foreach($wikiPages as $id => $name):?>
		<li><?php echo $this->Javascript->link($name, array('action' => 'view', $id), '#content-main')?></li>
	<?php endforeach;?>
	</ul>
</div>
<?php $this->Layout->blockEnd();?>