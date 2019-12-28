<?php 
/**
 * 
 */
class Logger
{
	public static function White(string $str) : void {
		echo $str.PHP_EOL;
	}

	public static function DarkYellow(string $str) : void {
		echo "\e[33m".$str."\e[0m".PHP_EOL;
	}

	public static function Green(string $str) : void {
		echo "\e[32m".$str."\e[0m".PHP_EOL;
	}

	public static function LightRed(string $str) : void {
		echo "\e[31m".$str."\e[0m".PHP_EOL;
	}
}