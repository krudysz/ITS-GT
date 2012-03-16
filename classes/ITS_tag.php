<?php
//=====================================================================//
/*
ITS_tag - tag related.

		Constructor: ITS_tag()	
								 ex. $ITS_tag = new ITS_tag();
								
	 Author(s): Greg Krudysz |  Nov-16-2011 						   */
//=====================================================================//

class ITS_tag {

		public $tb_tags;
	
	//=====================================================================//
  	   function __construct($tags_tb) {
	//=====================================================================//
	  global $db_dsn,$db_name,$tb_name,$db_table_user_state,$mimetex_path,$files_path;
		
		$this->tb_tags  = $tags_tb;
		$this->db_dsn   = $db_dsn;
	  
		// connect to database
		$mdb2 =& MDB2::connect($db_dsn);
		if (PEAR::isError($mdb2)){ throw new Exception($this->mdb2->getMessage()); }
			
	  $this->mdb2 = $mdb2;
   } 
    //=====================================================================//
	   function getByResource($tb,$id){
	//=====================================================================// 
        $query_tag_id = 'SELECT tag_id FROM '.$tb.' WHERE id='.$id;
        $res = mysql_query($query_tag_id);
        if (!$res) {die('Query execution problem in ITS_question: ' . msql_error());}
        $arr = explode(',',mysql_result($res,0));
        //var_dump($list); die();
        
        return $arr;
    }  
     //=====================================================================//
	   function getByKeyword($keyword,$exclude){ /* arr[id][name] */
	 //=====================================================================// 	  
	   //$query = "SELECT id,name FROM ".$this->tb_tags." WHERE name LIKE '$keyword%' ORDER BY name";
	   
	   if (!empty($exclude)){ $filter = ' AND id NOT IN ('.implode(",",$exclude).')'; }
	   else 				{ $filter = ''; }
	   $query = 'SELECT id,name FROM '.$this->tb_tags.' WHERE name REGEXP "^'.$keyword.'"'.$filter.' ORDER BY name';
       //die($query);
       $res   = mysql_query($query);
       if (!$res) { die('Query execution problem in ITS_question: ' . msql_error()); }
       
       while($arr[]=mysql_fetch_array($res,MYSQL_NUM));
	   //echo "<pre>";print_r ($list);echo "</pre>";die('out');
       return $arr;
	}  
     //=====================================================================//
	   function query($keyword,$exclude){ /* arr[id][name] */
	 //=====================================================================// 	  
	   if (!empty($exclude)){ $filter = ' AND id IN ('.implode(",",$exclude).')'; }
	   else 				{ $filter = ''; }
	   
	   $query = 'SELECT id,name FROM '.$this->tb_tags.' WHERE name="'.$keyword.'"'.$filter;
       $res   = mysql_query($query);
       if (!$res) { die('Query execution problem in ITS_question: ' . msql_error()); }
       
       while($arr[]=mysql_fetch_array($res,MYSQL_NUM));
	   //echo "<pre>";print_r ($arr);echo "</pre>";die('out');

       return $arr;
	} 	 
    //=====================================================================//
	   function query2($keyword,$rid,$rname){
	//=====================================================================// 	  
	   $list  = $this->getByKeyword($keyword,$rid,$rname);
       
       for ($t = 0; $t < count($list)-1; $t++) { 
			$tags .= '<div class="ITS_tag"><table><tr><td>'.$list[$t][1].'</td><td class="tag_add" tid="'.$list[$t][0].'" name="'.$list[$t][1].'" rname="'.$rname.'" rid="'.$rid.'">+</td></tr></table></div>';
	   }	
       return $tags;
	}   
    //=====================================================================//
	   function add($keyword,$rid,$rname){
	//=====================================================================// 	  
	   $tag = '<div class="ITS_tag"><table class="add"><tr><td>'.$keyword.'</td><td class="tag_add" tid="0" name="'.$keyword.'" rname="'.$rname.'" rid="'.$rid.'">+</td></tr></table></div>';

       return $tag;
	}	
    //=====================================================================//
	   function addToQues($tid,$tname,$rid,$rname){
	//=====================================================================//
	//die('in here'); 	  
	   //$tag = '<div class="ITS_tag"><table class="add"><tr><td>'.$keyword.'</td><td class="tag_add" tid="0" name="'.$keyword.'">+</td></tr></table></div>';
	   if ($tid==0){
		   $tid = $this->addTag($tname);
	   }
	   $query = 'SELECT CONCAT_WS(",",(SELECT tag_id FROM '.$rname.' WHERE id='.$rid.'),'.$tid.')';
	   $result = mysql_query($query);
	   $tids = mysql_result($result, 0);
	   $query = 'UPDATE '.$rname.' SET tag_id='.$tids.' WHERE id='.$rid;
	   $result = mysql_query($query);
	   
	   $tag = '<div class="ITS_tag"><table><tr><td>'.$tname.'</td><td class="tag_del" tid="'.$tid.'" rname="'.$rname.'" rid="'.$rid.'">x</td></tr></table></div>';
	   //die($tag);
       return $tag;
	}	
	// alter table tags change id id int auto_increment;
    //=====================================================================//
	   function addTag($tname){
	//=====================================================================// 	  
	   $query = 'INSERT INTO '.$this->tb_tags.' (name) VALUES ("'.$tname.'")'; 
	   $result = mysql_query($query);
	   //if( !mysql_query($query) ){echo '<br>'.$query;} 
	   $tid = mysql_insert_id();
	   //die($tid);
       return $tid;
	}	
    //=====================================================================//
	   function insert($qid,$tag,$tag_type){
	//=====================================================================// 	  
	   $query = "UPDATE webct_".$u."(question_id, tags) VALUES ('".$q."','".$t."')";
	   
	   $result = mysql_query($query);
	   $tags = '<div class="ITS_tag"><table class="add"><tr><td>'.$keyword.'</td><td class="tag_add" id="'.$keyword.'">+</td></tr></table></div>';

       return $tags;
	}	
    //=====================================================================//
	   function render($arr,$rid,$rname){
	//=====================================================================// 
		$tb = '<table>';
        $tag_ids = implode(',',$arr);
        if (!empty($tag_ids)) {
            $query = 'SELECT id,name FROM tags WHERE id IN ('.$tag_ids.') ORDER BY name';
            $res   = mysql_query($query);
            if (!$res) {
                die('Query execution problem in ITS_question: ' . msql_error());
            }
            $tag_list = '';
            while ($row = mysql_fetch_array($res, MYSQL_ASSOC)) {
                $tag_list .= '<div class="ITS_tag"><table><tr><td>'.$row['name'].'</td><td class="tag_del" id="'.$row['id'].'" rname="'.$rname.'" rid="'.$rid.'">x</td></tr></table></div>';
            }
			$tb .= '<tr><td id="Q_tag_list">'.$tag_list.'</td></tr>';
			
            //--- TAGS: SYSTEM ---//
            $query  = 'SELECT id,name FROM tags_system WHERE id IN ('.$tag_ids.') ORDER BY name';
            $res = mysql_query($query);
            if (!$res) {die('Query execution problem in ITS_question: ' . msql_error());}
            $tag_sys_list = '';
            while ($row = mysql_fetch_array($res, MYSQL_ASSOC)) {
                $tag_sys_list .= '<div class="ITS_tag_sys"><table><tr><td>'.$row['name'].'</td><td class="tag_del" id="'.$row['id'].'" rname="'.$rname.'" rid="'.$rid.'">x</td></tr></table></div>';
            }
            $tb .= '<tr><td class="tag_sep">'.$tag_sys_list.'</td></tr>';
        }
        $tb .= '</table>';
        
        return $tb;
     }		
    //=====================================================================//
	   function render2($arr){
	//=====================================================================// 
		$tb = '<table>';
        if (!empty($arr)) {
			$tag_list = '';
            for ($i = 0; $i < count($arr)-1; $i++) {
				echo '<div class="ITS_tag"><table><tr><td>'.$arr[$i][1].'</td><td class="tag_add" tid="'.$arr[$i][0].'" name="'.$arr[$i][1].'">+</td></tr></table></div>';
            }
			$tb .= '<tr><td>'.$tag_list.'</td></tr>';
        }
        $tb .= '</table>';
        
        return $tb;
     }
	//=====================================================================//
	   function main(){
	//=====================================================================// 
		$query = 'SELECT id,chapter,section,paragraph,content,tag_id,name FROM dspfirst WHERE meta="'.$this->meta.'" AND chapter='.$this->chapter;
		// die($query);
		$res = $this->mdb2->query($query);
    if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}
    $pars = $res->fetchAll();

