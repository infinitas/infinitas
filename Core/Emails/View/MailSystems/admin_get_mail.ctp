<?php
	if(!empty($mail['Email']['html'])){
		echo $mail['Email']['html'];
	}
	else{
		if(!empty($mail['Email']['text'])){
			echo $mail['Email']['text'];
		}
		else {
			echo $this->Text->autoLink(strip_tags(str_replace(array("\r\n", "\n"), '<br/>', $mail['Email']['html']), '<br>'));
		}
	}