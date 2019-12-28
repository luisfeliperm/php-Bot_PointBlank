<?php
/**
 * Class SendPacket
 * Armazena bytes no buffer e envia pro socket
 * @author luisfeliperm <luisfelipepoint@gmail.com>
 * @copyright  Copyright (c) 2019, luisfeliperm - PointBlank Private
 */

class SendPacket
{
    /** @var object Classe controladora do socket  AuthClient|GameClient **/
    public $SocketClient; 

    protected $buffer;


    /** Write Bytes **/
    protected function int8($i) : void {
        $this->buffer .=  pack("c", $i);
    }
    protected function uInt8($i) : void {
        $this->buffer .=  pack("C", $i);
    }
    protected function int16($i) : void {
        $this->buffer .=  pack("s", $i);
    }
    protected function uInt16($i) : void {
        $this->buffer .=  pack("v", $i);
    }
    protected function int32($i) : void {
        $this->buffer .=  pack("l", $i);
    }

    /**
    * Adiciona 4 bytes e forma um inteiro unsigned
    * @param $i int valor
    * @param $endianness bool ordem dos bytes
    **/
    protected function uInt32($i,bool $endianness = false) : void {

        if ($endianness === true) {  // big-endian
            $f = "N";
        }
        else if ($endianness === false) {  // little-endian
            $f = "V";
        }
        else if ($endianness === null) {  // machine byte order
            $f = "L";
        }
        $this->buffer .=  pack($f, $i);
    }
    protected function int64($i) : void {
        $this->buffer .=  pack("q", $i);
    }

    /**
    * Adiciona 8 bytes e forma um inteiro unsigned (ulong)
    * @param $i int valor
    * @param $endianness bool ordem dos bytes
    **/
    protected function uInt64($i,bool $endianness = false) : void {
        if ($endianness === true) {  // big-endian
            $f = "J";
        }
        else if ($endianness === false) {  // little-endian
            $f = "P";
        }
        else if ($endianness === null) {  // machine byte order
            $f = "Q";
        }
        $this->buffer .=  pack($f, $i);
    }

    /**
    * Preenche o buffer com bytes zerados
    * @param $i int
    * @return void
    **/
    protected function bytePadding($i) : void {

        for($x = 0; $x < $i; $x++){
            $this->int8(0);
        }
    }

    /**
    * Escreve um array de bytes no buffer
    * @param $matrix array
    * @return void
    **/
    protected function byteArray(array $matrix) : void {
        foreach ($matrix as $value) {
            $this->int8($value);
        }
    }

    /**
    * Escreve no buffer
    * @param $str String
    * @return void
    **/
    protected function string($str) : void{
        $str = mb_convert_encoding($str, "Windows-1252", "UTF-8");
        $this->buffer .= $str;
    }


    /**
    * Envia os dados no socket
    * @return bool
    **/
    public function Send() : bool{

        // Checa se ainda está conectado antes de enviar
        if( !is_resource($this->SocketClient->socket) ){ // Socket fechado
            Logger::LightRed("Envio cancelado, Socket nulo!");
            return false;
        }

        // Debug
        {
            $opcode = unpack("s", $this->buffer, 0)[1];
            Logger::DarkYellow("---- SEND OPCODE [".$opcode."] ----");
            Logger::White("LENGHT: ".strlen($this->buffer)." BUFFER: ".rtrim(chunk_split(bin2hex($this->buffer), 2, "-"), "-"));
        }
        

        // Faz a criptografia dos dados
        $bytesEnc = $this->encripta($this->buffer, $this->SocketClient->shift);

        
        // Adiciona o Lenght do campo de dados no começo do pacote
        $LenghtDecrypt = (int)( strlen($this->buffer) - 2 | 0x8000); // ushort
        array_unshift($bytesEnc, pack("v", $LenghtDecrypt) );

        // Junta os valores do array em um só
        $fin = implode($bytesEnc);

        return socket_write($this->SocketClient->socket, $fin, strlen($fin) );
    }



    /**
    * Calcula a proxima Seed|Hash
    * @param $seed int
    * @return int
    **/
    protected function nextSeed($seed){
        $i = $seed * 214013 + 2531011 >> 16 & 0x7FFF;
        Logger::White("NextSeed ". $i);
        return $i;
    }
    
    /**
    * Esvazia buffer
    * @return void
    **/
    public function clear() : void{
        $this->buffer = null;
    }



    /**
    * Realiza criptografia dos pacotes a serem enviados ao servidor
    * @param $_buffer byte
    * @param $shift int
    * @return array
    **/
    function encripta($_buffer, $shift) : array{

        /** Cria um array de bytes  **/
        $buffer = array_values(unpack("C*", $_buffer));

        $lenght = strlen($_buffer);
        $first = $buffer[0];

        $current = null; // Byte

        for($i = 0; $i < $lenght; $i++){

            if ($i >= ($lenght - 1) ) {
                $current = $first;
            }else{
                $current = $buffer[$i + 1];
            }

            $buffer[$i] =  pack("c", ($current >> (8 - $shift) | ($buffer[$i] << $shift)));
        }

        // return matriz de bytes encryptados
        return $buffer;
    }


    // public static byte[] Encrypt(byte[] buffer, int shift)
    //     {
    //         int length = buffer.Length;
    //         byte first = buffer[0];
    //         byte current;
    //         for (int i = 0; i < length; i++)
    //         {
    //             if (i >= (length - 1))
    //                 current = first;
    //             else
    //                 current = buffer[i + 1];
    //             buffer[i] = (byte)(current >> (8 - shift) | (buffer[i] << shift));
    //         }
    //         return buffer;
    //     }
}