<?php
	$errors = $this->Install->parseErrors();
?>
<p>
	<?php
		$message = array(
			__d('installer', 'Thank-you for choosing %s to power your website.', true),
			__d('installer',
				'Since you are on the %s installer you probably know a bit about %s.
				Before you go to the next step, make sure that you have create a database and you have the database details at hand.
				If you are unsure how to create a database, contact your web host support.', true),
			__d('installer',
				'%s uses the MIT License, the full license is shown below for your information.
				Unless you plan on modifiying and redistributing %s you do not need to worry about the license. 
				Note that this license only applies to the %s core code, some extensions may have other licenses.',
				true)
		);

		$siteName = '<b>Infinitas</b>';

		echo str_replace('%s', $siteName, implode('</p><p>', $message));
	?>
</p>

<blockquote>
<h2>MIT License</h2>
<p>Copyright &copy; <?php echo date('Y'); ?> Infinitas</p>
<p>Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:</p>
<p>The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.</p>
<p>THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.</p>
</blockquote>