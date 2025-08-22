<?php
namespace App\Models; use App\DB; use PDO;

class Wallet{
  public static function forUser(int $uid): array { $s=DB::pdo()->prepare('SELECT * FROM wallets WHERE user_id=? ORDER BY id ASC'); $s->execute([$uid]); return $s->fetchAll(PDO::FETCH_ASSOC); }
  public static function visibleForUser(int $uid): array { $s=DB::pdo()->prepare('SELECT * FROM wallets WHERE user_id=? AND is_visible=1 ORDER BY id ASC'); $s->execute([$uid]); return $s->fetchAll(PDO::FETCH_ASSOC); }
  public static function create(int $uid,array $d): int { $s=DB::pdo()->prepare('INSERT INTO wallets (user_id,label,chain,address,payment_uri,is_visible) VALUES (?,?,?,?,?,?)'); $s->execute([$uid,$d['label'],$d['chain']??'TRON',$d['address'],$d['payment_uri']??null,$d['is_visible']??1]); return (int)DB::pdo()->lastInsertId(); }
  public static function update(int $id,int $uid,array $d): void { $s=DB::pdo()->prepare('UPDATE wallets SET label=?, chain=?, address=?, payment_uri=?, is_visible=? WHERE id=? AND user_id=?'); $s->execute([$d['label'],$d['chain']??'TRON',$d['address'],$d['payment_uri']??null,$d['is_visible']??1,$id,$uid]); }
  public static function delete(int $id,int $uid): void { $s=DB::pdo()->prepare('DELETE FROM wallets WHERE id=? AND user_id=?'); $s->execute([$id,$uid]); }
}
