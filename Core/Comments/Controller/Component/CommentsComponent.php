<?php
	class CommentsComponent extends InfinitasComponent {
		/**
		 * @brief allow posting comments to any controller
		 *
		 * @todo this needs to be moved to the Comments plugin and is part of
		 * the reason this code needs to be more extendable
		 *
		 * @access public
		 *
		 * @return void
		 */
		public function actionComment() {
			if (!empty($this->Controller->request->data[$this->Controller->modelClass.'Comment'])) {
				$message = 'Your comment has been saved and will be available after admin moderation.';
				if (Configure::read('Comments.auto_moderate') === true) {
					$message = 'Your comment has been saved and is active.';
				}

				$this->Controller->request->data[$this->Controller->modelClass.'Comment']['class'] = $this->Controller->request->plugin . '.' . $this->Controller->modelClass;

				if(!empty($this->Controller->request->data[$this->Controller->modelClass.'Comment']['om_non_nom'])) {
					$this->Controller->Session->write('Spam.bot', true);
					$this->Controller->Session->write('Spam.detected', time());

					$this->Controller->notice(
						__d('comments', 'Not so fast spam bot.'),
						array(
							'redirect' => '/?bot=true'
						)
					);
				}

				else if ($this->Controller->{$this->Controller->modelClass}->createComment($this->Controller->request->data)) {
					$this->Controller->notice(
						__d('comments', $message),
						array('redirect' => true)
					);
				}

				else {
					$this->Controller->notice('not_saved');
				}
			}

			return $this->Controller->render(null, null, App::pluginPath('Comments') . 'View' . DS . 'InfinitasComments' . DS . 'add.ctp');
		}
	}