<?php
//=====================================================================//
/*
ITS_TagInterface class - generate and render ITS question tags.

		Constructor: ITS_TagInterface(...)
		
								 ex. $tagObj = new ITS_TagInterface(...);
	 
	 Nabanita Ghosal           | Apr-07-2010
	 Revision: Greg Krudysz    | Oct-28-2011
*/
//=====================================================================//
class ITS_TagInterface {

	var $alltagids;
	var $tempTagIds;
	//---------------------------------------------------------------------//
	public function __construct() {
	//---------------------------------------------------------------------//
		global $db_dsn;
		$this->db_dsn  = $db_dsn;
		
		$this->mdb2 =& MDB2::connect($this->db_dsn);
		
		$this->tagidarray = array();
		$this->alltagids = array();
	}
  //---------------------------------------------------------------------//	
	public function getTags($questionID){
	//---------------------------------------------------------------------//
	// Gets the tags and stores them in an array
		
	  // connect to database
		$mdb2 =& MDB2::connect($this->db_dsn);
		if (PEAR::isError($mdb2)){throw new Exception($this->mdb2->getMessage());}
     	
		//Select all tag ids associated with the question
		$query = "SELECT tag_id FROM webct WHERE id = ".$questionID;
		
		$res = & $mdb2->query($query);
		$tag_ids = $res->fetchRow();
		$mdb2->disconnect();
		
		$this->tempTagIds = array();
		$cnt = 0;
        $tempTagIds = explode(",",$tag_ids[0]);
		
		//echo $questionID.'<p>';
		//var_dump($tempTagIds); echo '<hr><p>';//die();
		/*
		while ($row = $res->fetchAll()){
			//Since the tag ids are a comma separated string, extract every tag id & store in an array
			$temp = preg_split("/[\s,]+/", $row[$cnt][0]);
			for($i=0;$i<count($temp);$i++) {
				array_push($this->tempTagIds, $temp[$i]);	
			}
			$cnt++;
		}
		var_dump($this->tempTagIds);
		*/
		//echo(sizeof($tempTagIds));
		//die('---');
		$reqdNumofTags = 0;
		$numberofTags = sizeof($this->tempTagIds);

		if (empty($numberofTags)) {
		//die('da');
		  $numberofTags = '';
		}
		$tagArray = array();
		$tagArray['id'] = array();
		$tagArray['name'] = array();
		
		if($numberofTags >= 4) {
			//select 4 tags at random from this list
			$numberofTags = 4;
		} else {
			$reqdNumofTags = 4 - $numberofTags;
		}
	  //var_dump(empty($numberofTags));
	  
		if (empty($numberofTags)) {
		   $rand_tagids = ''; 
		} 
		else {
		   $rand_tagids = array_rand($this->tempTagIds,$numberofTags);
		}
		//echo $numberofTags[0];die();
		for($i=0;$i<$numberofTags;$i++) {
			$cnt=0;
			//var_dump(count($rand_tagids));
			//echo count($rand_tagids[$i]);die('done');
			
			if (count($rand_tagids[$i])){
  			$tagArray['id'][] = $this->tempTagIds[$rand_tagids[$i]];
  			$query = "SELECT name FROM tags WHERE id = ".$this->tempTagIds[$rand_tagids[$i]];
				
  			$res = & $mdb2->query($query);
  			while ($row = $res->fetchAll()){
  				$tagArray['name'][] = $row[$cnt][0];
  				$cnt++;
  			}
			}
		}
		//echo $reqdNumofTags;die();
		if($reqdNumofTags!=0) {
			$cnt = 0;
			$res = & $mdb2->query("SELECT id FROM tags");
			$mdb2->disconnect();
			while ($row = $res->fetchAll()){
				for($i=0;$i<sizeof($row);$i++) {
					array_push($this->alltagids, $row[$i][0]);
				}		
				$cnt++;
			}
			$rand_tagids = array_rand($this->alltagids,$reqdNumofTags);
			
			for($i=0;$i<$reqdNumofTags;$i++) {
				$cnt=0;
				if($reqdNumofTags==1) {
					$query = "SELECT name, tag_id FROM index_1 WHERE id = ".$this->alltagids[$rand_tagids];
				  //echo $query;
					}
				else {
					$query = "SELECT name, tag_id FROM index_1 WHERE id = ".$this->alltagids[$rand_tagids[$i]];
				//echo $query.'<p>';
				}
				$res = & $mdb2->query($query);
				while ($row = $res->fetchAll()){
					$tagArray['name'][] = $row[$cnt][0];
					$tagArray['id'][] = $row[$cnt][1];
					$cnt++;
				}
			}	
		}
		return $tagArray;
	}	
	//---------------------------------------------------------------------// 
	public function createSearchAddBox($userid,$questionid){
	//---------------------------------------------------------------------//
	 /*   This creates the search box.
	 *    Can currently only have 1 per tag interface.
	 */
		$table = "stats_".$userid;
		$SearchAddBox = '<form action = "plugins/tagging/SearchSubmitTags.php" method="post" target="searchWindow">';
		$SearchAddBox = $SearchAddBox."<input type = "."text"." name = "."optional"." value = "."\"Search and Add tags\""." size = "."20"." onClick=\"clearText(this)\""." onBlur=\"returnText(this)\""." onkeyup="."this.form.sub.click()".">";
		$SearchAddBox = $SearchAddBox."<input type = "."submit class=\"InvisibleSrchBtn\""." name = "."sub"." value="."".">";
		$SearchAddBox = $SearchAddBox."<input type = "."submit"." name = "."add"." value = "."Add Tag"."onClick=\"submitTags(\'about:blank\',\'Tagging\')\" />";
		$SearchAddBox = $SearchAddBox."<input type = "."hidden"." name = "."questionid"." value = $questionid />";
		$SearchAddBox = $SearchAddBox."<input type = "."hidden"." name = "."tableid"." value = $table />";
		$SearchAddBox = $SearchAddBox."</form>";
		$SearchAddBox = $SearchAddBox."<iframe name = "."searchWindow"." src="."plugins/tagging/SearchSubmitTags.php"." width="."600".", height="."400"."></iframe>";
		return $SearchAddBox;
	}

