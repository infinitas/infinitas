<?php
class PayPalForm {
	protected $_general = array(
		'cmd' => array(
			'type' => 'hidden',
			'required' => true,
			'options' => array(
				'buy_now' => '_xclick',
				'cart' => '_cart',
				'gift' => '_oe-gift-certificate',
				'subscription' => '_xclick-subscriptions',
				'auto_billing' => '_xclick-auto-billing',
				'payment_plan' => '_xclick-payment-plan',
				'donation' => '_donations',
				'encrypted' => '_s-xclick'
			)
		),
		'notify_url' => array(
			'type' => 'hidden',
			'required' => false,
			'length' => 255
		),
		'charset' => array(
			'type' => 'hidden',
			'required' => true,
			'default' => 'UTF-8',
			'options' => array(
				'Big5 (Traditional Chinese in Taiwan)', 'EUC-JP', 'EUC-KR', 'EUC-TW',
				'gb2312 (Simplified Chinese)', 'gbk', 'HZ-GB-2312 (Traditional Chinese in Hong Kong)',
				'ibm-862 (Hebrew with European characters)', 'ISO-2022-CN', 'ISO-2022-JP',
				'ISO-2022-KR', 'ISO-8859-1 (Western European Languages)', 'ISO-8859-2', 'ISO-8859-3',
				'ISO-8859-4', 'ISO-8859-5', 'ISO-8859-6', 'ISO-8859-7', 'ISO-8859-8', 'ISO-8859-9',
				'ISO-8859-13', 'ISO-8859-15', 'KOI8-R (Cyrillic)', 'Shift_JIS', 'UTF-7', 'UTF-8', 'UTF-16',
				'UTF-16BE', 'UTF-16LE', 'UTF16_PlatformEndian', 'UTF16_OppositeEndian', 'UTF-32', 'UTF-32BE',
				'UTF-32LE', 'UTF32_PlatformEndian', 'UTF32_OppositeEndian', 'US-ASCII', 'windows-1250',
				'windows-1251', 'windows-1252', 'windows-1253', 'windows-1254', 'windows-1255', 'windows-1256',
				'windows-1257', 'windows-1258', 'windows-874 (Thai)', 'windows-949 (Korean)', 'x-mac-greek',
				'x-mac-turkish', 'x-mac-centraleurroman', 'x-mac-cyrillic', 'ebcdic-cp-us', 'ibm-1047'
			)
		),
		'return' => array(
			'required' => false,
			'validate' => 'url'
		),
		'currency_code' => array(
			'type' => 'hidden',
			'required' => true
		)
	);

	protected $_item = array(
		'amount' => array(),
		'discount_amount' => array(),
		'discount_amount2' => array(),
		'discount_rate' => array(),
		'discount_rate2' => array(),
		'discount_num' => array(),
		'item_name' => array(),
		'item_number' => array(),
		'quantity' => array(),
		'shipping' => array(),
		'shipping2' => array(),
		'tax' => array(),
		'tax_rate' => array(),
		'undefined_quantity' => array(),
		'weight' => array(),
		'weight_unit' => array(),
		'on0' => array(),
		'on1' => array(),
		'os0' => array(),
		'os1' => array(),
		'option_index' => array(),
		'option_select0' => array(),
		'option_amount0' => array(),
		'option_select1' => array(),
		'option_amount1' => array(),
	);

	protected $_transactions = array(
		'address_override' => array(),
		'currency_code' => array(),
		'custom' => array(),
		'handling' => array(),
		'invoice' => array(),
		'tax_cart' => array(),
		'weight_cart' => array(),
		'weight_unit' => array(),
	);

	protected $_cart = array(
		'add' => array(),
		'amount_%' => array(),
		'business' => array(),
		'discount_amount_cart' => array(),
		'discount_amount_%' => array(),
		'discount_rate_cart' => array(),
		'discount_rate_%' => array(),
		'display' => array(),
		'handling_cart' => array(),
		'item_name_%' => array(),
		'paymentaction' => array(),
		'shopping_url' => array(),
		'upload' => array(),
	);

	protected $_recurring = array(
		'business' => array(),
		'item_name' => array(),
		'currency_code' => array(),
		'a1' => array(),
		'p1' => array(),
		't1' => array(),
		'a2' => array(),
		'p2' => array(),
		't2' => array(),
		'a3' => array(),
		'p3' => array(),
		't3' => array(),

		'src' => array(),
		'srt' => array(),
		'sra' => array(),
		'no_note' => array(),
		'custom' => array(),
		'invoice' => array(),
		'modify' => array(),
		'usr_manage' => array(),
	);

	protected $_billing = array(
		'max_text' => array(),
		'set_customer_limit' => array(),
		'min_amount' => array(),
	);

	protected $_plan = array(
		'disp_tot' => array(),
		'option_index' => array(),
		'option_selectn' => array(),
		'option_selectn_name' => array(),
		'option_selectn_type' => array(),
		'option_selectn_am' => array(),
		'option_selectn_pm' => array(),
		'option_selectn_tm' => array(),
		'option_selectn_nm' => array()
	);

	protected $_checkout = array(
		'page_style' => array(),
		'image_url' => array(),
		'cpp_cart_border_color' => array(),
		'cpp_header_image' => array(),
		'cpp_headerback_color' => array(),
		'cpp_headerborder_color' => array(),
		'cpp_logo_image' => array(),
		'cpp_payflow_color' => array(),
		'lc' => array(),
		'no_note' => array(),
		'cn' => array(),
		'no_shipping' => array(),
		'return' => array(),
		'rm' => array(),
		'cbt' => array(),
		'cancel_return' => array(),
	);

	protected $_autofill = array(
		'address1' => array(),
		'address2' => array(),
		'city' => array(),
		'country' => array(),
		'email' => array(),
		'first_name' => array(),
		'last_name' => array(),
		'lc' => array(),
		'charset' => array(),
		'night_phone_a' => array(),
		'night_phone_b' => array(),
		'night_phone_c' => array(),
		'state' => array(),
		'zip' => array(),
	);

	protected $_instant_payment = array(
		'callback_url' => array(),
		'callback_timeout' => array(),
		'callback_version' => array(),
		'fallback_tax_amount' => array(),
		'fallback_shipping_option_name_%' => array(),
		'fallback_shipping_option_amount_%' => array(),
		'fallback_shipping_option_is_default_%' => array(),
		'fallback_insurance_option_offered' => array(),
		'fallback_insurance_amount' => array()
	);

	protected $_instant_size = array(
		'height_%' => array(),
		'height_unit' => array(),
		'width_%' => array(),
		'width_unit' => array(),
		'length_%' => array(),
		'length_unit' => array()
	);
}