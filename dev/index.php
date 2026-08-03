<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

use Bitrix\Main\Application;


$connection = Application::getConnection();
$sql = "SELECT `IBLOCK_ID`, `ELEMENT_ID` FROM `b_iblock_element_iprop` LIMIT 150000";
$recordset = $connection->query($sql);

while ($row = $recordset->fetch()) {
	$ipropValues = new \Bitrix\Iblock\InheritedProperty\ElementValues($row["IBLOCK_ID"], $row["ELEMENT_ID"]);

	$ipropValues->clearValues();
}

?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>