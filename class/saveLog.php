<?php
/**
 * Class SaveLog
 * Salva os logs do sistema em niveis separados e faz rotacionamento dos arquivos
 * @author luisfeliperm <luisfelipepoint@gmail.com>
 * @copyright  Copyright (c) 2019, luisfeliperm - PointBlank Private
 */

class SaveLog 
{
	/**
	* Escreve no arquivo de log
	* @param $msg string Mensagem de log
	* @param $nome string Nome do arquivo
	* @return void
	*/
	private static function save($nome, $msg) : void
	{

		$file = _DIR_ROOT."logs/".$nome.".log";

		if ( file_exists($file) &&  ( (filesize($file) /1048576)  > 2) )  {
			rename($file, _DIR_ROOT."logs/".$nome."_".date("Ymdhi").".log.old");
		}

		$log=fopen($file, "a+");
	    fputs($log, date("Y-m-d h:i:s")." ".$msg.PHP_EOL);
	    fclose($log);

	}

	public static function info($msg) : void{
		self::save("info", $msg);
	}
	public static function warning($msg) : void{
		self::save("warning", $msg);
	}
	public static function error($msg) : void{
		self::save("error", $msg);
	}
	public static function fatal($msg) : void{
		self::save("fatal", $msg);
	}
}
