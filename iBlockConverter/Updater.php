<?php

class Updater {

    private $settings = [
        'qtyToProc'     => '0',     //0: process all goods.             int
//        'qtyToProc'     => '4',     //0: process all goods.             int

        'dropAcceptors' => true,       //

        // 0: все.
        // 1. Труба Бесшовная.
        // 4. Труба Оцинкованная.
        // 29.Сетка рабица оцинкованная
        // 10 Швеллер
        // 11 Швеллер Гнутый
        // 9  балка двуартовая
        // 14 сталь угловая
        // 19 лист г/к
        'sectToProc'    => '0',

        'sleep'         => '1000',     //pause after item processing       int     ms
        'debugDB'       => false,   //записывать в значения свойств и полей товаров всякие тестовые значения при отсутствии реальных.
        "dump" => [
            //commented flags aren't implemeted
            'steelType' => false,
            'chopping'  => false,
            'weight'    => true,
            'lengths'   => false,
//            'category'  => true,
//            'qty'       => true,
//            'donPrice'   => true,
//            '2ndPriceID'   => true,
//            'DonId'   => true,
//            'AccID'   => true,
//            'status'   => true,
        ],
    ];

    const   SCODE_ACC   = 'catalog';
    const   SCODE_DON   = 'donor-catalog';

    //ДАЛЬШЕ НИЧЕГО МЕНЯТЬ НЕЛЬЗЯ!!!

    //system variables
    private $idAccIBlock;
    private $accordances;
    //system variables. end.

    private $goodsDon;

    //counters
    private $counterMain = 0;
    private $counterUpdated = 0;
    private $counterCreated = 0;
    //counters. end

    //properties for current good

    private $curDon;
    private $curDonPriceId = 16;
    private $curDonFields;      //for caching
    private $curDonProps;       //for caching

    private $curIdAcc;
    private $curAcc;
    private $curFieldsForAcc;
    private $curPropsForAcc;
    //properties for current good. end

    public function __construct(){
        CSearch::DeleteForReindex("iblock");

        $this->idAccIBlock = $this->getIdAccIBlock();
        $this->goodsDon = $this->getGoodsDon();
        $this->accordances = include('groupAccordance.php');
    }

    public function updateCatalog(){

        if ($this->settings['dropAcceptors']) $this->dropAcceptors();

        echo "Goods to Process: {$this->goodsDon->NavRecordCount}\n\n";

        while ($this->curDon = $this->goodsDon->getNextElement()) {

            if ($this->settings['qtyToProc'] > 0 && $this->counterMain > $this->settings['qtyToProc']) break;
            $this->counterMain += 1;

            echo str_pad($this->counterMain, 4);
            $this->procAcc();
            echo "\n";

            ($this->settings['sleep'] > 0) ? usleep($this->settings['sleep'] * 1000) : '';
        }

        echo "\nUpdated: {$this->counterUpdated}\n";
        echo "Created: {$this->counterCreated}\n";
    }

    public function dropAcceptors(){
        $result = CIBlockElement::GetList
        (
            array("ID"=>"ASC"),
            array
            (
                'IBLOCK_CODE' => self::SCODE_ACC,
            )
        );

        while($element = $result->Fetch()) {
            echo "Dropping Аcceptor with id {$element['ID']}\n";

			$ipropValues = new \Bitrix\Iblock\InheritedProperty\ElementValues($element["IBLOCK_ID"], $element["ID"]);
			$ipropValues->clearValues();

            CIBlockElement::Delete($element['ID']);
        }

        echo "Dropping all Аcceptors\n";
    }

