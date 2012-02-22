<?php
    /**
     * global content
	 *
	 * Almost everything has a title and a description of some sort, so this
	 * makes things a bit simpler. Here you can do some basic management.
     *
     * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     *
     * Licensed under The MIT License
     * Redistributions of files must retain the above copyright notice.
     *
     * @filesource
     * @copyright     Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     * @link          http://infinitas-cms.org
     * @package       Infinitas.contents
     * @subpackage    Infinitas.contents.views.global_content.admin_index
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
     * @since         0.8a
     */

    echo $this->Form->create('GlobalContent', array('action' => 'mass'));
        $massActions = $this->Infinitas->massActionButtons(array('edit', 'delete'));
		echo $this->Infinitas->adminIndexHead($filterOptions, $massActions);
?>
<div class="table">
    <table class ="listing" cellpadding="0" cellspacing="0">
        <?php
            echo $this->Infinitas->adminTableHeader(
                array(
                    $this->Form->checkbox('all') => array(
                        'class' => 'first',
                        'style' => 'width:25px;'
                    ),
                    $this->Paginator->sort('GlobalCategory.title', __d('contents', 'Category')),
                    $this->Paginator->sort('GlobalContent.model', __d('contents', 'Type')),
                    $this->Paginator->sort('title'),
					__d('contents', 'Missing Data'),
                    $this->Paginator->sort('modified', __d('contents', 'Updated')) => array(
                        'style' => 'width:100px;'
                    )
                )
            );

            foreach ($contents as $content) {
                ?>
                	<tr class="<?php echo $this->Infinitas->rowClass(); ?>">
                        <td><?php echo $this->Infinitas->massActionCheckBox($content); ?>&nbsp;</td>
                		<td>
                			<?php 
								if(!empty($content['GlobalCategory']['title'])) {
									echo $content['GlobalCategory']['title'];
								}
								else if($content['GlobalContent']['model'] != 'Contents.GlobalCategory') {
									echo __d('contents', 'Uncatgorised');
								}
								else {
									echo '-';
								}
							?>&nbsp;
                		</td>
                        <td>
							<?php 
								list($plugin, $model) = pluginSplit($content['GlobalContent']['model']);
								echo sprintf('%s %s', $plugin, prettyName(str_replace($plugin, '', $model))); 
							?>&nbsp;
						</td>
                		<td>
                			<?php 
								echo $this->Html->link(
									$content['GlobalContent']['title'],
									array(
										'plugin' => Inflector::underscore($plugin),
										'controller' => Inflector::pluralize(Inflector::underscore($model)),
										'action' => 'edit',
										$content['GlobalContent']['foreign_key']
									)
								);
							?>&nbsp;
                		</td>
						<?php
							$issues = array();
							if($content['GlobalContent']['keyword_not_in_description']) {
								$issues[] = __d('contents', 'Main keyword not in description');
							}

							if($content['GlobalContent']['keywords_missing']) {
								$issues[] = __d('contents', 'No meta keywords');
							}

							if($content['GlobalContent']['keywords_short']) {
								$issues[] = __d('contents', 'Short meta keywords');
							}

							if($content['GlobalContent']['keywords_duplicate']) {
								$issues[] = __d('contents', 'Duplicate keywords');
							}

							if($content['GlobalContent']['description_missing']) {
								$issues[] = __d('contents', 'No meta description');
							}

							if($content['GlobalContent']['description_short']) {
								$issues[] = __d('contents', 'Short meta description');
							}

							if($content['GlobalContent']['description_duplicate']) {
								$issues[] = __d('contents', 'Duplicate description');
							}

							if($content['GlobalContent']['description_too_long']) {
								$issues[] = __d('contents', 'Description too long for SERP');
							}

							if($content['GlobalContent']['missing_category']) {
								$issues[] = __d('contents', 'Not linked to a category');
							}

							if($content['GlobalContent']['missing_layout']) {
								$issues[] = __d('contents', 'No layout found');
							}

							if($content['GlobalContent']['missmatched_layout']) {
								$issues[] = __d('contents', 'Layout linked from another model');
							}

							if(count(explode(' ', strip_tags($content['GlobalContent']['body']))) < 300) {
								$issues[] = __d('contents', 'Content body is short');
							}

							if($content['GlobalContent']['introduction_duplicate']) {
								$issues[] = __d('contents', 'Two or more content share this intro');
							}

							if($content['GlobalContent']['body_duplicate']) {
								$issues[] = __d('contents', 'Two or more content share this body');
							}

							if($content['GlobalContent']['keyword_density_problem']) {
								$highLow = $content['GlobalContent']['keyword_density'] < 5 ? __d('contents', 'Low') : __d('contents', 'High');
								$issues[] = __d('contents', 'Keyword density <b>%s</b> too %s', $content['GlobalContent']['keyword_density'], $highLow);
							}
							
							$issueCount = 0;
							if($content['GlobalContent']['foreign_key'] && $issues) {
								$issueCount = count($issues);
								$issues = __d('contents', 'Possible Issues :: %s', $this->Design->arrayToList($issues));
							}
						?>
                		<td title="<?php echo $issueCount ? $issues : ''; ?>">
                			<?php 
								if($issueCount) {
									echo __dn('contents', '%d issue', '%d issues', $issueCount, $issueCount, $issueCount);
								}
								else if(!$content['GlobalContent']['foreign_key']) {
									echo 'Orphan';
								}
								else {
									echo '-';
								}
							?>&nbsp;
                		</td>
                		<td>
                			<?php echo $this->Time->niceShort($content['GlobalContent']['modified']); ?>&nbsp;
                		</td>
                	</tr>
                <?php
            }
        ?>
    </table>
    <?php echo $this->Form->end(); ?>
</div>
<?php echo $this->element('pagination/admin/navigation'); ?>