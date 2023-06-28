<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="//api.bitrix24.com/api/v1/"></script>
    <script src="//code.jquery.com/jquery-3.6.0.js" type="text/javascript"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script type="text/javascript">
        <?php
            require_once (__DIR__.'/crestcurrent.php');
            require_once(__DIR__ . '/crest.php');
            require_once(__DIR__ . '/settings.php');

            $dir = __DIR__ .  '/tmp/' . SERVER_PATH . '/';
            $SERVER_PATH = SERVER_PATH;
            if (isset($_GET["path"])){
                $SERVER_PATH = $_GET['path'];
                $dir = __DIR__ .  '/tmp/' . $SERVER_PATH . '/';
            }
            if(!file_exists($dir)){mkdir($dir, 0777, true);}

            $content = file_get_contents($dir . 'reports' . '.json');
            $arrayjson = json_decode($content, true);

            $arrayjson = array_unique($arrayjson, SORT_REGULAR);
            if ($arrayjson != null){file_put_contents($dir . 'reports' . '.json', json_encode($arrayjson, JSON_FORCE_OBJECT));}
            else {file_put_contents($dir . 'reports' . '.json', "{}", JSON_FORCE_OBJECT);}
        ?>
        function createReport(){
            window.location.href = 'extra-report-param-window.php?path=' + '<?=$SERVER_PATH;?>';
        }
        function openReport(el){
            window.location.href = 'extra-report-open-window.php?path=' + '<?=$SERVER_PATH;?>' + '&click_name=' + el.id;
        }
        function ajaxToDelete(){
            <?php
                if (isset($_POST['click_name'])){
                    $dir = __DIR__ .  '/tmp/' . $_POST['path'] . '/';
                    foreach($arrayjson as $key => $content){
                        if (isset($content) && $content["reportName"] === $_POST["click_name"]){
                            unset($arrayjson[$key]);
                            break;
                        }
                    }

                    if ($arrayjson === null){
                        file_put_contents($dir . 'reports' . '.json', json_encode(new stdClass(), JSON_FORCE_OBJECT));
                    }
                    else{
                        file_put_contents($dir . 'reports' . '.json', json_encode($arrayjson, JSON_FORCE_OBJECT));
                    }
                }
            ?>
        }
        function deleteReport(el){
            $.ajax({
               type: 'POST',
               url: 'index.php',
               data: {
                   'click_name': el.id,
                   'path' : '<?=$SERVER_PATH;?>',
               },
                success: function (){
                   ajaxToDelete();
                   el.parentNode.remove();
               }
            });
        }
    </script>
    <style >
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

        #report-panel{
            position: relative;
            display: flex;
            flex-direction: column;
            width: 100%;
            height: 100%;

            padding: 10px;
        }
        .report-item {
            display: flex;
            cursor: pointer;
            width: 100%;
            margin-left: auto;
            margin-right: 0;
            height: 100%;
            margin-bottom: 15px;
            padding-left: 10px;
            padding-top: 5px;
            border-top: 1px solid black;
            font-weight: bold;
            color: black;
        }
        .report-item * {
            margin-right: 5px;
        }
        #report-item-block:hover, #report-plus:hover{
            background-color: #a8a8a8;
        }
        #report-plus {
            cursor: pointer;
            width: 100%;
            margin-left: auto;
            margin-right: 10px;
            height: 30px;
            margin-bottom: 5px;
            text-align: center;
            padding-top: 5px;
            border: 1px solid black;
            border-radius: 15px;

            color: #5D6162;
            transition: .1s;
        }

        #report-param-panel{
            display: none;
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

        .report-item-delete {
            margin-left: auto;
            margin-right: 10px;

            width: 25px;
            height: 20px;

            border-radius: 15px;
            background-color: transparent;

            font-size: 1em;

            transition: .1s;

            z-index: 999;
        }
        #report-item-block{
            display: flex;
            cursor: pointer;
            width: 100%;
            height: 60px;
            padding-top: 25px;
            margin-bottom: 10px;
            border: 1px solid black;
            border-radius: 15px;
            font-weight: bold;
            color: black;
            transition: .1s;
        }
    </style>
</head>
<body>
    <header></header>
    <main>
        <div class="CENTERpanel">
            <div id="report-panel">
                <?php
                if (!isset($_POST["domain"]))
                {
                    if (file_exists($dir . 'reports' . '.json')) {
                        $content = file_get_contents($dir . 'reports' . '.json');
                        $arrayjson = json_decode($content, true);

                        $contain = '';

                        foreach ($arrayjson as $content){
                            $reportName = $content['reportName'];
                            $date = $content['date'];

                            $contain = $contain . "<div id='report-item-block'>
                                                        <div class='report-item' id='$reportName' onclick='openReport(this)'>
                                                            <h5 style='color: #575757'>Отчет от:</h5>" . $date . "<h5 style='color: #575757'>Название отчета:</h5>" . $reportName . "
                                                        </div>
                                                        <div class='report-item-delete' id='$reportName' onclick='deleteReport(this)'>
                                                            <img src='source/bin.png' style='margin-left: 5px; margin-top: 2px' width='15px' height='15px'>
                                                        </div>
                                                   </div>";
                        }
                        echo $contain;
                    }
                }
                ?>
                <div id="report-plus" onclick="createReport()">Создать отчет</div>
            </div>
        </div>
    </main>
</body>
</html>