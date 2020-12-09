<?php
require 'vendor/autoload.php';
$from = new SendGrid\Email("Example User", "app69333408@heroku.com");
$subject = "Sending with SendGrid is Fun";
$to = new SendGrid\Email("Example User", "zolotarev.jar@gmail.com");
$content = new SendGrid\Content("text/plain", "and easy to do anywhere, even with PHP");
$mail = new SendGrid\Mail($from, $subject, $to, $content);
$apiKey = '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';
$sg = new \SendGrid($apiKey);
$response = $sg->client->mail()->send()->post($mail);
echo $response->statusCode();
print_r($response->headers());
echo $response->body();