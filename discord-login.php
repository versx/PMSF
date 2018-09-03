<?php
include('config/config.php');

require_once "vendor/autoload.php";
require_once("DiscordAuth.php");

if ($discordLogin) {
	$auth = new DiscordAuth();
	$auth->gotoDiscord();
} else {
	header("Location: .");
}