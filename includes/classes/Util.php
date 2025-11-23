<?php
class Util {
	// static = do not have to create instance of class first
	public static function hashCsrf() {
		$csrfToken = $_SESSION['csrfToken'];
		return hash_hmac('sha256', 'util hash hmac', $csrfToken);
	}
}