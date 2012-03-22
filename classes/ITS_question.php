<?php
//=====================================================================//
/*
  ITS_question class - render ITS question according to type.

  type: |S|M|P|C|MC|O| - |short answer|matching|paragraph|calculated|multiple choice|other
  question_config: |1|2| - | text only | text on left, image on the right
  answers_config: |1|2|3|4| - | text only | -  | - | images

  Constructor: ITS_question(student_id,file_name,db_name,table_name)

  Methods: load_DATA_from_DB($q_num)
  * get_data()
  * render_ANSWERS($name,$mode) { // MODE: 1-Question | 2-EDIT
  * createEditTable($TargetName,$Target,$style)
  * renderQuestionForm($action)

  ex. $ITS_question = new ITS_question(90001,"its","user_cpt");

  Author(s): Greg Krudysz | Aug-28-2008
  Last Revision: Mar-5-2012
*/
//=====================================================================//

/*-- TAGGING module ----------------------------------*/
require_once("plugins/tagging/ITS_tagInterface.php");

class ITS_question {

    var $student_id;    // ITS student ID
    var $file_name;     // initial CPT file name (e.g. "ch7_iCPT.txt")
    var $db_name;       // DB name (e.g. "its")
    var $tb_name;       // question table name
    var $max_cols;      // max number of prob. entries for each node
    var $cpt_array;     // CPT array
    var $cpt_attrib;    // CPT table attributes
    var $fields;
    var $timestamp;

    public $Q_type;     // question type: |S|M|P|C|MC|O|
    public $Q_type_arr = array('mc', 'm', 'c', 's', 'p');
    public $Q_num;
    public $Q_title;
    public $Q_question;
    public $Q_image;
    public $Q_answers;
    public $Q_vals;
    public $Q_answers_config;
    public $Q_question_config;
    public $Q_category;
    public $style;
    public $Q_answers_permutation;
    var $Q_answers_fields = array();
    var $Q_answers_values = array();
    var $Q_weights_values = array();
    var $Q_images_values  = array();
    // Added by Khyati
    public $Q_question_parts = array();
    ////
    var $edit_flag = 0;

    public function set_Q_type($type) {
        $this->Q_type = $type;
    }

    public function get_Q_type() {
        return $this->Q_type;
    }

    public function set_Q_title($title) {
        $this->Q_title = $title;
    }

    public function get_Q_title() {
        return $this->Q_title;
    }

    public function set_Q_question($question) {
        $this->Q_question = $question;
    }

    public function get_Q_question() {
        return $this->Q_question;
    }

    public function set_Q_answers($answers) {
        $this->Q_answers = $answers;
    }

    public function get_Q_answers() {
        return $this->Q_answers;
    }

    // Constructor //======================================================//
    function __construct($student_id, $db_name, $table_name) {
        //=====================================================================//
        global $db_dsn, $db_name, $tb_name, $db_table_user_state, $mimetex_path, $files_path;

        $this->student_id = $student_id;
        $this->db_name 	  = $db_name;
        $this->tb_name 	  = $table_name;
        $this->cpt_array  = array();
        $this->cpt_attrib = array();
        $this->style 	  = 'ITS';
        $this->mimetex_path = $mimetex_path;
        $this->files_path   = $files_path;
        $this->fields = 'id,qtype,title,question,image,answers,answers_config,question_config,category';
    }
    //=====================================================================//
    function load_DATA_from_DB($qid) {
        //=====================================================================//
        // Pull out webct data
        $query = 'SELECT ' . $this->fields . ' FROM ' . $this->tb_name . ' WHERE id=' . $qid;
        //echo '<p>'.$query; die();
        $res = mysql_query($query);
        if (!$res) {
            die('Query execution problem in ITS_question: ' . msql_error());
        }
        $data = mysql_fetch_array($res);

        //print_r($data);die();
        //mysql_close($link);

        self::load_DATA($data);
    }
    //=====================================================================//
    function load_DATA($data) {
        //=====================================================================//
        //print_r($data);die();

        if (empty($data[0])) {
            $data[0] = '';
        }
        if (empty($data[1])) {
            $data[1] = '';
        }
        if (empty($data[2])) {
            $data[2] = '';
        }
        if (empty($data[3])) {
            $data[3] = '';
        }
        /*if (empty($data[4])) {
            $data[4] = '';
        }*/
        if (empty($data[5])) {
            $data[5] = 0;
        }

        $this->Q_num = $data[0];
        $this->Q_type = strtolower($data[1]);
        $this->Q_title = $data[2];
        $this->Q_question = $data[3];
        $this->Q_image = $data[4];
        $this->Q_answers = $data[5];

        if (isset($data[6])) {
            $this->Q_answers_config = $data[6];
        } else {
            $this->Q_answers_config = 1;
        } // default
        if (isset($data[7])) {
            $this->Q_question_config = $data[7];
        } else {
            $this->Q_question_config = 1;
        } // default
        $this->Q_category = $data[8];
    }
    //=====================================================================//
    function render_TITLE() {
        //=====================================================================//
        // Question info and debug
        echo "<input type=hidden id=ITS_question_info value=" . $this->Q_num . " name=" . $this->Q_type . ">";

        $TABLE_TITLE = createEditTable('TITLE', $this->Q_title, "ITS");

        $title_str = '<p><div class="SubHeading">' . $TABLE_TITLE . '</div>';
        return $title_str;
    }

    //=====================================================================//
    function render_QUESTION_check($conf) { // mode: (0-rand) | (1-DB) parameters
        //=====================================================================//
       
        if ($this->Q_type == 'c') { // replace question variable {v} with rv //
            //echo 'MODE: '.$mode.' at '.date('l jS \of F Y h:i:s A').'<p>';
            // echo '<font color="blue">render_QUESTION_check()</font>:';
            $query = "SELECT vals FROM " . $this->tb_name . "_" . $this->Q_type . " WHERE id=" . $this->Q_num;
            //echo $query;
            $res  = mysql_query($query);
            $vals = mysql_fetch_array($res);
			// Fetch answer options text
			// Khyatis changes start
			$fields = 'text1';
			for($k=1;$k<=$this->Q_answers;$k++){
				$fields .=', text'.($k+1);
			}
			$query = "SELECT " . $fields . " FROM " . $this->tb_name . "_" . $this->Q_type . " WHERE id=" . $this->Q_num;
			$res = mysql_query($query);
			if (!$res) {
				die('Query execution problem in ITS_question: ' . msql_error());
			}
			$this->Q_question_parts = mysql_fetch_assoc($res);
			
			
            // get token fields
            $fields = "val1,min_val1,max_val1";
            for ($i = 2; $i <= $vals[0]; $i++) {
                $fields = $fields . ",val" . $i . ",min_val" . $i . ",max_val" . $i;
            }

            $query = " SELECT " . $fields . " from " . $this->tb_name . "_" . $this->Q_type . " WHERE id=" . $this->Q_num;
            //echo $query;
            $res = mysql_query($query);
            if (!$res) {
                die('Query execution problem in ITS_question: ' . msql_error());
            }
            $vdata = mysql_fetch_array($res);
            $question = $this->Q_question;

            $mode = intval(empty($conf));
         //ITS_debug('<p>'.$conf.'<br>'.$mode.'</p>');           
            //echo 'MODE: '.$mode.' at '.date('l jS \of F Y h:i:s A').'<p>';
            switch ($mode) {
                //-------------------------------------------//
                case 0:
                //-------------------------------------------//
                    $vals = explode(',', $conf);
                    for ($i = 1; $i <= count($vals); $i++) {
                        //echo $vdata["val".$i].' '.$vals[($i-1)];
                        $question = str_replace($vdata["val" . $i], $vals[($i - 1)], $question);
                        //$this->Q_question_parts['text'.$i] = str_replace($vdata["val" . $i], $vals[($i - 1)], $this->Q_question_parts['text'.$i]);
				      for($k=1;$k<=$this->Q_answers;$k++){
							$this->Q_question_parts['text'.$k] =  str_replace($vdata["val" . $i], $rnv[($i - 1)],  $this->Q_question_parts['text'.$k]);
					  }                         
                    }                   
                    break;
                //-------------------------------------------//
                default:
                //-------------------------------------------//
                    $rnv = array();
                    for ($i = 1; $i <= $vals[0]; $i++) {
                        if ($vdata["min_val" . $i] == 0 & $vdata["max_val" . $i] == 1) {
                            $rnv[($i - 1)] = rand(1, 9) / 10;  // fraction 0.x
                        } else {
                            $rnv[($i - 1)] = rand($vdata["min_val" . $i], $vdata["max_val" . $i]);
                        }
                        //echo $vdata["min_val".$i].'___'.$vdata["max_val".$i].'<br>';
                        //echo $vdata["val".$i].' '.$rnv[($i-1)];
                        $question = str_replace($vdata["val" . $i], $rnv[($i - 1)], $question);
                        //  echo("heeloooooooooo".$this->Q_question_parts['text1'].$vdata["val" . $i]. $vals[($i - 1)]);
						for($k=1;$k<=$this->Q_answers;$k++){
							$this->Q_question_parts['text'.$k] =  str_replace($vdata["val" . $i], $rnv[($i - 1)],  $this->Q_question_parts['text'.$k]);
						}
                        $this->Q_answers_permutation[$i] = $rnv[($i - 1)];
                    }
                    //var_dump($this->Q_answers_permutation);
                    break;
                //-------------------------------------------//
            }

            $this->Q_question = $question;
        }
        $question_check_str = self::render_QUESTION();
        return $question_check_str;
    }

