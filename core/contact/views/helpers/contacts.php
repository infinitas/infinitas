<?php
	class ContactsHelper extends AppHelper{
		public $helpers = array(
			'Text',
			'Html'
		);

		public function drawCard($details){
			if(is_array(current($details))){
				$this->drawCards($details);
			}

			$out = '<div class="contact-card">';
				$out .= $this->__output($details);
			$out .= '</div>';

			return $out;
		}

		private function __output($details){
			$default = array(
				'checkbox' => '',
				'avitar' => '',
				'user' => '',
				'company' => '',
				'mobile' =>  '',
				'landline' => '',
				'email' => '',
				'address'
			);

			$return = array();

			$details = array_merge($default, $details);

			if(!empty($details['avitar'])){
				$return[] = $this->Html->image(
					$details['avitar'],
					array(
						'style' => 'width: 75; height: 75',
						'title' => sprintf('%s - %s', $details['user'], $details['company'])
					)
				);
			}

			if(!empty($details['mobile'])){
				$return[] = sprintf('<li class="mobile">%s</li>', $details['mobile']);
			}

			if(!empty($details['landline'])){
				$return[] = sprintf('<li class="landline">%s</li>', $details['landline']);
			}

			if(!empty($details['email'])){
				$return[] = sprintf('<li class="email">%s</li>', $this->Text->autoLinkEmails($details['email']));
			}

			if(!empty($details['address'])){
				$return[] = sprintf('<li class="address">%s</li>', $details['address']);
			}

			if(!empty($details['extra'])){
				$return[] = sprintf('<li class="extra">%s</li>', $details['extra']);
			}

			if(empty($details['user']) && !empty($details['company'])){
				$name = $details['company'];
			}

			else if(!empty($details['company'])){
				$name = sprintf('%s - %s', $details['user'], $details['company']);
			}
			
			else{
				$name = $details['user'];
			}

			return sprintf('<h2>%s%s</h2>', $details['checkbox'], $name) .
				'<ul class="details">' . implode('', $return) . '</ul>';
		}
	}