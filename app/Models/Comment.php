<?php
namespace App\Models; use App\DB; use PDO;

class Comment{
  private static function ensureTable(): void {
    static $done=false; if($done) return; $done=true;
    $sql = "CREATE TABLE IF NOT EXISTS comments (
      id INT AUTO_INCREMENT PRIMARY KEY,
      user_id INT NOT NULL,
      name VARCHAR(60) NOT NULL,
      body TEXT NOT NULL,
      ip VARBINARY(16) DEFAULT NULL,
      user_agent VARCHAR(191) DEFAULT NULL,
      created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
      INDEX (user_id, created_at),
      FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB";
    DB::pdo()->exec($sql);
  }
  public static function create(int $uid, string $name, string $body, ?string $ua, ?string $ip): void {
    self::ensureTable();
    $s=DB::pdo()->prepare('INSERT INTO comments (user_id,name,body,user_agent,ip) VALUES (?,?,?,?,?)');
    $s->execute([$uid, mb_substr($name,0,60), $body, $ua? mb_substr($ua,0,191): null, $ip]);
  }
  public static function recentForUser(int $uid, int $limit=20): array {
    self::ensureTable();
    $limit = max(1, min(100, (int)$limit));
    $sql='SELECT id, name, body, created_at FROM comments WHERE user_id=? ORDER BY id DESC LIMIT '.$limit;
    $s=DB::pdo()->prepare($sql);
    $s->bindValue(1, $uid, PDO::PARAM_INT);
    $s->execute();
    return $s->fetchAll(PDO::FETCH_ASSOC);
  }
  public static function find(int $id): ?array {
    self::ensureTable();
    $s=DB::pdo()->prepare('SELECT * FROM comments WHERE id=?');
    $s->execute([$id]);
    return $s->fetch(PDO::FETCH_ASSOC)?:null;
  }
  public static function delete(int $id, int $uid): void {
    self::ensureTable();
    $s=DB::pdo()->prepare('DELETE FROM comments WHERE id=? AND user_id=?');
    $s->execute([$id,$uid]);
  }
}


