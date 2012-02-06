<?php
	if(!$autorId) {
		return false;
	}

	$autor = ClassRegistry::init('Contents.GlobalAuthor')->getAuthor($authorId);
?>
<div id="author-bio" class="highlight-box">
	<?php echo $this->Avitar->image($author['GlobalAuthor']['email'], array('class' => 'img-box-small fl'));?>
	<h3><?php echo $author['GlobalAuthor']['name']; ?></h3>
	<p><?php echo $author['GlobalAuthor']['bio']; ?></p>
</div>