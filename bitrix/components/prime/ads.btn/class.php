<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

class UserCardComponent extends CBitrixComponent
{
    /**
     * Подготавливаем входные параметры
     *
     * @param array $arParams
     *
     * @return array
     */
    public function onPrepareComponentParams($arParams)
    {  
        return $arParams;
    }
    /**
     * Основной метод выполнения компонента
     *
     * @return void
     */
    public function executeComponent()
    {
        // Кешируем результат, чтобы не делать постоянные запросы к базе
        if ($this->startResultCache())
        {
            $this->initResult();
            
            $this->includeComponentTemplate();
        }
    }
    /**
     * Инициализируем результат
     *
     * @return void
     */
    private function initResult(): void
    {
        $this->arResult = [];
    }
}
?>
