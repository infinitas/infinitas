<?php
	header("Content-disposition: attachment; filename=all_contacts.vcf");
	header("Content-type: text/x-vcard");

	echo $content_for_layout;
?>