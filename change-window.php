<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <script src="//api.bitrix24.com/api/v1/"></script>
    <script src="//code.jquery.com/jquery-3.6.0.js" type="text/javascript"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script type="text/javascript">
        window.location.href = 'extra-report-open-window.php?path=' + '<?=$_GET['path'];?>' + '&click_name=' + '<?=$_GET['click_name'];?>';
    </script>
    <style>
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            overflow: hidden;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            font-family: sans-serif;
        }
        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 30px;
            background: #5D6162;
            justify-content: space-between;
            align-items: center;
            display: flex;

            z-index: 99;
            font-size: 0.9em;
        }
        main {
            position: fixed;
            top: 30px;
            left: 0;
            width: 100%;
            height: 95%;
            background: #D9D9D9;
            display: block;
            justify-content: space-between;

            z-index: 99;
            overflow-y: hidden;
        }
        main .CENTERpanel{
            position: absolute;
            display: flex;
            flex-flow: row wrap;
            align-content: flex-start;
            max-width: 98%;
            box-shadow: 0 0 10px 5px rgba(34, 60, 80, 0.19);

            width: 100%;
            height: 95%;
            background: #EDEDED;
            border-radius: 0 0 6px 6px;

            margin: 1%;
        }

        #report-param-panel{
            display: block;
            overflow-y: scroll;

            width: 100%;
            height: 100%;
        }
        #report-name-block{
            display: flex;
            flex-direction: column;

            width: 100%;
            height: auto;

            top: 10px;
            left: 10px;

            padding-top: 10px;
            padding-left: 5px;
            padding-right: 5px;

            margin-bottom: 10px;

            background-color: #798f69;
        }
        #report-name-block div{
            display: flex;
            flex-wrap: wrap;
            height: auto;
            margin-bottom: 10px;

            font-size: 1.2em;
        }
        #report-name-block div *{
            margin-right: 5px;
        }
        #report-name-block div textarea{
            width: 90%;

            margin-left: auto;
            margin-right: 5px;
        }
        #report-name-block div text{
            position: relative;
            top: 0px;
        }

        #report-filter-block{
            display: flex;
            flex-direction: column;

            width: 100%;
            height: auto;

            top: 10px;
            left: 10px;

            padding-top: 10px;
            padding-left: 5px;
            padding-right: 5px;

            margin-bottom: 10px;

            background-color: #798f69;
        }
        #report-filter-block div{
            display: flex;
            flex-wrap: wrap;
            height: auto;
            margin-bottom: 10px;

            font-size: 1.2em;
        }
        #report-filter-block div *{
            margin-right: 5px;
        }
        #report-filter-block div input{
        }
        #report-filter-block div label{
        }
        #report-filter-block div select{
        }
        #report-filter-block div text{
            position: relative;
            top: 0px;
            width: 105px;

            margin-right: 75px;
        }

        #sortable-entityes{
            display: inline-flex;
        }
        #sortable-entityes div{
            display: block;

            margin-right: 5px;
            border: black solid 1px;
            background-color: #e7e7e0;
            width: 165px;
            height: 300px;
            overflow-y: scroll;
            padding-left: 5px;
            font-size: 0.5em;
        }
        #sortable-entityes #sortable0{
            background-color: #e7e7e0;
            width: 278px;
            height: 300px;
            margin-right: 10px;

            overflow-y: scroll;
        }
        #sortable-entityes text{
            font-size: 1.5em;
        }

        #report-param-panel-close{
            position: fixed;

            width: 25px;
            height: 25px;
            border-radius: 10px;
            cursor: pointer;
            right: 45px;
            top: 52px;

            padding-top: 1px;
            text-align: center;
            font-size: 1.2em;

            background-color: rgba(192, 0, 0, 0.58);
        }
        #report-param-panel-create{
            position: fixed;

            width: auto;
            height: 25px;
            border-radius: 10px;
            cursor: pointer;
            right: 80px;
            top: 52px;

            text-align: center;
            font-size: 1.2em;

            background-color: rgba(35, 192, 0, 0.58);
        }

        .sortable ul li{
            border: 1px solid #000000;
            border-radius: 5px;

            color: #e7e7e0;
            background-color: #333333;
            font-weight: bold;
            cursor: pointer;

            margin-bottom: 5px;
        }
        .sortable #sortable-text{
            display: block;

            margin-right: 0;
            border: transparent solid 1px;
            background-color: transparent;
            width: auto;
            height: 30px;
            overflow-y: auto;
            padding-left: 1px;
            font-size: 1em;
        }
        #sortable0 ul li{
            border: 1px solid #000000;
            border-radius: 5px;

            color: #e7e7e0;
            background-color: #1f2e8a;
            font-weight: bold;

            margin-bottom: 5px;
        }

        input[type="checkbox"]{
            display: none;
        }
        .sortable::-webkit-scrollbar {
            width: 10px;
            border-radius: 5px;
            box-shadow: inset 0 0 5px rgba(0,0,0,.2);
        }

        .sortable::-webkit-scrollbar-thumb {
            background-color: #ccc;
            border-radius: 5px;
        }
    </style>
