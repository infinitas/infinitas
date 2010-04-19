<?php
	class ShopAppModel extends AppModel {
		var $tablePrefix = 'shop_';

		var $actsAs = array(
			'Feed.Feedable'
		);
	}