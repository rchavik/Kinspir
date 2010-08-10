<?php
class TwitterfollowShell extends Shell {
// Twitter user, password and screenname
private $user=’userIDforTwitterAccount’;
private $pass=’passwordForTwitterAccount’;
private $screen_name = “screennameForTwitterAccount”;function main() {
// $term contains the search words to find in recent tweets
$term = “twitter+OR+follow+OR+tweet”;

// Get followers
$cursor = -1;
$followed = array();
do {
$apiUrl = “http://api.twitter.com/1/statuses/friends/”.$this->screen_name.”.json?cursor=”.$cursor;
$apiresponse = $this->callTwitter($apiUrl);
if ($apiresponse) {
echo $cursor.”\n”;
$json = json_decode($apiresponse);

$cursor = $json->next_cursor_str;
if ($json != null) {
foreach ($json->users as $user) {
$followed[] = strtolower($user->screen_name);
}
}
}
} while ($apiresponse && $cursor);

// Get blocked users and add them to the followed array
//        $apiUrl = “http://api.twitter.com/1/blocks/blocking/ids.json”;
$blockedUsers = 0;
$page = 1;
do {
$apiUrl = “http://api.twitter.com/1/blocks/blocking.json?page=”.$page;
$apiresponse = $this->callTwitter($apiUrl);
if ($apiresponse) {
$json = json_decode($apiresponse);
if ($json != null) {
foreach ($json as $user) {
$followed[] = strtolower($user->screen_name);
$blockedUsers++;
}
}
if (count($json) == 20) {
$page++;
} else {
$page = 0;
}
}
} while ($apiresponse && $page);

// search some keywords !
$apiUrl = “http://search.twitter.com/search.json?q=” . $term . “&rpp=100″;
$apiresponse = $this->callTwitter($apiUrl);

if ($apiresponse) {
$results = json_decode($apiresponse);
$count = 20;

if ($results != null) {
$resultsArr = $results->results;
if (is_array($resultsArr)) {
foreach ($resultsArr as $result) {
$from_user = strtolower($result->from_user);
if (!in_array($from_user,$followed) ) {
$apiUrl = “http://twitter.com/friendships/create/” . $from_user. “.json”;
$apiresponse = $this->callTwitter($apiUrl,true,”follow=true”);
if ($apiresponse) {
$response = json_decode($apiresponse);
if ($response != null) {
if (property_exists($response,”following”)) {
if ($response->following === true) {
echo “Now following ” . $response->screen_name . “\n”;
$followed[] = strtolower($response->screen_name);
} else {
echo “Couldn’t follow ” . $response->screen_name . “\n”;
}
} else {
echo “Follow limit exceeded, skipped ” . $result->from_user.”(“.$result->from_user_id . “)\n”;
exit;
}
}
}
} else {
// Already following
echo ‘.’;
}
}
}
}
}
}

function callTwitter($apiUrl, $post=false, $postFields = null) {
$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_USERPWD, $this->user.”:”.$this->pass);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
if ($post) {
curl_setopt($ch, CURLOPT_POST, 1);
}
if ($postFields) {
curl_setopt($ch, CURLOPT_POSTFIELDS,$postFields);
}
$apiresponse = curl_exec($ch);
curl_close($ch);
return $apiresponse;
}
}
?>