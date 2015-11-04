<?php
/**
* @version 1.0.0
* @package RSSEO! 1.0.0
* @copyright (C) 2009-2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

class RSSeoGoogle
{
	/*
	* The URL
	*/
	public $url = null;
	
	
	public function __construct($url) {
		$this->url = $url;
	}
	
	public function prank() {
		$reqgr = "info:" . $this->url;
		$reqgre = "info:" . urlencode($this->url);
		$mGoogleCH = $this->googleCH($this->strord($reqgr) );
		$mGoogleCH = "6" . $this->googleNewCh($mGoogleCH);
		$link = "http://toolbarqueries.google.com/tbr?client=navclient-auto&ch=".$mGoogleCH."&ie=UTF-8&oe=UTF-8&features=Rank&q=".$reqgre;
		$contents = rsseoHelper::fopen($link,0);
		
		$contents = explode(':',$contents);
		if (isset($contents[2])) return $contents[2]; else return -1;
	}
	
	public function check() {
		$reqgr = "info:" . $this->url;
		$reqgre = "info:" . urlencode($this->url);
		$mGoogleCH = $this->googleCH($this->strord($reqgr) );
		$mGoogleCH = "6" . $this->googleNewCh($mGoogleCH);
		$link = "http://toolbarqueries.google.com/tbr?client=navclient-auto&ch=".$mGoogleCH."&ie=UTF-8&oe=UTF-8&features=Rank&q=".$reqgre;
		$contents = rsseoHelper::fopen($link,1);
		$pagerank = explode(':',$contents);
		
		if (isset($pagerank[2]))
		{
			$return = trim($pagerank[2]);
			if ($return == '0')
				return $return;
			else 
				$return = (int) $return;
			
			if ($return) return true;
				else return $contents;
		} else return $contents;
	}
	
	protected function googleCH($url) {
		$init = 0xE6359A60;	
		$length = count($url);
		$a = 0x9E3779B9;
		$b = 0x9E3779B9;
		$c = 0xE6359A60;
		$k = 0;
		$len = $length;
		$mixo = array(); 
		
		while( $len >= 12 ) {
			$a += ($url[$k+0] +($url[$k+1]<<8) +($url[$k+2]<<16) +($url[$k+3]<<24));
			$b += ($url[$k+4] +($url[$k+5]<<8) +($url[$k+6]<<16) +($url[$k+7]<<24));
			$c += ($url[$k+8] +($url[$k+9]<<8) +($url[$k+10]<<16)+($url[$k+11]<<24));
			$mixo = $this->mix($a,$b,$c);
			
			$a = $mixo[0]; $b = $mixo[1]; $c = $mixo[2];
			$k += 12;
			$len -= 12;
		}
		
		$c += $length;
		
		switch( $len ) {
			case 11:
			$c += $url[$k+10]<<24;
			
			case 10: 
			$c+=$url[$k+9]<<16;
			
			case 9 : 
			$c+=$url[$k+8]<<8;
			
			case 8 : 
			$b+=($url[$k+7]<<24);
			
			case 7 : 
			$b+=($url[$k+6]<<16);
			
			case 6 : 
			$b+=($url[$k+5]<<8);
			
			case 5 : 
			$b+=($url[$k+4]);
			
			case 4 : 
			$a+=($url[$k+3]<<24);
			
			case 3 : 
			$a+=($url[$k+2]<<16);
			
			case 2 : 
			$a+=($url[$k+1]<<8);
			
			case 1 : 
			$a+=($url[$k+0]);
		}
		
		$mixo = $this->mix( $a, $b, $c );
		
		if( $mixo[2] < 0 )
			return ( 0x100000000 + $mixo[2] );
		else
			return $mixo[2];
	}

	protected function mix( $a, $b, $c ) {
		$a -= $b; $a -= $c; $a ^= ( $this->zeroFill( $c, 13 ) );
		$b -= $c; $b -= $a; $b ^= ( $a << 8 );
		$c -= $a; $c -= $b; $c ^= ( $this->zeroFill( $b, 13 ) );
		$a -= $b; $a -= $c; $a ^= ( $this->zeroFill( $c, 12 ) );
		$b -= $c; $b -= $a; $b ^= ( $a << 16);
		$c -= $a; $c -= $b; $c ^= ( $this->zeroFill( $b, 5 ) );
		$a -= $b; $a -= $c; $a ^= ( $this->zeroFill( $c, 3 ) ); 
		$b -= $c; $b -= $a; $b ^= ( $a << 10);
		$c -= $a; $c -= $b; $c ^= ( $this->zeroFill( $b, 15 ) );
		
		return array($a,$b,$c);
	}
	
	protected function zeroFill( $a, $b ) {
		$z = hexdec(80000000);
		
		if( $z & $a )
		{
			$a = $a >> 1;
			$a &= ~$z;
			$a |= 0x40000000;
			$a = $a >> ( $b - 1 );
		} 
		else
			$a = $a >> $b;
		
		return $a;
	}

	protected function strord($string) {	
		$result = array();
		for($i = 0; $i < strlen($string); $i++ ) {
			$result[$i] =  ord($string[$i]);
		}	
		return $result;
	}

	protected function googleNewCh( $ch ) {
		$ch = ( ( ( $ch / 7 ) << 2 ) | ( ( $this->myfmod( $ch,13 ) ) & 7 ) );
	  
		$prbuf = array();
		$prbuf[0] = $ch;
		for( $i = 1; $i < 20; $i++ ) {
		  $prbuf[$i] = $prbuf[$i-1] - 9;
		}
		$ch = $this->googleCH($this->c32to8bit( $prbuf ), 80 );
		
		return $ch;
	 }
	 
	protected function c32to8bit( $arr32 ) {
		$arr8 = array();	
		
		for( $i = 0; $i < count($arr32); $i++ ) {
			for( $bitOrder = $i * 4; $bitOrder <= $i * 4 + 3; $bitOrder++ )  {
				$arr8[$bitOrder] = $arr32[$i] & 255;
				$arr32[$i] = $this->zeroFill( $arr32[$i], 8 );
			}
		}
		
		return $arr8;
	}
	
	protected function myfmod( $x, $y ) {
		$i = floor( $x / $y );
		return ( $x - $i * $y );
	}
}