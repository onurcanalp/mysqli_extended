<?php
/**
 * Mysqli DB bağlantı ve hata sınıfımız
 */



/*
 * MySQL Server a bağlantıda hata yaşarsak bu Exception sınıfından türettiğimiz hatayı döndüreceğiz.
 */
class DBConnectException extends Exception
{

    public function __construct($error, $errno = 0)
    {
        parent::__construct($error, $errno);
    }
}

/**
 * DB Query Exception class
 *
 * SQL sorgumuzu çalıştırırken oluşan hatalar için bu sınıfı kullanacağız..
 */
class DBQueryException extends Exception
{

    public function __construct($error, $errno = 0)
    {
        parent::__construct($error, $errno);
    }
}

/**
 * mysqli sınıfından türettiğimiz DB sınıfımız
 *
 */
class Database extends mysqli
{

    /**
     * __construct overwrite edelim. Bağlantı hataları durumunda Hata durumunda DBConnectException ları tutacağız
     *
     * @param string $host MySQL Host
     * @param string $user MySQL Kullanıcı Adı
     * @param string $pass MySQL Parola (password kullanmamak için null kullanın)
     * @param string $db MySQL Bağlanacağımız Veritabanı adı (kullanmamak için null kullanın)
     * @param string $port MySQL Bağlanacağımız port (kullanmamak için null kullanın)
     * @param string $socket MySQL Kullanılacak soket (kullanmamak için null kullanın)
     * @throws DBConnectException
     */
    public function __construct($host = 'localhost', $user = null, $pass = null, $db = null, $port = null, $socket = null)
    {
        @parent::__construct($host, $user, $pass, $db, $port, $socket);
        if ($this->connect_errno != 0) {
            // Hata olursa DBConnectException döndüreceğiz error message ve error code ile
            throw new DBConnectException($this->connect_error, $this->connect_errno);
        }
    }

    /**
     * Query metodumuz
     *
     * @param string $sql Çalıştırılacak SQL sorgusu
     * @return mysqli_result Object
     * @throws DBQueryException
     */
    public function query($sql)
    {
        // sql.log dosyamızda log tutacağız
        file_put_contents('/tmp/sql.log', $sql . "\n", FILE_APPEND);
        // query çalıştırılırken, parent query metodumuzu çağırıyoruz yani mysqli sınıfında ki
        // @ operatorü ile çağırdık gib gelebilecek hataları temizleyelim
        $result = @parent::query($sql);
        // errno set edilmiş mi varmı bakalım
        if ($this->errno != 0) {
            // kendi DBQueryException ımızı hata mesajı ve hata numarası ile döndürelim
            throw new DBQueryException($this->error, $this->errno);
        }
        // Herşey düzgünse mysqli_result object ini döndürelim
        return $result;
    }
}