    private function crtPropsForAcc(){
        if ($this->settings["debugDB"]) {
            $labelSteel = 'Марки Стали отсутствует';
            $labelSize = 'Размер Сетки отсутствует';
            $labelCutting = 'Цена порезки отсутствует';
            $labelWeightPM = '-1';
        } else {
            $labelSteel = '';
            $labelSize = '';
            $labelCutting = '';
            $labelWeightPM = '';
        }

        $props = [];
        if ($this->curDonProps['_5_MARKASAYT_ILI_RAZMER_SETKI']['VALUE'] != '') {
            $props['TYPE_METALL'] = $this->curDonProps['_5_MARKASAYT_ILI_RAZMER_SETKI']['VALUE'];
            $props['SIZE'] = $this->curDonProps['_5_MARKASAYT_ILI_RAZMER_SETKI']['VALUE'];
        }
        else {
            $props['TYPE_METALL'] = $labelSteel;
            $props['SIZE'] = $labelSize;

        }

        if ($this->curDonProps['_6_POREZKASAYT']['VALUE'] != '') {
            $props['PRICE_CUTTING'] = $this->curDonProps['_6_POREZKASAYT']['VALUE'];
        } else $props['PRICE_CUTTING'] = $labelCutting;

        if ($this->curDonProps['_3_VESPMSAYT']['VALUE'] != '') {
            $props['WEIGHT_PM'] = $this->curDonProps['_3_VESPMSAYT']['VALUE'] * 1000;
        } else $props['WEIGHT_PM'] = $labelWeightPM;

        if ($this->curDonProps['_3_VESPMSAYT']['VALUE'] != '') {
            $props['_3_VESPMSAYT'] = $this->curDonProps['_3_VESPMSAYT']['VALUE'];
        } else $props['_3_VESPMSAYT'] = $labelWeightPM;

        if ($this->curDonProps['_7_DLINASHT']['VALUE'] != '') {
            $props['_7_DLINASHT'] = $this->curDonProps['_7_DLINASHT']['VALUE'];
        } else $props['_7_DLINASHT'] = "";

        if ($this->curDonProps['_8_SHT']['VALUE'] != '') {
            $props['_8_SHT'] = $this->curDonProps['_8_SHT']['VALUE'];
        } else $props['_8_SHT'] = "";

        if ($this->curDonProps['SEO_NAME']['VALUE'] != '') {
            $props['SEO_NAME'] = $this->curDonProps['SEO_NAME']['VALUE'];
        } else $props['SEO_NAME'] = "";

        $props['UPD_STATUS'] = $this->curIdAcc ? 'updated' : 'created';


        echo $this->settings['dump']['steelType']
            ? str_pad("Steel/Grid Size: " . $this->toEng($this->curDonProps['_5_MARKASAYT_ILI_RAZMER_SETKI']['VALUE']), 40)
            : '';
        echo $this->settings['dump']['chopping']
            ? str_pad("Chopping: {$this->curDonProps['_6_POREZKASAYT']['VALUE']}", 20)
            : '';
        echo $this->settings['dump']['weight']
            ? str_pad("Weight PM: {$this->curDonProps['_3_VESPMSAYT']['VALUE']}", 20)
            : '';
        echo $this->settings['dump']['lengths']
            ? str_pad("Lengths: " . $this->toEng($this->curDonProps['_4_DLINYSAYT']['VALUE']), 30)
            : '';

//        echo str_pad("Lengths: " . $this->toEng($this->curDonProps['_4_DLINYSAYT']['VALUE']), 30);

        //проверка когда вместо номера категории в выгрузке лежит какая-то херня.
        $strToPrint = (strlen($this->curDonProps['_2_PODRAZDELSAYTA_NEW']['VALUE']) > 4) ? 'XX' : $this->curDonProps['_2_PODRAZDELSAYTA_NEW']['VALUE'];
        echo str_pad("Category: " . $this->toEng($strToPrint), 13);

        return $props;
    }

    private function crtFieldsForAcc() {
        $fields = [];
        $fields['IBLOCK_ID'] = $this->idAccIBlock;
        $fields['PROPERTY_VALUES'] = $this->curPropsForAcc;

        if ($this->curDonProps['_2_PODRAZDELSAYTA_NEW']['VALUE'] != ''){
            $fields['IBLOCK_SECTION_ID'] = $this->getSectIdAcc();
        }

        $fields['NAME'] = $this->crtNameForAcc();

        $fields['ACTIVE'] = $this->curDonFields['ACTIVE'];
        $fields['CODE'] = $this->curDonFields['CODE'];

        if ($this->curDonProps['SORTIROVKA_SAYT']['VALUE'] != ''){
            $fields['SORT'] = $this->curDonProps['SORTIROVKA_SAYT']['VALUE'];
        }

        $fields['PREVIEW_TEXT'] = $this->curDonFields['PREVIEW_TEXT'];
        $fields['EXTERNAL_ID'] = $this->curDonFields['EXTERNAL_ID'];

        return $fields;
    }