</head>
<body>
<header></header>
<main>
    <div class="CENTERpanel">
        <div id="report-param-panel">
            <div id="report-param-panel-close" onclick="closeReport()">
                <text>X</text>
            </div>
            <div id="report-param-panel-create" onclick="showReport()">
                <text>Сформировать отчет</text>
            </div>
            <div id="report-name-block">
                <div>
                    <text style="font-size: 1.3em; color: #e7e7e0">Имя отчета</text>
                </div>
            </div>
            <div id="report-filter-block">
                <div>
                    <text style="font-size: 1.3em; color: #e7e7e0">Параметры отчета</text>
                </div>
                <div>
                    <text>Период отчета</text>
                    <input type="date" name="calendar" value="" max="2099-12-30" min="2012-01-1" style="margin-right: 10px;" id="datebegin"">
                    <input type="date" name="calendar" value="" max="2099-12-30" min="2012-01-1" style="margin-left: 10px;" id="dateend"">
                </div>
                <div>
                    <text>Сотрудники</text>
                    <select id="worker" style="position: relative; width: 280px; height: auto; overflow-y: scroll" multiple>
                    </select>
                </div>
                <div>
                    <text>Поля отчета</text>
                    <div id="sortable-entityes">
                        <div id="sortable0" class="sortable">
                            <text>Перенесите в это поле нужные столбцы для отчета</text>
                        </div>
                        <div id="sortable1" class="sortable">
                            <div id="sortable-text"><text>Сделки</text></div>
                            <ul id="entityes">
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="DEAL#STAGE_ID#Статус сделки">Статус сделки</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="DEAL#DATE_CREATE#Дата создания сделки">Дата создания сделки</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="DEAL#CLOSEDATE#Дата закрытия сделки">Дата закрытия сделки</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="DEAL#ADDITIONAL_INFO#Дополнительная информация сделки">Дополнительная информация сделки</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="DEAL#BANK_DETAIL_ID#ID банковского реквизита">ID банковского реквизита</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="DEAL#COMMENTS#Коментарии сделки">Коментарии сделки</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="DEAL#CLOSED#Активность сделки">Активность сделки</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="DEAL#DATE_MODIFY#Дата изменения сделки">Дата изменения сделки</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="DEAL#IS_RETURN_CUSTOMER#Признак повторного лида сделки">Признак повторного лида сделки</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="DEAL#OPENED#Доступна сделка для всех">Доступна сделка для всех</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="DEAL#SOURCE_DESCRIPTION#Дополнительно об источнике сделки">Дополнительно об источнике сделки</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="DEAL#TAX_VALUE#Ставка налога сделки">Ставка налога сделки</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="DEAL#UTM_CAMPAIGN#Обозначение рекламной кампании сделки">Обозначение рекламной кампании сделки</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="DEAL#UTM_MEDIUM#Тип трафика сделки">Тип трафика сделки</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="DEAL#UTM_SOURCE#Рекламная система сделки">Рекламная система сделки</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="DEAL#UTM_TERM#Условие поиска кампании сделки">Условие поиска кампании сделки</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="DEAL#TITLE#Название сделки">Название сделки</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="WORKER#WORKER#Сотрудник">Сотрудник</li>
                            </ul>
                        </div>
                        <div id="sortable1" class="sortable">
                            <div id="sortable-text"><text>Дела</text></div>
                            <ul id="entityes">
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="ACTIVITY#DEADLINE#Сроки имполнения дела">Сроки имполнения дела</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="ACTIVITY#DESCRIPTION#Описание дела">Описание дела</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="ACTIVITY#DIRECTION#Направление дела">Направление дела: входящее/исходящее</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="ACTIVITY#EDITOR_ID#Кто изменил дело">Кто изменил дело</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="ACTIVITY#END_TIME#Время завершения дела">Время завершения дела</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="ACTIVITY#LAST_UPDATED#Дата последнего обновления дела">Дата последнего обновления дела</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="ACTIVITY#NOTIFY_TYPE#Тип уведомлений дела">Тип уведомлений дела</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="ACTIVITY#START_TIME#Время начала выполнения дела">Время начала выполнения дела</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="ACTIVITY#STATUS#Статус дела">Статус дела</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="ACTIVITY#SUBJECT#Субьект дела">Субьект дела</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="ACTIVITY#TYPE_ID#Тип дела">Тип дела</li>
                            </ul>
                        </div>
                        <div id="sortable1" class="sortable">
                            <div id="sortable-text"><text>Комм.предложения</text></div>
                            <ul id="entityes">
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="OFFER#BEGINDATE#Дата выставления предложения">Дата выставления предложения</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="OFFER#CLIENT_TITLE#Предложение: Название клиента">Предложение: Название клиента</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="OFFER#CLIENT_TPA_ID#Предложение: КПП клиента">Предложение: КПП клиента</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="OFFER#CLIENT_TP_ID#Предложение: ИНН клиента">Предложение: ИНН клиента</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="OFFER#CLOSED#Предложение завершено">Предложение завершено</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="OFFER#CLOSEDATA#Дата завершения предложения">Дата завершения предложения</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="OFFER#COMMENTS#Комментарий менеджера по предложению">Комментарий менеджера по предложению</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="OFFER#CONTENT#Содержание предложения">Содержание предложения</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="OFFER#CURRENCY_ID#Валюта предложения">Валюта предложения</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="OFFER#DATE_CREATE#Дата создания предложения">Дата создания предложения</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="OFFER#DATE_MODIFY#Дата изменения предложения">Дата изменения предложения</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="OFFER#OPENED#Доступно предложение для всех">Доступно предложение для всех</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="OFFER#QUOTE_NUMBER#Номер предложения">Номер предложения</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="OFFER#STATUS_ID#Статус предложения">Статус предложения</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="OFFER#TAX_VALUE#Сумма предложения">Сумма предложения</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="OFFER#TERMS#Условия предложения">Условия предложения</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="OFFER#TITLE#Название предложения">Название предложения</li>
                            </ul>
                        </div>
                        <div id="sortable1" class="sortable">
                            <div id="sortable-text"><text>Контакты</text></div>
                            <ul id="entityes">
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="CONTACT#NAME#Имя контакта">Имя контакта</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="CONTACT#LAST_NAME#Фамилия контакта">Фамилия контакта</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="CONTACT#SECOND_NAME#Отчество контакта">Отчество контакта</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="CONTACT#ADDRESS#Адрес контакта">Адрес контакта</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="CONTACT#BIRTHDATE#Дата рождения контакта">Дата рождения контакта</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="CONTACT#COMMENTS#Комментарии контакта">Комментарии контакта</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="CONTACT#CREATED_BY_ID#Кем создан контакт">Кем создан контакт</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="CONTACT#DATE_CREATE#Дата создания контакта">Дата создания контакта</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="CONTACT#DATE_MODIFY#Дата изменения контакта">Дата изменения контакта</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="CONTACT#EMAIL#Адрес электронной почты контакта">Адрес электронной почты контакта</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="CONTACT#EXPORT#Участвует ли контакт в экспорте">Участвует ли контакт в экспорте</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="CONTACT#HONORIFIC#Вид обращения контакта">Вид обращения контакта</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="CONTACT#ID#Идентификатор контакта контакта">Идентификатор контакта</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="CONTACT#IM#Мессенджеры контакта">Мессенджеры контакта</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="CONTACT#OPENED#Доступен контакт для всех">Доступен контакт для всех</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="CONTACT#PHONE#Телефон контакта">Телефон контакта</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="CONTACT#POST#Должность контакта">Должность контакта</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="CONTACT#SOURCE_DESCRIPTION#Описание источника контакта">Описание источника контакта</li>
                            </ul>
                        </div>
                        <div id="sortable1" class="sortable">
                            <div id="sortable-text"><text>Лиды</text></div>
                            <ul id="entityes">
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="LEAD#TITLE#Название лида">Название лида</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="LEAD#OPPORTUNITY#Предполагаемая сумма лида">Предполагаемая сумма лида</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="LEAD#NAME#Имя лида">Имя лида</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="LEAD#LAST_NAME#Фамилия лида">Фамилия лида</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="LEAD#SECOND_NAME#Отчество лида">Отчество лида</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="LEAD#POST#Должность лида">Должность лида</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="LEAD#PHONE#Телефон лида">Телефон лида</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="LEAD#DATE_CLOSED#Дата закрытия лида">Дата закрытия лида</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="LEAD#DATE_CREATE#Дата создания лида">Дата создания лида</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="LEAD#DATE_MODIFY#Дата изменения лида">Дата изменения лида</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="LEAD#EMAIL#Адрес электронной почты лида">Адрес электронной почты лида</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="LEAD#HONORIFIC#Вид обращения лида">Вид обращения лида</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="LEAD#IM#Мессенджеры лида">Мессенджеры лида</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="LEAD#MOVED_TIME#Дата перемещения лида на текущую стадию">Дата перемещения лида на текущую стадию</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="LEAD#OPENED#Дата перемещения лида на текущую стадию">Доступен лида для всех</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="LEAD#SOURCE_DESCRIPTION#Доступен лида для всех">Описание источника лида</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="LEAD#STATUS_ID#Описание источника лида">Статус лида</li>
                            </ul>
                        </div>
                        <div id="sortable1" class="sortable">
                            <div id="sortable-text"><text>Счета</text></div>
                            <ul id="entityes">
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="INVOICE#title#Название счет">Название счета</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="INVOICE#updatedBy#Кто обновил счет">Кто обновил счет</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="INVOICE#movedBy#Кто продвинул счет">Кто продвинул счет</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="INVOICE#createdTime#Дата создания счета">Дата создания счета</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="INVOICE#updatedTime#Дата обновления счета">Дата обновления счета</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="INVOICE#movedTime#Дата продвижения счета">Дата продвижения счета</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="INVOICE#categoryId#Категория счета">Категория счета</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="INVOICE#opened#Открыт счет">Открыт счет</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="INVOICE#begindate#Дата выставления счета">Дата выставления счета</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="INVOICE#closedate#Дата закрытия счета">Дата закрытия счета</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="INVOICE#opportunity#Счет">Счет</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="INVOICE#taxValue#Налог счета">Налог счета</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="INVOICE#sourceDescription#Описание счета">Описание счета</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="INVOICE#assignedById#Кем одобрен счет">Кем одобрен счет</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="INVOICE#comments#Комментарии к счету">Комментарии к счету</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="INVOICE#lastActivityBy#Кем последний раз изменен счет">Кем последний раз изменен счет</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="INVOICE#lastActivityTime#Дата последней активности счета">Дата последней активности счета</li>
                            </ul>
                        </div>
                        <div id="sortable1" class="sortable">
                            <div id="sortable-text"><text>Товары</text></div>
                            <ul  id="entityes">
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="PRODUCT#ID#идентификатор товарной позиции">идентификатор товарной позиции</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="PRODUCT#PRODUCT_ID#идентификатор продукта или услуги, который был добавлен к сделке">идентификатор продукта или услуги, который был добавлен к сделке</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="PRODUCT#PRODUCT_NAME#название продукта или услуги, который был добавлен к сделке">название продукта или услуги, который был добавлен к сделке</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="PRODUCT#PRICE#цена продукта или услуги, которые были добавлены к сделке">цена продукта или услуги, которые были добавлены к сделке</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="PRODUCT#QUANTITY#количество продукта или услуги, которые были добавлены к сделке">количество продукта или услуги, которые были добавлены к сделке</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="PRODUCT#MEASURE_NAME#название единицы измерения продукта или услуги">название единицы измерения продукта или услуги</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="PRODUCT#MEASURE_CODE#код единицы измерения продукта или услуги">код единицы измерения продукта или услуги</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="PRODUCT#CUSTOM_PRICE#флаг, указывающий, установлена ли пользовательская цена для данной товарной позиции">флаг, указывающий, установлена ли пользовательская цена для данной товарной позиции</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="PRODUCT#SORT#порядковый номер товарной позиции в списке">порядковый номер товарной позиции в списке</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="PRODUCT#DISCOUNT_TYPE_ID#тип скидки для данной товарной позиции">тип скидки для данной товарной позиции</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="PRODUCT#DISCOUNT_RATE#размер скидки для данной товарной позиции в процентах">размер скидки для данной товарной позиции в процентах</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="PRODUCT#DISCOUNT_SUM#размер скидки для данной товарной позиции в валюте сделки">размер скидки для данной товарной позиции в валюте сделки</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="PRODUCT#TAX_RATE#ставка налога для данной товарной позиции в процентах">ставка налога для данной товарной позиции в процентах</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="PRODUCT#TAX_INCLUDED#флаг, указывающий, включен ли налог в цену товарной позиции">флаг, указывающий, включен ли налог в цену товарной позиции</li>
                            </ul>
                        </div>
                        <div id="sortable1" class="sortable">
                            <div id="sortable-text"><text>Компании</text></div>
                            <ul id="entityes">
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="COMPANY#TITLE#Название компании">Название компании</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="COMPANY#ADDRESS#Адрес компании">Адрес компании</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="COMPANY#BANKING_DETAILS#Банковские реквизиты">Банковские реквизиты</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="COMPANY#COMMENTS#Комментарии компании">Комментарии компании</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="COMPANY#COMPANY_TYPE#Тип компании">Тип компании</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="COMPANY#CREATED_BY_ID#Кем создана компания">Кем создана компания</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="COMPANY#CURRENCY_ID#Валюта компании">Валюта компании</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="COMPANY#DATE_CREATE#Дата создания компании">Дата создания компании</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="COMPANY#DATE_MODIFY#Дата изменения компании">Дата изменения компании</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="COMPANY#EMAIL#Адрес электронной почты компании">Адрес электронной почты компании</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="COMPANY#EMPLOYESS#Количество сотрудников компании">Количество сотрудников компании</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="COMPANY#IM#Мессенджеры компании">Мессенджеры компании</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="COMPANY#INDUSTRY#Сфера деятельности компании">Сфера деятельности компании</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="COMPANY#OPENED#Компания доступна для всех">Компания доступна для всех</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="COMPANY#PHONE#Телефон компании">Телефон компании</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="COMPANY#REVENUE#Годовой оборот компании">Годовой оборот компании</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
</body>
</html>