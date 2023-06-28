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
        <?php
        require_once (__DIR__.'/crestcurrent.php');

        $click_name = $_GET['click_name'];
        $SERVER_PATH = $_GET['path'];
        $dir = __DIR__ .  '/tmp/' . $SERVER_PATH . '/';

        $content = file_get_contents($dir . 'reports' . '.json');
        $arrayjson = json_decode($content, true);
        $data = json_encode($content, true);

        foreach ($arrayjson as $content)
        {
            if ($click_name == $content["reportName"]) {
                $data_open = json_encode($content, true);

                $entityes = $content["entityes"];
                $dateend = $content["dateend"];
                $datebegin = $content["datebegin"];
                $workers = $content["workers"];
                $reportName = $content["reportName"];
                $reportDescription = $content["reportDescription"];

                $contain_name = '       <div>
                                            <text>Название отчета</text>
                                            <textarea id="report-name" style="margin-bottom: 5px">' . $reportName . '</textarea>
                                        </div>
                                        <div>
                                            <text>Описания отчета</text>
                                            <textarea id="report-description">' . $reportDescription . '</textarea>
                                        </div>';

                $contain_entityes = '<ul id="entityes">';

                foreach ($entityes as $entity)
                {
                    $entity_substr = explode('#', $entity);
                    $entity_class = $entity_substr[0];
                    $entity_option = $entity_substr[1];
                    $entity_name = $entity_substr[2];

                    $contain_entityes = $contain_entityes . '<li><input checked style="margin: 0; padding: 0; position: relative" type="checkbox" value="'. $entity .'">'. $entity_name .'</li>';
                }
                $contain_entityes = $contain_entityes . '</ul>';

                break;
            }
        }

        ?>

        var entityes = 'null';
        var datebegin = 'null';
        var dateend = 'null';
        var workers = [];
        var reportName = 'null';
        var reportDescription = 'null';
        var click_name = 'null';
        var selectedValues = 'null';
        var main_reportName;

        var arrayjson = <?=$data;?>;
        var arrayjson_open = <?=$data_open;?>;

        arrayjson = JSON.parse(arrayjson);
        arrayjson = Object.values(arrayjson);
        arrayjson_open = Object.values(arrayjson_open);

        function closeReport(){
            window.location.href = 'index.php?path=' + '<?=$SERVER_PATH;?>';
        }
        function showReport(){
            Object.values(document.getElementById('worker').selectedOptions).forEach(item => {
                workers.push(item.value);
            });

            workers = [...new Set(workers)];

            dateend = document.getElementById('dateend').value
            datebegin = document.getElementById('datebegin').value
            if ((reportName = document.getElementById('report-name').value) === undefined){reportName='null';}
            if ((reportDescription = document.getElementById('report-description').value) === undefined){reportDescription='null';}

            if (selectedValues.length !== 0 && reportName !== 'null' && reportDescription !== 'null' && workers.length !== 0){
                $.ajax({
                    type: 'POST',
                    url: 'extra-report-open-window.php',
                    data: {
                        'entityes': selectedValues,
                        'datebegin': datebegin,
                        'dateend': dateend,
                        'workers': workers,
                        'reportName': reportName,
                        'reportDescription': reportDescription,
                        'date': (new Date()).getDate() + '.' + ((new Date()).getMonth() + 1) + '.' + (new Date()).getFullYear(),
                        'path': '<?=$SERVER_PATH;?>',
                        'mainName': arrayjson_open[4]
                    },
                    success: function (){
                        var valid_value = true;
                        arrayjson.forEach(content => {
                            if (content['reportName'] === reportName){
                                valid_value = false;
                                if (arrayjson_open[4] === reportName){valid_value = true;}
                            }
                        });
                        if (valid_value) {
                            $.ajax({
                                type: 'POST',
                                url: 'extra-report-table-window.php',
                                data: {
                                    'entityes': selectedValues,
                                    'datebegin': datebegin,
                                    'dateend': dateend,
                                    'workers': workers,
                                    'reportName': reportName,
                                    'reportDescription': reportDescription,
                                    'date': (new Date()).getDate() + '.' + ((new Date()).getMonth() + 1) + '.' + (new Date()).getFullYear(),
                                    'path': '<?=$SERVER_PATH;?>'
                                },
                                success: function () {
                                    window.location.href = 'extra-report-table-window.php?path=' + '<?=$SERVER_PATH;?>';
                                }
                            });
                        }
                        else{
                            alert('Отчет с таким названием уже существует!');
                        }
                    }
                });
            }
            else{
                alert('Заполните все поля!');
            }
        }
        function ajaxToWrite(){
            <?php
            if (isset($_POST['reportName'])) {
                $SERVER_PATH = $_POST['path'];
                $dir = __DIR__ .  '/tmp/' . $SERVER_PATH . '/';
                $content = file_get_contents($dir . 'reports' . '.json');
                $arrayjson = json_decode($content, true);

                foreach($arrayjson as $key => $content){
                    if ($content["reportName"] === $_POST["mainName"]){
                        unset($arrayjson[$key]);
                        break;
                    }
                }
                $arrayjson[] = $_POST;

                file_put_contents($dir . 'reports' . '.json', json_encode($arrayjson, JSON_FORCE_OBJECT));
                file_put_contents($dir . 'test' . '.txt', $_POST['reportName'],true);
            }
            ?>
        }
        function change_example_table(){
            let values = document.querySelectorAll("#sortable0 ul li input");
            document.querySelector("#table-example tr").remove();
            ($('<tr id="table-example-tr"></tr>').appendTo("#table-example"));

            values.forEach(valueOfEntity => {
                let valueOfEntity_substr = valueOfEntity['value'].split('#');
                let valueOfEntity_name = valueOfEntity_substr[2];

                ($('<th id="header-th-sortable">'+ valueOfEntity_name +'</th>')
                    .appendTo($('#table-example-tr')));
            });
        }

        $( function() {
            $("#sortable0").sortable({
                connectWith: "#sortable0",
                items: "li", // указываем, что сортируемыми являются только элементы li
                stop: function(event, ui) {
                    $("#sortable0 input[type='checkbox']").each(function() {
                        selectedValues = [];

                        var MainList = document.querySelectorAll('#sortable0 li');
                        for (var i = 0; i < MainList.length; i++) {
                            var checkbox = MainList[i].querySelector('input[type="checkbox"]');
                            checkbox.checked = true;
                            checkbox.disabled = false;

                            selectedValues.push(checkbox.value);
                            change_example_table();
                        }
                    });
                }
            }).disableSelection();

            $("input[type='checkbox']").draggable({
                helper: "clone",
                connectToSortable: ".sortable ul"
            }).disableSelection();
        });
        $(document).ready(function() {
            const list_all = document.querySelectorAll('#sortable1 ul li');
            const list_main = document.querySelectorAll('#sortable0 ul li');

            list_main.forEach(function (listmain) {
                const main_checkbox = listmain.querySelector(':first-child');

                main_checkbox.addEventListener('change', function (){
                    if (!main_checkbox.checked) {
                        var MainList = document.querySelectorAll('#sortable1 li');
                        for (var i = 0; i < MainList.length; i++) {
                            var checkbox = MainList[i].querySelector('input[type="checkbox"]');
                            if (checkbox.value === main_checkbox.value){
                                checkbox.checked = false;
                                break;
                            }
                        }

                        listmain.remove();
                    }

                    $("#sortable0 input[type='checkbox']").each(function() {
                        selectedValues = [];

                        var MainList = document.querySelectorAll('#sortable0 li');
                        for (var i = 0; i < MainList.length; i++) {
                            var checkbox = MainList[i].querySelector('input[type="checkbox"]');
                            checkbox.checked = true;
                            checkbox.disabled = false;

                            selectedValues.push(checkbox.value);
                        }
                    });
                    change_example_table();
                });
            });
            // добавляем EventListener на каждый чекбокс
            list_all.forEach(function (list) {
                const all_checkbox = list.querySelector(':first-child');

                all_checkbox.addEventListener('change', function () {
                    // если чекбокс отмечен, то дублируем его в другом списке

                    if (all_checkbox.checked){
                        const duplicateLi = document.createElement('li');
                        const duplicateCheckbox = document.createElement('input');

                        duplicateCheckbox.type = 'checkbox';
                        duplicateCheckbox.checked = true;
                        duplicateCheckbox.value = all_checkbox.value;

                        // Переместить элемент checkbox перед текстом
                        duplicateLi.appendChild(duplicateCheckbox);
                        duplicateLi.appendChild(document.createTextNode(list.textContent));

                        document.querySelector('#sortable0 ul').appendChild(duplicateLi);

                        duplicateCheckbox.addEventListener('change', function () {
                            // при нажатии на дублированный чекбокс удаляем его из списка
                            if (!duplicateCheckbox.checked) {
                                var MainList = document.querySelectorAll('#sortable1 li');
                                for (var i = 0; i < MainList.length; i++) {
                                    var checkbox = MainList[i].querySelector('input[type="checkbox"]');
                                    if (checkbox.value === duplicateCheckbox.value){
                                        checkbox.checked = false;
                                        break;
                                    }
                                }

                                duplicateLi.remove();}

                            $("#sortable0 input[type='checkbox']").each(function() {
                                selectedValues = [];

                                var MainList = document.querySelectorAll('#sortable0 li');
                                for (var i = 0; i < MainList.length; i++) {
                                    var checkbox = MainList[i].querySelector('input[type="checkbox"]');
                                    checkbox.checked = true;
                                    checkbox.disabled = false;

                                    selectedValues.push(checkbox.value);
                                }
                            });
                            change_example_table();
                        });
                    }
                    else{
                        var MainList = document.querySelectorAll('#sortable0 li');
                        for (var i = 0; i < MainList.length; i++) {
                            var checkbox = MainList[i].querySelector('input[type="checkbox"]');
                            if (checkbox.value === all_checkbox.value){
                                MainList[i].remove();
                                break;
                            }
                        }
                    }

                    $("#sortable0 input[type='checkbox']").each(function() {
                        selectedValues = [];

                        var MainList = document.querySelectorAll('#sortable0 li');
                        for (var i = 0; i < MainList.length; i++) {
                            var checkbox = MainList[i].querySelector('input[type="checkbox"]');
                            checkbox.checked = true;
                            checkbox.disabled = false;

                            selectedValues.push(checkbox.value);
                        }
                    });
                    change_example_table();
                });
            });

            BX24.callMethod('user.get', {}, function (result) {
                    let users = result['answer']['result'];

                    console.log(users);

                    users.forEach(item => {
                        console.log(item);
                        if (item['NAME'] === "" && item['LAST_NAME'] === "")
                        {
                            ($('<option id="' + item["ID"] + '" class="filterBox-worker-name" value="' + item["ID"] + '">' + item["EMAIL"] + '</option>')
                                    .appendTo("#worker")
                            );
                        }
                        else
                        {
                            ($('<option id="' + item["ID"] + '" class="filterBox-worker-name" value="' + item["ID"] + '">' + item["NAME"] + " " + item["LAST_NAME"] + '</option>')
                                    .appendTo("#worker")
                            );
                        }
                    });

                    for (var i = 0; i < list_all.length; i++) {
                    var checkbox = list_all[i].querySelector('input[type="checkbox"]');
                    if (arrayjson_open[0].includes(checkbox.value))
                    {
                        checkbox.checked = true;
                    }
                }

                    main_reportName = '<?=$reportName;?>';
                    selectedValues = Object.values(arrayjson_open[0]);
                    dateend = '<?=$dateend;?>';
                    datebegin = '<?=$datebegin;?>';
                    workers = Object.values(arrayjson_open[3]);
                    workers.forEach(worker => {
                        document.getElementById(worker).selected = true;
                    });
                    change_example_table();
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

            background-color: transparent;
        }
        #report-name-block div{
            display: flex;
            flex-wrap: wrap;
            height: auto;
            margin-bottom: 10px;

            font-size: 1.2em;
        }
        #report-name-block div *{
            margin-right: 15px;
        }
        #report-name-block div textarea{
            width: 300px;
        }
        #report-name-block div text{
            position: relative;
            top: 0;
            width: 140px;

            margin-right: 40px;
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

            background-color: transparent;
        }
        #report-filter-block div{
            display: flex;
            flex-wrap: wrap;
            height: auto;
            margin-bottom: 10px;

            font-size: 1.2em;
        }
        #report-filter-block div *{
            margin-right: 15px;
        }
        #report-filter-block div input{
        }
        #report-filter-block div label{
        }
        #report-filter-block div select{
        }
        #report-filter-block div text{
            position: relative;
            top: 0;
            width: 140px;

            margin-right: 40px;
        }

        #sortable-entityes{
            display: flex;
        }
        #sortable1-group div{
            display: block;

            margin-right: 25px;
            border: black solid 1px;
            background-color: #e7e7e0;
            width: 250px;
            height: 300px;
            overflow-y: scroll;
            padding-left: 5px;
            font-size: 0.5em;
        }
        #sortable0 {
            display: block;

            width: 400px;
            overflow-y: scroll;

            margin-right: 5px;
            border: black solid 1px;
            background-color: #e7e7e0;
            height: 100%;
            padding-left: 5px;
        }
        #sortable1-group {
            width: 70%;
        }
        #sortable-entityes text{
            font-size: 1.5em;
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
        #report-param-panel-create{
            position: fixed;

            width: auto;
            height: 25px;
            border-radius: 10px;
            cursor: pointer;
            right: 30px;
            top: 2px;

            text-align: center;
            font-size: 1.2em;

            z-index: 999;

            color: #e7e7e0;
            text-decoration: underline;
            background-color: transparent;
        }

        .sortable ul li{
            border: 1px solid #000000;
            border-radius: 5px;

            color: black;
            background-color: white;
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

            color: black;
            background-color: white;
            font-weight: bold;

            margin-bottom: 5px;

        }
        #sortable0 ul li input[type="checkbox"]{
            padding-left: 5px;
            margin-right: 10px;
        }
        #sortable0 *, #sortable0 text {
            font-size: 0.7em;
        }

        input[type="checkbox"]{
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

        #report-table-example-block{
            width: 100%;
            height: 80px;

            overflow-x: auto;
            background-color: transparent;

            margin-top: 10px;

            margin-bottom: 50px;
        }
        #table-example {
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
        #table-example th, #table-example td {
            padding: 0;
            text-align: center;

            border: 1px solid #666;
        }
        #table-example th {
            text-transform: uppercase;
            background-color: transparent;
        }
        #table-example tr {
            height: 50px;
        }
    </style>
