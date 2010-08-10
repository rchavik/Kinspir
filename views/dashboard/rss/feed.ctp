<?php
Configure::write('debug', 0);

$this->set('channel', array(
	'title' => "{$CurrentProject->name}/Feed",
	'link' => $rssFeed
));

foreach ($feed as $data) {
	$type = $data['Timeline']['model'];

	if (empty($data[$type])) {
		continue;
	}

	switch ($type) {
		case 'Commit':
			$title = "{$type}/" . $data[$type]['revision']; //$chaw->commit($commit['Commit']['revision'], $commit['Project'])
			if (!empty($data['Commit']['branch'])) {
				$title = "Branches/" . $data['Commit']['branch'] . "/" . $title;
			}
			$link = array('controller' => 'commits', 'action' => 'view', $data[$type]['revision']);
			$pubDate = $data[$type]['created'];
			$description = $data[$type]['message'];
			$author = !empty($data['User']['username']) ? $data['User']['username'] : $data['Commit']['author'];
		break;
	}

	if (!empty($data['Project'])) {
		$link = $chaw->url($data['Project'], $link);
		$title = $data['Project']['name'].'/' . $title;
	}

	$pubDate = date('r', strtotime($pubDate));

	echo $rss->item(null, compact('title', 'link', 'pubDate', 'description', 'author'));
}