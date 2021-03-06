<?php
    session_start();
    // var_dump($_GET['prefecture_id']);//前の画面から送られてきたIDが何か判別できる
    require('dbconnect.php');

    $_SESSION['login_member_id']=1;
    $_SESSION['time']=time();

        //   //ログインチェック
    if(isset($_SESSION['login_member_id']) && ($_SESSION['time'] + 3600 >time())){
        // ログインしている
      // 最終アクション時間を更新
    $_SESSION['time'] = time();

    }

    // 都道府県検索
    if(isset($_GET['prefecture_id'])){
    // articles&prefecturesより全てのデータを取ってくる
    // $sql = 'SELECT * FROM `articles` INNER JOIN `prefectures` ON `articles`.`prefecture_id`=`prefectures`.`prefecture_id` WHERE `prefectures`.`prefecture_id`='.$_GET['prefecture_id'];

    $sql = 'SELECT * FROM `products` INNER JOIN (`articles` INNER JOIN `prefectures` ON `articles`.`prefecture_id`=`prefectures`.`prefecture_id`) ON `products`.`product_id`=`articles`.`product_id` WHERE `prefectures`.`prefecture_id`='.$_GET['prefecture_id'];

    $stmt=$dbh->prepare($sql);
    $stmt->execute();
    $article=array();
    while($record=$stmt->fetch(PDO::FETCH_ASSOC)){

        // favorite状態の取得（ログインユーザーごと）
    $sql='SELECT COUNT(*) as `favorite_count` FROM `favorites` WHERE `article_id`='.$record['article_id'].' AND `member_id`='.$_SESSION['login_member_id'];

    // sql文実行
    $stmt_flag=$dbh->prepare($sql);
    $stmt_flag->execute();
    $favorite_cnt=$stmt_flag->fetch(PDO::FETCH_ASSOC);


    // 全件配列に入れる
    $article[]=array(
    "article_id"=>$record['article_id'],
    "member_id"=>$record['member_id'],
    "title"=>$record['title'],
    "prefecture_id"=>$record['prefecture_id'],
    "prefecture"=>$record['prefecture'],
    "place"=>$record['place'],
    "access"=>$record['access'],
    "start"=>$record['start'],
    "finish"=>$record['finish'],
    "product_id"=>$record['product_id'],
    "product"=>$record['product'],
    "work"=>$record['work'],
    "treatment1"=>$record['treatment1'],
    "treatment2"=>$record['treatment2'],
    "treatment3"=>$record['treatment3'],
    "treatment4"=>$record['treatment4'],
    "treatment5"=>$record['treatment5'],
    "treatment6"=>$record['treatment6'],
    "landscapes"=>$record['landscapes'],
    "comment"=>$record['comment'],
    "favorite_flag"=>$favorite_cnt
    );



    if($favorite_cnt['favorite_count']==0){
      $favorite_flag=0; //favoriteされていない
    }else{
      $favorite_flag=1; //favoriteされている
    }

  }
}
    // 期間検索
    // if(isset($_SESSION['start'] && $_SESSION['finish'])){

    // }


    $_GET['product_id']=2;
    // 作物検索
    if(isset($_GET['product_id'])){
    // articles&productsより全てのデータを取ってくる
    $sql='SELECT * FROM `products` INNER JOIN (`articles` INNER JOIN `prefectures` ON `articles`.`prefecture_id`=`prefectures`.`prefecture_id`) ON `products`.`product_id`=`articles`.`product_id` WHERE `products`.`product_id`='.$_GET['product_id'];
    $stmt=$dbh->prepare($sql);
    $stmt->execute();
    $article=array();
    while($record=$stmt->fetch(PDO::FETCH_ASSOC)){

        // favorite状態の取得（ログインユーザーごと）
    $sql='SELECT COUNT(*) as `favorite_count` FROM `favorites` WHERE `article_id`='.$record['article_id'].' AND `member_id`='.$_SESSION['login_member_id'];

    // sql文実行
    $stmt_flag=$dbh->prepare($sql);
    $stmt_flag->execute();
    $favorite_cnt=$stmt_flag->fetch(PDO::FETCH_ASSOC);


    // 全件配列に入れる
    $article[]=array(
    "article_id"=>$record['article_id'],
    "member_id"=>$record['member_id'],
    "title"=>$record['title'],
    "prefecture_id"=>$record['prefecture_id'],
    "prefecture"=>$record['prefecture'],
    "place"=>$record['place'],
    "access"=>$record['access'],
    "start"=>$record['start'],
    "finish"=>$record['finish'],
    "product_id"=>$record['product_id'],
    "product"=>$record['product'],
    "work"=>$record['work'],
    "treatment1"=>$record['treatment1'],
    "treatment2"=>$record['treatment2'],
    "treatment3"=>$record['treatment3'],
    "treatment4"=>$record['treatment4'],
    "treatment5"=>$record['treatment5'],
    "treatment6"=>$record['treatment6'],
    "landscapes"=>$record['landscapes'],
    "comment"=>$record['comment'],
    "favorite_flag"=>$favorite_cnt
    );
        if($favorite_cnt['favorite_count']==0){
          $favorite_flag=0; //favoriteされていない
        }else{
          $favorite_flag=1; //favoriteされている
        }
    }}

