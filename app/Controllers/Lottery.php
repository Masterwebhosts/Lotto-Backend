<?php
namespace App\Controllers;

use App\Core\Database;
use App\Models\User;
use App\Models\Transaction;

// استدعاء الدالة المساعدة للبريد
require_once __DIR__ . '/../Helpers/mail.php';

class Lottery {
    public static function processWinners(int $lotteryId): bool {
        $db = Database::getInstance();

        // جلب السحب
        $stmt = $db->prepare("SELECT * FROM lotteries WHERE id = ?");
        $stmt->execute([$lotteryId]);
        $lottery = $stmt->fetch();

        if (!$lottery || empty($lottery['winning_numbers'])) {
            return false;
        }

        $winningNumbers = array_map('trim', explode(",", $lottery['winning_numbers']));

        // جلب رهانات هذا السحب
        $stmt = $db->prepare("SELECT b.*, u.email, u.name 
                              FROM bets b 
                              JOIN users u ON b.user_id = u.id 
                              WHERE b.lottery_id = ?");
        $stmt->execute([$lotteryId]);
        $bets = $stmt->fetchAll();

        foreach ($bets as $bet) {
            $userNumbers = array_map('trim', explode(",", $bet['numbers']));
            $matches = count(array_intersect($winningNumbers, $userNumbers));

            // قاعدة الجائزة: إذا جاب 3 أرقام أو أكثر = جائزة (ضعف المبلغ)
            if ($matches >= 3) {
                $prize = $bet['amount'] * 2;

                // تحديث الرصيد + تسجيل معاملة
                User::updateBalance($bet['user_id'], $prize);
                Transaction::create($bet['user_id'], $prize, "win");

                // 📧 إرسال بريد إشعار
                $subject = "🎉 مبروك فوزك في السحب - {$lottery['name']}";
                $body = "
                    <h2>مرحباً {$bet['name']}</h2>
                    <p>لقد ربحت في السحب <b>{$lottery['name']}</b>.</p>
                    <p>أرقامك: {$bet['numbers']}</p>
                    <p>الأرقام الفائزة: {$lottery['winning_numbers']}</p>
                    <p>المبلغ المضاف إلى رصيدك: <b>{$prize}$</b></p>
                    <p>شكراً لاستخدامك نظام Lotto Backend.</p>
                ";
                send_mail($bet['email'], $subject, $body);
            }
        }

        return true;
    }
}

