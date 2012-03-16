<?php	
//=====================================================================//
/*					
	  Author(s): Gregory Krudysz	 
	  Last Revision: Feb-28-2012
*/
//=====================================================================//
//echo $status.'  '.$role;
?>
<!-- FOOTER -->
<div id="footerContainer">		
    <ul id="navlist" class="navlist">
        <li><code>ITS v.<?php echo $ITS_version; ?></code></li>
        <?php
        if ($status == 'admin' OR $status == 'instructor' ) {

            switch ($status) {
                case 'admin':
                    $opt_arr = array($status,'instructor','student');
                    break;
                case 'instructor':
                    $opt_arr = array($status, 'student');
                    break;
            }
            //---------//

            $option = '';
            for ($o = 0; $o < count($opt_arr); $o++) {
                if ($role == $opt_arr[$o]) {
                    $sel = 'selected="selected"';
                } else {
                    $sel = '';
                }
                $option .= '<option value="' . $opt_arr[$o] . '" ' . $sel . '>' . $opt_arr[$o] . '</option>';
            }
            //---------//
            $user = '<li><form id="role" name="role" action="screen.php" method="post">' .
                    '<select class="ITS_select" name="role" id="myselectid" onchange="javascript:this.form.submit();">' .
                    $option .
                    '</select>' .
                    '</form></li></ul>';

            $spacer = '&nbsp;<b><font color="silver">&diams;</font></b>&nbsp;';

            switch ($role) {
                case 'admin':
                    $footer_list = '<div id="footer_admin"><div id="footer_list" class="ITS_ADMIN"><div class="footer_col"><ul class="footer">' .
                            '<li><a href="docs/">Documentation</a></li>' .
                            '<li><a href="Tags.php">Tags</a></li>' .
                            '</ul></div>' .
                            '<div class="footer_col"><ul class="footer">' .
                            '<li><a href="FILES/DATA/DATA.php">DATA</a></li> ' .
                            '<li><a href="dSPFirst.php">eDSPFirst</a></li>' .
                            '<li><a href="search.php">Search Tool</a></li>' .
                            '</ul></div>' .
                            '<div class="footer_col"><ul class="footer">' .
                            '<li><a href="survey1.php?survey=Fall_2011">Survey - Fall 2011</a></li>' .
                            '<li><a href="survey1.php?survey=Spring_2011">Survey - Spring 2011</a></li>' .
                            '<li><a href="survey1.php?survey=BMED6787">Survey - BMED6787</a></li> ' .
                            '</ul></div>' .
                            '<div class="footer_col" style="float:right;width:auto;"><div id="navcontainer"><ul class="ITS_navlist">'.
                            '<li><a style="border:1px solid #999" href="Profile.php">Profile</a></li>'.
                            '<li><a style="border:1px solid #999" href="Question.php">Question</a></li>'.
                            '</ul><div id="ur"></div></div></div></div>'. //'<img src="css/media/bgFooter.png" width="100%" height="100%" alt="Smile">'.
                            '</div>';
                    break;
                case 'instructor':
                    $footer_list = '<div id="footer_admin"><div id="footer_list" class="ITS_ADMIN">' .
                            '<div class="footer_col"><ul class="footer">' .
                            '<li><a href="survey1.php?survey=Fall_2011">Survey - Fall 2011</a></li>' .
                            '</ul></div>' .
                            '<div class="footer_col" style="float:right;width:auto;"><div id="navcontainer"><ul class="ITS_navlist">'.
                            '<li><a style="border:1px solid #999" href="Profile.php">Profile</a></li>'.
                            '<li><a style="border:1px solid #999" href="Question.php">Questions</a></li>'.
                            '</ul><div id="ur"></div></div></div></div>'.
                            '</div>';
                    break;
                default:
                    $footer_list = '';
                    break;
            }

            $ftr = $spacer . $user . $footer_list;
        } else {
            $ftr = '&bull; ' . preg_replace('/_/', ' ', $status) . '<p>';
        }
        //echo $footer_list; die();
        echo $ftr;

        $footer = new ITS_footer($status,$LAST_UPDATE,'');
        echo $footer->main();
        ?>
</div>
<!-- end div#footerContainer -->