	 /* @param object $userid
	 *  @param object $questionid
	 *  @param object $tags
	 *  @return 
	 */
	//---------------------------------------------------------------------//
	public function displayMoreTags() {
	//---------------------------------------------------------------------//
		// connect to database
		$mdb2 =& MDB2::connect($this->db_dsn);
		if (PEAR::isError($mdb2)){throw new Exception($this->mdb2->getMessage());}
     	
	  $query = 'SELECT name FROM lab_1';
		$res = & $mdb2->query($query);
		$lab_name = $res->fetchCol();
	  
		//--- LABS --------------------------------//
		$labs = '';
    for($i=0;$i<count($lab_name);$i++) {
      $labs = $labs.'<span class="TAG_MORE">lab '.($i+1).'</span>';
    }
		//--- CHAPTERS ----------------------------//
	  $query = 'SELECT id FROM chapter_1';
		$res = & $mdb2->query($query);
		$chapters = $res->fetchCol();
	  
		$chps = '';
    for($i=0;$i<count($chapters);$i++) {
      $chps = $chps.'<span class="TAG_MORE">chapter'.$chapters[$i].'</span>';
    }
		//--- GUIS --------------------------------//
	  $query = 'SELECT name FROM gui_1';
		$res = & $mdb2->query($query);
	  $gui_name = $res->fetchCol();
		
		$guis = '';
    for($i=0;$i<count($gui_name);$i++) { //class="TAG_ACTIVE"
      $guis = $guis.'<span class="TAG_MORE">'.$gui_name[$i].'</span>';
    }	
		//------------//
     
		$mdb2->disconnect();
		 
	 	$more = '<ul class="tagsMore"><li><span class="TAG_MORE">Procedures</span><span class="TAG_MORE">Survey</span><input id="publish" type="checkbox" class="TAG_MORE"></li>'
		        .'<li>'.$labs.'</li>'
						.'<li>'.$chps.'</li>'
						.'<li>'.$guis.'</li>'
						.'</ul>';
		
		return $more;
	 }
	//---------------------------------------------------------------------//
	public function displayTags($userid,$questionid,$tags) {
	//---------------------------------------------------------------------//
		$style = 'TAG_ACTIVE';
		//$tagContainer = '<span onmouseover="tooltip.show(\'<font font=Georgia size=2.5 color=#ffff33><b>A tag is a keyword that is assigned to any piece of information. <br>By tagging this question, you will bookmark it with a relevant keyword. <br>You can tag this question through a single click on one of the 4 buttons below or you can add a tag by typing it in the text box. You can also search for more tags using the search box below.</b></font>\', 570);" onmouseout="tooltip.hide();">Tag the Question</span><br>';
		//\'A <b>tag</b> is a keyword or a label.<p><p><b>[Ex.]</b>: Tag <tt>x(t)</tt> where ...<p><img src=tagInfo.png>\'
		
		$tagContainer = '<div class="tags"><div class="tagsHeader"><span>TAGs:</span></div>';
		//<span class="ITS_info" src="tagInfo.png" onmouseover="tooltip.show()" onmouseout="tooltip.hide();">?</span>
		
		/* -- Example
		$tagContainer = $tagContainer.'<p style="text-align:left;margin-left:35px;"><code><font size=5pt>x(t) = cos(t)</font></code><p>';
		$$tags['name'] = array('<tt>sine</tt>','<tt>cosine</tt>','<tt>tangent</tt>');
		/ -- */
		
		for($i=0;$i<count($tags['name']);$i++) { 
			//$params = './tagging/SubmitTags.php?uid='.$userid.'&qid='.$questionid.'&tid='.$tags['id'][$i].'&tnm='.$tags['name'][$i];
			//$tagContainer = $tagContainer.'<button class="TagButton" value="'.$tags['id'][$i].'" onMouseOver="this.bgColor=\'#00CC00\'" onMouseOut="this.bgColor=\'#009900\'" OnClick="submitTags(\''.$params.'\',\'Tagging\')">'.$tags['name'][$i].'</button>';
			$tagContainer = $tagContainer.'<span name="tag" id="tag" class="TAG_ACTIVE" value="'.$tags['id'][$i].'" qid="'.$questionid.'" n="'.$i.'" onclick="ITS_content_select3(this)">'.$tags['name'][$i].'</span>';

			//$tagContainer = $tagContainer.'<button value="'.$tags['id'][$i].'" OnClick="submitTags(\''.$params.'\',\'Tagging\')">'.$tags['name'][$i].'</button>';
		}
		//$tagContainer = $tagContainer.'<p>'.$this->createSearchAddBox($userid, $questionid);
		
		$more = $this->displayMoreTags();
        $tagContainer = $tagContainer.'<span name="tag" id="tag" class="TAG_ACTIVE">NONE of these!</span></div><div id="header" class="tagsMore"><span class="tagsMoreText">&raquo; more <code>tags</code></span></div><div id="list" class="tagsMore">'.$more.'</div>';
		
		return $tagContainer;
	}		
	//---------------------------------------------------------------------//
}
//=====================================================================//
?>
