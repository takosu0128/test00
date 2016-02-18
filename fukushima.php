<?php

/*

取得する作品数の変更方法
21行目,47行目,94行目,113行目の終了条件を変更する





*/
header("Content-Type: text/html; charset=UTF-8");
//$hpに検索したいURLをいれる



$array=array();

//ページ数の指定
for($page=1;$page<10;$page++){
$hp="http://rental.geo-online.co.jp/search2/q/c-dvd/dg-1/o-rating/p-".$page."/st-2/stk-1/";

//htmlを$htmlに保存
$html = file_get_contents($hp);

/*
$dom = new DOMDocument();
@$dom->loadHTML($html);
$val = $dom->getElementById('')->nodeValue;
*/


/*タグのパターンを指定
//$pattern = '{\<.*? href=[\"|\'].*?ranking_movie.*?[\"|\']>(.*?)<\/.*?>}s';
*/

$pattern = '{<div class="product_title">(.*?)</div>}';

//$pattern = '{<meta property= content="キングスマン"}s';


preg_match_all($pattern,$html,$matches);

    
//1ページあたり50件
//最大49
for($i=0;$i<=1;$i++){

//文字化け直すよ
$a=mb_convert_encoding($matches[0][$i], "Shift-JIS", "EUC-JP");

//タグをとるよ
$title[$i]=strip_tags($a);
//array_push($title,"\n");
//$i++;
    
}


//print_r($title);


$file = fopen("movie.csv", "w");

/* CSVファイルを配列へ */
if( $file ){
    
   fputcsv($file, $title);
}

/* ファイルポインタをクローズ */
fclose($file);

/*
$array = array("<店舗名>","<問い合わせ電話番号>","<予約電話>","<住所>","<URL>","<最寄駅>","<Webサイト>","<営業時間>","\n");
$file = fopen("movie.csv", "a");
  fputcsv($file,$array);
    fwrite($file,$a.",");
    fwrite($file,"\n");


/* CSVファイルを配列へ */
/* ファイルポインタをクローズ */


//こっからはリンクの取得を頑張るよ
$pattern2 = '{<a href="(.*?)" class="productTitleCut"}';

preg_match_all($pattern2,$html,$matches);
        //$matchesの中に検索結果が入ってる

        //
        for( $a=0; $a<=1; $a++ ){

            $b=str_replace('<a href="',"",$matches[0][$a]);
            $b=str_replace(' class="productTitleCut"',"",$b);
            $b=str_replace('"','',$b);
            
            $URL[$a]="http://rental.geo-online.co.jp/".$b;
            //タグを除去してから検索結果を表示
            
        }


//print_r($URL);





//50作品分のループ
for($c=0;$c<=1;$c++){

//echo($title[$c]);
array_push($array,$title[$c]);
    
$ii=0;
$k=1;
//kがページ番号
while($k < 11){
  
    $hp="$URL[$c]";
  if($k<>1){
    $hp=$hp."?p=".strval($k);
  }
  
  //htmlを$htmlに保存
  $html = file_get_contents($hp);

  $min_pos = 99999999999999;//十分に大きな数字
  $from_encoding ='UTF-8';//デフォルト
  foreach(array('UTF-8','SJIS','EUC-JP','ASCII','JIS','ISO-2022-JP') as $charcode){
    if($min_pos > stripos($html,$charcode,0) && stripos($html,$charcode,0)>0){
      $min_pos =  stripos($html,$charcode,0);
      $from_encoding = $charcode;
    }
  }
  $html = mb_convert_encoding($html, "utf8", $from_encoding);
  //$html="p claalt\"3点\"ss=c_rating-_raiting-point_07star><span class=c_raiting-point_08></span></p";		
  //タグのパターンを指定
  $pattern = '/\"[0-9]点\"/';
  //検索！！
  preg_match_all($pattern,$html,$matches);
		//$matchesの中に検索結果が入ってる
  $matches=null;
  $match_num = preg_match_all($pattern, $html, $matches);
  $count=$match_num; // int 2
  //echo ($count);
  for( $i=0; $i<$count; $i++ ){
    $ii++;
    $point[$ii] = str_replace("\"alt\"", "", $matches[0][$i]);
    $point[$ii] = str_replace("点\"", "", $point[$ii]);
    $point[$ii] = str_replace("\"", "", $point[$ii]);
      
    //echo($point[$ii]);
      array_push($array,$point[$ii]);
      
  }
  $k++;

}
/* ファイルポインタをオープン */
    //echo("'");
    array_push($array,"'");
    
}

}


//print_r($array);
/*
fwrite($file, $b);
fclose($file);
//検索！！
/*
preg_match_all($pattern,$html,$matches);
		//$matchesの中に検索結果が入ってる
		for( $a=0; $a<=50; $a++ ){
            
            //タグを除去してから検索結果を表示
            echo strip_tags($matches[0][$a]);
            echo $matches[0][$a];
         $file = fopen("movie.csv", "w");
if( $file ){
  fputcsv($file, $b);

        }
            
            //うんち
        }
*/



$file = fopen("movie.csv", "w");

/* CSVファイルを配列へ */
if( $file ){
    
   fputcsv($file, $array);
}

/* ファイルポインタをクローズ */
fclose($file);

?>