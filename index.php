<?php
$dsn = 'mysql:host=localhost;dbname=salmonrun_data;charset=utf8;';
$user = 'root';
$pass = '';

$currentTime = date("Y/m/d H:i:s");

$json = file_get_contents('https://splamp.info/salmon/api/now');
$array = json_decode($json, TRUE);

//変数まとめ
$currentShiftNo = $array[0]['num'];
$stageVal = $array[0]['stage_ja'];
$stageId = $array[0]['stage'];
$buki1Val = $array[0]['w1_ja'];
$buki2Val = $array[0]['w2_ja'];
$buki3Val = $array[0]['w3_ja'];
$buki4Val = $array[0]['w4_ja'];
$buki1Id = $array[0]['w1'];
$buki2Id = $array[0]['w2'];
$buki3Id = $array[0]['w3'];
$buki4Id = $array[0]['w4'];

if($array[0]['start'] <= time()){
    $head = sprintf('【第%d回サーモンラン 開催中！】', $array[0]['num']);
}else{
    $head = '【次回のサーモンランをお待ちください】';
}

try{
    $pdo = new PDO($dsn,$user,$pass,
		[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]
    );
    //DB接続成功
    $stmt = $pdo->query('select * from MST_NUMBERING');
	$rows = $stmt->fetch();
    $dbShiftNo = $rows['CURRENT_SHIFT_NO'];
    $dbShiftTime = $rows['CURRENT_SHIFT_TIMES'];

    if ($currentShiftNo !== $dbShiftNo){
        $stmt = $pdo->prepare('UPDATE MST_NUMBERING SET CURRENT_SHIFT_NO = :shiftno, CURRENT_SHIFT_TIMES = :shifttime, UPDATE_DT = :currentdt');
        $stmt->execute(array(':shiftno' => $currentShiftNo, ':shifttime' => '1', ':currentdt' => $currentTime));
    }
}catch (Exception $e){
    echo $e->getMessage();
}
?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title></title>
	<script src="js/jquery-3.4.1.min.js"></script>
	<script src='bootstrap/js/bootstrap.bundle.js'></script>
	<link rel='stylesheet' href='bootstrap/css/bootstrap.css'>
	<link rel='stylesheet' href='css/style.css'>
</head>
<body>
    <div>
        <h5>サーモンランデータ収集ツール</h5>
    </div>
    <form method="post">
        <div>
            開催中：第 <?php echo $currentShiftNo; ?> 回
        </div>
        <input type="hidden" name="shiftNo" value="<?php echo $currentShiftNo; ?>">
        <div>
            場所：<?php echo $stageVal; ?>
        </div>
        <input type="hidden" name="stageNo" value="<?php echo $stage; ?>">
        <div>
            ブキ：・<?php echo $buki1Val; ?>・<?php echo $buki2Val; ?>・<?php echo $buki3Val; ?>・<?php echo $buki4Val; ?>
        </div>
        <div>
            現在：<?php echo $dbShiftTime; ?>回目のバイト
        </div>
        <input type="hidden" name="stageNo" value="<?php echo $dbShiftTime; ?>">
        <div>
            Wave：
            <input type="radio" name="wave" id="wave1"><label for="wave1" class="mr-3">Wave1</label>
            <input type="radio" name="wave" id="wave2"><label for="wave2" class="mr-3">Wave2</label>
            <input type="radio" name="wave" id="wave3"><label for="wave3">Wave3</label>
        </div>






    </form>
</body>