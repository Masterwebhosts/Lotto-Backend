php
require_once __DIR__ . '....configconfig.php';
require_once __DIR__ . '....appModelsUser.php';

 تحقق من تسجيل الدخول
if (empty($_SESSION['user'])  $_SESSION['user']['role'] !== 'user') {
    header(Location  . BASE_URL . authlogin.php);
    exit;
}

 التحقق من إرسال POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = floatval($_POST['amount']  0);

    if ($amount  0) {
        $userModel = new User();
        $success = $userModel-updateBalance($_SESSION['user']['id'], $amount);

        if ($success) {
            $_SESSION['success'] = ✅ تم شحن محفظتك بمبلغ  . number_format($amount, 2) .  بنجاح!;
        } else {
            $_SESSION['error'] = ❌ حدث خطأ أثناء شحن الرصيد. حاول مرة أخرى.;
        }
    } else {
        $_SESSION['error'] = ⚠️ يجب إدخال مبلغ صحيح أكبر من 0.;
    }

     إعادة التوجيه إلى صفحة المحفظة
    header(Location  . BASE_URL . userwallet.php);
    exit;
}

 إذا دخل المستخدم للملف مباشرة بدون POST
header(Location  . BASE_URL . userwallet.php);
exit;
