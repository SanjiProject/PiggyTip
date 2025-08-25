<?php
namespace App\Controllers; use App\Lib\Helpers; use App\Models\Analytics; use App\Models\Link;
use App\Models\Comment;

class ApiController{
  public static function track(): void { $in=json_decode(file_get_contents('php://input'),true)?:[]; $event=$in['event']??''; $uid=(int)($in['public_user_id']??0); $linkId=isset($in['link_id'])?(int)$in['link_id']:null; if(!in_array($event,['pageview','click'],true)||$uid<=0){ Helpers::json(['ok'=>false,'error'=>'bad_request'],400);} Analytics::recordEvent($uid,$linkId,$event,$_SERVER['HTTP_USER_AGENT']??null, Helpers::clientIpBinary()); Helpers::json(['ok'=>true]); }
  public static function captcha(): void {
    $uid=(int)($_GET['uid']??0); if($uid<=0){ Helpers::json(['ok'=>false,'error'=>'bad_request'],400); }
    $a=random_int(1,9); $b=random_int(1,9);
    $_SESSION['captcha_'.$uid] = $a + $b;
    Helpers::json(['ok'=>true,'q'=>"What is $a + $b?", 'a'=>null]);
  }
  public static function postComment(): void {
    try{
      $in=json_decode(file_get_contents('php://input'),true)?:[]; $uid=(int)($in['public_user_id']??0);
      $name=trim($in['name']??''); $body=trim($in['comment']??'');
      $captcha=trim((string)($in['captcha']??''));
      if($uid<=0 || $name==='') Helpers::json(['ok'=>false,'error'=>'bad_request'],400);
      $expected = $_SESSION['captcha_'.$uid] ?? null; if($expected===null || !ctype_digit($captcha) || (int)$captcha !== (int)$expected){ Helpers::json(['ok'=>false,'error'=>'captcha'],422); }
      // Reject links
      if (preg_match('~https?://|www\.|@[a-zA-Z0-9_.-]+\.[a-z]{2,}~i', $body)) { Helpers::json(['ok'=>false,'error'=>'links_not_allowed'],422); }
      // Limit length
      if (mb_strlen($body) > 500) { $body = mb_substr($body, 0, 500); }
      Comment::create($uid, $name, $body, $_SERVER['HTTP_USER_AGENT']??null, Helpers::clientIpBinary());
      unset($_SESSION['captcha_'.$uid]);
      Helpers::json(['ok'=>true]);
    }catch(\Throwable $e){ Helpers::json(['ok'=>false,'error'=>'db_error'],500); }
  }
  public static function reorderLinks(): void { $uid=(int)($_SESSION['user_id']??0); $ids=$_POST['order']??[]; if(!is_array($ids)) $ids=[]; \App\Models\Link::reorder($uid,array_map('intval',$ids)); Helpers::json(['ok'=>true]); }
  public static function exportCsv(): void { $uid=(int)($_SESSION['user_id']??0); $rows=\App\Models\Analytics::clicksPerLink($uid); header('Content-Type: text/csv'); header('Content-Disposition: attachment; filename="analytics_clicks.csv"'); $out=fopen('php://output','w'); fputcsv($out,['Link ID','Label','URL','Clicks']); foreach ($rows as $r) { fputcsv($out,[$r['id'],$r['label'],$r['url'],$r['clicks']]); } fclose($out); exit; }
  public static function redirectLink(int $id): void { $link=Link::find($id); if(!$link){ http_response_code(404); echo 'Not found'; return; } Analytics::recordEvent((int)$link['user_id'],(int)$link['id'],'click',$_SERVER['HTTP_USER_AGENT']??null, Helpers::clientIpBinary()); header('Location: '.$link['url'], true, 302); exit; }
  public static function comments(): void {
    $uid=(int)($_GET['uid']??0); if($uid<=0){ Helpers::json([]); }
    $rows=Comment::recentForUser($uid,20);
    $safe=array_map(function($r){ return ['id'=>(int)($r['id']??0),'name'=>htmlspecialchars($r['name']??'',ENT_QUOTES,'UTF-8'),'body'=>nl2br(htmlspecialchars($r['body']??'',ENT_QUOTES,'UTF-8')),'created_at'=>$r['created_at']]; }, $rows);
    Helpers::json($safe);
  }
  public static function deleteComment(): void {
    $uid=(int)($_SESSION['user_id']??0); $cid=(int)($_POST['id']??0);
    if($uid<=0||$cid<=0){ Helpers::json(['ok'=>false,'error'=>'unauthorized'],403); }
    $row=Comment::find($cid); if(!$row|| (int)$row['user_id']!==$uid){ Helpers::json(['ok'=>false,'error'=>'forbidden'],403); }
    Comment::delete($cid,$uid); Helpers::json(['ok'=>true]);
  }

  public static function sponsorSubmit(): void {
    Helpers::requireCsrf();
    $name=trim($_POST['name']??''); $url=trim($_POST['url']??''); $logo=trim($_POST['logo']??'');
    if(!\App\Lib\Validator::nonEmpty($name,2,60) || !\App\Lib\Validator::url($url) || !\App\Lib\Validator::nonEmpty($logo,1,255)){
      $_SESSION['flash_error']='Invalid sponsor info'; Helpers::redirect('/sponsor');
    }
    $path=APP_BASE_PATH.'/assets/sponsors.json'; $list=[]; if(is_file($path)){ $list=json_decode(file_get_contents($path),true)?:[]; }
    $list[]=['name'=>$name,'url'=>$url,'logo'=>$logo];
    file_put_contents($path, json_encode($list, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));
    $_SESSION['flash_success']='Thanks! Your sponsorship has been submitted.'; Helpers::redirect('/');
  }
}
