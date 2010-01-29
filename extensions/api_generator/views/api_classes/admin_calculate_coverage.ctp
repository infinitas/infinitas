<?php
$out = array(
	'id' => $id,
	'coverage' => $analysis['finalScore'] * 100
);
echo json_encode($out);
?>