    private function updateAcc(){
        $this->curAcc->update($this->curIdAcc, $this->curFieldsForAcc);
        $this->counterUpdated +=1;
    }

    private function createAcc(){
        if($code = $this->curAcc->add($this->curFieldsForAcc)) {
            $this->counterCreated +=1;
            $this->curIdAcc = $code;
        }
        else {
            echo "create error: " . $this->curAcc->LAST_ERROR;
        }
    }

    private function procAcc(){
        $this->curDonFields = $this->curDon->getFields();
        $this->curDonProps = $this->curDon->getProperties();

        $this->curIdAcc = $this->getIdCurrAcc();

        $this->curAcc = new CIBlockElement;

        $this->curPropsForAcc = $this->crtPropsForAcc();
        $this->curFieldsForAcc = $this->crtFieldsForAcc();


        $this->curIdAcc ? $this->updateAcc() : $this->createAcc();
//        $this->curIdAcc = $this->curIdAcc ? $this->getIdCurrAcc() : $this->curIdAcc;

        $this->procAccSCItem();
        $this->procPrices();

        echo str_pad("Don ID: {$this->curDonFields['ID']}", 15);
        echo str_pad("Acc ID: $this->curIdAcc", 15);
        echo str_pad("{$this->curPropsForAcc['UPD_STATUS']}", 10);
    }

    /**
     * Process Acceptors Sales Catalog's Item.
     *
     */
    private function procAccSCItem(){
        $donSC = CCatalogProduct::GetByID($this->curDonFields['ID']);
        echo str_pad("Qty: {$donSC['QUANTITY']}", 14);

        $isAdded = CCatalogProduct::Add(
            [
                "ID"                => $this->curIdAcc,
                "QUANTITY"          => $donSC['QUANTITY'],
                "QUANTITY_TRACE"    => 'D',
                "WEIGHT"            => $this->curPropsForAcc['WEIGHT_PM']
            ]
        );
        echo str_pad("isAdded: $isAdded", 14);
    }

    private function procPrices() {
        //Получение цены из донора
        $priceDon = $this->getDonPrice();

        echo str_pad("DonPrice?: $priceDon", 19);

        //запись базовой цены в аццептора
        $priceBase = (strlen(trim($this->curDonProps['_7_DLINASHT']['VALUE'])) > 0) ? round($priceDon*$this->curDonProps['_7_DLINASHT']['VALUE']) : $priceDon;
        $this->setConcretePrice(1, $priceBase);
        $this->setConcretePrice($this->curDonPriceId, $priceDon);

        //запись второй цены в аццептора
        $idSecondPrice = '';
        foreach ($this->accordances as $accordance) {
            if ($accordance['idDon'] == $this->curDonProps['_2_PODRAZDELSAYTA_NEW']['VALUE']) {
                $idSecondPrice = $accordance['idSecondPrice'];
            }
        }
        echo str_pad("2ndPriceID: $idSecondPrice", 15);


        if ($idSecondPrice) {
            switch ($idSecondPrice) {
                case 2:
                    $SecondPriceVal = $priceDon * 1000;
                    break;
            }

            $this->setConcretePrice($idSecondPrice, $SecondPriceVal);
        }
    }

    private function setConcretePrice($priceId, $priceVal){
        $arFields = [
            "PRODUCT_ID" => $this->curIdAcc,
            "CATALOG_GROUP_ID" => $priceId,
            "PRICE" => $priceVal,
            "CURRENCY" => "RUB",
        ];
        $prices = CPrice::GetList(
            array(),
            array(
                "PRODUCT_ID" => $this->curIdAcc,
                "CATALOG_GROUP_ID" => $priceId
            )
        );

        ($priceItem = $prices->Fetch()) ? CPrice::Update($priceItem["ID"], $arFields) : CPrice::Add($arFields);
    }

