<?php
class Auth
{
	/**
	 * 加/解密字符串
	 * 
	 * @access public
	 * @param  string $string 原字符串
	 * @param  string $key    加密密钥
	 * @param  boolean $encode 是加密还是解密
	 * @return string 加密或解密后的字符串
	 */
	public static function authcode($string, $key, $encode = true)
	{
		if (!$encode) return Auth::decrypt(base64_decode($string),$key);
		return base64_encode(Auth::encrypt($string, $key));
	}
	
	/**
	 * 混淆密码
	 * 
	 * @access public
	 * @param  string $pwd 密码
	 * @param  string $key 密钥
	 */
	public static function codestr($pwd, $key) {
		return md5($pwd . $key);
	}
	
	/**
	 * 长整数转换成字符串
	 */
	public static function long2str($v, $w) {
		$len = count ( $v );
		$n = ($len - 1) << 2;
		
		if ($w) {
			$m = $v [$len - 1];
			if (($m < $n - 3) || ($m > $n))
				return false;
			$n = $m;
		}
		
		$s = array ();
		for($i = 0; $i < $len; $i ++) {
			$s [$i] = pack ( "V", $v [$i] );
		}
		
		if ($w) {
			return substr ( join ( '', $s ), 0, $n );
		} else {
			return join ( '', $s );
		}
	}
	
	/**
	 * 字符串转换成长整数
	 */
	public static function str2long($s, $w) {
		$v = unpack ( "V*", $s . str_repeat ( "\0", (4 - strlen ( $s ) % 4) & 3 ) );
		$v = array_values ( $v );
		
		if ($w) {
			$v [count ( $v )] = strlen ( $s );
		}
		
		return $v;
	}
	
	/**
	 * 转换成整数
	 */
	public static function int32($n) {
		while ( $n >= 2147483648 )
			$n -= 4294967296;
			
		while ( $n <= - 2147483649 )
			$n += 4294967296;
		
		return ( int ) $n;
	}
	
	/**
	 * 加密
	 */
	public static function encrypt($str, $key)
	{
		if ($str == "") {
			return "";
		}
		
		$v = Auth::str2long ( $str, true );
		$k = Auth::str2long ( $key, false );
		
		if (count ( $k ) < 4) {
			for($i = count ( $k ); $i < 4; $i ++) {
				$k [$i] = 0;
			}
		}
		
		$n = count ( $v ) - 1;
		
		$z = $v [$n];
		$y = $v [0];
		
		$delta = 0x9E3779B9;
		
		$q = floor ( 6 + 52 / ($n + 1) );
		$sum = 0;
		
		while ( 0 < $q -- ) {
			$sum = Auth::int32 ( $sum + $delta );
			$e = $sum >> 2 & 3;
			
			for($p = 0; $p < $n; $p ++) {
				$y = $v [$p + 1];
				$mx = Auth::int32 ( (($z >> 5 & 0x07ffffff) ^ $y << 2) + (($y >> 3 & 0x1fffffff) ^ $z << 4) ) ^ Auth::int32 ( ($sum ^ $y) + ($k [$p & 3 ^ $e] ^ $z) );
				$z = $v [$p] = Auth::int32 ( $v [$p] + $mx );
			}
			
			$y = $v [0];
			$mx = Auth::int32 ( (($z >> 5 & 0x07ffffff) ^ $y << 2) + (($y >> 3 & 0x1fffffff) ^ $z << 4) ) ^ Auth::int32 ( ($sum ^ $y) + ($k [$p & 3 ^ $e] ^ $z) );
			$z = $v [$n] = Auth::int32 ( $v [$n] + $mx );
		}
		
		return Auth::long2str ( $v, false );
	}
	
	/**
	 * 解密
	 */
	public static function decrypt($str, $key) {
		if ($str == "") {
			return "";
		}
		
		$v = Auth::str2long ( $str, false );
		$k = Auth::str2long ( $key, false );
		
		if (count ( $k ) < 4) {
			for($i = count ( $k ); $i < 4; $i ++) {
				$k [$i] = 0;
			}
		}
		
		$n = count ( $v ) - 1;
		
		$z = $v [$n];
		$y = $v [0];
		$delta = 0x9E3779B9;
		$q = floor ( 6 + 52 / ($n + 1) );
		$sum = Auth::int32 ( $q * $delta );
		
		while ( $sum != 0 ) {
			$e = $sum >> 2 & 3;
			
			for($p = $n; $p > 0; $p --) {
				$z = $v [$p - 1];
				$mx = Auth::int32 ( (($z >> 5 & 0x07ffffff) ^ $y << 2) + (($y >> 3 & 0x1fffffff) ^ $z << 4) ) ^ Auth::int32 ( ($sum ^ $y) + ($k [$p & 3 ^ $e] ^ $z) );
				$y = $v [$p] = Auth::int32 ( $v [$p] - $mx );
			}
			
			$z = $v [$n];
			$mx = Auth::int32 ( (($z >> 5 & 0x07ffffff) ^ $y << 2) + (($y >> 3 & 0x1fffffff) ^ $z << 4) ) ^ Auth::int32 ( ($sum ^ $y) + ($k [$p & 3 ^ $e] ^ $z) );
			$y = $v [0] = Auth::int32 ( $v [0] - $mx );
			$sum = Auth::int32 ( $sum - $delta );
		}
		
		return Auth::long2str ( $v, true );
	}
	
	/**
	 * 加密数字
	 * 
	 * @access public
	 * @param  string $id
	 * @param  string $key
	 * @return string
	 */
	public static function encryptId($id, $key)
	{
		$id = base_convert($id, 10, 36);
		$data = mcrypt_encrypt(MCRYPT_BLOWFISH, $key, $id, 'ecb');
		$data = bin2hex($data);
		return $data;
	}
	
	/**
	 * 解密数字
	 * 
	 * @access public
	 * @param  string $encrypted_id
	 * @param  string $key
	 * @return string
	 */
	public static function decryptId($encrypted_id, $key)
	{
		$data = pack('H*', $encrypted_id);
		$data = mcrypt_decrypt(MCRYPT_BLOWFISH, $key, $data, 'ecb');
		$data = base_convert($data, 36, 10);
		return $data;
	}
}
?>