    //=====================================================================//
    function render_QUESTION_simple() {
        //=====================================================================//
        //echo "<DIV class=Question>".$this->Q_question."</DIV>";
        $question_simple_str = $this->Q_question;

        return $question_simple_str;
    }
    //=====================================================================//
    function render_QUESTION() {
        //=====================================================================//
        $ques_str = $this->Q_question;
        /*
                $query_tag_id = "SELECT tag_id FROM " . $this->tb_name ." WHERE id=" . $this->Q_num;
                $res = mysql_query($query_tag_id);
                if (!$res) {die('Query execution problem in ITS_question: ' . msql_error());}
                $tag_ids = mysql_result($res,0);

                //$query  = 'SELECT id,name FROM tags WHERE id IN ('.$tag_id.')';
                //echo $tag_id;
                //echo array_map('intval',$tag_id);
                //die();
                $query  = 'SELECT id,name FROM tags WHERE id IN ('.$tag_ids.')';
                
                 // array_map('intval',$tag_id)
                 //echo $query;
                $res = mysql_query($query);
                if (!$res) {die('Query execution problem in ITS_question: ' . msql_error());}
    //var_dump($tags);
    //die();
		//--- REGEXP ---//
			/*$tag_list = '';
	    	echo '<pre>';
	print_r($tags);
	echo '</pre>';
	    echo count($tags);    
	    //die();	

		while ($row = mysql_fetch_array($res, MYSQL_ASSOC)) {
		$tag_href = '<a class="tagref" tid="'.$row['id'].'">'.$row['name'].'</a>';
		//$pattern = '/(\w+) (\d+), (\d+)/i';
		//$replacement = '${1}1,$3';
		//echo $tag_href;
		$ques_str = str_ireplace($row['name'],$tag_href,strtolower($ques_str));
		$tag_list .= '<hr><div class="taginfo">'.$row['name'].'</div>';
		}
		$ques_str .=  $tag_list;
        */
        //-------------//

        //--DEBUG--// echo '<pre>';print_r('<font color=green>'.$ques_str.'</font>');echo '</pre>';

        //echo $TABLE_QUESTION.'<hr>';
        //--- IMAGE ----------------//
        //echo getcwd() . "\n"; //die();
        //echo '<br>img src="' . $this->files_path . '--'.$this->Q_image;
        //die();

        if ($this->Q_image) {
/*---NEW---			
            $query_img = 'SELECT dir,name FROM images WHERE id='. $this->Q_image;
            $res = mysql_query($query_img);
            if (!$res) {
                die('Query execution problem in ITS_question: ' . msql_error());
            }

            $row = mysql_fetch_assoc($res);
            $src = $this->files_path.'/'.$row['dir'].'/'.$row['name'];
                        $img = '<img src="' . $src . '" class="ITS_question_img" alt="' . $src . '">';
---NEW---*/
            //echo '<br>';
            //die($src);
            //$src = 'ITS_FILES/images/question/5/lighthouse.png';

            $img = '<img src="' . $this->files_path . $this->Q_image . '" class="ITS_question_img" alt="' . $this->files_path . $this->Q_image . '">';
            //$img = '<a class="example2" href="' . $src . '"><img src="' . $src . '" alt="' . $src . '"></a>';
        } else {
            $img = '';
        }
		//echo $this->files_path . $this->Q_image.'<br>';
        //$TABLE_IMAGE = $this->createImageTable('IMAGE_ID', $img, "ITS_QUESTION");
        $TABLE_QUESTION = $this->createEditTable('QUESTION', $img.$ques_str, "ITS_QUESTION");
        $TABLE_QUESTION = $this->renderFieldCheck($TABLE_QUESTION);
        //--------------------------//
        //echo $this->Q_question_config;
        //if ($this->Q_question_config == 2){ //---- TITLED ----//
        //$question_str = '<table><tr><td>'.$TABLE_QUESTION.'</td></tr></table>'
        /*
          $question_str = '<div class="ITS_question_text2">'.$TABLE_QUESTION.'</div>'
          .'<div class="ITS_question_img">'.$img.'</div>';
        */

        //$question_str = '<ul class="ITS_list" style="display: inline;"><li>'.$TABLE_QUESTION.'</li><li>'.$img.'</li></ul>';
        //$tb = new ITS_table('question_image',1,2,array($TABLE_QUESTION,$img),array(60,40),'ITS_QUESTION');
        //$question_str = $tb->str;
        //}else{
        //$question_str = "<p><DIV class=Question>" . $TABLE_QUESTION;
        //$question_str = $this->Q_question;
        //$question_str = $question_str . "</DIV>";
        //}

        //$question_str     = '<div style="float:left;border:2px solid green;clear:left;overflow:auto" >' . $TABLE_QUESTION . '</div>';
        //$question_img   = '<div class="ITS_question_img">' . $TABLE_IMAGE . '</div>';
        $div_ITS_QUESTION = '<div class="ITS_QUESTION">' . $TABLE_QUESTION . '</div>';

        return $div_ITS_QUESTION;
    }
    //=====================================================================//
    function get_data() {
        //=====================================================================//
        $data = array($this->Q_num,
                $this->Q_type,
                $this->Q_title,
                $this->Q_question,
                $this->Q_image,
                $this->Q_answers,
                $this->Q_category);
        return $data;
    }
    //=====================================================================//
    function render_data() {
        //=====================================================================//
        //var_dump($this->Q_image);     //

        if (empty($this->Q_image)) {
            //<form method="POST" enctype="multipart/form-data"><input type="hidden" name="protocol" value="http"><input type="file" name="files[]" multiple></form>';
            //$img = '<form name="ITS_file" action="upload2.php" enctype="multipart/form-data" method="POST"><input name="ITS_image" size="10" type="file"><input id="testme" name="upload" value="Upload" type="submit"><input type="hidden" name="qid" value="'.$this->Q_num.'" /></form>';
            $img = '<form name="ITS_file" action="ajax/ITS_image.php" enctype="multipart/form-data" method="POST"><input name="ITS_image" size="10" type="file"><input id="testme" name="upload" value="Upload" type="submit"><input type="hidden" name="qid" value="'.$this->Q_num.'" /><noscript><input type="submit" value="Submit"></noscript></form>';
        } else {
            $img = $this->Q_image;
        }
//var_dump($img);
        $qid = $this->Q_num;
        if (empty($qid)) {
            $qid = 1;
        }

        $t = new ITS_tag('tags');
        $Ques_tag_arr  = $t->getByResource($this->tb_name,$qid);
        $Ques_tag_list = $t->render($Ques_tag_arr,$qid,$this->tb_name);
        //$Ques_tag_list = '';
        //var_dump($this->tb_name);die();

        //-- search box --//
        $s  = new ITS_search();
        $sb = $s->renderBox($this->tb_name,$qid);
        //---
        //$tagBox = new ITS_tagInterface();
        //$tags   = $tagBox->displayTags($this->id,$qid,$tagBox->getTags($qid));
        //$stags  = $b.'<br>'.$tagBox->createSearchAddBox(1,$qid);
        //=======
        //echo $style;

        $style = 'ITS';
        $css   = 'ITS_QUESTION_DB';
        //                '<td class="' . $css . '">' . $this->createEditTable('tags', $tagList, $style) . '</td>' .
        $db1 = '<tr><th>Title</th><th>Ans</th><th>Category</th></tr>' .
                '<tr>' .
                '<td class="' . $css . '">' . $this->createEditTable('title', $this->Q_title, $style) . '</td>' .
                '<td class="' . $css . '">' . $this->createEditTable('answers', $this->Q_answers, $style) . '</td>' .
                '<td class="' . $css . '">' . $this->createEditTable('category', $this->Q_category, $style) . '</td>' .
                '</tr>'.
                '<tr><th colspan="4">Tags</th></tr><tr><td colspan="4">'.$Ques_tag_list.$sb.'</td></tr>';
        $db2 = '';

        switch ($this->Q_type) {
            case 'c':
            
            /** Khyati's Changes ***/
              $vals   = $this->Q_answers_values; // contains the formulaes
                $fields = $this->Q_answers_fields;
                $Nvals = $this->Q_vals;
                $db2 .= '<tr>';
                for($k=0;$k<$this->Q_answers;$k++){
					$w=$k+1;
					$db2 .= '<th>Formula'.$w.'</th>';
					$edit_tb[$k] = $this->createEditTable('formula'.$w, $vals[$k], $style);
				}
                
                $db2 .= '<th>Value</th><th>Min value</th><th>Max value</th></tr>'
                      .'<tr>';
                for($k=0;$k<$this->Q_answers;$k++)    
						$db2 .= '<td rowspan="' . $Nvals . '" class="' . $css . '">' . $edit_tb[$k] . '</td>';
                for ($f = 0; $f < $Nvals; $f++) {
                    $val_tb = $this->createEditTable('val' . ($f + 1), $vals[3 * $f + $this->Q_answers + 1], $style);
                    $min_tb = $this->createEditTable('min_val' . ($f + 1), $vals[3 * $f + $this->Q_answers + 2], $style);
                    $max_tb = $this->createEditTable('max_val' . ($f + 1), $vals[3 * $f + $this->Q_answers + 3], $style);
                    //$answer_str .= '<font color="blue">'.$f.'</font> = '.$vals[$f].'<br>';
                    $db2 .= '<td class="ITS_QUESTION_DB">' . $val_tb . '</td><td class="' . $css . '">' . $min_tb . '</td><td class="' . $css . '">' . $max_tb . '</td></tr>';
                } 
             /** Khyati s changes end*/
             /*   $vals   = $this->Q_answers_values;
                $fields = $this->Q_answers_fields;
                $Nvals  = (count($fields) - 1) / 3;
                //ITS_debug($fields); // die();
                $edit_tb = $this->createEditTable('formula', $vals[0], $style);
                $db2 .= '<tr><th>formula</th><th>value</th><th>min value</th><th>max value</th></tr>'
                        . '<tr><td rowspan="' . $Nvals . '" class="' . $css . '">' . $edit_tb . '</td>';

                for ($f = 0; $f < $Nvals; $f++) {
                    $val_tb = $this->createEditTable('val' . ($f + 1), $vals[3 * $f + 1], $style);
                    $min_tb = $this->createEditTable('min_val' . ($f + 1), $vals[3 * $f + 2], $style);
                    $max_tb = $this->createEditTable('max_val' . ($f + 1), $vals[3 * $f + 3], $style);
                    //$answer_str .= '<font color="blue">'.$f.'</font> = '.$vals[$f].'<br>';
                    $db2 .= '<td class="ITS_QUESTION_DB">' . $val_tb . '</td><td class="' . $css . '">' . $min_tb . '</td><td class="' . $css . '">' . $max_tb . '</td></tr>';
                }
                * 
                */
                //$tb = new ITS_table('ANSWER_C',1,1,$tb_C_str,array(100),$class);
                //$answer_str = '<center><div class="ITS_ANSWER">'.$tb_C_str.'</div></center>';
                break;
            default: $db2 = '';
        }

        $tb  = '<table class="'.$css.'">' . $db1 . $db2 . '</table>';
        $str = '<p><center>'.$tb.'</center></p>';
        return $str;
    }
    //=====================================================================//
    function get_ANSWERS_data_from_DB() {
        //=====================================================================//
        //echo 'get_ANSWERS_data_from_DB'.$this->Q_type; die();
        switch ($this->Q_type) {
            //-------------------------------------------//
            case 's':
            //-------------------------------------------//
                break;
            //-------------------------------------------//
            case 'mc':
            //-------------------------------------------//
                $n = $this->Q_answers;
                $fields = "answer1";
                $weights = "weight1";
                $images = "image1";
                for ($i = 2; $i <= $n; $i++) {
                    $fields  .= ",answer" . $i;
                    $weights .= ",weight" . $i;
                    $images  .= ",image"  . $i;
                }

                $query = "SELECT " . $fields . " FROM " . $this->tb_name . "_" . $this->Q_type . " WHERE id=" . $this->Q_num;
                $res = mysql_query($query);
                if (!$res) {
                    die('Query execution problem in ITS_question: ' . msql_error());
                }
                $answers = mysql_fetch_array($res);

                $query = "SELECT " . $weights . " FROM " . $this->tb_name . "_" . $this->Q_type . " WHERE id=" . $this->Q_num;
                $res = mysql_query($query);
                if (!$res) {
                    die('Query execution problem in ITS_question: ' . msql_error());
                }
                $weights = mysql_fetch_array($res);

                $query = "SELECT " . $images . " FROM " . $this->tb_name . "_" . $this->Q_type . " WHERE id=" . $this->Q_num;
                $res = mysql_query($query);
                if (!$res) {
                    die('Query execution problem in ITS_question: ' . msql_error());
                }
                $images = mysql_fetch_array($res);

                $this->Q_answers_fields = $fields;
                $this->Q_answers_values = $answers;
                $this->Q_weights_values = $weights;
                $this->Q_images_values  = $images;
                break;
            //-------------------------------------------//
            case 'p':
            //-------------------------------------------//
            // TEMPLATE
                $query = "SELECT template FROM " . $this->tb_name . "_" . $this->Q_type . " WHERE id=" . $this->Q_num;
                $res = mysql_query($query);
                if (!$res) {
                    die('Query execution problem in ITS_question: ' . msql_error());
                }
                $template = mysql_fetch_array($res);
                if (!empty($template)) {
                    $this->Q_answers_values = $template;
                }
                break;
            //-------------------------------------------//
            case 'm':
            //-------------------------------------------//

                $n = $this->Q_answers;
                $L_fields = "L1";
                $L_images = "Limage1";
                $R_fields = "R1";
                $R_images = "Rimage1";
                for ($i = 2; $i <= $n; $i++) {
                    $L_fields .=  ",L" . $i;
                    $R_fields .=  ",R" . $i;
                    $L_images .=  ",Limage" . $i;
                    $R_images .=  ",Rimage" . $i;
                }

                $Lquery  = "SELECT " . $L_fields . " FROM " . $this->tb_name . "_" . $this->Q_type . " WHERE id=" . $this->Q_num;
                $Rquery  = "SELECT " . $R_fields . " FROM " . $this->tb_name . "_" . $this->Q_type . " WHERE id=" . $this->Q_num;
                $Liquery = "SELECT " . $L_images . " FROM " . $this->tb_name . "_" . $this->Q_type . " WHERE id=" . $this->Q_num;
                $Riquery = "SELECT " . $R_images . " FROM " . $this->tb_name . "_" . $this->Q_type . " WHERE id=" . $this->Q_num;

                $Lres  = mysql_query($Lquery);
                $Rres  = mysql_query($Rquery);
                $Lires = mysql_query($Liquery);
                $Rires = mysql_query($Riquery);

                if (!$Lres) {
                    die('Query execution problem in ITS_question: ' . msql_error());
                }

                $L_answers = mysql_fetch_array($Lres);
                $R_answers = mysql_fetch_array($Rres);
                $L_images  = mysql_fetch_array($Lires);
                $R_images  = mysql_fetch_array($Rires);

                $this->Q_answers_fields = array($L_fields, $R_fields);
                $this->Q_answers_values = array($L_answers, $R_answers);
                $this->Q_images_values  = array($L_images, $R_images);
                break;
            //-------------------------------------------//
            case 'c':
            //-------------------------------------------//
                $query = "SELECT vals FROM " . $this->tb_name . "_" . $this->Q_type . " WHERE id=" . $this->Q_num;
                //echo $query.'<p>';
                $res = mysql_query($query);
                if (!$res) {
                    die('Query execution problem in ITS_question: ' . msql_error());
                }
                $Nanswers = mysql_fetch_array($res);
                // Khyati s cahnges start
                $this->Q_vals = $Nanswers[0];
                $n = $this->Q_answers;
                $fields = 'formula';
                for($k=1;$k<=$n;$k++){
					$fields .= ', formula'.($k+1);
				}
				
                // Khyatis changes end
                
                for ($i = 1; $i <= $Nanswers[0]; $i++) {
                    $fields .= ',val' . $i . ',min_val' . $i . ',max_val' . $i;
                }
/*
                //$n = $this->Q_answers;
                $fields = 'formula';
                for ($i = 1; $i <= $Nanswers[0]; $i++) {
                    $fields .= ',val' . $i . ',min_val' . $i . ',max_val' . $i;
                }
                */

                $query = 'SELECT ' . $fields . ' FROM ' . $this->tb_name . '_' . $this->Q_type . ' WHERE id=' . $this->Q_num;
                //echo $query.'<p>'; die();
                $res = mysql_query($query);
                if (!$res) {
                    die('Query execution problem in ITS_question: ' . msql_error());
                }
                $answers = mysql_fetch_array($res);

                $this->Q_answers_fields = explode(',', $fields);
                $this->Q_answers_values = $answers;
                //$this->Q_weights_values = $weights;
                break;
        }
    }
    //=====================================================================//
    function render_ANSWERS($name, $mode) { // MODE: 1-Question | 2-EDIT
        //=====================================================================//
        $answer_str = '';
        //--DEBUG--// ITS_debug($mode);

        switch ($this->Q_type) {
            //-------------------------------------------//
            case 's':
            //-------------------------------------------//
                $answer_str = $answer_str
                        . '<form action=score.php method=post name=form1>'
                        . '<textarea class=TXA_ANSWER name=TXA_ANSWER width=100% cols=80% rows=3></textarea>'
                        . '<p><noscript><input type="submit" value="Submit"></noscript></form>';
                break;
            //-------------------------------------------//
            case 'mc':
            //-------------------------------------------//
                $rows = $this->Q_answers;
                $width = array(2, 2, 96);
                $answer = array();

                //--DEBUG--// ITS_debug($rows);
                $str = '<p><div class="ITS_ANSWER_IMG">';
                for ($i = 1; $i <= $rows; $i++) {
                    if (($i % 2) == 0) {
                        $style = "ITS_ANSWER_STRIPE";
                    } else {
                        $style = "ITS_ANSWER";
                    }

                    if ($this->Q_answers_config == 3) {
                        $style = "ITS_ANSWER";
                    }

                    $ans = $this->Q_answers_values["answer" . $i];
                    $ans = $this->renderFieldCheck($ans);
                    $answer[$i] = trim($ans);
                    $weight[$i] = $this->Q_weights_values[$i - 1];
                    $image[$i]  = $this->renderQuestionImage($this->Q_images_values[$i - 1]);
                    switch ($mode) {
                        case 2: // 2-Edit
                            $ans = $this->Q_answers_values["answer" . $i];
                            $answer[$i] = $this->createEditTable('ANSWER' . $i, trim($ans), 'ITS_ANSWER');
                            $answer[$i] = $this->renderFieldCheck($answer[$i]);
                            $weight[$i] = $this->createEditTable('WEIGHT' . $i, $this->Q_weights_values[$i - 1], 'ITS_WEIGHT');

                            $image[$i]  = $this->createImageTable('IMAGE' . $i, $image[$i], 'ITS_IMAGE');
                            break;
                    }

                    // solution check and selection
                    $checked = 'false';
                    $chk = '<input type="radio" name="' . $name . '" id="' . $name . '" value="' . chr($i + 64) . '" "' . $checked . '">';
                    //$chk  = "<input type=checkbox name=".$name."[".($i)."] value=".chr($i+64)." ".$checked.">";
                    //$data = array('<span id="TextAlphabet'.chr($i+64).'" class="TextAlphabet">'.chr($i+64).'.</span>',$chk,$edit_tb);
                    //$tb   = new ITS_table('ANSWER_'.$this->Q_type,1,count($data),$data,$width,$style);
                    //$answer[$i] = $tb->str;
                    //$style = "ITS_ANSWER";
                    //$edit_tb2 = $this->createEditTable('ANSWER'.$i,'<span id="TextAlphabet'.chr($i+64).'" class="TextAlphabet">'.chr($i+64).'.</span>'.trim($this->Q_answers_values["answer".$i]),$style);
                    //$str = $str.'<li name="answerLab_active" id="answerLab_active" onclick=ITS_content_select(this)><span id="TextAlphabet'.chr($i+64).'" class="TextAlphabet">'.chr($i+64).'.</span>'.trim($this->Q_answers_values["answer".$i]).'</li>';

                    $caption[$i] = '<span id="TextAlphabet' . chr($i + 64) . '" class="TextAlphabet">' . chr($i + 64) . '.</span>';  //chr($i+64)
                }
                //var_dump($this->Q_answers_config);//die();
                //echo $mode;
                $answer_str = new ITS_configure($this->Q_num, $caption, $answer, $weight, $image, $this->Q_answers_config, $mode);
                //$str = $str.'</ul>';
                $answer_str = $answer_str->str;

                break;
            //-------------------------------------------//
            case 'p':
            //-------------------------------------------//
            // TEMPLATE
                $template = $this->Q_answers_values;
                if (!empty($template)) {
                    $TABLE_TEMPLATE = $this->createEditTable('TEMPLATE', $template[0], "ITS_TEMPLATE");
                    $answer_str = $answer_str . '<br>' . $TABLE_TEMPLATE;
                }

                // ANSWERS
                switch ($mode) {
                    case 0: $answer_str = '';
                        break;
                    case 2: $answer_str = '';
                        break;
                    default:
                        for ($n = 1; $n <= $this->Q_answers; $n++) {
                            $answer_str = $answer_str . '<textarea class="TXA_ANSWER" id="ITS_TA" name="' . $name . '"></textarea>';
                        }
                }
                break;
            //-------------------------------------------//
            case 'm':
            //-------------------------------------------//
            //$this->mode = 1;
                $n = $this->Q_answers;
                $answers_values = $this->Q_answers_values;
                $L_answers = $answers_values[0];
                $R_answers = $answers_values[1];

                $images_values = $this->Q_images_values;
                $L_images = $images_values[0];
                $R_images = $images_values[1];

                $ii = 1;
                $L_list = '';
                for ($i = 1; $i <= $n; $i++) {
                    //echo $L_answers["L".$i].' ~ '.$R_answers["R".$i].'<p>';
                    $check_NULL  = !strcmp($L_answers["L" . $i], 'NULL');
                    $check_EMPTY = empty($L_answers["L" . $i]);
                    if (!(($check_NULL) OR ($check_EMPTY))) {
                        $L[$i - 1] = $i;
                    } else {
                        $L[$i - 1] = -$i;
                    } //$L_list .= $i.','; }//$nn++; }
                    //$L_answers["L".$ii] = $L_answers["L".$i];
                    //$ii++;
                }
                $R = $L; //range(1,count($R_answers)/2);
                //print_r($R); die();

                switch ($mode) {
                    //-------------------------------------------//
                    case 2:
                    //-------------------------------------------//
                        $inactive = '_inactive';
                        break;
                    //-------------------------------------------//
                    case 1:
                    //-------------------------------------------//
                        $inactive = '';
                        shuffle($R);
                        //$R = array(-1,7,2,-6,3,5,4); // C,E,G,F,B
                        //$R = array(3,-1,7,4,2,-6,5); // C,E,G,F,B
                        //echo '<p>AFTER SHUFFLE: '.implode(',',$R).'<p>';
                        //print_r($R); echo '<p>count(R) mode=1: '.count($R).'<p>';
                        break;
                    //-------------------------------------------//
                    default:
                    //-------------------------------------------//
                        $inactive = '_inactive';  //print_r($this->Q_answers_permutation);
                        // Config from DB
                        $query = 'SELECT comment FROM stats_' . $this->student_id . ' WHERE question_id=' . $this->Q_num . ' ORDER BY id';
                        //echo 'IN MODE=default<p>'.$query; //die();
                        $res = mysql_query($query);
                        if (!$res) {
                            die('Query execution problem in ITS_question: ' . msql_error());
                        }
                        $C = mysql_fetch_array($res);
                        //echo 'C<p>'; //print_r($C);//echo count($C); die();
                        $R = explode(',', $C[0]); //$this->Q_answers_permutation;
                    //print_r($R);
                    //echo '<p>AFTER SHUFFLE (INACTIVE): '.implode(',',$R).'<p>';die();
                }

                // construct ANSWERS table
                $rows = $this->Q_answers;
                $width = array(10, 40, 50);
                //--------------------------------//
                // LEFT TABLE
                //--------------------------------//
                $nn = 0;
                $tb_L_str = '';

                $class = 'ansCheck';
                $nn = count($R);

                $ii = 1;
                $ik = 1;
                for ($i = 1; $i <= $nn; $i++) {
                    $check_NULL = !strcmp($L_answers["L" . $i], 'NULL');
                    $check_EMPTY = empty($L_answers["L" . $i]);
                    if (!(($check_NULL) OR ($check_EMPTY))) {
                        if (($i % 2) == 0) {
                            $style = "ITS_ANSWER_STRIPE";
                        } else {
                            $style = "ITS_ANSWER";
                        }

                        $bank = '';
                        for ($b = 1; $b <= $n; $b++) {
                            //echo '<p>id='.$L_idx[$i-1].'_'.$b.'_'.$nn.'_'.$n.'<p>';
                            $bank .= '<label class="' . $class . $inactive . '" id="label_check_' . $i . '_' . $b . '_' . $nn . '_' . $n . '" for="check_' . $i . '_' . $b . '_' . $nn . '_' . $n . '"><input type="checkbox" class="' . $class . '" id="check_' . $i . '_' . $b . '_' . $nn . '_' . $n . '" name="checkL"/>' . chr($b + 64) . '</label>';
                        }
                        $style = '';
                        //echo '<p>'.$L_idx[$i-1].' - '.$L_answers["L".$L_idx[$i-1]].'<p>';
                        //DEBUG: $edit_tb = $this->createEditTable('L'.$i,"L".$i.' * '.$L_answers["L".$i],$style);
                        //echo 'MODE: '.$mode.'<p>';
                        switch ($mode) {
                            case 0: $ans = $this->renderFieldCheck($L_answers["L" . $i]);
                                break;
                            case 1: $ans = $this->renderFieldCheck($L_answers["L" . $i]);
                                break;
                            case 2:
                                $ans = $this->createEditTable('L' . $i, $L_answers["L" . $i], $style);
                                $ans = $this->renderFieldCheck($ans);
                                $ig  = $this->renderQuestionImage($L_images["Limage" . $i]);
                                $img = $this->createImageTable('Limage' . $i, $ig, $style);
                                break;
                            //case 2: $ans = $this->createEditTable('L'.$i,$L_answers["L".$i],$style); break;
                        }
                        $data[$ii - 1] = '<b>' . $ik . '. </b>';
                        $data[$ii] 	   = '<div class="' . $class . '">' . $bank . '</div>';
                        $data[$ii + 1] = $ans;
                        $data[$ii + 2] = $img;

                        //$tb_L    = new ITS_table('ANSWER_'.$this->Q_type.'_'.$i,1,count($data),$data,$width,$class);
                        //$tb_L_str = $tb_L_str.$tb_L->str;
                        //$tb_L_str .= '<li name="matchingLeft">'.$tb_L->str.'</li>';
                        $ii = $ii + 4;
                        $ik++;
                    }
                }
                $tb_L = new ITS_table('ANSWER_M_Left', count($data) / 4, 4, $data, array(2, 4, 90, 4), $class);
                $tb_L_str = $tb_L->str;
                //$tb_L_str = '<ul id="sortable1" class="ITS_ANSWER_M">'.$tb_L->str.'</ul>';
                //--------------------------------//
                // RIGHT TABLE
                //--------------------------------//
                //print_r($R_answers);die();
                $widthR = array(10, 90);
                $tb_R_str = '<table class="ITS_ANSWER_BOXED">';
                for ($i = 1; $i <= $n; $i++) {
                    //##echo '<p>'.$i.' - '.$R[$i-1].' - '.$R_answers["R".abs($R[$i-1])].'<p>';
                    //if ( $R[$i-1] > 0 ) { //
                    //if (!(is_null($R_answers["R".abs($R[$i-1])]))){
                    //if (($i % 2) == 0){ $style = "ITS_ANSWER_STRIPE"; }
                    //else 			        { $style = "ITS_ANSWER";        }
                    $style = "ITS_ANSWER";

                    $label = '<span class="TextAlphabet">' . chr($i + 64) . '</span>';
                    //DEBUG: $edit_tb = $this->createEditTable('R'.$i,'('.$R[$i-1].') * '.$R_answers["R".abs($R[$i-1])],$style);
                    //
                    //var_dump($R); //echo ($R[$i-1]).' # <p>';//.(abs($R[$i-1])).' # '.$R_answers["R".(abs($R[$i-1]))].'<p><hr>';
                    //if (isset($R[$i-1])) { $ans = $R_answers["R".(abs($R[$i-1]))]; }
                    //else 								 { $ans = '--';                            }
                    $ans = $R_answers["R" . (abs($R[$i - 1]))];
                    $ans = $this->renderFieldCheck($ans);
                    //$xx = preg_replace("/</",'+',$ans);
                    //echo $xx.'<p>';
                    /*
                      preg_match_all('/<img[^>]+>/i',$ans, $result);
                      if (!empty($result)) {
                      //print_r($result);
                      $img = array();
                      foreach( $result as $img_tag) {
                      print_r($img_tag);
                      //preg_match_all('/(src)=("[^"]*")/i',$img_tag, $img[$img_tag]);
                      }
                      //print_r($img);
                      //
                      } */

                    $edit_tb = $this->createEditTable('R' . $i, $ans, $style);

                    $ig   = $this->renderQuestionImage($R_images["Rimage" . $i]);
                    $Rimg = $this->createImageTable('Rimage' . $i, $ig, $style);

                    //==$tb_R = new ITS_table('RT'.$this->Q_type,1,2,array($label,$edit_tb),array(30,70),'ITSxx');
                    $data = array($label, $edit_tb,$Rimg);
                    $style = 'CHOICE_ACTIVE';
                    $tb_R_str .= '<tr><td class="ITS_ANSWER_M">' . $label . '</td><td class="ITS_ANSWER_M"><div class="' . $style . '">' . $edit_tb . '</div></td><td class="ITS_ANSWER_M"><div class="'.$style.'">' . $Rimg . '</div></td></tr>';
                    /*
					$form_image = '<form name="browser" action="server_browser.php"><input type="hidden" name="id" value="'.$this->Q_num.'"><input type="hidden" name="col_name" value="Rimage' . $i.'"><input type="submit" value="Upload bb Image"></form>';
                    $tb_R_str .= '<td>'.$form_image.'</td></tr>';
                    */
                    //}
                }
                $tb_R_str .= '</table>';
                $this->Q_answers_permutation = $R;

                $tb = new ITS_table('ANSWER_' . $this->Q_type, 1, 2, array($tb_L_str, $tb_R_str), array(50, 50), 'ITS');
                $answer_str = $tb->str;
                break;
            /* ======== OLD SOLUTION ==========
              $n = $this->Q_answers;
              $answers_values = $this->Q_answers_values;
              $L_answers = $answers_values[0];
              $R_answers = $answers_values[1];

              $R = range(1,count($R_answers)/2);
              shuffle($R);

              // construct ANSWERS table
              $rows  = $this->Q_answers;
              $width = array(1,99);

              // Left table
              $tb_L_str = '';
              for ($i=1;$i<=$n;$i++){
              $check_NULL = !strcmp($L_answers["L".$i],'NULL');
              $check_EMPTY = empty($L_answers["L".$i]);
              //echo $L_answers["L".$i].'<p>'; //(!$check_EMPTY)
              if (!(($check_NULL) OR ($check_EMPTY))) {
              if (($i % 2) == 0){ $style = "ITS_ANSWER_STRIPE"; }
              else 			        { $style = "ITS_ANSWER";        }

              $edit_tb = $this->createEditTable('L'.$i,$L_answers["L".$i],$style);
              $data    = array("<span class=TextAlphabet>".chr($i+64).".</span>",$edit_tb);
              $tb_L    = new ITS_table('ANSWER_'.$this->Q_type.'_'.$i,1,count($data),$data,$width,$style);
              $tb_L_str = $tb_L_str.$tb_L->str;
              }
              }
              $tb_L_str = '<DIV class="ITS_ANSWER">'.$tb_L_str.'</div>';

              // Right table
              $tb_R_str = '';
              for ($i=1;$i<=$n;$i++){
              if (!(is_null($R_answers["R".$i]))){
              if (($i % 2) == 0){ $style = "ITS_ANSWER_STRIPE"; }
              else 			        { $style = "ITS_ANSWER";        }
              $edit_tb = $this->createEditTable('R'.$i,$R_answers["R".$R[$i-1]],$style);

              $data    = array('<input type=text size=1 maxlength=1 name="'.$name.'" id="'.$name.'" value="">',$edit_tb);
              //$data    = array('<input type=text size=1 maxlength=1 name="'.$name.'['.$i.']" value="">',$edit_tb);
              $tb_R    = new ITS_table('ANSWER_'.$this->Q_type,1,count($data),$data,$width,$style);
              $tb_R_str = $tb_R_str.$tb_R->str;
              }
              }
              $tb_R_str = '<DIV class="ITS_ANSWER">'.$tb_R_str.'</div>';

              $this->Q_answers_permutation = $R;

              $tb = new ITS_table('ANSWER_'.$this->Q_type,1,2,array($tb_L_str,$tb_R_str),array(50,50),'ITS');
              $answer_str = $answer_str . $tb->str;

              $new = '<ol id="selectable">'.
              '<li class="ui-state-default">1</li>'.
              '<li class="ui-state-default">2</li>'.
              '<li class="ui-state-default">3</li>'.
              '<li class="ui-state-default">4</li>'.
              '</ol>';
              echo $new;

              break;
              ======== OLD SOLUTION ========== */              
            //-------------------------------------------//
            case 'c':
            //-------------------------------------------//
                $style = '';
                $class = 'ITS';
                /* for ($i=1;$i<=$N;$i++){
                  $answer[$i] = $this->createEditTable('ANSWER'.$i,$this->data[$i],'ITS_ANSWER');
                  $weight[$i] = $this->createEditTable('WEIGHT'.$i,$this->weight[$i],'ITS_WEIGHT');
                  $tb = '<table class="ITS_ANSWER_BOXED"><tr><td class="ITS_ANSWER_BOXED_ALPH">'.$this->caption[$i].'</td><td class="ITS_ANSWER_BOXED"><span class="'.$style.'">'.$answer[$i].'</span></td><td><div class="CHOICE_WEIGHT">'.$weight[$i].'</div></td></tr></table>';
                  $answer_str .= $tb;
                  } */

                // ANSWERS
                switch ($mode) {
                    case 0: $answer_str = '';
                        break;
                    case 2: $answer_str = '';
                        break;
                    default:
                    //Khyatis changes
                     {
						//Changes to make multiple answer boxes: SHOULD the IDS be different for diff boxes?? how is the scoring done
						for($k=0;$k<$this->Q_answers;$k++)
						$answer_str .= $this->Q_question_parts['text'.($k+1)].'&nbsp;&nbsp;&nbsp;&nbsp;<textarea class="TXA_ANSWER" id="ITS_TA'.$k.'" name="' . $name . '"></textarea><br>';
					}
					$answer_str .= '<input type="hidden" value="'.$this->Q_answers.'" id="answersCount">';
                    // khyatis Changes
                    
                    // $answer_str .= '<textarea class="TXA_ANSWER" id="ITS_TA" name="' . $name . '"></textarea>';
                }
                break;
            //-------------------------------------------//
        }
        //$mysqldate = date( 'Y-m-d H:i:s', $phpdate );
        $this->timestamp = date('Y-m-d H:i:s');
        //echo '<span style="color:#800000;background:pink">ITS_question.php :: render_ANSWERS</span><p>';
        $div_ITS_ANSWER = '<div class="ITS_ANSWER">'. $answer_str .'</div>';

        return $div_ITS_ANSWER;
        // SUBMIT BUTTON
        //echo "<p><input type=submit name=score_question value=Submit>";
    }
    //=====================================================================//
    function get_ANSWERS_solution_from_DB() {
        //=====================================================================//
        switch ($this->Q_type) {
            //-------------------------------------------//
            case 's':
            //-------------------------------------------//
                break;
            //-------------------------------------------//
            case 'mc':
            //-------------------------------------------//
                $n = $this->Q_answers;
                $fields = "weight1";
                for ($i = 2; $i <= $n; $i++) {
                    $fields = $fields . ",weight" . $i;
                }
                $query = "SELECT " . $fields . " from " . $this->tb_name . "_" . $this->Q_type . " WHERE id=" . $this->Q_num;

                $res = mysql_query($query);
                if (!$res) {
                    die('Query execution problem in ITS_question: ' . msql_error());
                }
                $weights = mysql_fetch_array($res);

                $this->Q_weights_values = $weights;
                break;
            //-------------------------------------------//
            case 'p':
            //-------------------------------------------//
                break;
            //-------------------------------------------//
            case 'm':
            //-------------------------------------------//
            //die('need solution');
                break;
            //-------------------------------------------//
            case 'c':
            //-------------------------------------------//
                break;
        }
    }
    //=====================================================================//
    function set_ANSWERS_solution($solution) {
        //=====================================================================//
        switch ($this->Q_type) {
            //-------------------------------------------//
            case 's':
            //-------------------------------------------//
                break;
            //-------------------------------------------//
            case 'mc':
            //-------------------------------------------//
                $n = $this->Q_answers;
                for ($i = 0; $i <= $n - 1; $i++) {
                    if (($solution - 1) == $i) {
                        $weights[$i] = 100;
                    } else {
                        $weights[$i] = 0;
                    }
                }
                $this->Q_weights_values = $weights;
                break;
            //-------------------------------------------//
            case 'p':
            //-------------------------------------------//
                break;
            //-------------------------------------------//
            case 'm':
            //-------------------------------------------//
                break;
            //-------------------------------------------//
            case 'c':
            //-------------------------------------------//
                break;
        }
    }
    //=====================================================================//
    function get_ANSWERS_solution() {
        //=====================================================================//
        switch ($this->Q_type) {
            //-------------------------------------------//
            case 's':
            //-------------------------------------------//
                break;
            //-------------------------------------------//
            case 'mc':
            //-------------------------------------------//
                $n = $this->Q_answers;
                $mx_weight = max($this->Q_weights_values);

                for ($i = 0; $i <= $n - 1; $i++) {
                    if ($this->Q_weights_values[$i] == $mx_weight) {
                        return chr(65 + $i);
                    }
                }
                break;
            //-------------------------------------------//
            case 'p':
            //-------------------------------------------//
                break;
            //-------------------------------------------//
            case 'm':
            //-------------------------------------------//
                break;
            //-------------------------------------------//
            case 'c':
            //-------------------------------------------//
                break;
        }
    }
    //=====================================================================//
    function renderQuestionImage($img_val) {
        //=====================================================================//
        if ($img_val) {
            $query_img = 'SELECT dir,name FROM images WHERE id='. $img_val;
            $res = mysql_query($query_img);
            if (!$res) {
                die('Query execution problem in ITS_question: ' . msql_error());
            }

            $row = mysql_fetch_assoc($res);
            $src = $this->files_path.'/'.$row['dir'].'/'.$row['name'];
            $img = '<img src="' . $src . '" class="ITS_question_img" alt="' . $src . '">';
        } else {
            $img = '';
        }

        return $img;
    }
    //=====================================================================//
    function renderQuestionForm($action) {
        //=====================================================================//
        $act    = explode('~', $action);                        // echo $act[0];
        $class  = 'text ui-widget-content ui-corner-all ITS_Q';
        $fields = explode(',', $this->fields);

        /* $qtypes = array('Multiple Choice','Matching','Calculated','Short Answer','Paragraph');
          $qtype = '<select id="ITS_qtype" name="qtype" qid="'.$this->Q_num.'">';
          for ($t=1; $t<=count($this->Q_type_arr); $t++) {
          if ($this->Q_type_arr[$t-1]==$act[1]) { $issel = 'selected="selected"'; }
          else                                  { $issel = '';                    }
          $qtype .= '<option '.$issel.' value="'.$this->Q_type_arr[$t-1].'">'.$qtypes[$t-1].'</option>';
          }
          $qtype .= '</select>'; */

        $qtype = '<div id="navContainer">' .
                '<ul id="navListQC">' .
                '<li qtype="mc" value="mc" name="qtype"><a href="#X" id="current">Multiple Choice</a></li>' .
                '<li qtype="m"  value="m"  name="qtype"><a href="#">Matching</a></li>' .
                '<li qtype="c"  value="c"  name="qtype"><a href="#">Calculated</a></li>' .
                '<li qtype="s"  value="s"  name="qtype"><a href="#">Short Answer</a></li>' .
                '<li qtype="p"  value="p"  name="qtype"><a href="#">Paragraph</a></li>' .
                '</ul>' .
                '</div>';

        $form = '<form id="Qform"><fieldset><table class="ITS_newQuestion">';
        $form .= '<tr><td colspan="2" style="position:relative;width:100%;">' . $qtype . '</td></tr>';

        for ($i = 2; $i < count($fields); $i++) {
            $name = 'Q_' . $fields[$i];
            $label = '<label for="' . $fields[$i] . '"><b>' . strtoupper(preg_replace('/_/', ' ', $fields[$i])) . ': </b></label>';
            //--------------------------//
            //ITS_debug($fields[$i]);
            switch ($fields[$i]) {
                //+++++++++++++++++++++++++++++++++++++++++++//
                case 'answers':
                //+++++++++++++++++++++++++++++++++++++++++++//
                    $ansMax = 10;
                    $Nans = $this->$name;
                    /* $sel = '<select id="'.$fields[$i].'" name="'.$fields[$i].'" qid="'.$this->Q_num.'"  style="float:right">';
                      for ($a=1; $a<=$ansMax; $a++) {
                      if ($a==$Nans) { $issel = 'selected="selected"'; }
                      else           { $issel = ''; }
                      $sel .= '<option '.$issel.' value="'.($a).'">'.$a.'</option>';
                      }
                      $sel .= '</select>'; */

                    $sel = '<input type="button" name="changeAnswer" id="remAnswer" v="-" value="&mdash;" class="ITS_buttonQ" /><br>'
                          .'<input type="button" name="changeAnswer" id="addAnswer" v="+" value="+"       class="ITS_buttonQ" />';

                    switch ($this->Q_type) {
                        //-------------------------------------------//
                        case 's':
                        //-------------------------------------------//
                            break;
                        //-------------------------------------------//
                        case 'mc':
                        //-------------------------------------------//
                            $n = $this->Q_answers;
                            var_dump($this->Q_answers_values);
                            $ans = '<table id="ITS_Qans" class="ITS_Qans" n="' . $n . '" qtype="' . $this->Q_type . '">';
                            for ($a = 1; $a <= $n; $a++) {
                                //echo $this->Q_answers_values["answer".$a].'<br>';
                                //htmlspecialchars($this->Q_answers_values["answer".$a])
                                $answer_label = '<label for="answer' . $a . '">' . 'answer&nbsp;' . $a . '</label>';
                                $answer_field = '<textarea name="answer' . $a . '" id="answer' . $a . '" class="' . $class . '">'.htmlspecialchars($this->Q_answers_values[$a - 1]).'</textarea>';
                                $weight_label = '<label for="weight' . $a . '">' . 'weight&nbsp;' . $a . '</label>';
                                $weight_field = '<input type="text" name="weight' . $a . '" id="weight' . $a . '" value="' . htmlspecialchars($this->Q_weights_values[$a - 1]) . '" class="' . $class . '" />';
                                $ans .= '<tr><td width="10%">' . $answer_label . '</td><td width="60%">' . $answer_field . '</td><td width="10%">' . $weight_label . '</td><td width="5%">' . $weight_field . '</td></tr>';
                            }
                            $ans .= '</table>';
                            //$form .= '<tr id="ansQ"><td>'.$label.'<br>'.$sel.'<span id="ansUpdate" class="ansUpdate" action="'.$action.'">update</span></td><td>'.$ans.'</td></tr>';
                            $form .= '<tr id="ansQ"><td>' . $label . $sel . '</td><td>' . $ans . '</td></tr>';
                            break;
                        //-------------------------------------------//
                        case 'p':
                        //-------------------------------------------//
                            break;
                        //-------------------------------------------//
                        case 'm':
                        //-------------------------------------------//
                        //$this->Q_answers_fields = array($L_fields,$R_fields);
                        //$this->Q_answers_values = array($L_answers,$R_answers);
                            $n = $this->Q_answers;
                            $ans = '<table id="ITS_Qans" class="ITS_Qans" n="' . $n . '" qtype="' . $this->Q_type . '">';
                            for ($a = 1; $a <= $n; $a++) {
                                //var_dump($this->Q_answers_values);
                                //echo htmlspecialchars($this->Q_answers_values["answer".$a])
                                //echo $this->Q_answers_values[1]["L".$a].'<hr>';
                                $L_label = '<label for="L' . $a . '">' . 'L&nbsp;' . $a . '</label>';
                                $L_field = '<textarea name="L' . $a . '" id="answer' . $a . '" class="' . $class . '">'.htmlspecialchars($this->Q_answers_values[0]["L".$a]).'</textarea>';
                                $R_label = '<label for="R' . $a . '">' . 'R&nbsp;' . $a . '</label>';
                                $R_field = '<textarea name="R' . $a . '" id="R' . $a . '" class="' . $class . '">'.htmlspecialchars($this->Q_answers_values[1]["R".$a]).'</textarea>';
                                $ans .= '<tr><td width="10%">' . $L_label . '</td><td width="50%">' . $L_field . '</td><td width="10%">' . $R_label . '</td><td width="50%">' . $R_field . '</td></tr>';
                            }
                            $ans .= '</table>';

                            //$ansL = 'fields '.implode(',',$this->Q_answers_fields);
                            //$ansR = 'values '.implode(',',$this->Q_answers_values);
                            $form .= '<tr id="ansQ"><td>' . $label . $sel . '</td><td>' . $ans . '</td></tr>';
                            break;
                        //-------------------------------------------//
                        case 'c':
                        //-------------------------------------------//
                      //-------------------------------------------//
                            $n = 1; //$this->Q_answers;
                            $formula_count = $this->Q_answers;  
                          // Khyatis changes
                            $ans = '<table id="ITS_Qans" class="ITS_Qans" n="' . $n . '" qtype="' . $this->Q_type . '">'
									. '<tr width=100%><td><LABEL >Number of Formulaes</LABEL></td>'
									. '<td><input type="button" value="+" id="add_fcount" class="ITS_buttonQ"></td><td><input type="button" id="dec_fcount" value="-" class="ITS_buttonQ"></td>'
									. '<td width="90%">Weights must sum up to 100</td></tr>'
									. '<tr><input type="hidden" name="answers" id="answers" value="1" /></tr>'
                                    . '<tr class="formla" id="formulaes1" >'
                                    . '<td width="10%"><label for="text1">Text</label></td>'
									. '<td width="30%" ><textarea name="text1" id="text1" value="" class="'. $class . '" /></td>'
                                    . '<td width="10%"><label for="formula">Formula</label></td>'
                                    . '<td width="90%" colspan="6"><textarea name="formula" id="formula" value="" class="' . $class . '" /></td>'
                                    . '<td width="10%"><label for="weight1">Weight</label></td><td width="40%"><input type="text" MAXLENGTH=3 name="weight1" id="weight1" class="' . $class . '"></td></tr>'
                                    . '<tr><td><input type="hidden" name="vals" id="vals" value="'.$n.'" /></td></tr>';
                                    
                            for ($a = 1; $a <= $n; $a++) {
                                $ans .= '<tr>'
                                        . '<td width="10%"><label for="value' . $a . '">value&nbsp;' . $a . '</label></td>'
                                        . '<td width="40%"><input type="text" name="val' . $a . '" id="answer' . $a . '" value="" class="' . $class . '" /></td>'
                                        . '<td width="10%"><label for="minvalue' . $a . '">min</label></td>'
                                        . '<td width="10%"><input type="text" name="min_val' . $a . '" id="minvalue' . $a . '" value="" class="' . $class . '" /></td>'
                                        . '<td width="10%"><label for="maxvalue' . $a . '">max</label></td>'
                                        . '<td width="10%"><input type="text" name="max_val' . $a . '" id="maxvalue' . $a . '" value="" class="' . $class . '" /></td>'
                                        . '</tr>';
                            }
                            $ans .= '</table>';
                            //$ans = '';
							//Khyatis Changes
                            //$form .= '<tr id="ansQ"><td>' . $label . $sel . '</td><td>' . $ans . '</td></tr>';
                            $form .= '<tr id="ansQ"><td><b>Number of Variables</b><br>'. $sel . '</td><td>' . $ans . '</td></tr>';
                            
                            break;
                    }

                    break;
                //+++++++++++++++++++++++++++++++++++++++++++//
                case 'question':
                //+++++++++++++++++++++++++++++++++++++++++++//
                    $field = '<textarea name="' . $fields[$i] . '" id="' . $fields[$i] . '" class="' . $class . '" style="height:100px">' . htmlspecialchars($this->$name) . '</textarea>';
                    $form .= '<tr><td style="width:10%;padding:0.25em;text-align:right">' . $label . '</td><td colspan="5">' . $field . '</td></tr>';
                    break;
                //+++++++++++++++++++++++++++++++++++++++++++//
                case 'image_id':
                //+++++++++++++++++++++++++++++++++++++++++++//
                    $field = '<input type="file" size="50%" name="' . $fields[$i] . '" id="' . $fields[$i] . '" value="' . htmlspecialchars($this->$name) . '" class="' . $class . '"/>';
                    $form .= '<tr><td style="width:10%;padding:0.25em;text-align:right">' . $label . '</td><td colspan="5" style="text-align:center">' . $field . '</td></tr>';
                    break;
                //+++++++++++++++++++++++++++++++++++++++++++//
                case 'question_config':
                //+++++++++++++++++++++++++++++++++++++++++++//
                /*
                      $conf = $this->$name;
                      if ($conf==2){ $cmt = '&nbsp; Question with image on the side'; }
                      else         { $cmt = ''; }
                      $sel = '<select id="'.$fields[$i].'" name="'.$fields[$i].'" style="margin:0">';
                      for ($a=1; $a<=2; $a++) {
                      if ($a==$conf) { $issel = 'selected="selected"'; }
                      else           { $issel = '';                    }
                      $sel .= '<option '.$issel.' value="'.($a).'">'.$a.'</option>';
                      }
                      $sel  .= '</select>';
                      $field = $sel.$cmt;
                      $form .= '<tr><td style="width:10%;padding:0.25em;text-align:right">'.$label.'</td><td colspan="5">'.$field.'</td></tr>';
                */
                    break;
                //+++++++++++++++++++++++++++++++++++++++++++//
                case 'answers_config':
                //+++++++++++++++++++++++++++++++++++++++++++//
                /*
                      $Nans = $this->$name;
                      $cmtArr = array("LIST","TILED","MATRIX","IMAGES");
                      $sel = '<select id="'.$fields[$i].'" name="'.$fields[$i].'" style="margin:0">';
                      $issel = '';
                      $cmt = '';
                      for ($a=1; $a<=4; $a++) {
                      if ($a==$Nans) { $issel = 'selected="selected"'; $cmt = '&nbsp;'.$cmtArr[$a-1]; }
                      else { 				  $issel = ''; $cmt = '';  }
                      $sel .= '<option '.$issel.' value="'.($a).'">'.$a.'</option>';
                      }
                      $sel  .= '</select>';
                      $field = $sel.$cmt;
                      $form .= '<tr><td style="width:10%;padding:0.25em;text-align:right">'.$label.'</td><td colspan="5">'.$field.'</td></tr>';
                */
                    break;
                //+++++++++++++++++++++++++++++++++++++++++++//
                case 'category':
                //+++++++++++++++++++++++++++++++++++++++++++//
                    $C = $this->$name;
                    $issel = '';
                    $sel = '<select id="' . $fields[$i] . '" name="' . $fields[$i] . '" class="ITS_question">';
                    $query = 'SELECT DISTINCT category FROM ' . $this->tb_name . ' GROUP BY category';
                    //echo $query.'<p>';
                    $res = mysql_query($query);
                    if (!$res) {
                        die('Query execution problem in ITS_question: ' . msql_error());
                    }
                    //$categories = mysql_fetch_row($res);
                    //var_dump($categories);
                    for ($c = 0; $c < mysql_num_rows($res); $c++) {
                        $val = mysql_result($res, $c);
                        //echo $c.' - '.$val.' - '.$C.' - '.mysql_num_rows($res).'<br>';
                        if ($C == $val) {
                            $issel = 'selected="selected"';
                        } else {
                            $issel = '';
                        }
                        $sel .= '<option ' . $issel . ' value="' . $val . '">' . $val . '</option>';
                    }
                    $sel  .= '</select>';
                    $form .= '<tr><td style="width:10%;padding:0.25em;text-align:right">' . $label . '</td><td colspan="5">' . $sel . '</td></tr>';
                    mysql_free_result($res);
                    break;
                //+++++++++++++++++++++++++++++++++++++++++++//
                default:
                //+++++++++++++++++++++++++++++++++++++++++++//
                    $field = '<textarea name="' . $fields[$i] . '" id="' . $fields[$i] . '" value="' . htmlspecialchars($this->$name) . '" class="' . $class . '" />';
                    $form .= '<tr><td style="width:10%;padding:0.25em;text-align:right">' . strtoupper($label) . '</td><td colspan="5">' . $field . '</td></tr>';
            }
        }
        $buttons = '<div id="cancelDialog" class="ITS_button" style="float:right">Cancel</div>'.
                '<div id="submitDialog" class="ITS_button" style="float:right">Create New Question</div>';
        $form  .= '</table>' . $buttons . '</fieldset><noscript><input type="submit" value="Submit"></noscript></form>';
        $dialog = '<div title="Create New Question">' . $form . '</div>';   // id="dialog-form"
        /*
          $dialog = '<div id="dialog-form" title="Create new Question" style="display:none">'
          .'<p class="validateTips">To create a templated question just click on the "Create Question" button.</p><br>'
          .'<form>'
          .'<fieldset>'
          .'<table class="ITS_newQuestion"><tr>'
          .'<td style="width:20%"></td>'.$Qtitle_label
          .'<td style="width:80%"></td>'
          .'</tr><tr><td><label for="Qimage">Image</label></td>'
          .'<td><input type="text" name="Qimage" id="Qimage" value="'.$image.'" class="text ui-widget-content ui-corner-all ITS_Q" /></td>'
          .'</tr><tr><td><label for="Qquestion">Question</label></td>'
          .'<td><input type="text" name="Qquestion" id="Qquestion" value="'.$question.'" style="height:150px" class="text ui-widget-content ui-corner-all ITS_Q" /></td>'
          .'</tr><tr><td><label for="Qanswers">No. Answers</label>&nbsp;&nbsp;'
          .'<select id="Qanswers" style="float:right">'
          .'<option value="1">1</option>'
          .'<option value="2">2</option>'
          .'<option value="3">3</option>'
          .'<option value="4" selected="selected">4</option>'
          .'<option value="5">5</option>'
          .'</select></td><td></td>'
          .'</tr><tr><td><label for="Qcategory">Category</label></td>'
          .'<td><input type="text" name="Qcategory" id="Qcategory" value="'.$category.'" class="text ui-widget-content ui-corner-all ITS_Q" /></td>'
          .'</tr></table><p><div id="cancelDialog" class="ITS_button" style="float:right">Cancel</div><div id="submitDialog" class="ITS_button" style="float:right">Create New Question</div>'
          .'</fieldset></form></div>';
        */
        return $dialog;
    }
    //=====================================================================//
    function render_Admin_Nav($qid, $qtype, $style) {
        //=====================================================================//
        $nav = '<input type="button" class="' . $style . '" id="createQuestion" name="new"   value="New"   qid="' . $qid . '" qtype="' . $qtype . '">'
                . '<input type="button" class="' . $style . '" id="cloneQuestion"  name="clone" value="Clone" qid="' . $qid . '" qtype="' . $qtype . '">'
                . '<input type="button" class="' . $style . '" id="importQuestion" name="new"   value="import QTI" qid="' . $qid . '">'
                . '<input type="button" class="' . $style . '" id="exportQuestion" name="export"   value="export to QTI" qid="' . $qid . '">'
                . '<input type="button" class="' . $style . '" id="exportManyQuestion" name="export_many"   value="export multiple question" qid="' . $qid . '">'
                . '<input type="button" class="' . $style . '" onclick="ITS_QCONTROL_EDITMODE(this)" name="editMode" value="Edit" status="true">';

        /* <!--<input type="button" class="ITS_button" id="deleteQuestion" name="delete" value="Delete" qid="<?php echo $qid;?>">
          <input type="button" class="ITS_button" id="testme" name="test" value="test" qid="<?php echo $qid;?>">-->
        */
        return $nav;
    }
    //=====================================================================//
    function createEditTable($TargetName, $Target, $style) {
        //=====================================================================//
        // eg. createEditTable('TITLE','This is my title',$style);
        //die($this->mimetex_path);
        //echo '|div id="ITS_'.$TargetName.'_TARGET" class="ITS_TARGET" code="'.htmlspecialchars($Target_str).'" path="'.$this->mimetex_path.'"|<hr>';

        if(stristr($TargetName, 'image')!=FALSE && $Target!='') {
            $tb  = '<img src="'.$this->files_path.$Target.'">';
        }
        else {
            $tb  = $Target;
        }

        $Table = '<table class="'.$style.'">'
                . '<tr>'
                . '<td class="' . $style . '">'
                . '<div style="border:1px solid #FFFFFF" id="ITS_' . $TargetName . '_TARGET" class="ITS_TARGET" code="' . htmlspecialchars($Target) . '" path="' . $this->mimetex_path . '">'
                . $tb
                . '</div>'
                . '</td>'
                . '<td class="' . $style . '">'
                . '<span class="ITS_QCONTROL" id="ITS_' . $TargetName . '" ref="'.strtolower($TargetName).'"></span>'
                . '</td>'
                . '</tr>'
                . '</table>';
        return $Table;
    }
    //=====================================================================//
    function createImageTable($TargetName, $Target, $style) {
        //=====================================================================//
        // eg. createEditTable('TITLE','This is my title',$style);
        //die($this->mimetex_path);
        //echo '|div id="ITS_'.$TargetName.'_TARGET" class="ITS_TARGET" code="'.htmlspecialchars($Target_str).'" path="'.$this->mimetex_path.'"|<hr>';

        //if(stristr($TargetName, 'image')!=FALSE && $Target!=''){ $tb  = '<img src="'.$this->files_path.$Target.'">'; }
        //else 												   { $tb  = $Target; }

        $Table = '<table class="'.$style.'">'
                . '<tr>'
                . '<td class="'.$style.'">'
                . '<div style="border:1px solid #fff" id="ITS_' . $TargetName . '_TARGET" class="ITS_TARGET">'
                . $Target
                . '</div>'
                . '</td>'
                . '<td class="' . $style . '">'
                . '<span class="ITS_ICONTROL" id="ITS_' . $TargetName . '" ref="'.strtolower($TargetName).'"></span>'
                . '</td>'
                . '</tr>'
                . '</table>';
        return $Table;
    }
    //=====================================================================//
    function renderFieldCheck($field) {
        //=====================================================================//
        // LATEX: <latex>latex_code</latex> ==> MIMETEX img
        // IMAGE_PATH

        $field = latexCheck($field, $this->mimetex_path);
        $field = preg_replace("/MIMETEX_PATH/", $this->mimetex_path, $field);
        $field = preg_replace("/RESOURCE_PATH/", $this->files_path, $field);

        return $field;
    }
    //=====================================================================//
}

