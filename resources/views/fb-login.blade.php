<!DOCTYPE html>
<html>
<head>
    <title>Facebook Login</title>
    <meta charset="UTF-8">
</head>
<body>
<?php
use Facebook\Facebook as Facebook;
session_start();
$fb = new Facebook([
'app_id' => env('FB_CLIENT_ID'),
'app_secret' => env('FB_CLIENT_SECRET'),
'default_graph_version' => 'v3.2',
]);

$helper = $fb->getRedirectLoginHelper();

$permissions = ['email']; // Optional permissions
$loginUrl = $helper->getLoginUrl(env('FB_REDIRECT'), $permissions);
//$loginUrl = $helper->getLoginUrl('https://wasteless.bboa14171205.nctu.me/api/user/facebook/call-back', $permissions);

echo '<a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a>';
?>
</body>
</html>
