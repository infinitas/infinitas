<?php
	$code =  ClassRegistry::init('Libs.ShortUrl')->newUrl('index.php?title=Events_forsdf_Controllers_and_Components#User_Datssaa');
	var_dump($code);

	$url =  ClassRegistry::init('Libs.ShortUrl')->getUrl($code);
	pr($url);


    echo $form->create('User', array('url' => array('plugin' => 'user', 'controller' => 'users', 'action' =>'forgot_password')));
        echo $form->input('email');
    echo $form->end(__('Reset Password', true));
?>