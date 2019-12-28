<?php 
/**
 * BASE_USER_ENTER_PAK
 * pacote de resposta
 * @author luisfeliperm <luisfelipepoint@gmail.com>
 * @copyright  Copyright (c) 2019, luisfeliperm - PointBlank Private
 */

{
	(int) $erro = $this->readBytes->uint32();
}

{
	if($erro > 0){
		Logger::LightRed("[ENTER ERRO] Erro: ". $erro );
		$this->close();
	}else{
		Logger::Green("[ENTER GAME SUCESS]");	
	}
	

}