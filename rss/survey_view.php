<?php
/**
 * survey_view.php along with index.php provides a survey list/view application
 * 
 * @package SurveySez
 * @author Joe Blow <me@example.com>
 * @version 0.1 2018/07/23
 * @link http://www.example.com/
 * @license https://www.apache.org/licenses/LICENSE-2.0
 * @see index.php
 * @todo none
 */


# '../' works for a sub-folder.  use './' for the root  
require '../rss/inc_0700/config_inc.php'; #provides configuration, pathing, error handling, db credentials
 
# check variable of item passed in - if invalid data, forcibly redirect back to demo_list.php page
if(isset($_GET['id']) && (int)$_GET['id'] > 0){#proper data must be on querystring
	 $myID = (int)$_GET['id']; #Convert to integer, will equate to zero if fails
}else{
	//myRedirect(VIRTUAL_PATH . "demo/demo_list.php");
    header('Location:index.php');
}

$mySurvey = new Survey($myID);

if($mySurvey->IsValid)
{#only load data if record found
	$config->titleTag = $mySurvey->Title . " survey"; #overwrite PageTitle with Muffin info!
}

# END CONFIG AREA ---------------------------------------------------------- 

get_header(); #defaults to theme header or header_inc.php

if($mySurvey->IsValid)
{#records exist - show survey!
?>
    <h3 align="center"><?=$mySurvey->Title?></h3>
    <p><?=$mySurvey->Description?></p>

<?php
}else{//no such survey!
    echo '<div align="center">What! No such survey? There must be a mistake!!</div>';
    echo '<div align="center"><a href="' . VIRTUAL_PATH . 'surveys/index.php">View Surveys</a></div>';
}

get_footer(); #defaults to theme footer or footer_inc.php

class Survey{
    public $SurveyID = 0;
    public $Title = '';
    public $Description = '';
    public $IsValid = false;
    
    public function __construct($id){
        $this->SurveyID = (int)$id;
        $sql = "select Title,Description from sm18_surveys where SurveyID = " . $this->SurveyID;

        $result = mysqli_query(IDB::conn(),$sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));

        if(mysqli_num_rows($result) > 0)
        {#records exist - process
               $this->IsValid = true;	
               while ($row = mysqli_fetch_assoc($result))
               {
                    $this->Title = dbOut($row['Title']);
                    $this->Description = dbOut($row['Description']);
               }
        }

        @mysqli_free_result($result); # We're done with the data!
    }//end Survey constructor
    
    
    
}//end Survey class


