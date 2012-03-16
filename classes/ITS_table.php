<?php
//=====================================================================//
/*
ITS_table - create table according to number of rows, columns, column
						width, and style.

		Constructor: ITS_table(name,rows,cols,data,width)
		
								 ex. $ITS_table = new ITS_table('tableA',2,2,array(1,2,3,4),array(20,30));
								
	 Author(s): Greg Krudysz |  Jul-25-2008
*/
//=====================================================================//
class ITS_table {

		public $rows;
		public $cols;
		public $data;
		public $width;
		public $str;
		public $style;

		public function set_style($style) { $this->style = $style; }
		//public function set_width($width) { $this->width = $width.'%'; }  
		public function get_style() { return $this->style; }
		public function get_str() { return $this->str; }
	
	//=====================================================================//
  	   function __construct($name,$rows,$cols,$data,$width) {
	//=====================================================================//
				$this->name = $name;
				$this->rows = $rows;																																 
				$this->cols = $cols;			
				//$this->width = '100%';
				
				// Default $data
				if (!(isset($data))){
					for ($r=1;$r<=$this->rows;$r++) {			 						    
			 		 for ($c=1;$c<=$this->cols;$c++) {
							 $data_temp[$r][$c] = '';//"row".$r.",col".$c;
					 }
			    }
				} else {
					for ($idx=0;$idx<=count($data)-1;$idx++) {
							$c = fmod($idx,$this->cols)+1;
							$r = floor(($idx)/$this->cols)+1;
							//echo $r." x ".$c."<p>";
							//var_dump($data[$idx]);
							
							$data_temp[$r][$c] = $data[$idx];
							// echo "<p>".$r ."-".$c."  ".$data[$idx]."<p>"; 	 						    							
			    }		
				}	 
			  $this->data = $data_temp;
			 
			 	// Default $style
				if (func_num_args() > 5){
			 		$this->style = func_get_arg(5); 
			  }	
				
				// Default $width = equal width
				if (!(isset($width))){
				echo "width not set";
					 for ($c=0;$c<$this->cols;$c++) {
					 		 $this->width[$c] = round(100/$this->cols);
					 }
			  } else {
				$this->width = $width;
				}
			 self::set_str();	 
   } 
	//=====================================================================//
	   function setstyle($rowsN,$colsN,$style){
	//=====================================================================//
	
	}
	//=====================================================================//
	   function set_str(){
	//=====================================================================//
	// Construct Table
			 $Table= '<table class="'.$this->style.'" border=1>';
			 
			 for ($r=1;$r<=$this->rows;$r++) {
			 		 $Table .= '<tr>';

			 		 for ($c=1;$c<=$this->cols;$c++) {
  				 		$Table .= '<td nowrap class="'.$this->style.'" width="'.$this->width[$c-1].'%">'
							 							 .'<span class="'.$this->style.'" id="'.$this->name.'_'.$r.'_'.$c.'">'
							 							 . $this->data[$r][$c]
							 							 .'</span>'
							 							 .'</td>';																			 
					 }
					 $Table .= '</tr>';
			 }
			 $Table .= '</table>';
			 
			 // Save HTML string
			 $this->str = $Table;	
			 // return $this->str;
	 }
	//=====================================================================//
} //eo:class
//=====================================================================//
?>

