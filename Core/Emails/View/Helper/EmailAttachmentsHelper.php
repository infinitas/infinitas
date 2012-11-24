<?php
/**
 * EmailAttachmentsHelper
 *
 * @package Infinitas.Emails.Helper
 */

/**
 * EmailAttachmentsHelper
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Emails.Helper
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.7a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class EmailAttachmentsHelper extends AppHelper {
/**
 * Helpers to load
 *
 * @var array
 */
	public $helpers = array(
		'Text',
		'Html',
		'Events.Event'
	);

/**
 * Display attachment in an iframe
 *
 * @param arrat $mail the mail
 *
 * @return string
 */
	public function outputBody($mail) {
		$_url = $this->Event->trigger('emails.slugUrl', array('type' => 'mail', 'data' => $mail));
		return '<iframe src="' . Router::url(current($_url['slugUrl'])) . '" height="100%" width="100%"></iframe>';
	}

/**
 * generate an attachment icon for emails with an attachment
 *
 * @param array $mail
 * @param boolean $small
 *
 * @return boolean|string
 */
	public function hasAttachment($mail = array(), $small = true) {
		if((isset($mail['attachments']) && $mail['attachments']) || $mail === true) {
			$size = ($small === true) ? 16 : 24;
			return $this->Html->image(
				'/newsletter/img/attachment.png',
				array(
					'alt' => __d('emails', 'Attachment'),
					'height' => $size.'px',
					'width' => $size.'px',
					'title' => __d('emails', 'This email has attachments')
				)
			);
		}

		return false;
	}

/**
 * generate an icon for emails that are flagged
 *
 * @param array $mail
 * @param string $image
 * @param boolean $small
 *
 * @return string
 */
	public function isFlagged($mail = array(), $image = '/newsletter/img/flagged.png', $small = true) {
		$title = __d('emails', 'Flagged :: This email has been flagged');
		$alt = __d('emails', 'Flagged');

		if((isset($mail['flagged']) && (bool)$mail['flagged'] === false) || $mail === false) {
			$image = '/newsletter/img/flagged-not.png';
			$title = __d('emails', 'Not Flagged :: Flag this email');
			$alt = __d('emails', 'Not Flagged');
		}

		$size = ($small === true) ? 16 : 24;

		return $this->Html->image(
			$image,
			array(
				'alt' => $alt,
				'height' => $size.'px',
				'width' => $size.'px',
				'title' => $title
			)
		);
	}

/**
 * List a mails attachements
 *
 * @param array $attachments the mails attachements
 *
 * @return string
 */
	public function listAttachments($attachments = array()) {
		if(empty($attachments) || !is_array($attachments)) {
			return false;
		}

		$return = array();
		foreach($attachments as $attachment) {
			switch($attachment['type']) {
				case 'jpeg':
				case 'gif':
					$data = $this->__imageAttachment($attachment);
					break;

				case 'msword':
				case 'pdf':
					$data = $this->__fileAttachment($attachment);
					break;

				default:
					pr($attachment);
					exit;
					break;
			}

			$return[] = $this->__addAttachment($attachment, $data);
		}

		if(!empty($return)) {
			return sprintf('<ul class="attachments"><li>%s</li></ul>', implode('</li><li>', $return));
		}

		return false;
	}

/**
 * Attachment details
 *
 * @param array $attachment
 * @param string $data
 *
 * @return string
 */
	private function __addAttachment($attachment, $data) {
		return sprintf('<h4>%s</h4>%s', $attachment['name'], $data);
	}

/**
 * Show an image attachment
 *
 * @param array $attachment the image attachment
 *
 * @return string
 */
	private function __imageAttachment($attachment) {
		return $this->Html->link(
			$this->Html->image(
				$attachment['versions']['thumbnail'],
				array(
					'alt' => $attachment['filename'],
					'title' => sprintf('%s (%s)', $attachment['filename'], $attachment['size'])
				)
			),
			InfinitasRouter::url($attachment['versions']['large']),
			array(
				'escape' => false,
				'class' => 'thickbox'
			)
		);
	}

/**
 * Show a file attachment
 *
 * @param array $attachment the attachement details
 *
 * @return string
 */
	private function __fileAttachment($attachment) {
		return $this->Html->link(
			sprintf('%s (%s)', $this->Text->truncate($attachment['filename'], 50), convert($attachment['size'])),
			$attachment['download_url']
		);
	}

}