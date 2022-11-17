<?php

function is_base64_encoded($str) {
	   $decoded_str = base64_decode($str);
	   $Str1 = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $decoded_str);
	   if ($Str1!=$decoded_str || $Str1 == '') {
	      return false;
	   }
	   return true;
}

function rxtag_match($tagName, $string) {
	preg_match_all("/\[".$tagName."\](\s*|.*)\[\/".$tagName."\]/ims", $string, $data);
	if (isset($data[1][0]))
		return $data[1][0];
	else
		return "";
}

function getviewparts($string) {
	$viewparts = [
		"pageTitle"=>"",
		"pageStyles"=>"",
		"pageContents"=>"",
		"pageScripts"=>""
	];

	$viewparts["pageTitle"] = rxtag_match("title", $string);
	$viewparts["pageStyles"] = rxtag_match("styles", $string);
	$viewparts["pageContents"] = rxtag_match("contents", $string);
	$viewparts["pageScripts"] = rxtag_match("scripts", $string);

	return $viewparts;
}