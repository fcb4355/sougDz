<?php
/*
  *****
  ***** Rename The Title Of Every Page From The Variable $pageTitle
  *****
*/

function titlePage($pageTitle)
{
  if (isset($pageTitle)) {
    echo $pageTitle;
  } else {
    echo "Market";
  }
}


/*
  *****
  ***** Show The Message in Page From The Request BackEnd
  *****
*/

function showMssg($mssg, $status)
{
?>
  <div class="message-box <?php echo $status ?> border shadow-sm">
    <div class="message"> <?php echo $mssg ?> </div>
    <i class="fa-solid fa-circle-info"></i>
  </div>
<?php
}


/*
  *****
  ***** Check if Item is exist in DataBase
  *****
*/

function CheckItem($table , $item , $value){

  global $conn;

  $stm = $conn->prepare("SELECT * FROM $table WHERE $item = ?");

  $stm->execute(array($value));

  $row = $stm->fetchAll();

  $count = count($row);

  return $count;
}

