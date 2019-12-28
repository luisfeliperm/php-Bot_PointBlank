<?php 
/**
 * Class BASE_SERVER_CHANGE_REC
 * Pacote de login - Auth PointBlank
 * @author luisfeliperm <luisfelipepoint@gmail.com>
 * @copyright  Copyright (c) 2019, luisfeliperm - PointBlank Private
 */


class BASE_SERVER_CHANGE_REC extends SendPacket
{
	function __construct($AuthClient)
	{
		/** Esvazia buffer do SendPacket, não sei se é necessario **/
		$this->clear();

		$this->SocketClient = $AuthClient;

		// Gera proxima Seed
		$this->SocketClient->seed = $this->nextSeed($this->SocketClient->seed);
	}

	function write(){
		$this->int16(2577); // Opcode
		$this->int16($this->SocketClient->seed); // Seed

	}

	
}