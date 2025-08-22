<?php
namespace App\Models;

use App\DB; use PDO;

class User{
  public static function findById(int $id): ?array { $s=DB::pdo()->prepare('SELECT * FROM users WHERE id = ?'); $s->execute([$id]); return $s->fetch(PDO::FETCH_ASSOC)?:null; }
  public static function findByUsername(string $u): ?array { $s=DB::pdo()->prepare('SELECT * FROM users WHERE username = ?'); $s->execute([$u]); return $s->fetch(PDO::FETCH_ASSOC)?:null; }
  public static function findBySlug(string $u): ?array { $s=DB::pdo()->prepare('SELECT * FROM users WHERE slug = ?'); $s->execute([$u]); return $s->fetch(PDO::FETCH_ASSOC)?:null; }
  public static function findByEmail(string $e): ?array { $s=DB::pdo()->prepare('SELECT * FROM users WHERE email = ?'); $s->execute([$e]); return $s->fetch(PDO::FETCH_ASSOC)?:null; }
  public static function create(array $d): int { $s=DB::pdo()->prepare('INSERT INTO users (username,email,password_hash,display_name,bio,avatar,slug) VALUES (?,?,?,?,?,?,?)'); $s->execute([$d['username'],$d['email'],$d['password_hash'],$d['display_name'],$d['bio']??null,$d['avatar']??null,$d['slug']]); return (int)DB::pdo()->lastInsertId(); }
  public static function updateProfile(int $id,array $d): void { $s=DB::pdo()->prepare('UPDATE users SET display_name=?, bio=?, username=?, slug=?, primary_link_id=? WHERE id=?'); $s->execute([$d['display_name'],$d['bio']??null,$d['username'],$d['slug'],$d['primary_link_id']??null,$id]); }
  public static function updateAvatar(int $id,?string $p): void { $s=DB::pdo()->prepare('UPDATE users SET avatar=? WHERE id=?'); $s->execute([$p,$id]); }
}
