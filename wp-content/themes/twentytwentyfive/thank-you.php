<?php
/**
* Template Name: Thank You Page
* Template Post Type: page
*/

// Maybe also have the "nonce" parameter sent here as a GET parameter and double-check the value with the value sent from the actual form. If they are equal, the response is VALID. This will act as a "CSRF" token here as well.
header('Content-Type: application/json');
die(json_encode(['success'=>true]));