    private function getDonPrice() {
        $prices = CPrice::GetList(
            array(),
            array(
                "PRODUCT_ID" => $this->curDonFields['ID'],
                "CATALOG_GROUP_ID" => $this->curDonPriceId
            )
        );

        if ($price = $prices->Fetch()) {
            $priceVal = $price['PRICE'];
        }
        else  {
            $priceVal = false;
        }
        return $priceVal;
    }

    /**
     * @return int? or false
     */
    private function getIdCurrAcc() {
        $goodsAcc = CIBlockElement::GetList(
            Array(),
            Array(
                'IBLOCK_CODE' => self::SCODE_ACC,
                'EXTERNAL_ID' => $this->curDonFields['EXTERNAL_ID']
            ),
            false,
            false,
            Array()
        );

        return ($goodAcc = $goodsAcc->GetNextElement()) ? $goodAcc->GetFields()['ID'] : false;
    }

    private function getSectIdAcc(){
        $idGroupNew = '';
//        $this->accordances = include('groupAccordance.php');

        foreach ($this->accordances as $accordance) {
            if ($this->curDonProps['_2_PODRAZDELSAYTA_NEW']['VALUE'] == $accordance['idDon']) {

                $secCodeAcc = $accordance['secCode'];

                $accSecs = CIBlockSection::GetList(
                    array(),
                    array('IBLOCK_ID' => $this->idAccIBlock, '=CODE' => $secCodeAcc)
                );
                if ($accSec = $accSecs->Fetch()) {
                    $idGroupNew = $accSec['ID'];
                }
            }
        }
        return $idGroupNew;
    }

    private function crtNameForAcc() {
        if ($this->settings["debugDB"]) {
            $nameLabel = ' [из основного имени товара]';
            $nameSiteLabel = ' [из имени для сайта]';
            $dliniSiteLabel = ' [из длины-сайт]';
        } else {
            $nameLabel = '';
            $nameSiteLabel = '';
            $dliniSiteLabel = '';
        }

        $nameGoodAcc = $this->curDonFields['NAME'] . $nameLabel;

        if ($this->curDonProps['_3_NAIMENOVANIESAYT']['VALUE'] != '') {
            $nameGoodAcc = $this->curDonProps['_3_NAIMENOVANIESAYT']['VALUE'] . $nameSiteLabel;
        }

        if ($this->curDonProps['_4_DLINYSAYT']['VALUE'] != '') {
            $nameGoodAcc .= " {$this->curDonProps['_4_DLINYSAYT']['VALUE']}$dliniSiteLabel";
        }

        return $nameGoodAcc;
    }

    private function getIdAccIBlock(){

        $accs = CIBlock::GetList(
            Array(),
            Array("CODE"      =>  self::SCODE_ACC),
            true
        );

        while($acc = $accs->Fetch()) {
            $idAcc = $acc['ID'];
        }

        return $idAcc;
    }

    private function getGoodsDon(){
        $filter = [
            'IBLOCK_CODE' => self::SCODE_DON,
        ];

        if ($this->settings['sectToProc'] > 0) {
            $filter['PROPERTY__2_PODRAZDELSAYTA_NEW'] = $this->settings['sectToProc'];
        }

        $goodsDon = CIBlockElement::GetList(
            Array(),
            $filter,
            false,
            false,
            Array()
        );
        $goodsDon->NavStart(100500);

        return $goodsDon;
    }

    private function toEng($st) {
        $st = strtr($st,
            "абвгдежзийклмнопрстуфыэАБВГДЕЖЗИЙКЛМНОПРСТУФЫЭ",
            "abvgdegziyklmnoprstufieABVGDEGZIYKLMNOPRSTUFIE"
        );
        $st = strtr($st, array(
            'ё'=>"yo",    'х'=>"h",  'ц'=>"ts",  'ч'=>"ch", 'ш'=>"sh",
            'щ'=>"shch",  'ъ'=>'',   'ь'=>'',    'ю'=>"yu", 'я'=>"ya",
            'Ё'=>"Yo",    'Х'=>"H",  'Ц'=>"Ts",  'Ч'=>"Ch", 'Ш'=>"Sh",
            'Щ'=>"Shch",  'Ъ'=>'',   'Ь'=>'',    'Ю'=>"Yu", 'Я'=>"Ya",
        ));
        return $st;
    }

}
