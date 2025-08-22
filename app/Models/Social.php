<?php
namespace App\Models; use App\DB; use PDO;
class Social{ public static function forUser(int $uid): array { $s=DB::pdo()->prepare('SELECT * FROM socials WHERE user_id=? AND is_visible=1 ORDER BY sort_order ASC, id ASC'); $s->execute([$uid]); return $s->fetchAll(PDO::FETCH_ASSOC); } }
