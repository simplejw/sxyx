<?php
/**
 * 获取对应的缩略图
 * 
 * @access public
 * 
 * @param string @string
 * @param string @type
 * 
 * @return string
 */
function smarty_modifier_thumb($string, $type = 't')
{
	return Helper::thumb($string, $type);
}
