<?php

require_once __DIR__.'/config.php';
require_once __DIR__.'/vendor/autoload.php';
require_once(__DIR__."/lib/connect.php");
require_once(__DIR__."/lib/oauth_server.php");

$db = connect();

// Handle a request to a resource and authenticate the access token
$server = oauth_server($db);
$request = OAuth2\Request::createFromGlobals();
$token = $server->getAccessTokenData($request);
$response = new OAuth2\Response();
if (!$server->verifyResourceRequest($request, $response)) {
    $response->send();
    die;
}
$user_id = $token['user_id'];

$stmt = $db->prepare("SELECT `id`, `sLogin`, `sFirstName`, `sLastName` FROM `users` WHERE `id` = :user_id");
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetchObject();

$stmt = $db->prepare("SELECT `badge` FROM `user_badges` WHERE `idUser` = :user_id");
$stmt->execute(['user_id' => $user_id]);
$badges = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

if (false /* interroger la base castor */)
  $badges[] = 'alkindi2015_tour2';

echo json_encode([
  'id' => $user_id,
  'sLogin' => $user->sLogin,
  'sFirstName' => $user->sFirstName,
  'sLastName' => $user->sLastName,
  'badges' => $badges
]);

