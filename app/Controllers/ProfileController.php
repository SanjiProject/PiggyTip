<?php
namespace App\Controllers; use App\Lib\Helpers; use App\Models\User;

class ProfileController{
  private static function currentUser(): array { $u=User::findById((int)($_SESSION['user_id']??0)); if(!$u) Helpers::redirect('/login'); return $u; }
  public static function updateProfile(): void { $user=self::currentUser(); $display=trim($_POST['display_name']??''); $bio=trim($_POST['bio']??''); $username=trim($_POST['username']??''); $q1=\App\DB::pdo()->prepare('SELECT id FROM users WHERE username=? AND id<>?'); $q1->execute([$username,$user['id']]); if($q1->fetch()){ $_SESSION['flash_error']='Username already taken'; Helpers::redirect('/dashboard/profile'); }
    \App\Models\User::updateProfile((int)$user['id'],['display_name'=>$display,'bio'=>$bio,'username'=>$username,'slug'=>$user['slug'],'primary_link_id'=>$user['primary_link_id']??null]); $_SESSION['flash_success']='Profile updated'; Helpers::redirect('/dashboard/profile'); }
  public static function uploadAvatar(): void {
    $user=self::currentUser();
    if(!isset($_FILES['avatar'])||$_FILES['avatar']['error']!==UPLOAD_ERR_OK){ $_SESSION['flash_error']='Upload failed'; Helpers::redirect('/dashboard/profile'); }
    $f=$_FILES['avatar']; if($f['size']>6*1024*1024){ $_SESSION['flash_error']='File too large (max 6MB)'; Helpers::redirect('/dashboard/profile'); }
    $fi=new \finfo(FILEINFO_MIME_TYPE); $mime=$fi->file($f['tmp_name']);
    $ok=in_array($mime,['image/jpeg','image/png','image/gif','image/webp'],true);
    if(!$ok){ $_SESSION['flash_error']='Invalid image type'; Helpers::redirect('/dashboard/profile'); }

    $dir=APP_BASE_PATH.'/assets/uploads'; if(!is_dir($dir)) @mkdir($dir,0777,true);
    $name='avatar_'.$user['id'].'_'.bin2hex(random_bytes(4)).'.webp';
    $dest=$dir.'/'.$name; $public='/assets/uploads/'.$name;

    // If WebP support missing, fallback to plain move without conversion
    $canWebp = function_exists('imagewebp');
    if($mime==='image/webp' || !$canWebp){
      if(!move_uploaded_file($f['tmp_name'],$dest)) { $_SESSION['flash_error']='Could not move file'; Helpers::redirect('/dashboard/profile'); }
    } else {
      // Decode source image
      try{
        switch($mime){
          case 'image/jpeg': $src=@imagecreatefromjpeg($f['tmp_name']); break;
          case 'image/png':  $src=@imagecreatefrompng($f['tmp_name']);  break;
          case 'image/gif':  $src=@imagecreatefromgif($f['tmp_name']);  break;
          default: $src=null; break;
        }
        if(!$src){ $_SESSION['flash_error']='Could not read image'; Helpers::redirect('/dashboard/profile'); }
        $w=imagesx($src); $h=imagesy($src);
        // Optional downscale to max 512px dimension to keep size small
        $max=512; $nw=$w; $nh=$h; if(max($w,$h)>$max){ if($w>=$h){ $nw=$max; $nh=(int)round($h*($max/$w)); } else { $nh=$max; $nw=(int)round($w*($max/$h)); } }
        $dst=imagecreatetruecolor($nw,$nh);
        // Preserve transparency for PNG/GIF
        imagealphablending($dst,false); imagesavealpha($dst,true);
        imagecopyresampled($dst,$src,0,0,0,0,$nw,$nh,$w,$h);
        if(!imagewebp($dst,$dest,85)){ $_SESSION['flash_error']='Could not save image'; Helpers::redirect('/dashboard/profile'); }
        imagedestroy($src); imagedestroy($dst);
      }catch(\Throwable $e){ $_SESSION['flash_error']='Image processing failed'; Helpers::redirect('/dashboard/profile'); }
    }

    \App\Models\User::updateAvatar((int)$user['id'],$public);
    $_SESSION['flash_success']='Avatar updated';
    Helpers::redirect('/dashboard/profile');
  }
}
