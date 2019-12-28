<?php
cli_set_process_title("PHP PointBlank Bot Client - by Luisfeliperm");
error_reporting(E_ALL);
const _DIR_ROOT = __DIR__."/";

require("class/logger.php");
require("class/saveLog.php");
require("class/ServerList.php");
require("class/_readpacket.php");
require("class/SendPacket.php");
require("network/auth/AuthClient.php");

/* Include Packets to Send AUTH **/
require(_DIR_ROOT."/network/auth/write/base_login_rec.php");
require(_DIR_ROOT."/network/auth/write/base_server_change_rec.php");



// Inicia processo de comunicação com o AUTH
$AuthClient = new AuthClient();

while ($AuthClient->status == 0) {
	

	$AuthClient->receive();

	if($AuthClient->packetReply === null) break;

	$resposta = new $AuthClient->packetReply($AuthClient);

	$resposta->write();

	if (!$resposta->send()) {
		Logger::LightRed("Falha ao enviar dados no socket");
		break;
	}
	
}
if($AuthClient->status == 2) {
	Logger::LightRed("----- [Aplicação fechada!] -----");
	exit();
}

// Bora pro game hehehe
if (!isset($serverList)) {
	Logger::Green("[Atenção] GameClient não iniciado!");
	exit();
}

Logger::DarkYellow("----- [SERVER LIST] -----");



foreach ($GLOBALS['serverList'] as $key => $value) {
	Logger::White("[$key] ".$value['addr'].":".$value['port']. " Players ".$value['lastCount']."/".$value['maxPlayers'] );
}
Logger::White("Iniciando socket no server #1");
sleep(0);






/****************** GAME *****************/


require("network/game/GameClient.php");

/* Include Packets to Send GAME **/
require(_DIR_ROOT."/network/game/write/base_user_enter_rec.php");


// Inicia processo de comunicação com o AUTH
$GameClient = new GameClient();

while ($GameClient->status == 0) {
	

	$GameClient->receive();

	if($GameClient->packetReply === null) break;

	$resposta = new $GameClient->packetReply($GameClient);

	$resposta->write();

	if (!$resposta->send()) {
		Logger::LightRed("Falha ao enviar dados no socket");
		break;
	}
	
}


?>