    $book = '<div class="ITS_BOOK"><p>';
    //for ($i = 2; $i <= 6; $i++) {  //count($pars)-1
		for ($i = 0; $i <= count($pars)-1; $i++) {
    
    if (empty($pars[$i][5])) { $pars[$i][5] = '""'; }
    
    $query = 'SELECT name FROM tags WHERE id IN ('.$pars[$i][5].')'; // echo '<p>'.$i.' '.$query.'<p>';
    $res = $this->mdb2->query($query);
    if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}
    $name = $res->fetchAll();
		
		$fpath = '/FILES/SP1Figures/';
		//echo '<p>'.$this->meta.'</p>';
		
    	switch ($this->meta) {
    	//----------------------//
    		case 'paragraph':
    			//----------------------//
    			$tags = '';
          for ($t = 0; $t <= count($name)-1; $t++) {
             $tags .= '<span class="ITS_tag">'.$name[$t][0].'</span>';
          }
    			//$book = $book.'<div class="ITS_PARAGRAPH">'.$pars[$i][4].'</div><br>'; 
					//echo '<font color=red>'.$pars[$i][4].'</font><hr>';
					
    			$book .= $pars[$i][4].'<div class="ITS_tags">'.$tags.'</div>';		
    			break;
    			//----------------------//
    		case 'equation':
    		  //----------------------//
    			$tags = ''; //array();
          for ($t = 0; $t <= count($name)-1; $t++) { 
             $tags .= '<span class="ITS_tag">'.$name[$t][0].'</span>';
          }
    			//if ($i==0) { $book .= '<hr class="ITS_hr">';}
    			//$book .= '<div class="sectionContainer"><table class="ITS_BOOK"><tr><td width="5%"><font color="blue">'.$pars[$i][0].'</font></td><td><img class="ITS_EQUATION" src="'.$this->mpath.$pars[$i][4].'"/></td></tr><tr><td colspan="2">'.$tags.'</td></tr></table></div>';
    			
					$book .= '<img class="ITS_EQUATION" src="'.$this->mpath.$pars[$i][4].'"/>'; 
					break;
    			//----------------------//
    		case 'math':
    			//----------------------//
          //$str ="REFERENCE#fig:dtsig#REFERENCE";      
          //$str = preg_replace("/(a)(.*)(d)/","a($2)d",$str);  
          // a(s)dfd a()dsfd a(aaa)da(s)d
					//$book = preg_replace("/I want (\S+) one/", "$1 is the one I want", "I want that one") . "\n";
					
    			$book .= '<div class="sectionContainer"><table class="ITS_BOOK"><tr><td width="5%"><font color="blue">'.$pars[$i][0].'</font><td><img class="ITS_EQUATION" src="'.$this->mpath.$pars[$i][4].'"/></td></tr></table></div>';			
					break;
    			//----------------------//
    		case 'image': // NO SCORE
    			//----------------------//			
          $tags = ''; //array();
          for ($t = 0; $t <= count($name)-1; $t++) {
             $tags .= '<span class="ITS_tag">'.$name[$t][0].'</span>';
          }
					
    			//if ($i==0) { $book .= '<hr class="ITS_hr">';}
					$ch  = $pars[$i][1];
					$sec = $pars[$i][2];
					$fig = explode('/',$pars[$i][6]);
					$fN  = count($fig);
					$fname = trim(str_replace('}','',$fig[$fN-1]));
					$chs = sprintf('%02d',$ch);
					$imn = sprintf('%02d',($i+1));

					//$img_source = $this->filepath.'SP1Figures/Ch'.$chs.'/Fig'.$chs.'-'.$imn.'_'.$fname.'.png';
					//$img_source = $fpath.'Ch'.$chs.'/art/'.$fname.'.png';
					$img_source = '../BOOK/BOOK_R/Chapter'.$chs.'/art/'.$fname.'.png';
					
					//echo $img_source.'<p>';
					//die($img_source);
					$caption = $pars[$i][4];
					//$caption = preg_replace("/($)(.*)?($)/U",'<img class="ITS_EQUATION" src="'.$mimetex_path.'$2"/></a>"',$caption);
					$caption = preg_replace("/(REFERENCE#)(.*)?(#REFERENCE)/U","<a>$2</a>",$caption);
					
          $img_str = '<div class="ITS_Image"><img src="'.$img_source.'" alt="'.$img_source.'"><br><div class="ITS_Caption">'.$caption.'</div></div>';
					$book   .= '<div class="sectionContainer"><table class="ITS_BOOK"><tr><td width="5%"><font color="blue">'.$pars[$i][0].'</font></td><td>'.$img_str.'</td></tr><tr><td colspan="2">'.$tags.'</td></tr></table></div>';	
					break;
    			//----------------------//
    	}
    }
    $book = $book.'</div><p>';	
    				
		return $book;
	}
	//=====================================================================//
}
?>
