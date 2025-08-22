<?php
namespace App\Models; use App\DB; use PDO;

class Link{
  public static function forUser(int $uid): array { $s=DB::pdo()->prepare('SELECT * FROM links WHERE user_id=? ORDER BY sort_order ASC, id ASC'); $s->execute([$uid]); return $s->fetchAll(PDO::FETCH_ASSOC); }
  public static function visibleForUser(int $uid): array { $s=DB::pdo()->prepare('SELECT * FROM links WHERE user_id=? AND is_visible=1 ORDER BY sort_order ASC, id ASC'); $s->execute([$uid]); return $s->fetchAll(PDO::FETCH_ASSOC); }
  public static function find(int $id): ?array { $s=DB::pdo()->prepare('SELECT * FROM links WHERE id=?'); $s->execute([$id]); return $s->fetch(PDO::FETCH_ASSOC)?:null; }
  public static function create(int $uid,array $d): int { $s=DB::pdo()->prepare('INSERT INTO links (user_id,label,url,type,sort_order,is_visible) VALUES (?,?,?,?,?,?)'); $s->execute([$uid,$d['label'],$d['url'],$d['type']??'custom',$d['sort_order']??0,$d['is_visible']??1]); return (int)DB::pdo()->lastInsertId(); }
  public static function update(int $id,int $uid,array $d): void { $s=DB::pdo()->prepare('UPDATE links SET label=?, url=?, type=?, is_visible=? WHERE id=? AND user_id=?'); $s->execute([$d['label'],$d['url'],$d['type']??'custom',$d['is_visible']??1,$id,$uid]); }
  public static function delete(int $id,int $uid): void { $s=DB::pdo()->prepare('DELETE FROM links WHERE id=? AND user_id=?'); $s->execute([$id,$uid]); }
  public static function reorder(int $uid,array $ids): void { $pdo=DB::pdo(); $pdo->beginTransaction(); try{ $sort=0; $s=$pdo->prepare('UPDATE links SET sort_order=? WHERE id=? AND user_id=?'); foreach($ids as $i){ $sort+=10; $s->execute([$sort,(int)$i,$uid]); } $pdo->commit(); }catch(\Throwable $e){ $pdo->rollBack(); throw $e; } }
}