?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>AGLANDSCAPES</title>

      <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700|Raleway:400,700" rel="stylesheet" />
      <link href="img/favicon.png" type="image/x-icon" rel="shortcut icon" />
      <link href="css/screen.css" rel="stylesheet" />
      <link rel="stylesheet" type="text/css" href="css/assets/css/font-awesome.min.css">
      <link rel="stylesheet" type="text/css" href="css/assets/css/bootstrap.css">

    <!-- Bootstrap -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="assets/css/form.css" rel="stylesheet">
    <link href="assets/css/timeline.css" rel="stylesheet">
    <link href="assets/css/risa_main.css" rel="stylesheet">
    <link href="assets/css/risa_ag_original.css" rel="stylesheet">
    <link href="assets/css/anly_main.css" rel="stylesheet">
    <link href="assets/css/anly_ag_original.css" rel="stylesheet">
    <!--
      designフォルダ内では2つパスの位置を戻ってからcssにアクセスしていることに注意！
     -->
  </head>
<body>


<!-- ヘッダー -->
      <?php include('header.php') ?>


  <div class="container">

   <div class="content-box">

         

         <!-- Destinations Section -->
         <section class="section section-destination">
            <!-- Title -->
            <div class="section-title">
              <div class="container">
              <?php if (isset($_GET['prefecture_id'])) { ?>
                  <h2 class="title" style="padding-top: 80px">地域別>><?php echo $article[0]['prefecture']; ?></h2>
              <?php } ?>
              <?php if (isset($_GET['product_id'])) { ?>
                  <h2 class="title" style="padding-top: 80px">作物別>><?php echo $article[0]['product']; ?></h2>
              <?php } ?>
                      </div>



      <div class="container">
        <div class="row">
          <hr>
            <div class="row row-margin-bottom">
      <!-- card section-->
    <?php foreach ($article as $article_each) { ?>

    <?php $article_id=$article_each['article_id']; ?>
    <?php $title=$article_each['title']; ?>
    <?php $prefecture=$article_each['prefecture']; ?>
    <?php $place=$article_each['place']; ?>
    <?php $access=$article_each['access']; ?>
    <?php $start=$article_each['start']; ?>
    <?php $finish=$article_each['finish']; ?>
    <?php $product=$article_each['product']; ?>
    <?php $work=$article_each['work']; ?>
    <?php $treatment1=$article_each['treatment1']; ?>
    <?php $treatment2=$article_each['treatment2']; ?>
    <?php $treatment3=$article_each['treatment3']; ?>
    <?php $treatment4=$article_each['treatment4']; ?>
    <?php $treatment5=$article_each['treatment5']; ?>
    <?php $treatment6=$article_each['treatment6']; ?>
    <?php $landscape=$article_each['landscapes']; ?>
    <?php $comment=$article_each['comment']; ?>
    <?php $favorite_flag=$article_each['favorite_flag']['favorite_count']; ?>
    <?php if(isset($_SESSION['apply_flag'])){
        $apply_flag=$_SESSION['apply_flag'];} ?>
  <?php include('card.php'); ?>

  <?php } ?>





               <div class="align-center">
                  <a href="#" class="btn btn-default btn-load-boats">
                    <span class="text">1
                    </span>
                    <i class="icon-spinner6">
                    </i>
                  </a>
                  <a href="#" class="btn btn-default btn-load-boats">
                    <span class="text">2
                    </span>
                    <i class="icon-spinner6">
                    </i>
                  </a>
                  <a href="#" class="btn btn-default btn-load-boats">
                    <span class="text">3
                    </span>
                    <i class="icon-spinner6">
                    </i>
                  </a>
               </div>
            </div>
         </section>
      </div>
    </div>


       <!-- Scripts -->
      <script src="js/jquery.js"></script>
      <script src="js/functions.js"></script>
   </body>
</html>

<!-- フッター -->
<?php include('footer.php'); ?>



    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="../assets/js/jquery-3.1.1.js"></script>
    <script src="../assets/js/jquery-migrate-1.4.1.js"></script>
    <script src="../assets/js/bootstrap.js"></script>
  </body>
</html>
