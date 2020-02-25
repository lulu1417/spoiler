<?php
session_start(); //to deal with CSRF
?>
<!DOCTYPE html>
<html>
<head>
    <title>Facebook Login</title>
    <meta charset="UTF-8">
</head>
<body>
<?php
use Facebook\Facebook as Facebook;
$fb = new Facebook([
    'app_id' => env('FB_CLIENT_ID'),
    'app_secret' => env('FB_CLIENT_SECRET'),
    'default_graph_version' => 'v3.2',
]);

$helper = $fb->getRedirectLoginHelper();

$permissions = ['email']; // Optional permissions
$loginUrl = $helper->getLoginUrl(env('FB_REDIRECT'), $permissions);

echo '<a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a>';
?>
</body>
</html>
