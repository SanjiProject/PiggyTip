<?php
namespace App\Models; use App\DB; use PDO;

class Analytics{
  public static function recordEvent(int $uid, ?int $linkId, string $type, ?string $ua, ?string $ip): void {
    $pdo=DB::pdo();
    $recent=$pdo->prepare('SELECT id FROM analytics_events WHERE user_id=? AND IFNULL(link_id,0)=IFNULL(?,0) AND event_type=? AND user_agent=? AND ip=? AND created_at > (NOW() - INTERVAL 10 SECOND) LIMIT 1');
    $recent->execute([$uid,$linkId,$type, substr((string)$ua,0,191), $ip]);
    if($recent->fetch()) return;
    $s=$pdo->prepare('INSERT INTO analytics_events (user_id,link_id,event_type,user_agent,ip) VALUES (?,?,?,?,?)');
    $s->execute([$uid,$linkId,$type, substr((string)$ua,0,191), $ip]);
  }
  public static function summaryCounters(int $uid,int $days=7): array { $s=DB::pdo()->prepare("SELECT DATE(created_at) d, SUM(event_type='pageview') views, SUM(event_type='click') clicks FROM analytics_events WHERE user_id=? AND created_at >= (CURDATE() - INTERVAL ? DAY) GROUP BY DATE(created_at) ORDER BY d ASC"); $s->execute([$uid,$days]); return $s->fetchAll(PDO::FETCH_ASSOC); }
  public static function clicksPerLink(int $uid): array { $sql="SELECT l.id,l.label,l.url, SUM(a.event_type='click') clicks FROM links l LEFT JOIN analytics_events a ON a.link_id=l.id AND a.user_id=l.user_id WHERE l.user_id=? GROUP BY l.id ORDER BY clicks DESC, l.sort_order ASC"; $s=DB::pdo()->prepare($sql); $s->execute([$uid]); return $s->fetchAll(PDO::FETCH_ASSOC); }
}
