<?php
class Util {
	// static = do not have to create instance of class first
	public static function hashCsrf() {
        $csrfToken = $_SESSION['csrfToken'];
        return hash_hmac('sha256', 'util hash hmac', $csrfToken);
    }

    public static function formatSecondsHhMmSs($t,$f=':') {
        return sprintf("%02d%s%02d%s%02d", floor($t/3600), $f, ($t/60)%60, $f, $t%60);
    }
}