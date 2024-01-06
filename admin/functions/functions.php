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

function CheckItem($table, $item, $value)
{

  global $conn;

  $stm = $conn->prepare("SELECT * FROM $table WHERE $item = ?");

  $stm->execute(array($value));

  $row = $stm->fetchAll();

  $count = count($row);

  return $count;
}


/*
****** function To Check is items exist without my row 
****** Get 5 Parameter 
****** table , where 1 & where 2 and Values
*/

function calcAll($table)
{

  global $conn;

  $stm = $conn->prepare("SELECT * FROM $table");

  $stm->execute();

  return count($stm->fetchAll());
}


// Get All Records

function GetAll($table)
{

  global $conn;

  $stm = $conn->prepare("SELECT * FROM $table");

  $stm->execute();

  $rows = $stm->fetchAll();

  return $rows;
}


// Get Last Record In table
function GetLastRow($table, $limit)
{
  global $conn;

  $stm = $conn->prepare("SELECT * FROM $table ORDER BY $limit DESC LIMIT 1");

  $stm->execute();

  $rows = $stm->fetch();

  return $rows;
}


// Calc The Number Of items in Category

function CalcItemsInCatg($catID)
{
  global $conn;

  $stm = $conn->prepare("SELECT * FROM items WHERE cat_ID = ?");

  $stm->execute(array($catID));

  $rows = $stm->fetchAll();

  return $count = count($rows);
}
