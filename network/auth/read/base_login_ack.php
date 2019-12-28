<?php 
/**
 * base_login_ack
 * pacote de resposta de login
 * @author luisfeliperm <luisfelipepoint@gmail.com>
 * @copyright  Copyright (c) 2019, luisfeliperm - PointBlank Private
 */

{
	(int) $result = $this->readBytes->uint32();
	$this->readBytes->uint8();
	(int) $playerId = $this->readBytes->int64();

	(string) $login = $this->readBytes->string( (int)$this->readBytes->int8()  );
}

{
	$msg = null;
    switch ($result)
    {
        case 0:
            break;
        case 0x80000101:
            $msg = "Conta já esta online!";
            break;
        case 0x80000118:
            $msg = "Senha invalida!";
            break;
        case 0x80000107:
            $msg = "Voce esta banido!";
            break;
        case 0x80000117:
            $msg = "Username invalido!";
            break;
        case 0x80000126:
        case 0x80000121:
            $msg = "Regiao bloqueada";
            break;
        case 0x80000119:
        case 0x80000102:
            $msg = "Deleted Account";
            break;
        case 0x80000127:
            $msg = "Usuario ou senha incorretos";
            break;
        case 0x80000133:
            $msg = "Hardware bloqueado";
            break;
        default:
            $msg = "Error: [" . $result . "] ";
            break;
    }

    if ($result != 0) {
    	Logger::LightRed("[LOGIN] Erro ao logar! PlayerId: ". $playerId. " Status: ". $msg);
    	$this->close(true);
    	
    }else{
    	Logger::Green("[LOGIN] Sucess ! PlayerId: ".$playerId);
        $this->packetReply = "BASE_SERVER_CHANGE_REC";
    }
	
	

	
}