</head>
<body>
<header>
    <div id="report-param-panel-close" onclick="closeReport()">
        <text>На главную</text>
    </div>
    <div id="report-param-panel-create" onclick="showReport()">
        <text>Сформировать отчет</text>
    </div>
</header>
<main>
    <div class="CENTERpanel">
        <div id="report-param-panel">
            <div id="report-name-block">
                <div style="border-bottom: 3px solid black;">
                    <text style="font-size: 1.3em; color: black; width: 100%">Имя отчета</text>
                </div>
                <?php
                    echo $contain_name;
                ?>
            </div>
            <div id="report-filter-block">
                <div style="border-bottom: 3px solid black;">
                    <text style="font-size: 1.3em; color: black; width: 100%">Параметры отчета</text>
                </div>
                <div>
                    <text>Период отчета</text>
                    <input type="date" name="calendar" value="<?=$datebegin;?>" max="2099-12-30" min="2012-01-1" style="margin-right: 87px;" id="datebegin"">
                    <input type="date" name="calendar" value="<?=$dateend;?>" max="2099-12-30" min="2012-01-1" id="dateend"">
                </div>
                <div>
                    <text>Сотрудники</text>
                    <select id="worker" style="position: relative; width: 300px; height: auto; overflow-y: scroll" multiple></select>
                </div>
                <div>
                    <text>Поля отчета</text>
                    <div id="sortable-entityes">
                        <div id="sortable0" class="sortable">
                            <text style="width: 100%; margin-bottom: 0;">Перенесите в это поле нужные столбцы для отчета</text>
                            <?php
                                echo $contain_entityes;
                            ?>
                        </div>
                        <div id="sortable1-group">
                            <div id="sortable1" class="sortable">
                            <div id="sortable-text"><text>Сделки</text></div>
                            <ul id="entityes">
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="DEAL#TITLE#Название сделки">Название сделки</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="WORKER#WORKER#Сотрудник">Сотрудник</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="DEAL#STAGE_ID#Статус сделки">Статус сделки</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="DEAL#OPPORTUNITY#Сумма сделки">Сумма сделки</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="DEAL#DATE_CREATE#Дата создания сделки">Дата создания сделки</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="DEAL#CLOSEDATE#Дата закрытия сделки">Дата закрытия сделки</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="DEAL#ADDITIONAL_INFO#Дополнительная информация сделки">Дополнительная информация сделки</li>
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
                            </ul>
                            </div>
                            <div id="sortable1" class="sortable">
                            <div id="sortable-text"><text>Дела</text></div>
                            <ul id="entityes">
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="ACTIVITY#DEADLINE#Сроки имполнения дела">Сроки имполнения дела</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="ACTIVITY#DESCRIPTION#Описание дела">Описание дела</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="ACTIVITY#DIRECTION#Направление дела">Направление дела: входящее/исходящее</li>
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
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="OFFER#CLOSEDATE#Дата завершения предложения">Дата завершения предложения</li>
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
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="CONTACT#DATE_CREATE#Дата создания контакта">Дата создания контакта</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="CONTACT#DATE_MODIFY#Дата изменения контакта">Дата изменения контакта</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="CONTACT#EXPORT#Участвует ли контакт в экспорте">Участвует ли контакт в экспорте</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="CONTACT#HONORIFIC#Вид обращения контакта">Вид обращения контакта</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="CONTACT#OPENED#Доступен контакт для всех">Доступен контакт для всех</li>
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
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="LEAD#DATE_CLOSED#Дата закрытия лида">Дата закрытия лида</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="LEAD#DATE_CREATE#Дата создания лида">Дата создания лида</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="LEAD#DATE_MODIFY#Дата изменения лида">Дата изменения лида</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="LEAD#HONORIFIC#Вид обращения лида">Вид обращения лида</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="LEAD#MOVED_TIME#Дата перемещения лида на текущую стадию">Дата перемещения лида на текущую стадию</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="LEAD#OPENED#Доступен лида для всех">Доступен лида для всех</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="LEAD#SOURCE_DESCRIPTION#Описание источника лида">Описание источника лида</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="LEAD#STATUS_ID#Статус лида">Статус лида</li>
                            </ul>
                        </div>
                            <div id="sortable1" class="sortable">
                            <div id="sortable-text"><text>Счета</text></div>
                            <ul id="entityes">
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="INVOICE#title#Название счет">Название счета</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="INVOICE#opportunity#Счет">Счет</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="INVOICE#createdTime#Дата создания счета">Дата создания счета</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="INVOICE#updatedTime#Дата обновления счета">Дата обновления счета</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="INVOICE#movedTime#Дата продвижения счета">Дата продвижения счета</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="INVOICE#categoryId#Категория счета">Категория счета</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="INVOICE#opened#Открыт счет">Открыт счет</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="INVOICE#begindate#Дата выставления счета">Дата выставления счета</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="INVOICE#closedate#Дата закрытия счета">Дата закрытия счета</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="INVOICE#taxValue#Налог счета">Налог счета</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="INVOICE#sourceDescription#Описание счета">Описание счета</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="INVOICE#comments#Комментарии к счету">Комментарии к счету</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="INVOICE#lastActivityTime#Дата последней активности счета">Дата последней активности счета</li>
                            </ul>
                        </div>
                            <div id="sortable1" class="sortable">
                            <div id="sortable-text"><text>Товары</text></div>
                            <ul  id="entityes">
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
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="COMPANY#CURRENCY_ID#Валюта компании">Валюта компании</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="COMPANY#DATE_CREATE#Дата создания компании">Дата создания компании</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="COMPANY#DATE_MODIFY#Дата изменения компании">Дата изменения компании</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="COMPANY#EMPLOYEES#Количество сотрудников компании">Количество сотрудников компании</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="COMPANY#INDUSTRY#Сфера деятельности компании">Сфера деятельности компании</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="COMPANY#OPENED#Компания доступна для всех">Компания доступна для всех</li>
                                <li><input style="margin: 0; padding: 0; position: relative" type="checkbox" value="COMPANY#REVENUE#Годовой оборот компании">Годовой оборот компании</li>
                            </ul>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="report-table-example-block">
                <table id="table-example">
                    <tr></tr>
                </table>
            </div>
        </div>
    </div>
</main>
</body>
</html>