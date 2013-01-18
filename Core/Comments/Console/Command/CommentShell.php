<?php
class CommentShell extends AppShell {

	public $uses = array(
		'Comments.InfinitasComment'
	);

	public function main() {
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
