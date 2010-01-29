<?php
/**
 * Breadcrumb element. Makes a breadcrumb trail for file browsing
 *
 */
$pathSegments = explode('/', $currentPath);
echo $html->link('/', array('action' => 'source')) . ' ';

for ($i = 0, $pathCount = count($pathSegments) - 1; $i < $pathCount; $i++) :
	$pathBit = array_slice($pathSegments, $i, 1);
	$path = implode('/', array_slice($pathSegments, 0, $i + 1));
	echo $html->link($pathBit[0], array('action' => 'source', $path)) . ' / ';
endfor;

echo $pathSegments[$pathCount];
?>