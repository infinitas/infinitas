<?php
    /**
     * Comment Template.
     *
     * @todo -c Implement .this needs to be sorted out.
     *
     * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     *
     * Licensed under The MIT License
     * Redistributions of files must retain the above copyright notice.
     *
     * @filesource
     * @copyright     Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     * @link          http://infinitas-cms.org
     * @package       sort
     * @subpackage    sort.comments
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
     * @since         0.5a
     */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Infinitas :: <?php echo $title_for_layout; ?></title>
        <?php
            echo $this->Html->css( '/installer/css/install' );
            echo $scripts_for_layout;
        ?>
    </head>
    <body>
        <center>
            <div id="main_block">
                <div id="top_block">
        			<div class="navi">
        				<a href="#" class="<?php echo $a = ( $this->action == 'index' ) ? 'navi_hm' : 'navi_tx'; ?>">Checks</a>
        				<a href="#" class="<?php echo $a = ( $this->action == 'database' ) ? 'navi_hm' : 'navi_tx'; ?>">Database</a>
        				<a href="#" class="<?php echo $a = ( $this->action == 'config' ) ? 'navi_hm' : 'navi_tx'; ?>">Services</a>
        				<a href="#" class="<?php echo $a = ( $this->action == 'done' ) ? 'navi_hm' : 'navi_tx'; ?>">About Us</a>
        				<a href="#" class="<?php echo $a = ( $this->action == 'help' ) ? 'navi_hm' : 'navi_tx'; ?>">Help</a>
        			</div>
        			<div class="tp_img1">
        				<div class="tp_img2">
        					<a href="#"><img src="images/logo.gif" alt="" width="211" height="131" class="logo" /></a>
        					<img src="images/tp_tx.gif" alt="" width="450" height="49" class="tp_tx" />
        				</div>
        			</div>
                </div>
            	<div id="sub_block1">
            		<!--Content Block Starts -->
            		<div id="content_block">
            			<!--Left Block Starts -->
            			<div id="left_block">
            				<span class="blk2_lp">
            					<span class="nws">News Updates</span>
            					<span class="dat" style="margin-top:22px;"><span>12-10-2007</span><br />Let us turn your stressful environment into a tranquil setting with plants.</span>
            					<span class="dat"><span>04-11-2007</span><br />Trees and blooming plants will create and and more.Trees and blooming plants will create and and more.</span>

            					<span class="dat"><span>11-01-2008</span><br />impressive entrance for your clients and a relaxing area for your employees. </span>
            					<span class="dat"><span>04-02-2008</span><br />Let us turn that empty into a company oasis! trees and blooming plants will create.</span>
            					<span class="dat"><span>04-11-2007</span><br />Trees and blooming plants will create and and more.</span>
            					<span class="dat"><span>11-01-2008</span><br />impressive entrance for your clients and a relaxing area for your employees. Trees and blooming plants will create and and more.
            					<input name="" type="submit" value="" />
            					</span>
            				</span>
            			</div>
            			<div id="right_block">
                            <?php
                                echo $this->Session->flash();
                                echo $content_for_layout;
                            ?>
            			</div>
            		</div>
            	</div>
            	<div id="sub_block2">
            		<div id="footer_block">

            			<span class="ftr_nv">
            				 <a href="#">Home</a>

            				 <span>|</span>
            				 <a href="#">Clients </a>
            				 <span>|</span>
            				 <a href="#">Services</a>
            				 <span>|</span>
            				 <a href="#">About Us </a>
            				 <span>|</span>
            				 <a href="#">Contact us</a>

            			</span>
            			<span class="cpy">Copyright Infinitas &copy; 2009, All rights reserved.</span>
            		</div>
            	</div>
            </div>
        </center>
    </body>
</html>