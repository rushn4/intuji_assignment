<?php

require 'include.main.files.php';
$redirectUri = 'http://localhost/intuji_assignment/google_oauth.php';


$client = new Google_Client();
$client->setAuthConfig('./credentials.json');
$client->addScope(Google_Service_Calendar::CALENDAR);
$client->setRedirectUri($redirectUri);
$client->setAccessType('offline');
$client->setPrompt('select_account consent');

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $_SESSION['access_token'] = $token;
    header('Location: ' . filter_var($client->getRedirectUri(), FILTER_SANITIZE_URL));
    exit();
}

if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    $client->setAccessToken($_SESSION['access_token']);
    if ($client->isAccessTokenExpired()) {
        $refreshToken = $client->getRefreshToken();
        $client->fetchAccessTokenWithRefreshToken($refreshToken);
        $_SESSION['access_token'] = $client->getAccessToken();
    }
    header('Location: events.php');
    exit();
} else {
    $authUrl = $client->createAuthUrl();
    header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
    exit();
}

