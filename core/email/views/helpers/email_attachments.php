<?php
	class EmailAttachmentsHelper extends AppHelper{
		public $helpers = array(
			'Text',
			'Html'
		);

		/**
		 * generate an attachment icon for emails with an attachment
		 *
		 * @param mixed true to show, mail array to check
		 * @return mixed false or html for attachment icon
		 */
		public function hasAttachment($mail = array(), $small = true){
			if((isset($mail['attachments']) && $mail['attachments']) || $mail === true){
				$size = $small === true ? 16 : 24;
				return $this->Html->image(
					'/newsletter/img/attachment.png',
					array(
						'alt' => __('Attachment', true),
						'height' => $size.'px',
						'width' => $size.'px',
						'title' => __('This email has attachments', true)
					)
				);
			}

			return false;
		}

		/**
		 * generate an icon for emails that are flagged
		 *
		 * @param mixed true to show, mail array to check
		 * @return mixed false or html for attachment icon
		 */
		public function isFlagged($mail = array(), $image = '/newsletter/img/flagged.png', $small = true){
			$title = __('Flagged :: This email has been flagged', true);
			$alt = __('Flagged', true);

			if((isset($mail['flagged']) && (bool)$mail['flagged'] === false) || $mail === false){
				$image = '/newsletter/img/flagged-not.png';
				$title = __('Not Flagged :: Flag this email', true);
				$alt = __('Not Flagged', true);
			}

			$size = $small === true ? 16 : 24;

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
		 * 
		 * @param <type> $attachments
		 * @return string html block of attachments
		 */
		public function listAttachments($attachments = array()){
			if(empty($attachments) || !is_array($attachments)){
				return false;
			}

			$return = array();
			foreach($attachments as $attachment){
				switch($attachment['type']){
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

			if(!empty($return)){
				return sprintf('<ul class="attachments"><li>%s</li></ul>', implode('</li><li>', $return));
			}

			return false;
		}

		private function __addAttachment($attachment, $data){
			return sprintf('<h4>%s</h4>%s', $attachment['name'], $data);
		}

		/**
		 * Show an image attachment
		 * @param <type> $attachment
		 */
		private function __imageAttachment($attachment){
			return $this->Html->link(
				$this->Html->image(
					$attachment['versions']['thumbnail'],
					array(
						'alt' => $attachment['filename'],
						'title' => sprintf('%s (%s)', $attachment['filename'], $attachment['size'])
					)
				),
				Router::url($attachment['versions']['large']),
				array(
					'escape' => false,
					'class' => 'thickbox'
				)
			);
		}
		/**
		 * Show a file attachment
		 * @param <type> $attachment
		 */
		private function __fileAttachment($attachment){
			return $this->Html->link(
				sprintf('%s (%s)', $this->Text->truncate($attachment['filename'], 50), convert($attachment['size'])),
				$attachment['download_url']
			);
		}
	}