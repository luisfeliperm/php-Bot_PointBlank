<?php 
/**
 * server_message_disconnect_ack
 * pacote de desconexão
 * @author luisfeliperm <luisfelipepoint@gmail.com>
 * @copyright  Copyright (c) 2019, luisfeliperm - PointBlank Private
 */

{
	$this->readBytes->int32();
	(int) $erro = $this->readBytes->uint32();
	(bool) $useHack = $this->readBytes->int8();
}

{

	Logger::LightRed("[DESCONECTADO] Erro: ". $erro . " UseHack? " ($useHack) );

	$this->close();
}