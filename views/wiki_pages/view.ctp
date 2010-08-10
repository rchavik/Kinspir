<div class="wikiPages box wikiPagesView">	
	<div class="right">
		<?php echo $this->Javascript->link('Edit', array('action' => 'edit', $wikiPage['WikiPage']['id']), '#content-main', array())?>
		<?php echo $this->Html->link('Delete', array('action' => 'delete', $wikiPage['WikiPage']['id']), array(), 'Are you sure you want to delete this Wiki Page?')?>
	</div>
	<h4><?php echo $wikiPage['WikiPage']['title']?></h4>
	<div class="markItUpParsed">
		<?php echo $markdown->parse($wikiPage['WikiPage']['content'])?>
	</div>
</div>