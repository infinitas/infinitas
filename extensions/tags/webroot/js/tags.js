/* 
 * Short Description / title.
 * 
 * Overview of what the file does. About a paragraph or two
 * 
 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * 
 * @filesource
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package {see_below}
 * @subpackage {see_below}
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since {check_current_milestone_in_lighthouse}
 * 
 * @author {your_name}
 * 
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 */

$(document).ready(function() {
	$('#' + Infinitas.model + 'Tags').tagEditor({
		confirmRemoval: false,
		completeOnSeparator: true,
		completeOnBlur: true,
		continuousOutputBuild: true
	});

	$('ul.tagEditor').prepend(
		'<li title="Remove All Tags" id="' + Infinitas.model + 'TagsReset">Remove All</li>'
	);

	$('#' + Infinitas.model + 'TagsReset').click(function() {
		$('#' + Infinitas.model + 'Tags').tagEditorResetTags();
	});
});
