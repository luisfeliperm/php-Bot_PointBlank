<?php 
/**
 * 
 */
class ReadPacket
{
	public $offset = 0;
	public $buffer;

	public function int8() : int {
        $x = unpack("c", $this->buffer, $this->offset)[1];
        $this->offset += 1;
        return $x; 
    }
    public function uint8() : int {
        $x = unpack("C", $this->buffer, $this->offset)[1];
        $this->offset += 1;
        return $x; 
    }
    public function int16() : int {
        $x = unpack("s", $this->buffer, $this->offset)[1];
        $this->offset += 2;
        return $x; 
    }
    public function uint16() : int {
        $x = unpack("v", $this->buffer, $this->offset)[1];
        $this->offset += 2;
        return $x; 
    }
    public function int32() : int {
        $x = unpack("l", $this->buffer, $this->offset)[1];
        $this->offset += 4;
        return $x; 
    }
    /**
    * Lê 4 bytes e forma um inteiro unsigned
    * @param $endianness bool ordem dos bytes
    * @return int
    **/
    public function uint32(bool $endianness = false) : int {
        if ($endianness === true) {  // big-endian
            $f = "N";
        }
        else if ($endianness === false) {  // little-endian
            $f = "V";
        }
        else if ($endianness === null) {  // machine byte order
            $f = "L";
        }


        $x = unpack($f, $this->buffer, $this->offset)[1];
        $this->offset += 4;
        return $x; 
    }

    public function int64() : int {
        $x = unpack("q", $this->buffer, $this->offset)[1];
        $this->offset += 4;
        return $x; 
    }

    /**
    * Lê 8 bytes e forma um inteiro unsigned (ulong)
    * @param $endianness bool ordem dos bytes
    * @return int
    **/
    public function uint64(bool $endianness = false) : int {
        
        if ($endianness === true) {  // big-endian
            $f = "J";
        }
        else if ($endianness === false) {  // little-endian
            $f = "P";
        }
        else if ($endianness === null) {  // machine byte order
            $f = "Q";
        }


        $x = unpack($f, $this->buffer, $this->offset)[1];
        $this->offset += 8;
        return $x;

    }



    /**
    * Le os bytes e codifica no formato 1252 (Padrão do servidor do PointBlank)
    * @param $size int quantidade de bytes a serem lidos
    * @return string
    **/
    public function string($size) : string {

        $str = null;

        $z = unpack("c*", $this->buffer, $this->offset);
        for($i = 1; $i <= $size; $i++){
            $str .= chr($z[$i]);
        }

        return $str = mb_convert_encoding($str, "Windows-1252", "UTF-8");; 
    }

    function reset() : void {
        $this->offset = 0;
        $this->buffer = null;
    }
}