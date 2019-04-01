<?php


namespace entity;


use DateTime;
use PDO;
use PDOStatement;
use RuntimeException;

class User
{

    const TOKEN_SALT = 'somesaltanssomedigits123456789987654321';
    const PASSWD_SALT = 'somesaltpassword12345698765454545asdasdas';

    /** @var PDO */
    private $db;
    /** @var string */
    private $table = 'users';

    /** @var int */
    public $id;
    /** @var string */
    public $login;
    /** @var string */
    public $email;
    /** @var string */
    public $token;
    /** @var string */
    public $password;
    /** @var int */
    public $token_expire_at;


    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function create()
    {

        $query = 'INSERT INTO  `' . $this->table . '` ' .
            'SET `login`=:login, `email`=:email, `token`=:token, `token_expire_at`=:token_expire_at, `password`=:password';

        $stmt = $this->db->prepare($query);
        $this->login = htmlspecialchars(strip_tags($this->login));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = $this->generateHashPassword(htmlspecialchars(strip_tags($this->password)));

        if (true === $this->isUserExist()) {
            throw new RuntimeException('User already exist');
        }

        $stmt->bindParam(':login', $this->login);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $this->password);
        $token = $this->generateTokenAndExpireDate($this->login, $this->password);
        $stmt->bindParam(':token', $token['token']);
        $stmt->bindParam(':token_expire_at', $token['expireAt']);

        if (false !== $stmt->execute()) {
            return $token['token'];
        }
        return false;
    }


    /**
     * @return false|PDOStatement
     * @throws RuntimeException
     */
    public function findByToken()
    {
        $query = 'SELECT * FROM `' . $this->table . '` WHERE `token`=:token';
        $stmt = $this->db->prepare($query);
        $this->token = htmlspecialchars(strip_tags($this->token));
        $stmt->bindParam(':token', $this->token);

        $connection = $this->db->query($query);
        if (false === $connection) {
            var_dump($this->db->errorInfo());
            throw new RuntimeException($this->db->errorCode());
        }
        return $connection;
    }

    /**
     * @return string|integer
     */
    private function isUserExist()
    {
        $query = 'SELECT * FROM `' . $this->table . '` WHERE `login` =:login';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':login', $this->login);
        $stmt->execute();
        $rowCount = $stmt->rowCount();
        if (false === $rowCount) {
            var_dump($this->db->errorInfo());
            throw new RuntimeException($this->db->errorCode());
        }
        return 0 !== $rowCount;
    }

    public function checkIsAuth()
    {

    }

    public function generateTokenAndExpireDate($login, $pass)
    {
        $hashString = $login . $pass . time() . self::TOKEN_SALT;
        $result = [
            'token' => hash('sha256', $hashString),
            'expireAt' => (new DateTime('+ 1 hour'))->format('Y-m-d H:i:s'),
        ];
        return $result;
    }

    /**
     * @param $password
     * @return string
     */
    public function generateHashPassword($password)
    {
        return hash('sha256', $password . self::PASSWD_SALT);
    }


}
