<?php
class WidgetsController extends AppController {

	public function element($element = null) {
		$this->render('/elements/widgets/' . $element);
	}

}