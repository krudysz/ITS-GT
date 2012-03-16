<?php
//=====================================================================//
/*
  ITS_screen - creates user ITS screen.

  Constructor: ITS_footer(name,rows,cols,data,width)

  ex. $ITS_table = new ITS_footer();

  Author(s): Greg Krudysz |  Apr-11-2011
 */
//=====================================================================//

class ITS_footer {

    public $id;
    public $term;

    //=====================================================================//
    function __construct($status, $date, $runtime) {
        //=====================================================================//
        global $db_dsn, $db_name, $tb_name, $db_table_user_state;


        $this->status = $status;
        $this->date = $date;
        $this->runtime = $runtime;
        /*
          $this->role    = $role;
          $this->db_name = $db_name;
          $this->tb_name = $tb_name;
          $this->tb_user = $db_table_user_state;

          $this->record  = array();
          $this->db_dsn  = $db_dsn;

          // connect to database
          $mdb2 =& MDB2::connect($db_dsn);
          if (PEAR::isError($mdb2)){ throw new Exception($this->mdb2->getMessage()); }

          $this->mdb2 = $mdb2;
         */
        self::main($status, $date, $runtime);
    }

    //=====================================================================//
    function main() {
        //=====================================================================//
        $footer = '<div class="ITS_footer"><ul class="ITS_footer">' .
                '<li>Last Updated: ' . $this->date . '</li>';

        if (!empty($this->runtime)) {
            $footer .= '<li>Page created in ' . round($this->runtime, 2) . ' secs</li>';
        }

        $footer .= '<li>krudysz<b>&Dagger;</b>ece.gatech.edu <b>+</b> jim.mcclellan<b>&Dagger;</b>ece.gatech.edu</li>' .
                '<li></li></ul>' .
                '</div>';

        return $footer;
    }

    //=====================================================================//
}

// eo:class
//=====================================================================//
?>
