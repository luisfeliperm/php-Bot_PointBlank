<?php 
/**
 * Class AuthClient
 * Socket Sincrono de autenticação com o servidor Auth - PointBlank
 * @author luisfeliperm <luisfelipepoint@gmail.com>
 * @copyright  Copyright (c) 2019, luisfeliperm - PointBlank Private
 */

class AuthClient 
{
	/** @ int 0 = running | 1 complete | 2 exit aplication **/
	public $status = 0;

	/** @var int total de pacotes recebidos **/
	public $totalReceive = 0;

	/** @var resource Socket client **/
	public $socket;

	/** @var int Hash **/
	public $seed;

	/** @var ID de sessão **/
	public $sessionId;

	/** @var int deslocamento de bit **/
	public $shift;

	/** @var object Guarda o buffer e faz a leitura dos bytes **/
	private $readBytes;

	/** @var string Nome do pacote que será enviado **/
	public $packetReply = null;

	function __construct()
	{
		$this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		if ($this->socket === false) {
		    echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . PHP_EOL;
		    return;
		}
		$result = socket_connect($this->socket, "10.10.10.101", 39190);
		if ($result === false) {
		    echo "socket_connect() failed.".PHP_EOL."Reason: ($result) " . socket_strerror(socket_last_error($this->socket)) .PHP_EOL;
		    return;
		}

		$this->readBytes = new ReadPacket();
	}


	/* 	Function Receive 
	*	Recebe os dados e chama o metodo de leitura
	*/
	function Receive()
	{
		$this->packetReply = null;
		$buffer = null;


		while (($this->status == 0) && $buffer = socket_read($this->socket, 2048)) {

			if($this->socket === null){
				echo "[AuthClient] Socket Null!!". PHP_EOL;
				return;
			}

			$this->totalReceive += 1;

			Logger::DarkYellow("---- Packet Receive [".$this->totalReceive."] -----");

			$this->read($buffer);
			$this->readBytes->reset();

			// Se houver resposta, sai do loop
			if ($this->packetReply !== null) {
				break;
			}

		}
	}
	
	/** 	
	* Function read 
	* Faz a leitura do header e em seguida o payload
	* @param $buffer bytes
	* @return void
	*/
	function read($buffer) : void{

		$this->readBytes->buffer = $buffer;

		$payloadLenght = (int)($this->readBytes->int16() + 4);
		$opcode = $this->readBytes->int16();

		Logger::White("[".$opcode."] Buffer: ".rtrim(chunk_split(bin2hex($buffer), 2, "-"), "-"));
		

		$packet = null;

		switch ($opcode) {
			case 2049:
				$packet = "base_server_list_ack";
				break;
			
			case 2062:
				$packet = "server_message_disconnect_ack";
				break;

			case 2564:
                $packet = "base_login_ack";
				break;
			case 2578:
				$packet = "base_server_change_pak";
				break;
			default:
				Logger::LightRed("Opcode não encontrado!");
				return;
		}

		require(_DIR_ROOT. "/network/auth/read/".$packet.".php");
	}

	/**
	* Fecha o socket
	**/
	function close(bool $exit = false){
		if($exit)
			$this->status = 2;
		else
			$this->status = 1;

		socket_close($this->socket);
	}
}