//eo:class
//=====================================================================//
function latexCheck($str, $path) {
//=====================================================================//
    //$pattern = "/<latex>(.*?)<\/latex>/im";
    //echo $str.'<p><hr>';
    $pattern = "/<latex[^>]*>(.*?)<\/latex>/im";
    $replacement = '<img class="ITS_LaTeX" latex="${1}" src="' . $path . '${1}"/>';
    $str = preg_replace($pattern, $replacement, $str);

    //if(preg_match_all($pattern, $str, $matches,PREG_SET_ORDER)){
    /* echo '<pre>';
      print_r($matches);
      echo '</pre>';
    */
    /*
      //if ( $matches && count($matches) ) {
      for ($k=0; $k<count($matches); $k++) {
      echo '<p>'.$k.'  '.$matches[1][$k].'<p>';

      //}
      //$replacement = "<img src=\"".$path.$matches[1]."\" code=\"".$str."\" />";
      $replacement = '<img latex="'.$matches[1][$k].'" src="'.$path.$matches[1][$k].'"/>';
      echo $replacement.'<p>';
      //$replacement = "<img src=\"".$this->mimetex_path.$matches[1]."\" />";
      $str = preg_replace($pattern, $replacement, $str);
      echo $str.'<p>';
      }
      foreach ($matches as $val) {
      echo "matched: " . $val[0] . $val[1]."<p>";
      }
      } */
    return $str;
}
// <latex>\Large\cos(123 t)</latex>
?>
