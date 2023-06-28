<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <script src="//api.bitrix24.com/api/v1/"></script>
    <script src="//code.jquery.com/jquery-3.6.0.js" type="text/javascript"></script>
    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script type="text/javascript">
        <?php
        $SERVER_PATH = $_GET['path'];
        $dir = __DIR__ .  '/tmp/' . $SERVER_PATH . '/';

        if (isset($_POST['reportName'])){
            $arrayjson = $_POST;

            $SERVER_PATH = $_POST['path'];
            $dir = __DIR__ .  '/tmp/' . $SERVER_PATH . '/';

            file_put_contents($dir . 'transit_values' . '.json', json_encode($arrayjson, JSON_FORCE_OBJECT));
        }

        $content = file_get_contents($dir . 'transit_values' . '.json');
        $arrayjson = json_decode($content, true);
        $data = json_encode($content, true);
        ?>

        var result_batch;
        var entity_compare = [];
        var entity_dictionary = {
            "ACTIVITY#DIRECTION#MISSED" : "пропущенный звонок",
            "ACTIVITY#DIRECTION#INTERNAL" : "внутренняя коммуникация",

            "ACTIVITY#NOTIFY_TYPE#IMMEDIATELY" : "немедленное уведомление",
            "ACTIVITY#NOTIFY_TYPE#SUMMARY" : "сводка уведомлений",
            "ACTIVITY#NOTIFY_TYPE#DELAYED" : "отложенное уведомление",

            "COMPANY#COMPANY_TYPE#CUSTOMER" : "клиент или заказчик",
            "COMPANY#COMPANY_TYPE#PARTNER" : "партнер компании",
            "COMPANY#COMPANY_TYPE#SUPPLIER" : "поставщик компании",
            "COMPANY#COMPANY_TYPE#COMPETITOR" : "конкурент компании",
            "COMPANY#COMPANY_TYPE#OTHER" : "другой тип компании",

            "DEAL#SOURCE_ID#CALL" : "Звонок",
            "DEAL#SOURCE_ID#EMAIL" : "Электронная почта",
            "DEAL#SOURCE_ID#WEB" : "Веб-сайт",
            "DEAL#SOURCE_ID#ADVERTISING" : "Реклама",
            "DEAL#SOURCE_ID#REPEAT_SALES" : "Существующий клиент",
            "DEAL#SOURCE_ID#RECOMMENDATION" : "По рекомендации",
            "DEAL#SOURCE_ID#EXHIBITION" : "Выставка",
            "DEAL#SOURCE_ID#CRM_FORM" : "CRM-форма",
            "DEAL#SOURCE_ID#CALLBACK" : "Обратный звонок",
            "DEAL#SOURCE_ID#SALES_GENERATOR" : "Генератор продаж",
            "DEAL#SOURCE_ID#ECOMM" : "Интернет-магазин",

            "DEAL#CATEGORY_ID#0" : "Нет категории",
            "DEAL#CATEGORY_ID#1" : "Продажи",
            "DEAL#CATEGORY_ID#2" : "Консультации",
            "DEAL#CATEGORY_ID#3" : "Проекты",

            "DEAL#TYPE_ID#SALE" : "ПРОДАЖА",
            "DEAL#TYPE_ID#PURCHASE" : "ПОКУПКА",
            "DEAL#TYPE_ID#DEAL" : "СДЕЛКА",
            "DEAL#TYPE_ID#QUOTE" : "ПРЕДЛОЖЕНИЕ",
            "DEAL#TYPE_ID#SERVICE" : "СЕРВИС",
            "DEAL#TYPE_ID#RENT" : "АРЕНДА",

            "DEAL#STAGE_ID#NEW" : "НОВЫЙ",
            "DEAL#STAGE_ID#PREPARATION" : "ПОДГОТОВКА",
            "DEAL#STAGE_ID#PRESENTATION" : "ПРЕЗЕНТАЦИЯ",
            "DEAL#STAGE_ID#NEGOTIATION" : "ПРЕГОВОРЫ",
            "DEAL#STAGE_ID#WON" : "ВЫИГРАНА",
            "DEAL#STAGE_ID#FAILED" : "ПРОИГРАНА",
            "DEAL#STAGE_SEMANTIC_ID#P" : "ПРОГРЕСС",
            "DEAL#STAGE_SEMANTIC_ID#S" : "УСПЕХ",
            "DEAL#STAGE_SEMANTIC_ID#F" : "ПРОВАЛ",

            "DEAL#IS_MANUAL_OPPORTUNITY#Y" : "СУММА ВОЗМОЖНОЙ ПРИБЫЛИ БЫЛА УСТАНОВЛЕНА В РУЧНУЮ",
            "DEAL#IS_MANUAL_OPPORTUNITY#N" : "СУММА ВОЗМОЖНОЙ ПРИБЫЛИ БЫЛА АВТОМАТИЧЕСКИ РАССЧИТАНА",

            "DEAL#IS_REPEATED_APPROACH#Y" : "СДЕЛКА ЯВЛЕТСЯ ПОВТОРНЫМ КОНТАКТОМ ИЛИ ПРИБЛИЖЕНИЕМ К КЛИЕНТУ",
            "DEAL#IS_REPEATED_APPROACH#N" : "СДЕЛКА ЯВЛЯЕТСЯ ПЕРВЫМ КОНТАКТОМ С КЛИЕНТОМ",

            "DEAL#IS_RETURN_CUSTOMER#Y" : "ПОСТОЯННЫЙ ИЛИ ПОВТОРНЫЙ КЛИЕНТ",
            "DEAL#IS_RETURN_CUSTOMER#N" : "НОВЫЙ КЛИЕНТ",

            "DEAL#IS_RECURRING#Y" : "СДЕЛКА ПОВТОРЯЮЩАЯСЯ ИЛИ РЕГУЛЯРНАЯ",
            "DEAL#IS_RECURRING#N" : "ОДНОРАЗОВАЯ СДЕЛКА",

            "DEAL#OPENED#Y" : "СДЕЛКА ОТКРЫТА ДЛЯ ДОСТУПА",
            "DEAL#OPENED#N" : "СДЕЛКА ОГРАНИЧЕНА ПО ДОСТУПУ",

            "DEAL#CLOSED#Y" : "СДЕЛКА ЗАКРЫТА",
            "DEAL#CLOSED#N" : "СДЕЛКА ОТКРЫТА",

            "DEAL#IS_NEW#Y" : "НОВАЯ СДЕЛКА",
            "DEAL#IS_NEW#N" : "СДЕЛКА НАХОДИТСЯ В РАБОТЕ",

            "ACTIVITY#OWNER_TYPE_ID#DEAL": "СДЕЛКА",
            "ACTIVITY#OWNER_TYPE_ID#CONTACT": "КОНТАКТ",
            "ACTIVITY#OWNER_TYPE_ID#COMPANY": "КОМПАНИЯ",
            "ACTIVITY#OWNER_TYPE_ID#LEAD": "ЛИД",

            "ACTIVITY#TYPE_ID#MEETING": "ВСТРЕЧА",
            "ACTIVITY#TYPE_ID#TASK": "ЗАДАЧА",
            "ACTIVITY#TYPE_ID#EMAIL": "ПОЧТА",
            "ACTIVITY#TYPE_ID#WEBFORM": "ВЕБ-ФОРМА",
            "ACTIVITY#TYPE_ID#CHAT": "ЧАТ",
            "ACTIVITY#TYPE_ID#VISIT": "ВИЗИТ",
            "ACTIVITY#TYPE_ID#OTHER": "ДРУГОЕ",
            "ACTIVITY#TYPE_ID#CALL": "ЗВОНОК",

            "ACTIVITY#PROVIDER_TYPE_ID#CRM ": "CRM",
            "ACTIVITY#PROVIDER_TYPE_ID#IMOPENLINES": "ОТКРЫТЫЕ ЛИНИИ",
            "ACTIVITY#PROVIDER_TYPE_ID#REST": "REST",
            "ACTIVITY#PROVIDER_TYPE_ID#CALENDAR": "КАЛЕНДАРЬ",
            "ACTIVITY#PROVIDER_TYPE_ID#TASKS": "ЗАДАЧИ",
            "ACTIVITY#PROVIDER_TYPE_ID#TELEPHONY": "ТЕЛЕФОНИЯ",
            "ACTIVITY#PROVIDER_TYPE_ID#EMAIL": "ПОЧТА",
            "ACTIVITY#PROVIDER_TYPE_ID#WEBFORM": "ВЕБ-ФОРМА",
            "ACTIVITY#PROVIDER_TYPE_ID#CALL_TRACKER": "ТРЕКЕР ЗВОНКОВ",
            "ACTIVITY#PROVIDER_TYPE_ID#CHAT": "ЧАТ",

            "ACTIVITY#COMPLETED#Y": "ВЫПОЛНЕНО",
            "ACTIVITY#COMPLETED#N": "НЕ ВЫПОЛНЕНО",

            "ACTIVITY#STATUS#PLANNED": "СТАТУС:ЗАПЛАНИРОВАНО",
            "ACTIVITY#STATUS#COMPLETED": "СТАТУС:ВЫПОЛНЕНО",
            "ACTIVITY#STATUS#POSTPONED": "СТАТУС:ОТЛОЖЕНО",
            "ACTIVITY#STATUS#DECLINED": "СТАТУС:ОТКЛОНЕНО",
            "ACTIVITY#STATUS#STARTED": "СТАТУС:НАЧАЛОСЬ",

            "ACTIVITY#PRIORITY#HIGH": "ВЫСОКИЙ ПРИОРИТЕТ",
            "ACTIVITY#PRIORITY#NORMAL": "СРЕДНИЙ ПРИОРИТЕТ",
            "ACTIVITY#PRIORITY#LOW": "НИЗКИЙ ПРИОРИТЕТ",

            "ACTIVITY#NOTIFY_TYPE#EMAIL": "УВЕДОМЛЕНИЕ ОТПРАВЛЕНО ПО ПОЧТЕ",
            "ACTIVITY#NOTIFY_TYPE#SYSTEM": "УВЕДОМЛЕНИЕ ОТПРАВЛЕНО ЧЕРЕЗ СИСТЕМУ",
            "ACTIVITY#NOTIFY_TYPE#NONE": "УВЕДОМЛЕНИЕ НЕ ОТПРАВЛЕНО",

            "ACTIVITY#DESCRIPTION_TYPE#PLAIN": "ОБЫЧНЫЙ ТЕКСТ",
            "ACTIVITY#DESCRIPTION_TYPE#HTML": "ТЕКСТ В ФОРМАТЕ HTML",

            "ACTIVITY#DIRECTION#INCOMING": "ВХОДЯЩЕЕ НАПРАВЛЕНИЕ",
            "ACTIVITY#DIRECTION#OUTGOING": "ИСХОДЯЩАЯ НАПРАВЛЕНЕ",

            "ACTIVITY#RESULT_MARK#POSITIVE": "ПОЛОЖИТЕЛЬНЫЙ РЕЗУЛЬТАТ",
            "ACTIVITY#RESULT_MARK#NEGATIVE": "ОТРИЦАТЕЛЬНЫЙ РЕЗУЛЬТАТ",

            "ACTIVITY#RESULT_STATUS#SUCCESS": "УСПЕШНОЕ ВЫПОЛНЕНИЕ ЗАДАЧИ ИЛИ ДЕЛА",
            "ACTIVITY#RESULT_STATUS#FAILED": "НЕУДАЧНОЕ ВЫПОЛНЕНИЕ ЗАДАЧИ ИЛИ ДЕЛА",

            "ACTIVITY#RESULT_STREAM#INCOMING": "ПОТОК ВХОДЯЩИХ РЕЗУЛЬТАТОВ",
            "ACTIVITY#RESULT_STREAM#OUTGOING": "ПОТОК ИСХОДЯЩИХ РЕЗУЛЬТАТОВ",

            "ACTIVITY#AUTOCOMPLETE_RULE#NONE": "АВТОЗАПОЛНЕНИЕ НЕ ИСПОЛЬЗУЕТСЯ ",
            "ACTIVITY#AUTOCOMPLETE_RULE#START_TIME": "ПОЛЕ ВРЕМЯ НАЧАЛА ЗАПОЛНЕНО АВТОМАТИЧЕСКИ",
            "ACTIVITY#AUTOCOMPLETE_RULE#END_TIME": "ПОЛЕ ВРЕМЯ КОНЦА ЗАПОЛНЕНО АВТОМАТИЧЕСКИ",
            "ACTIVITY#AUTOCOMPLETE_RULE#DURATION": "ПОЛЕ ПРОДОЛЖИТЕЛЬНОСТЬ ЗАПОЛНЕНО АВТОМАТИЧЕСКИ",
            "ACTIVITY#AUTOCOMPLETE_RULE#RESPONSIBLE_ID": "ПОЛЕ ОТВЕТСТВЕННЫЙ ЗАПОЛНЕНО АВТОМАТИЧЕСКИ",
            "ACTIVITY#AUTOCOMPLETE_RULE#ORIGIN_ID": "ИСТОЧНИК ЗАДАЧИ ИЛИ ДЕЛА ЗАПОЛНЕН АВТОМАТИЧЕСКИ",
            "ACTIVITY#AUTOCOMPLETE_RULE#ORIGINATOR_ID": "ИДЕНТИФИКАТОР ПОЛЬЗОВАТЕЛЯ ЗАПОЛНЕН АВТОМАТИЧЕСКИ",

            "OFFER#STATUS_ID#DRAFT": "КОММЕРЧЕСКОЕ ПРЕДЛОЖЕНИЕ НАХОДИТСЯ В СТАДИИ ЧЕРНОВИК",
            "OFFER#STATUS_ID#SENT": "КОММЕРЧЕСКОЕ ПРЕДЛОЖЕНИЕ БЫЛО ОТПРАВЛЕНО КЛИЕНТУ",
            "OFFER#STATUS_ID#APPROVED": "КОММЕРЧЕСКОЕ ПРЕДЛОЖЕНИЕ БЫЛО ОДОБРЕННО КЛИЕНТОМ",
            "OFFER#STATUS_ID#DECLINED": "КОММЕРЧЕСКОЕ ПРЕДЛОЖЕНИЕ БЫЛО ОТКЛОНЕНО КЛИЕНТОМ",
            "OFFER#STATUS_ID#WAITING": "КОММЕРЧЕСКОЕ ПРЕДЛОЖЕНИЕ НАХОДИТСЯ В ОЖИДАНИИ РЕШЕНИЯ КЛИЕНТА",

            "OFFER#OPENED#Y": "БЫЛО ОТКРЫТО КЛИЕНТОМ",
            "OFFER#OPENED#N": "НЕ БЫЛО ОТКРЫТО КЛИЕНТОМ",

            "OFFER#CLOSED#Y": "БЫЛО ЗАКРЫТО",
            "OFFER#CLOSED#N": "НЕ БЫЛО ЗАКРЫТО",

            "CONTACT#SOURCE_ID#CALL" : "Звонок",
            "CONTACT#SOURCE_ID#EMAIL" : "Электронная почта",
            "CONTACT#SOURCE_ID#WEB" : "Веб-сайт",
            "CONTACT#SOURCE_ID#ADVERTISING" : "Реклама",
            "CONTACT#SOURCE_ID#REPEAT_SALES" : "Существующий клиент",
            "CONTACT#SOURCE_ID#RECOMMENDATION" : "По рекомендации",
            "CONTACT#SOURCE_ID#EXHIBITION" : "Выставка",
            "CONTACT#SOURCE_ID#CRM_FORM" : "CRM-форма",
            "CONTACT#SOURCE_ID#CALLBACK" : "Обратный звонок",
            "CONTACT#SOURCE_ID#SALES_GENERATOR" : "Генератор продаж",
            "CONTACT#SOURCE_ID#ECOMM" : "Интернет-магазин",

            "CONTACT#EXPORT#Y": "КОНТАКТ БЫЛ ЭКСПОРТИРОВАН В ФАЙЛ",
            "CONTACT#EXPORT#N": "КОНТАКТ НЕ ЭКСПОРТИРОВАН В ФАЙЛ",

            "CONTACT#HAS_PHONE#Y": "ЕСТЬ НОМЕР ТЕЛЕФОН",
            "CONTACT#HAS_PHONE#N": "НЕТ НОМЕРА ТЕЛЕФОНА",

            "CONTACT#HAS_EMAIL#Y": "ЕСТЬ ПОЧТА",
            "CONTACT#HAS_EMAIL#N": "НЕТ ПОЧТЫ",

            "CONTACT#HAS_IMOL#Y": "ЕСТЬ ПОДКЛЮЧЕНИЕ К МЕССЕНДЖЕРУ В CRM БИТРИКС24",
            "CONTACT#HAS_IMOL#N": "НЕТ  ПОДКЛЮЧЕНИЯ К МЕССЕНДЖЕРУ В CRM БИТРИКС24",

            "CONTACT#OPENED#Y": "КОНТАКТ БЫЛ ОТКРЫТ",
            "CONTACT#OPENED#N": "КОНТАКТ НЕ БЫЛ ОТКРЫТ",

            "CONTACT#TYPE_ID#PARTNER": "КОНТАКТ ЯВЛЯЕТСЯ ПАРТНЕРОМ",
            "CONTACT#TYPE_ID#CLIENT": "КОНТАКТ ЯВЛЯЕТСЯ КЛИЕНТОМ",
            "CONTACT#TYPE_ID#REFERRAL": "КОНТАКТ ЯВЛЯЕТСЯ РЕФЕРРАЛОМ",

            "LEAD#SOURCE_ID#CALL" : "Звонок",
            "LEAD#SOURCE_ID#EMAIL" : "Электронная почта",
            "LEAD#SOURCE_ID#WEB" : "Веб-сайт",
            "LEAD#SOURCE_ID#ADVERTISING" : "Реклама",
            "LEAD#SOURCE_ID#REPEAT_SALES" : "Существующий клиент",
            "LEAD#SOURCE_ID#RECOMMENDATION" : "По рекомендации",
            "LEAD#SOURCE_ID#EXHIBITION" : "Выставка",
            "LEAD#SOURCE_ID#CRM_FORM" : "CRM-форма",
            "LEAD#SOURCE_ID#CALLBACK" : "Обратный звонок",
            "LEAD#SOURCE_ID#SALES_GENERATOR" : "Генератор продаж",
            "LEAD#SOURCE_ID#ECOMM" : "Интернет-магазин",

            "LEAD#STATUS_ID#NEW": "ЛИД НАХОДИТСЯ В СТАДИИ НОВЫЙ",
            "LEAD#STATUS_ID#IN_PROCESS": "ЛИД НАХОДИТСЯ В СТАДИИ В ПРОЦЕССЕ",
            "LEAD#STATUS_ID#CONVERTED": "ЛИД БЫЛ ПРЕОБРАЗОВАН",
            "LEAD#STATUS_ID#JUNK": "ЛИД БЫЛ ОТБРОШЕН",
            "LEAD#STATUS_ID#CONTACT": "ЛИД БЫЛ ПРЕОБРАЗОВАН ТОЛЬКО В КОНТАКТ",
            "LEAD#STATUS_ID#COMPANY": "ЛИД БЫЛ ПРЕОБРАЗОВАН ТОЛЬКО В КОМПАНИБ",

            "LEAD#IS_RETURN_CUSTOMER#Y": "ЛИД ЯВЛЯЕТСЯ ПОВТОРНЫМ КЛИЕНТОМ",
            "LEAD#IS_RETURN_CUSTOMER#N": "ЛИД ЯВЛЯЕТСЯ НОВЫМ КЛИЕНТОМ ",

            "LEAD#IS_MANUAL_OPPORTUNITY#Y": "СУММА ПОТЕНЦИАЛЬНОЙ ВЫРУЧКИ БЫЛА УСТАНОВЛЕНА ВРУЧНУЮ",
            "LEAD#IS_MANUAL_OPPORTUNITY#N": "СУММА ПОТЕНЦИАЛЬНОЙ ВЫРУЧКИ БЫЛА УСТАНОВЛЕНА АВТОМАТИЧЕСКИ",

            "LEAD#HAS_PHONE#Y": "ЕСТЬ НОМЕР ТЕЛЕФОНА",
            "LEAD#HAS_PHONE#N": "НЕТ НОМЕРА ТЕЛЕФОНА",

            "LEAD#HAS_EMAIL#Y": "ЕСТЬ ПОЧТА",
            "LEAD#HAS_EMAIL#N": "НЕТ ПОЧТЫ",

            "LEAD#HAS_IMOL#Y": "ЕСТЬ ПОДКЛЮЧЕНИЕ К МЕССЕНДЖЕРУ В CRM БИТРИКС24",
            "LEAD#HAS_IMOL#N": "НЕТ  ПОДКЛЮЧЕНИЯ К МЕССЕНДЖЕРУ В CRM БИТРИКС24",

            "LEAD#STATUS_SEMANTIC_ID#Y": "СТАТУС ЛИДА ИМЕЕТ ПОТЕНЦИАЛ ДЛЯ ДАЛЬНЕЙШЕЙ РАБОТЫ",
            "LEAD#STATUS_SEMANTIC_ID#N": "СТАТУС ЛИДА НЕ ИМЕЕТ ПОТЕНЦИАЛ ДЛЯ ДАЛЬНЕЙШЕЙ РАБОТЫ",

            "LEAD#OPENED#Y": "БЫЛ ОТКРЫТ КЛИЕНТОМ",
            "LEAD#OPENED#N": "НЕ БЫЛ ОТКРЫТ КЛИЕНТОМ",

            "INVOICE#categoryId#0" : "Нет категории",
            "INVOICE#categoryId#1" : "Продажи",
            "INVOICE#categoryId#2" : "Консультации",
            "INVOICE#categoryId#3" : "Проекты",

            "INVOICE#stageId#NEW": "Новая",
            "INVOICE#stageId#IN_PROCESS": "В работе",
            "INVOICE#stageId#WON": "Закрыта",
            "INVOICE#stageId#LOSE": "Не закрыта",

            "INVOICE#opened#Y": "БЫЛ ОТКРЫТ КЛИЕНТОМ",
            "INVOICE#opened#N": "НЕ БЫЛ ОТКРЫТ КЛИЕНТОМ",

            "INVOICE#isManualOpportunity#Y": "СУММА ПОТЕНЦИАЛЬНОЙ ВЫРУЧКИ БЫЛА УСТАНОВЛЕНА ВРУЧНУЮ",
            "INVOICE#isManualOpportunity#N": "СУММА ПОТЕНЦИАЛЬНОЙ ВЫРУЧКИ БЫЛА УСТАНОВЛЕНА АВТОМАТИЧЕСКИ",

            "PRODUCT#ACTIVE#Y": "ПРОУДКТ АКТИВЕН",
            "PRODUCT#ACTIVE#N": "ПРОУДКТ НЕ АКТИВЕН",

            "PRODUCT#VAT_INCLUDED#Y": "ДА",
            "PRODUCT#VAT_INCLUDED#N":"НЕТ",

            "PRODUCT#DESCRIPTION_TYPE#text": "ОБЫЧНЫЙ ТЕКСТ",
            "PRODUCT#DESCRIPTION_TYPE#html": "ТЕКСТ В ФОРМАТЕ HTML",

            "COMPANY#ORIGIN_ID#0": "Нет источника",
            "COMPANY#ORIGIN_ID#1": "Реклама",
            "COMPANY#ORIGIN_ID#2": "Наши менеджеры",
            "COMPANY#ORIGIN_ID#3": "Партнеры",
            "COMPANY#ORIGIN_ID#4": "Сайт",
            "COMPANY#ORIGIN_ID#5": "Рекомендации",
            "COMPANY#ORIGIN_ID#6": "Переговоры",
            "COMPANY#ORIGIN_ID#7": "E-mail-рассылки",
            "COMPANY#ORIGIN_ID#8": "Телефонные звонки",
            "COMPANY#ORIGIN_ID#9": "Социальные сети",
            "COMPANY#ORIGIN_ID#10": "Другое",

            "COMPANY#HAS_PHONE#Y": "ЕСТЬ НОМЕРА ТЕЛЕФОНА",
            "COMPANY#HAS_PHONE#N": "НЕТ НОМЕРА ТЕЛЕФОНА",

            "COMPANY#HAS_EMAIL#Y": "ЕСТЬ ПОЧТА",
            "COMPANY#HAS_EMAIL#N": "НЕТ ПОЧТЫ",

            "COMPANY#HAS_IMOL#Y": "ЕСТЬ ПОДКЛЮЧЕНИЕ К МЕССЕНДЖЕРУ В CRM БИТРИКС24",
            "COMPANY#HAS_IMOL#N": "НЕТ  ПОДКЛЮЧЕНИЯ К МЕССЕНДЖЕРУ В CRM БИТРИКС24",

            "COMPANY#IS_MY_COMPANY#Y": "ДА",
            "COMPANY#IS_MY_COMPANY#N": "НЕТ",

            "COMPANY#OPENED#Y": "БЫЛА ОТКРЫТА КЛИЕНТОМ",
            "COMPANY#OPENED#N": "НЕ БЫЛА ОТКРЫТА КЛИЕНТОМ",
        };
        var entity_dictionary_except = {

        };
        var deal_batch;
        var productrows;
        var activity_batch;
        var offer_batch;
        var contact_batch;
        var lead_batch;
        var invoice_batch;
        var product_batch;
        var company_batch;
        var workers_batch;
        var option;
        var string;
        var counter = 0;
        var valueOfEntity_counter = 0;
        var result_value;

        var arrayjson = <?=$data;?>;

        arrayjson = JSON.parse(arrayjson);
        arrayjson = Object.values(arrayjson);

        function closeReport(){
            window.location.href = 'index.php?path=' + '<?=$SERVER_PATH;?>';
        }
        function value_validator(entity ,value, option){
            let key = entity + "#" + option + "#" + value;
            if (entity_dictionary.hasOwnProperty(key)){return entity_dictionary[key];}
            else {
                if (entity_dictionary_except.hasOwnProperty(key)) {
                } else {
                    return value;
                }
            }
        }
        function comparer(index) {
            return function(a, b) {
                var valA = getCellValue(a, index), valB = getCellValue(b, index);
                return $.isNumeric(valA) && $.isNumeric(valB) ? valA - valB : valA.localeCompare(valB);
            }
        }
        function getCellValue(row, index){ return $(row).children('td').eq(index).text() }

        /* Переданные данные для таблицы */
        var selectedValues_choose = Object.values(arrayjson[0]);
        var datebegin_choose = arrayjson[1];
        var dateend_choose = arrayjson[2];
        var workers_choose = Object.values(arrayjson[3]);
        var reportName_choose = arrayjson[4];
        var reportDescription_choose = arrayjson[5];

        console.log('JSON: ', arrayjson);

        $(document).ready(function() {
            /* Батч запрос по сущностям */
            BX24.callBatch({
                get_user: ['user.get', {}],
                get_deal: ['crm.deal.list', {}],
                get_case: ['crm.activity.list', {}],
                get_offer: ['crm.quote.list', {}],
                get_contact: ['crm.contact.list', {}],
                get_lead: ['crm.lead.list', {}],
                get_invoice: ['crm.item.list', {'entityTypeId': 31}],
                get_product: ['crm.product.list', {}],
                get_company: ['crm.company.list', {}],
            }, function(result) {
                workers_batch = result['get_user']['answer']['result'];

                deal_batch = result['get_deal']['answer']['result'];
                activity_batch = result['get_case']['answer']['result'];
                offer_batch = result['get_offer']['answer']['result'];
                contact_batch = result['get_contact']['answer']['result'];
                lead_batch = result['get_lead']['answer']['result'];
                invoice_batch = result['get_invoice']['answer']['result']['items'];
                product_batch = result['get_product']['answer']['result'];
                company_batch = result['get_company']['answer']['result'];

                result_batch = result;
                console.log('BATCH', result);

                selectedValues_choose.forEach(valueOfEntity => {
                    let valueOfEntity_substr = valueOfEntity.split('#');
                    let valueOfEntity_class = valueOfEntity_substr[0];
                    let valueOfEntity_option = valueOfEntity_substr[1];
                    let valueOfEntity_name = valueOfEntity_substr[2];

                    ($('<th id="header-th-sortable">'+ valueOfEntity_name +'</th>')
                        .appendTo('#table-head'));
                });

                workers_choose.forEach(worker_choose => {
                    deal_batch.forEach(deal => {
                        BX24.callMethod(
                            "crm.deal.productrows.get",
                            { id: deal['ID'] },
                            function (result){

                                entity_date_begin = new Date(deal['DATE_CREATE']);
                                entity_date_end = new Date(deal['CLOSEDATE']);

                                datebegin_choose = new Date(datebegin_choose);
                                dateend_choose = new Date(dateend_choose);

                                if (deal['CREATED_BY_ID'] === worker_choose
                                    && entity_date_end >= datebegin_choose
                                    && entity_date_end <= dateend_choose)
                                {
                                    counter++;
                                    string = 'table-main-tr' + counter;
                                    ($('<tr id=' + string + '></tr>')
                                        .appendTo('#table'));

                                    selectedValues_choose.forEach(valueOfEntity => {
                                        let valueOfEntity_substr = valueOfEntity.split('#');
                                        let valueOfEntity_class = valueOfEntity_substr[0];
                                        let valueOfEntity_option = valueOfEntity_substr[1];

                                        valueOfEntity_counter++;

                                        switch (valueOfEntity_class){
                                            case "WORKER":
                                                try {
                                                    workers_batch.forEach(worker => {
                                                        if (worker['ID'] === worker_choose) {
                                                            let worker_name = worker['NAME'] + " " + worker['LAST_NAME'];
                                                            let worker_href = 'https://' + arrayjson[7] + '/crm/company/personal/user/' + worker['ID'] + '/';

                                                            if (worker_name === " "){worker_name = worker['EMAIL'];}

                                                            ($('<td></td>')
                                                                    .appendTo('#' + string)
                                                                    .append('<a href="'+ worker_href +'" target="_blank">' + worker_name + '</a>')
                                                            );

                                                            throw new Error('value_found');
                                                        }
                                                    });
                                                } catch (error) {
                                                    if (error.message !== 'value_found') {
                                                        throw error;
                                                    }
                                                }
                                                break;
                                            case "DEAL":
                                                let deal_href = 'https://' + arrayjson[7] + '/crm/deal/details/' + deal['ID'] + '/';
                                                result_value = value_validator(valueOfEntity_class, deal[valueOfEntity_option], valueOfEntity_option);

                                                ($('<td></td>')
                                                    .appendTo('#' + string)
                                                        .append('<a href="'+ deal_href +'" target="_blank">' + result_value + '</a>')
                                                );

                                                entity_compare.push(deal);
                                                break;
                                            case "ACTIVITY":
                                                string_intable = 'table-main-tr' + String(counter) + String(valueOfEntity_counter);
                                                ($('<td></td>')
                                                        .appendTo('#' + string)
                                                        .append($('<div id="into-table"></div>')
                                                            .append($('<table id=' + string_intable + '></table>')))
                                                );

                                                activity_batch.forEach(activity => {
                                                    if (String(activity['OWNER_ID']) === String(deal['ID'])) {
                                                        result_value = value_validator(valueOfEntity_class, activity[valueOfEntity_option], valueOfEntity_option);

                                                        ($('<tr></tr>')
                                                                .appendTo('#' + string_intable)
                                                                .append($('<th>' + result_value + '</th>'))
                                                        );
                                                        entity_compare.push(activity);
                                                    }
                                                });
                                                break;
                                            case "OFFER":
                                                string_intable = 'table-main-tr' + String(counter) + String(valueOfEntity_counter);
                                                ($('<td></td>')
                                                        .appendTo('#' + string)
                                                        .append($('<div id="into-table"></div>')
                                                            .append($('<table id=' + string_intable + '></table>')))
                                                );

                                                offer_batch.forEach(offer => {
                                                    if (String(offer['DEAL_ID']) === String(deal['ID'])) {
                                                        result_value = value_validator(valueOfEntity_class, offer[valueOfEntity_option], valueOfEntity_option);

                                                        ($('<tr></tr>')
                                                                .appendTo('#' + string_intable)
                                                                .append($('<th>' + result_value + '</th>'))
                                                        );
                                                        entity_compare.push(offer);
                                                    }
                                                });
                                                break;
                                            case "CONTACT":
                                                string_intable = 'table-main-tr' + String(counter) + String(valueOfEntity_counter);
                                                ($('<td></td>')
                                                        .appendTo('#' + string)
                                                        .append($('<div id="into-table"></div>')
                                                            .append($('<table id=' + string_intable + '></table>')))
                                                );

                                                contact_batch.forEach(contact => {
                                                    if (String(contact['ID']) === String(deal['CONTACT_ID'])) {
                                                        result_value = value_validator(valueOfEntity_class, contact[valueOfEntity_option], valueOfEntity_option);

                                                        ($('<tr></tr>')
                                                                .appendTo('#' + string_intable)
                                                                .append($('<th>' + result_value + '</th>'))
                                                        );
                                                        entity_compare.push(contact);
                                                    }
                                                });
                                                break;
                                            case "LEAD":
                                                string_intable = 'table-main-tr' + String(counter) + String(valueOfEntity_counter);
                                                ($('<td></td>')
                                                        .appendTo('#' + string)
                                                        .append($('<div id="into-table"></div>')
                                                            .append($('<table id=' + string_intable + '></table>')))
                                                );

                                                lead_batch.forEach(lead => {
                                                    if (String(lead['ID']) === String(deal['LEAD_ID'])) {
                                                        result_value = value_validator(valueOfEntity_class, lead[valueOfEntity_option], valueOfEntity_option);

                                                        ($('<tr></tr>')
                                                                .appendTo('#' + string_intable)
                                                                .append($('<th>' + result_value + '</th>'))
                                                        );
                                                        entity_compare.push(lead);
                                                    }
                                                });
                                                break;
                                            case "INVOICE":
                                                string_intable = 'table-main-tr' + String(counter) + String(valueOfEntity_counter);
                                                ($('<td></td>')
                                                        .appendTo('#' + string)
                                                        .append($('<div id="into-table"></div>')
                                                            .append($('<table id=' + string_intable + '></table>')))
                                                );

                                                invoice_batch.forEach(invoice => {
                                                    if (String(invoice['parentId2']) === String(deal['ID'])) {
                                                        result_value = value_validator(valueOfEntity_class, invoice[valueOfEntity_option], valueOfEntity_option);

                                                        ($('<tr></tr>')
                                                                .appendTo('#' + string_intable)
                                                                .append($('<th>' + result_value + '</th>'))
                                                        );
                                                        entity_compare.push(invoice);
                                                    }
                                                });
                                                break;
                                            case "PRODUCT":
                                                string_intable = 'table-main-tr' + String(counter) + String(valueOfEntity_counter);
                                                ($('<td></td>')
                                                        .appendTo('#' + string)
                                                        .append($('<div id="into-table"></div>')
                                                            .append($('<table id=' + string_intable + '></table>')))
                                                );
                                                productrows = result.data();

                                                productrows.forEach(productrow => {
                                                    result_value = value_validator(valueOfEntity_class, productrow[valueOfEntity_option], valueOfEntity_option);

                                                    ($('<tr></tr>')
                                                            .appendTo('#' + string_intable)
                                                            .append($('<th>' + result_value + '</th>'))
                                                    );
                                                    entity_compare.push(productrow);
                                                });
                                                break;
                                            case "COMPANY":
                                                string_intable = 'table-main-tr' + String(counter) + String(valueOfEntity_counter);
                                                ($('<td></td>')
                                                        .appendTo('#' + string)
                                                        .append($('<div id="into-table"></div>')
                                                            .append($('<table id=' + string_intable + '></table>')))
                                                );

                                                company_batch.forEach(company => {
                                                    if (String(company['ID']) === String(deal['COMPANY_ID'])) {
                                                        result_value = value_validator(valueOfEntity_class, company[valueOfEntity_option], valueOfEntity_option);

                                                        ($('<tr></tr>')
                                                                .appendTo('#' + string_intable)
                                                                .append($('<th>' + result_value + '</th>'))
                                                        );
                                                        entity_compare.push(company);
                                                    }
                                                });
                                                break;
                                        }
                                    });
                                }

                                if (deal_batch.indexOf(deal) === deal_batch.length - 1 && workers_choose.indexOf(worker_choose) === workers_choose.length - 1)
                                {

                                    var loader = document.querySelector('.loader');
                                    loader.style.display = 'none';

                                    selectedValues_choose.forEach(valueOfEntity => {
                                        let valueOfEntity_substr = valueOfEntity.split('#');
                                        let valueOfEntity_class = valueOfEntity_substr[0];
                                        let valueOfEntity_option = valueOfEntity_substr[1];

                                        switch (valueOfEntity_class){
                                            case "ACTIVITY":
                                                activity_batch.forEach(activity => {
                                                    if (entity_compare.includes(activity)) {}
                                                    else {
                                                        entity_date_begin = new Date(activity['CREATED']);
                                                        entity_date_end = new Date(activity['CREATED']);

                                                        datebegin_choose = new Date(datebegin_choose);
                                                        dateend_choose = new Date(dateend_choose);

                                                        if (activity['AUTHOR_ID'] === worker_choose
                                                            && entity_date_end >= datebegin_choose
                                                            && entity_date_end <= dateend_choose) {
                                                            counter++;
                                                            string = 'table-main-tr' + counter;
                                                            ($('<tr id=' + string + '></tr>')
                                                                .appendTo('#table'));

                                                            selectedValues_choose.forEach(valueOfEntity => {
                                                                let valueOfEntity_substr = valueOfEntity.split('#');
                                                                let valueOfEntity_class = valueOfEntity_substr[0];
                                                                let valueOfEntity_option = valueOfEntity_substr[1];

                                                                valueOfEntity_counter++;

                                                                switch (valueOfEntity_class) {
                                                                    case "WORKER":
                                                                        try {
                                                                            workers_batch.forEach(worker => {
                                                                                if (worker['ID'] === worker_choose) {
                                                                                    let worker_name = worker['NAME'] + " " + worker['LAST_NAME'];
                                                                                    ($('<td>' + worker_name + '</td>')
                                                                                        .appendTo('#' + string));

                                                                                    throw new Error('value_found');
                                                                                }
                                                                            });
                                                                        } catch (error) {
                                                                            if (error.message !== 'value_found') {
                                                                                throw error;
                                                                            }
                                                                        }
                                                                        break;
                                                                    case "ACTIVITY":
                                                                        string_intable = 'table-main-tr' + String(counter) + String(valueOfEntity_counter);
                                                                        ($('<td></td>')
                                                                                .appendTo('#' + string)
                                                                                .append($('<div id="into-table"></div>')
                                                                                    .append($('<table id=' + string_intable + '></table>')))
                                                                        );
                                                                        result_value = value_validator(valueOfEntity_class, activity[valueOfEntity_option], valueOfEntity_option);

                                                                        ($('<tr></tr>')
                                                                                .appendTo('#' + string_intable)
                                                                                .append($('<th>' + result_value + '</th>'))
                                                                        );
                                                                        entity_compare.push(activity);
                                                                        break;

                                                                    case "DEAL":
                                                                    case "OFFER":
                                                                    case "CONTACT":
                                                                    case "LEAD":
                                                                    case "INVOICE":
                                                                    case "PRODUCT":
                                                                    case "COMPANY":
                                                                        ($('<td></td>')
                                                                            .appendTo('#' + string));
                                                                        break;
                                                                }
                                                            });
                                                        }
                                                    }
                                                });
                                                break;
                                            case "OFFER":
                                                offer_batch.forEach(offer => {
                                                    if (entity_compare.includes(offer)) {
                                                    } else {
                                                        entity_date_begin = new Date(offer['DATE_CREATE']);
                                                        entity_date_end = new Date(offer['CLOSEDATE']);

                                                        datebegin_choose = new Date(datebegin_choose);
                                                        dateend_choose = new Date(dateend_choose);

                                                        if (offer['CREATED_BY_ID'] === worker_choose
                                                            && entity_date_end >= datebegin_choose
                                                            && entity_date_end <= dateend_choose) {
                                                            counter++;
                                                            string = 'table-main-tr' + counter;
                                                            ($('<tr id=' + string + '></tr>')
                                                                .appendTo('#table'));

                                                            selectedValues_choose.forEach(valueOfEntity => {
                                                                let valueOfEntity_substr = valueOfEntity.split('#');
                                                                let valueOfEntity_class = valueOfEntity_substr[0];
                                                                let valueOfEntity_option = valueOfEntity_substr[1];

                                                                valueOfEntity_counter++;

                                                                switch (valueOfEntity_class) {
                                                                    case "WORKER":
                                                                        try {
                                                                            workers_batch.forEach(worker => {
                                                                                if (worker['ID'] === worker_choose) {
                                                                                    let worker_name = worker['NAME'] + " " + worker['LAST_NAME'];
                                                                                    ($('<td>' + worker_name + '</td>')
                                                                                        .appendTo('#' + string));

                                                                                    throw new Error('value_found');
                                                                                }
                                                                            });
                                                                        } catch (error) {
                                                                            if (error.message !== 'value_found') {
                                                                                throw error;
                                                                            }
                                                                        }
                                                                        break;
                                                                    case "OFFER":
                                                                        string_intable = 'table-main-tr' + String(counter) + String(valueOfEntity_counter);
                                                                        ($('<td></td>')
                                                                                .appendTo('#' + string)
                                                                                .append($('<div id="into-table"></div>')
                                                                                    .append($('<table id=' + string_intable + '></table>')))
                                                                        );
                                                                        result_value = value_validator(valueOfEntity_class, offer[valueOfEntity_option], valueOfEntity_option);

                                                                        ($('<tr></tr>')
                                                                                .appendTo('#' + string_intable)
                                                                                .append($('<th>' + result_value + '</th>'))
                                                                        );
                                                                        entity_compare.push(offer);
                                                                        break;
                                                                    case "DEAL":
                                                                    case "ACTIVITY":
                                                                    case "CONTACT":
                                                                    case "LEAD":
                                                                    case "INVOICE":
                                                                    case "PRODUCT":
                                                                    case "COMPANY":
                                                                        ($('<td></td>')
                                                                            .appendTo('#' + string));
                                                                        break;
                                                                }
                                                            });
                                                        }
                                                    }
                                                });
                                                break;
                                            case "CONTACT":
                                                contact_batch.forEach(contact => {
                                                    if (entity_compare.includes(contact)) {
                                                    } else {
                                                        entity_date_begin = new Date(contact['DATE_CREATE']);
                                                        entity_date_end = new Date(contact['DATE_CREATE']);

                                                        datebegin_choose = new Date(datebegin_choose);
                                                        dateend_choose = new Date(dateend_choose);

                                                        if (contact['CREATED_BY_ID'] === worker_choose
                                                            && entity_date_end >= datebegin_choose
                                                            && entity_date_end <= dateend_choose) {
                                                            counter++;
                                                            string = 'table-main-tr' + counter;
                                                            ($('<tr id=' + string + '></tr>')
                                                                .appendTo('#table'));

                                                            selectedValues_choose.forEach(valueOfEntity => {
                                                                let valueOfEntity_substr = valueOfEntity.split('#');
                                                                let valueOfEntity_class = valueOfEntity_substr[0];
                                                                let valueOfEntity_option = valueOfEntity_substr[1];

                                                                valueOfEntity_counter++;

                                                                switch (valueOfEntity_class) {
                                                                    case "WORKER":
                                                                        try {
                                                                            workers_batch.forEach(worker => {
                                                                                if (worker['ID'] === worker_choose) {
                                                                                    let worker_name = worker['NAME'] + " " + worker['LAST_NAME'];
                                                                                    ($('<td>' + worker_name + '</td>')
                                                                                        .appendTo('#' + string));

                                                                                    throw new Error('value_found');
                                                                                }
                                                                            });
                                                                        } catch (error) {
                                                                            if (error.message !== 'value_found') {
                                                                                throw error;
                                                                            }
                                                                        }
                                                                        break;
                                                                    case "CONTACT":
                                                                        string_intable = 'table-main-tr' + String(counter) + String(valueOfEntity_counter);
                                                                        ($('<td></td>')
                                                                                .appendTo('#' + string)
                                                                                .append($('<div id="into-table"></div>')
                                                                                    .append($('<table id=' + string_intable + '></table>')))
                                                                        );
                                                                        result_value = value_validator(valueOfEntity_class, contact[valueOfEntity_option], valueOfEntity_option);

                                                                        ($('<tr></tr>')
                                                                                .appendTo('#' + string_intable)
                                                                                .append($('<th>' + result_value + '</th>'))
                                                                        );
                                                                        entity_compare.push(contact);
                                                                        break;

                                                                    case "DEAL":
                                                                    case "ACTIVITY":
                                                                    case "OFFER":
                                                                    case "LEAD":
                                                                    case "INVOICE":
                                                                    case "PRODUCT":
                                                                    case "COMPANY":
                                                                        ($('<td></td>')
                                                                            .appendTo('#' + string));
                                                                        break;

                                                                }
                                                            });
                                                        }
                                                    }
                                                });
                                                break;
                                            case "LEAD":
                                                lead_batch.forEach(lead => {
                                                    if (entity_compare.includes(lead)) {
                                                    } else {
                                                        console.log(lead);

                                                        entity_date_begin = new Date(lead['DATE_CREATE']);
                                                        entity_date_end = new Date(lead['DATE_CREATE']);

                                                        datebegin_choose = new Date(datebegin_choose);
                                                        dateend_choose = new Date(dateend_choose);

                                                        if (lead['CREATED_BY_ID'] === worker_choose
                                                            && entity_date_end >= datebegin_choose
                                                            && entity_date_end <= dateend_choose) {
                                                            counter++;
                                                            string = 'table-main-tr' + counter;
                                                            ($('<tr id=' + string + '></tr>')
                                                                .appendTo('#table'));

                                                            selectedValues_choose.forEach(valueOfEntity => {
                                                                let valueOfEntity_substr = valueOfEntity.split('#');
                                                                let valueOfEntity_class = valueOfEntity_substr[0];
                                                                let valueOfEntity_option = valueOfEntity_substr[1];

                                                                valueOfEntity_counter++;

                                                                switch (valueOfEntity_class) {
                                                                    case "WORKER":
                                                                        try {
                                                                            workers_batch.forEach(worker => {
                                                                                if (worker['ID'] === worker_choose) {
                                                                                    let worker_name = worker['NAME'] + " " + worker['LAST_NAME'];
                                                                                    ($('<td>' + worker_name + '</td>')
                                                                                        .appendTo('#' + string));

                                                                                    throw new Error('value_found');
                                                                                }
                                                                            });
                                                                        } catch (error) {
                                                                            if (error.message !== 'value_found') {
                                                                                throw error;
                                                                            }
                                                                        }
                                                                        break;
                                                                    case "LEAD":
                                                                        string_intable = 'table-main-tr' + String(counter) + String(valueOfEntity_counter);
                                                                        ($('<td></td>')
                                                                                .appendTo('#' + string)
                                                                                .append($('<div id="into-table"></div>')
                                                                                    .append($('<table id=' + string_intable + '></table>')))
                                                                        );
                                                                        result_value = value_validator(valueOfEntity_class, lead[valueOfEntity_option], valueOfEntity_option);

                                                                        ($('<tr></tr>')
                                                                                .appendTo('#' + string_intable)
                                                                                .append($('<th>' + result_value + '</th>'))
                                                                        );
                                                                        entity_compare.push(lead);
                                                                        break;

                                                                    case "DEAL":
                                                                    case "ACTIVITY":
                                                                    case "OFFER":
                                                                    case "CONTACT":
                                                                    case "INVOICE":
                                                                    case "PRODUCT":
                                                                    case "COMPANY":
                                                                        ($('<td></td>')
                                                                            .appendTo('#' + string));
                                                                        break;

                                                                }
                                                            });
                                                        }
                                                    }
                                                });
                                                break;
                                            case "INVOICE":
                                                invoice_batch.forEach(invoice => {
                                                    if (entity_compare.includes(invoice)) {
                                                    } else {
                                                        entity_date_begin = new Date(invoice['createdTime']);
                                                        entity_date_end = new Date(invoice['closedate']);

                                                        datebegin_choose = new Date(datebegin_choose);
                                                        dateend_choose = new Date(dateend_choose);

                                                        if (toString(invoice['createdBy']) === toString(worker_choose)
                                                            && entity_date_end >= datebegin_choose
                                                            && entity_date_end <= dateend_choose) {
                                                            counter++;
                                                            string = 'table-main-tr' + counter;
                                                            ($('<tr id=' + string + '></tr>')
                                                                .appendTo('#table'));

                                                            selectedValues_choose.forEach(valueOfEntity => {
                                                                let valueOfEntity_substr = valueOfEntity.split('#');
                                                                let valueOfEntity_class = valueOfEntity_substr[0];
                                                                let valueOfEntity_option = valueOfEntity_substr[1];

                                                                valueOfEntity_counter++;

                                                                switch (valueOfEntity_class) {
                                                                    case "WORKER":
                                                                        try {
                                                                            workers_batch.forEach(worker => {
                                                                                if (worker['ID'] === worker_choose) {
                                                                                    let worker_name = worker['NAME'] + " " + worker['LAST_NAME'];
                                                                                    ($('<td>' + worker_name + '</td>')
                                                                                        .appendTo('#' + string));

                                                                                    throw new Error('value_found');
                                                                                }
                                                                            });
                                                                        } catch (error) {
                                                                            if (error.message !== 'value_found') {
                                                                                throw error;
                                                                            }
                                                                        }
                                                                        break;
                                                                    case "INVOICE":
                                                                        string_intable = 'table-main-tr' + String(counter) + String(valueOfEntity_counter);
                                                                        ($('<td></td>')
                                                                                .appendTo('#' + string)
                                                                                .append($('<div id="into-table"></div>')
                                                                                    .append($('<table id=' + string_intable + '></table>')))
                                                                        );
                                                                        result_value = value_validator(valueOfEntity_class, invoice[valueOfEntity_option], valueOfEntity_option);

                                                                        ($('<tr></tr>')
                                                                                .appendTo('#' + string_intable)
                                                                                .append($('<th>' + result_value + '</th>'))
                                                                        );
                                                                        entity_compare.push(invoice);
                                                                        break;

                                                                    case "DEAL":
                                                                    case "ACTIVITY":
                                                                    case "OFFER":
                                                                    case "CONTACT":
                                                                    case "LEAD":
                                                                    case "PRODUCT":
                                                                    case "COMPANY":
                                                                        ($('<td></td>')
                                                                            .appendTo('#' + string));
                                                                        break;

                                                                }
                                                            });
                                                        }
                                                    }
                                                });
                                                break;
                                            case "PRODUCT":
                                                product_batch.forEach(product => {
                                                    if (entity_compare.includes(product)) {
                                                    } else {
                                                        entity_date_begin = new Date(product['DATE_CREATE']);
                                                        entity_date_end = new Date(product['DATE_CREATE']);

                                                        datebegin_choose = new Date(datebegin_choose);
                                                        dateend_choose = new Date(dateend_choose);

                                                        if (product['CREATED_BY'] === worker_choose
                                                            && entity_date_end >= datebegin_choose
                                                            && entity_date_end <= dateend_choose) {
                                                            counter++;
                                                            string = 'table-main-tr' + counter;
                                                            ($('<tr id=' + string + '></tr>')
                                                                .appendTo('#table'));

                                                            selectedValues_choose.forEach(valueOfEntity => {
                                                                let valueOfEntity_substr = valueOfEntity.split('#');
                                                                let valueOfEntity_class = valueOfEntity_substr[0];
                                                                let valueOfEntity_option = valueOfEntity_substr[1];

                                                                valueOfEntity_counter++;

                                                                switch (valueOfEntity_class) {
                                                                    case "WORKER":
                                                                        try {
                                                                            workers_batch.forEach(worker => {
                                                                                if (worker['ID'] === worker_choose) {
                                                                                    let worker_name = worker['NAME'] + " " + worker['LAST_NAME'];
                                                                                    ($('<td>' + worker_name + '</td>')
                                                                                        .appendTo('#' + string));

                                                                                    throw new Error('value_found');
                                                                                }
                                                                            });
                                                                        } catch (error) {
                                                                            if (error.message !== 'value_found') {
                                                                                throw error;
                                                                            }
                                                                        }
                                                                        break;
                                                                    case "PRODUCT":
                                                                        string_intable = 'table-main-tr' + String(counter) + String(valueOfEntity_counter);
                                                                        ($('<td></td>')
                                                                                .appendTo('#' + string)
                                                                                .append($('<div id="into-table"></div>')
                                                                                    .append($('<table id=' + string_intable + '></table>')))
                                                                        );
                                                                        switch(valueOfEntity_option){
                                                                            case "PRODUCT_NAME":
                                                                                except_option = "NAME";
                                                                                break;
                                                                            case "QUANTITY":
                                                                                except_option = "";
                                                                                break;
                                                                            case "MEASURE_NAME":
                                                                                except_option = "MEASURE";
                                                                                break;
                                                                            case "MEASURE_CODE":
                                                                                except_option = "CODE";
                                                                                break;
                                                                            case "CUSTOM_PRICE":
                                                                                except_option = "";
                                                                                break;
                                                                            case "DISCOUNT_TYPE_ID":
                                                                                except_option = "";
                                                                                break;
                                                                            case "DISCOUNT_RATE":
                                                                                except_option = "";
                                                                                break;
                                                                            case "DISCOUNT_SUM":
                                                                                except_option = "PRICE";
                                                                                break;
                                                                            case "TAX_RATE":
                                                                                except_option = "";
                                                                                break;
                                                                            case "TAX_INCLUDED":
                                                                                except_option = "";
                                                                                break;
                                                                        }
                                                                        result_value = value_validator(valueOfEntity_class, product[except_option], valueOfEntity_option);

                                                                        ($('<tr></tr>')
                                                                                .appendTo('#' + string_intable)
                                                                                .append($('<th>' + result_value + '</th>'))
                                                                        );
                                                                        entity_compare.push(product);
                                                                        break;

                                                                    case "DEAL":
                                                                    case "ACTIVITY":
                                                                    case "OFFER":
                                                                    case "CONTACT":
                                                                    case "LEAD":
                                                                    case "INVOICE":
                                                                    case "COMPANY":
                                                                        ($('<td></td>')
                                                                            .appendTo('#' + string));
                                                                        break;

                                                                }
                                                            });
                                                        }
                                                    }
                                                });
                                                break;
                                            case "COMPANY":
                                                company_batch.forEach(company => {
                                                    if (entity_compare.includes(company)) {
                                                    } else {
                                                        entity_date_begin = new Date(company['DATE_CREATE']);
                                                        entity_date_end = new Date(company['DATE_CREATE']);

                                                        datebegin_choose = new Date(datebegin_choose);
                                                        dateend_choose = new Date(dateend_choose);

                                                        if (company['CREATED_BY_ID'] === worker_choose
                                                            && entity_date_end >= datebegin_choose
                                                            && entity_date_end <= dateend_choose) {
                                                            counter++;
                                                            string = 'table-main-tr' + counter;
                                                            ($('<tr id=' + string + '></tr>')
                                                                .appendTo('#table'));

                                                            selectedValues_choose.forEach(valueOfEntity => {
                                                                let valueOfEntity_substr = valueOfEntity.split('#');
                                                                let valueOfEntity_class = valueOfEntity_substr[0];
                                                                let valueOfEntity_option = valueOfEntity_substr[1];

                                                                valueOfEntity_counter++;

                                                                switch (valueOfEntity_class) {
                                                                    case "WORKER":
                                                                        try {
                                                                            workers_batch.forEach(worker => {
                                                                                if (worker['ID'] === worker_choose) {
                                                                                    let worker_name = worker['NAME'] + " " + worker['LAST_NAME'];
                                                                                    ($('<td>' + worker_name + '</td>')
                                                                                        .appendTo('#' + string));

                                                                                    throw new Error('value_found');
                                                                                }
                                                                            });
                                                                        } catch (error) {
                                                                            if (error.message !== 'value_found') {
                                                                                throw error;
                                                                            }
                                                                        }
                                                                        break;
                                                                    case "COMPANY":
                                                                        string_intable = 'table-main-tr' + String(counter) + String(valueOfEntity_counter);
                                                                        ($('<td></td>')
                                                                                .appendTo('#' + string)
                                                                                .append($('<div id="into-table"></div>')
                                                                                    .append($('<table id=' + string_intable + '></table>')))
                                                                        );
                                                                        result_value = value_validator(valueOfEntity_class, company[valueOfEntity_option], valueOfEntity_option);

                                                                        ($('<tr></tr>')
                                                                                .appendTo('#' + string_intable)
                                                                                .append($('<th>' + result_value + '</th>'))
                                                                        );
                                                                        entity_compare.push(company);
                                                                        break;

                                                                    case "DEAL":
                                                                    case "ACTIVITY":
                                                                    case "OFFER":
                                                                    case "CONTACT":
                                                                    case "LEAD":
                                                                    case "INVOICE":
                                                                    case "PRODUCT":
                                                                        ($('<td></td>')
                                                                            .appendTo('#' + string));
                                                                        break;

                                                                }
                                                            });
                                                        }
                                                    }
                                                });
                                                break;
                                        }
                                    });

                                    {
                                        $('#table th').click(function(){
                                            var table = $(this).parents('table').eq(0);
                                            var rows = table.find('tr:gt(0)').toArray().sort(comparer($(this).index()));
                                            this.asc = !this.asc;
                                            if (!this.asc){rows = rows.reverse()}
                                            for (var i = 0; i < rows.length; i++){table.append(rows[i])}
                                        })
                                    }
                                }
                            }
                        );
                    });
                });
            });
        });
    </script>
    <style>
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            overflow: hidden;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            font-family: "Times New Roman";
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
        #report-param-panel-close{
            position: fixed;

            width: auto;
            height: 25px;
            border-radius: 10px;
            cursor: pointer;
            left: 15px;
            top: 2px;

            padding-top: 1px;
            text-align: center;
            font-size: 1.2em;

            color: #e7e7e0;
            text-decoration: underline;
            background-color: transparent;
        }

        #table-div{
            background-color: transparent;
            position: relative;
            overflow: auto;

            left: 10px;
            top: 10px;

            width: 99%;
            height: 98%;
        }
        #table {
            width: 99%;

            table-layout: auto;
            padding: 0;
            float: left;
            border-collapse: collapse;

            box-shadow: 0 0 1em rgba(0, 0, 0, 0.5);
            -moz-box-shadow: 0 0 1em rgba(0, 0, 0, 0.5);
            -webkit-box-shadow: 0 0 1em rgba(0, 0, 0, 0.5);
            background-position: 0 -100px;
        }
        #table th, #table td {
            padding: 0;
            text-align: center;

            border: 1px solid #666;
        }
        #table th {
            text-transform: uppercase;
            background-color: transparent;
        }
        #table tr {
            height: auto;
        }
        #table td.highlight {
            background: #e8e8e8;
        }

        #into-table{
            padding: 0;
            margin: 0;
            width: 100%;
            height: 100%;

            border: 0 solid transparent;
        }
        #into-table table{
            border-collapse: collapse;
            table-layout: auto;
            width: 100%;
            height: 100%;
            font-size: 0.6em;
            border: 0 solid transparent;
        }
        #into-table table th{
            padding: 0.5em;

            border: 0;

            width: auto;
            font-size: 1.3em;

            text-transform: uppercase;
            background-color: transparent;
        }
        #into-table table tr{
            height: 100%;
            border: 0 solid transparent;
        }
        #header-th-sortable{
            cursor: pointer;
        }

        .loader {
            position: fixed;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            z-index: 9999;
        }
        .loader span {
            display: inline-block;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #1a73e8;
            animation: loader-animation 1s ease-in-out infinite;
            margin-right: 5px;
        }
        .loader span:last-child {
            margin-right: 0;
        }
        @keyframes loader-animation {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(0.5);
            }
            100% {
                transform: scale(1);
            }
        }
    </style>
</head>
<body>
<header>
    <div id="report-param-panel-close" onclick="closeReport()">
        <text>На главную</text>
    </div>
</header>
<main>
    <div class="CENTERpanel">
        <div id="report-param-panel-create" style="display: none;" onclick="tableExport()">
            <text>Экспортировать отчет</text>
        </div>
        <div id="table-div">
            <table id="table">
                <tr id="table-head"></tr>
            </table>
        </div>
    </div>
</main>
<div class="loader">
    <span></span>
    <span></span>
    <span></span>
</div>
</body>
</html>