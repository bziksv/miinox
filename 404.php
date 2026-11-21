<?
include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/urlrewrite.php');

CHTTP::SetStatus("404 Not Found");
@define("ERROR_404","Y");
define("HIDE_SIDEBAR", true);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->SetTitle("Страница не найдена");?><div class="mi-main-content">
	<div class="mi-category-section_mod">
		<div class="container"> 
			<div class="row">

				<div class="bx-404-container">
					<div class="bx-404-block"><img src="<?=SITE_DIR?>images/404.png" alt=""></div>
					<div class="bx-404-text-block"><br><br>Неправильно набран адрес <br>или такой страницы на сайте больше не существует.<br><br></div>
					<div class="">Вернитесь на <a href="<?=SITE_DIR?>">Главную.</a></div>
				</div>

			</div>
		</div>
	</div>
</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>