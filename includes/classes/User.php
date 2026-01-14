<?php
class User
{
    private $con;
    private string $username;

    public function __construct($con, $username)
    {
        $this->con = $con;
        $this->username = $username;
    }

    public function getUsername(): string
    {
        return $this->username;
    }
}
