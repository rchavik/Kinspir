<?php

App::Import('Vendor', 'markdown');
App::Import('Core', 'Sanitize');

class WikiPagesController extends AppController {

	public $helpers = array('Libs.Markdown');
	
	public function edit() {
		// redirect to index after creating a new page
		parent::edit(null, array('action' => 'index'));
		if ($this->Session->check('Milestone.id')) {
			$stacksConditions = array(
				'conditions' => array(
					'milestone_id' => $this->Session->read('Milestone.id')
				)
			);
		} else {
			$stacksConditions = array(
				'conditions' => array(
					'workspace_id' => $this->Session->read('Workspace.id')
				)
			);
		}
		$stacks = $this->WikiPage->Stack->find('list', $stacksConditions);
		$this->set(compact('stacks'));
	}

	public function index()	{
		$wikiPages = $this->WikiPage->find('list');
		if (empty($wikiPages)) {
			$this->Redirect->flash(array('no_records', 'Wiki Pages'), array('action' => 'add'));
		}
		$this->set(compact('wikiPages'));
	}

	/**
	 * This function takes in a string of markdown formatted text (via POST) and converts it to HTML using the vendor parser
	 * @param string $content (via $this->data)
	 */
	public function parse() {
		if (!empty($this->data)) {
			$content = $this->data;
			$my_html = Sanitize::stripScripts(Markdown($content));
			echo $my_html;
		}
		$this->render(false);
	}

}