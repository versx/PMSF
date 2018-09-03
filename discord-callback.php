<?php

require_once("config/config.php");
require_once("DiscordAuth.php");

try {
	if (isset($_GET['code'])) {
		$auth = new DiscordAuth();
		$auth->handleAuthorizationResponse($_GET);
		$user = json_decode($auth->get("/api/users/@me"));
		$guilds = json_decode($auth->get("/api/users/@me/guilds"));
		
		$inTrusted = inTrustedGuild($discordGuildId, $guilds);
		if (!$inTrusted) { die("You must join versx's discord https://discord.ver.sx"); }

		//TODO: Requires Bot <bot_token> for Authorization
		//$res = $auth->get("/api/guilds/342025055510855680/members/266771160253988875");
		//print_r($res);
		
		$data = print_r($user, true);
		file_put_contents('discord_users.txt', $data, FILE_APPEND);
		
		$_SESSION['user'] = $user->{'username'};

		/*TODO: Check if user has supporter role.*/
		setcookie("LoginCookie", session_id(), time()+$sessionLifetime);
	}
	header("Location: .");
} catch (Exception $e) {
	header("Location: ./discord-login");
}

function inTrustedGuild($trustedGuildId, $guilds) {
	if ($trustedGuildId == 0) { return true; }
	
	foreach ($guilds as $key=>$value) {
		if ($value->id == $trustedGuildId) { return true; }
	}
	return false;
}