<?php 
/**
 * Class BASE_USER_ENTER_REC
 * Pacote de entrada - Game PointBlank
 * @author luisfeliperm <luisfelipepoint@gmail.com>
 * @copyright  Copyright (c) 2019, luisfeliperm - PointBlank Private
 */


class BASE_USER_ENTER_REC extends SendPacket
{
	function __construct($GameClient)
	{
		/** Esvazia buffer do SendPacket, não sei se é necessario **/
		$this->clear();

		$this->SocketClient = $GameClient;

		// Gera proxima Seed
		$this->SocketClient->seed = $this->nextSeed($this->SocketClient->seed);
	}


	function write(){
		$user = "luisfeliperm";
		$pass = "1234";
		$this->int16(2579); // Opcode
		$this->int16($this->SocketClient->seed); // Seed


		// Client Version
		$this->int8(strlen($user));
		$this->string($user);
		$this->int64(5); // pId

		$this->int8(0); // isRealIp
		$this->int32(ip2long("192.168.0.2")); // localIp


	}


	
}