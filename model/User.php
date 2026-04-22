<?php

class User
{
    private string $username;
    private string $passwordHash;
    private string $email;
    private bool $emailConfirmed;
    private string $role;


    public function __construct($username, $passwordHash, $email, $role, $emailConfirmed = false)
    {
        $this->setUsername($username);
        $this->setPasswordHash($passwordHash);
        $this->setEmail($email);
        $this->setRole($role);
        $this->setEmailConfirmed($emailConfirmed);
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    private function setUsername(string $username): void
    {
        $this->username = $username;
    }

    private function setPasswordHash(string $passwordHash): void
    {
        $this->passwordHash = $passwordHash;
    }

    private function setEmail(string $email): void
    {
        $this->email = $email;
    }

    private function setEmailConfirmed(bool $emailConfirmed): void
    {
        $this->emailConfirmed = $emailConfirmed;
    }

    public function isEmailConfirmed(): bool
    {
        return $this->emailConfirmed;
    }

    private function setRole(string $role)
    {
        $this->role = $role;
    }
}
