<?php
class HeaderMgr
{
	private static $headers = array();
	
	static function setHeader($header)
	{
		HeaderMgr::$headers[] = $header;
	}
	
	static function getHeadersAndClear()
	{
		$headers = HeaderMgr::$headers;
		HeaderMgr::$headers = array();
		return $headers;
	}
}