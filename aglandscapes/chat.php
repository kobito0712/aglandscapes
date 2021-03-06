<?php
      session_start();

      require('dbconnect.php');


    if (isset($_SESSION['login_member_id']) && ($_SESSION['time'] + 3600 > time())) {
      // 存在してたらログインしてる
      // 最終アクション時間を更新
      $_SESSION['time'] = time();


      $sql = 'SELECT * FROM `members` WHERE `member_id` ='.$_SESSION['login_member_id'];
      // ログインする際にはPOST送信で送信されているのでarray($POST())になるが
      // すでにログインしている人をSESSIONで情報を保存している
      // どこの画面からでも使えるSESSIONで使える
      // ログインしている情報をいろんなページで閲覧できるようにSESSIONで保存した方が使いやすい
      $stmt = $dbh->prepare($sql);
      $stmt->execute();
      $record = $stmt->fetch(PDO::FETCH_ASSOC);
      $name = $record['name'];

    }else{

      // ログインしていない
      header('Location: top.php');
      exit();
    }


    $_SESSION['article_id'] = $_GET['article_id'];

    $sql = 'SELECT * FROM `articles` WHERE `article_id`='.$_SESSION['article_id'];
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $card = array();

    while ($record = $stmt->fetch(PDO::FETCH_ASSOC)) {



    $sql = 'SELECT COUNT(*) as `favorite_count` FROM `favorites` WHERE `article_id`='.$record['article_id'].' AND `member_id`='.$_SESSION['login_member_id'];

    // sql文実行
    $stmt_flag = $dbh->prepare($sql);
    $stmt_flag->execute();
    $favorite_cnt = $stmt_flag->fetch(PDO::FETCH_ASSOC);


        $card[] = array("article_id"=>$record['article_id'],
                         "member_id"=>$record['member_id'],
                             "title"=>$record['title'],
                     "prefecture_id"=>$record['prefecture_id'],
                             "place"=>$record['place'],
                            "access"=>$record['access'],
                             "start"=>$record['start'],
                            "finish"=>$record['finish'],
                        "product_id"=>$record['product_id'],
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

    }



    $sql = 'SELECT * FROM `prefectures` INNER JOIN `articles` ON `prefectures`.`prefecture_id`=`articles`.`prefecture_id` WHERE `article_id` ='.$_SESSION['article_id'];

    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $record = $stmt->fetch(PDO::FETCH_ASSOC);

    $prefecture = $record['prefecture'];


    $sql = 'SELECT * FROM `products` INNER JOIN `articles` ON `products`.`product_id`=`articles`.`product_id` WHERE `article_id` ='.$_SESSION['article_id'];

    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $record = $stmt->fetch(PDO::FETCH_ASSOC);

    $product = $record['product'];





    if($favorite_cnt['favorite_count']==0){
      $favorite_flag=0; //favoriteされていない
    }else{
      $favorite_flag=1; //favoriteされている
    }








      foreach ($card as $record) {


    $title = $record['title'];
    $prefecture_id = $record['prefecture_id'];
    $place = $record['place'];
    $access = $record['access'];
    $start = $record['start'];
    $finish = $record['finish'];
    $product_id = $record['product_id'];
    $work = $record['work'];
    $treatment1 = $record['treatment1'];
    $treatment2 = $record['treatment2'];
    $treatment3 = $record['treatment3'];
    $treatment4 = $record['treatment4'];
    $treatment5 = $record['treatment5'];
    $treatment6 = $record['treatment6'];
    $comment = $record['comment'];
    $landscape = $record['landscapes'];
    $article_id = $record['article_id'];


  }









?>

<!DOCTYPE html>
<html lang="ja" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AGLANDSCAPES</title>
    <!-- Bootstrap -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="assets/css/form.css" rel="stylesheet">
    <link href="assets/css/timeline.css" rel="stylesheet">
    <link href="assets/css/risa_main.css" rel="stylesheet">
    <link href="assets/css/risa_ag_original.css" rel="stylesheet">
  </head>

  <body>
    <?php include('header.php') ?>



        <!-- 空白 -->
        <Table border="0" width="100%" height="60" cellspacing="0" bgcolor="#ffffff">
          <Tr>
          <Td align="center" valign="top"></Td>
          </Tr>
        </Table>
        <!-- メイン画面 -->
          <section class="module">
            <div class="container"><br><br>
              <div class="row">

                <!-- 左側の部分 -->
                <div class="col-xs-12 col-sm-4 col-md-5">
                  <div>
                     <?php require('card.php'); ?>
                  </div>
                </div>

                <!-- 右側の部分 -->
                <div class="col-xs-12 col-sm-8 col-md-7">
                  <div>
                    <div class="panel-footer">
                      <div class="input-group">
                        <textarea id="btn-input" type="text" class="form-control input-sm"
                                   placeholder="質問したい内容をこちらに入力してください。"></textarea>
                        <br>
                        <br>
                      </div>
                        <span class="input-group-btn">
                          <button class="btn btn-warning btn-sm pull-right" id="btn-chat">送信</button>
                        </span>
                      <div class="panel-heading">
                          <span class="glyphicon glyphicon-comment"></span> 質問
                      </div>
                    </div>
                    <div class="panel-body">
                      <ul class="chat">
                          <li class="right clearfix">
                            <span class="chat-img pull-left"></span>
                            <div class="chat-body clearfix farmer">
                              <div class="header">
                                <strong class="primary-font">〇〇さん</strong>
                                <small class="pull-right text-muted">
                                <span class="glyphicon glyphicon-time"></span>12 mins ago</small>
                              </div>
                              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare dolor, quis ullamcorper ligula sodales.</p>
                            </div>
                          </li>
                          <li class="left clearfix">
                            <span class="chat-img pull-right"></span>
                            <div class="chat-body clearfix user">
                              <div class="header">
                                <small class=" text-muted"><span class="glyphicon glyphicon-time"></span>13 mins ago</small>
                                <strong class="pull-right primary-font">自分</strong>
                              </div>
                              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare dolor, quis ullamcorper ligula sodales.</p>
                            </div>
                          </li>
                          <li class="right clearfix">
                            <span class="chat-img pull-left"></span>
                            <div class="chat-body clearfix farmer">
                              <div class="header">
                                <strong class="primary-font">〇〇さん</strong> <small class="pull-right text-muted">
                                <span class="glyphicon glyphicon-time"></span>14 mins ago</small>
                              </div>
                              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare dolor, quis ullamcorper ligula sodales.</p>
                            </div>
                          </li>
                          <li class="left clearfix">
                            <span class="chat-img pull-right"></span>
                            <div class="chat-body clearfix user">
                              <div class="header">
                                <small class=" text-muted"><span class="glyphicon glyphicon-time"></span>15 mins ago</small>
                                <strong class="pull-right primary-font">自分</strong>
                              </div>
                              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare dolor, quis ullamcorper ligula sodales.</p>
                            </div>
                          </li>
                          <li class="right clearfix">
                            <span class="chat-img pull-left"></span>
                            <div class="chat-body clearfix farmer">
                              <div class="header">
                                <strong class="primary-font">〇〇さん</strong>
                                <small class="pull-right text-muted"><span class="glyphicon glyphicon-time"></span>12 mins ago</small>
                              </div>
                              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare dolor, quis ullamcorper ligula sodales.</p>
                            </div>
                          </li>
                          <li class="left clearfix">
                            <span class="chat-img pull-right"></span>
                            <div class="chat-body clearfix user">
                              <div class="header">
                                <small class=" text-muted"><span class="glyphicon glyphicon-time"></span>13 </small>
                                <strong class="pull-right primary-font">自分</strong>
                              </div>
                              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornaredolor, quis ullamcorper ligula sodales.</p>
                            </div>
                          </li>
                          <li class="right clearfix">
                            <span class="chat-img pull-left"></span>
                            <div class="chat-body clearfix farmer">
                              <div class="header">
                                <strong class="primary-font">〇〇さん</strong> <small class="pull-right text-muted">
                                <span class="glyphicon glyphicon-time"></span>14 mins ago</small>
                              </div>
                              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare dolor, quis ullamcorper ligula sodales.</p>
                            </div>
                          </li>
                          <li class="left clearfix">
                            <span class="chat-img pull-right"></span>
                            <div class="chat-body clearfix user">
                              <div class="header">
                                <small class=" text-muted"><span class="glyphicon glyphicon-time"></span>15 mins ago</small>
                                <strong class="pull-right primary-font">自分</strong>
                              </div>
                              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare dolor, quis ullamcorper ligula sodales.</p>
                            </div>
                          </li>
                      </ul>
                    </div>
                  </div>
                </div>
                        <!-- 空白 -->
                <Table border="0" width="100%" height="60" cellspacing="0" bgcolor="#ffffff">
                  <Tr>
                  <Td align="center" valign="top"></Td>
                  </Tr>
                </Table>
                  <div class="container">
                    <div class="row">
                      <div class="text-center">
                        <a href="#" class="btn btn-default">トップページへ</a><br><br><br><br>
                      </div>
                    </div>
                  </div>
                        <!-- 空白 -->
                    <Table border="0" width="100%" height="50" cellspacing="0" bgcolor="#ffffff">
                      <Tr>
                      <Td align="center" valign="top"></Td>
                      </Tr>
                    </Table>
          </div>
        </div>
      </section>

      <?php include('footer.php') ?>

            <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
            <script src="assets/js/jquery-3.1.1.js"></script>
            <script src="assets/js/jquery-migrate-1.4.1.js"></script>
            <script src="assets/js/bootstrap.js"></script>
  </body>
</html>
