<?php 
/**
 * BASE_SERVER_LIST_ACK
 * Primeiro pacote. Handshake
 * @author luisfeliperm <luisfelipepoint@gmail.com>
 * @copyright  Copyright (c) 2019, luisfeliperm - PointBlank Private
 */

{
	$this->sessionId = $this->readBytes->uint32();
	long2ip($this->readBytes->uint32(true)); // IP 
	$cryptoKey = $this->readBytes->int16(); // cryptoKey

	$this->seed = $this->readBytes->uint16();
	$this->readBytes->offset += 11;
	$countServer = $this->readBytes->int32(); // Server Count


	for($i = 0; $i < $countServer; $i++){

		$GLOBALS['serverList'][] = array(
			'state' => $this->readBytes->int32(), 
			'addr' => long2ip($this->readBytes->uint32(true)),
			'port' => $this->readBytes->uint16(), 
			'type' => $this->readBytes->int8(), 
			'maxPlayers' => $this->readBytes->uint16(), 
			'lastCount' => $this->readBytes->int32(), 

		);
	}
}

{
	$this->shift = (int)($this->sessionId % 7 + 1);

	// Responde com BASE_LOGIN_REC
	$this->packetReply = "BASE_LOGIN_REC";


	Logger::Green("[INFORMAÇÕES RECEBIDAS]");
    Logger::Green("[] SessionID: " . $this->sessionId);
    Logger::Green("[] CryptoKey: " . $cryptoKey);
    Logger::Green("[] Shift: " . $this->shift);
    Logger::Green("[] Seed: " . $this->seed);
    Logger::Green("[] Server Count: " . $countServer);
}