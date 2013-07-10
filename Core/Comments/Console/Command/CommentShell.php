<?php
App::uses('SpamRating', 'Contents.Lib');

class CommentShell extends AppShell {

	public $uses = array(
		'Comments.InfinitasComment'
	);

	public function stats() {

	}
/**
 * Reclasify spam status
 *
 * This is to be used when adjusting the algorithm that calculates the spam score, it will automatically 
 * re-adjust the score and spam for all comments based on the current settings.
 *
 * This makes it possible to keep tabs with the spam as the bots change.
 * 
 * @return void
 */
	public function reclasify() {
		$SpamRating = new SpamRating(array(
			'content' => 'comment',
			'column_email' => 'email',
			'model' => $this->InfinitasComment,
		));
		$join = $this->InfinitasComment->autoJoinModel('Comments.InfinitasCommentAttribute');
		$join['conditions'][] = 'InfinitasCommentAttribute.key = "website"';
		$this->InfinitasComment->virtualFields['website'] = 'InfinitasCommentAttribute.val';
		$comments = $this->InfinitasComment->find('all', array(
			'fields' => array(
				'InfinitasComment.id',
				'InfinitasComment.email',
				'InfinitasComment.comment',
				'InfinitasComment.active',
				'InfinitasComment.points',
				'InfinitasComment.status',
				'InfinitasComment.mx_record',
				'InfinitasComment.ip_address',
				'website'
			),
			'joins' => array(
				$join
			)
		));

		$count = count($comments);
		foreach ($comments as $k => $comment) {
			$this->out(sprintf('%d of %d', $k, $count) . "\r", 0);
			
			$save = $SpamRating->outcome($comment['InfinitasComment']);
			$save[$this->InfinitasComment->primaryKey] = $comment['InfinitasComment']['id'];
			var_dump($this->InfinitasComment->save($save));
			exit;
		}
	}

	public function recount() {
		$this->InfinitasComment->virtualFields['count'] = 'COUNT(InfinitasComment.id)';
		$comments = $this->InfinitasComment->find('all', array(
			'fields' => array(
				'count',
				$this->InfinitasComment->alias . '.class',
				$this->InfinitasComment->alias . '.foreign_id',
			),
			'conditions' => array(
				$this->InfinitasComment->alias . '.active' => 1
			),
			'group' => array(
				$this->InfinitasComment->alias . '.class',
				$this->InfinitasComment->alias . '.foreign_id',
			)
		));
		
		foreach ($comments as $comment) {
			$Model = ClassRegistry::init(ucfirst($comment['InfinitasComment']['class']));
			$Model->id = $comment['InfinitasComment']['foreign_id'];
			$Model->saveField('comment_count', $comment['InfinitasComment']['count']);
		}
	}
}
