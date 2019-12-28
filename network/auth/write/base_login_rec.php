<?php 
/**
 * Class BASE_LOGIN_REC
 * Pacote de login - Auth PointBlank
 * @author luisfeliperm <luisfelipepoint@gmail.com>
 * @copyright  Copyright (c) 2019, luisfeliperm - PointBlank Private
 */


class BASE_LOGIN_REC extends SendPacket
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
		$user = "luisfeliperm";
		$pass = "1234";
		$this->int16(2561); // Opcode
		$this->int16($this->SocketClient->seed); // Seed


		// Client Version
		$this->int8(1);
		$this->int16(15);
		$this->int16(42);

		$this->int8(5); // Pais

		$this->int8(strlen($user)); 
		$this->int8(strlen($pass)); 
		$this->string($user); 
		$this->string($pass); 

		$this->int8(0xba); // mac
		$this->int8(0xba); // mac
		$this->int8(0xca); // mac
		$this->int8(0x00); // mac
		$this->int8(0x00); // mac
		$this->int8(0x00); // mac
		
            
		$this->int16(0);
		$this->int8(0);
            

		$this->int8(192); // LocalIP
		$this->int8(168); // LocalIP
		$this->int8(0); // LocalIP
		$this->int8(1); // LocalIP

		$this->uint64(0); // Key

		$this->string("5E5E59C5D0E40C137D57310C4501549B"); //UserFileList

		$this->bytePadding(16);

		$this->string("4a4f18a6befa9d27e1c33c47dc617775"); //d3x

		$this->byteArray( [0x01, 0x20, 0x3C, 0x01, 0x01, 0x02, 0x75, 0x0B, 0xFF, 0x0F, 0xDE,
                0x10, 0x00, 0x00, 0x65, 0x0A, 0x00, 0x00, 0x15, 0x00, 0x15, 0x00, 0xCC, 0x03,
                0x64, 0x01, 0x00, 0x10, 0x0C, 0x00, 0x04, 0x00, 0x03] );

	}


	
}