<?php

namespace models;

use Lib\BaseDatos;
use PDO;
use PDOException;

/**
 * Clase Usuario
 *
 * Esta clase representa un usuario en el sistema.
 */
class Usuario
{
    /**
     * @var string|null $id El ID del usuario.
     */
    public string|null $id;

    /**
     * @var string $nombre El nombre del usuario.
     */
    public string $nombre;

    /**
     * @var string $apellidos Los apellidos del usuario.
     */
    public string $apellidos;

    /**
     * @var string $email El email del usuario.
     */
    public string $email;

    /**
     * @var string $password La contraseña del usuario.
     */
    public string $password;

    /**
     * @var string $rol El rol del usuario.
     */
    public string $rol;

    /**
     * @var int $confirmado Si el usuario ha confirmado su email.
     */
    public int $confirmado;

    /**
     * @var string $token El token del usuario.
     */
    public string $token;

    /**
     * @var string $token_exp La fecha de expiración del token.
     */
    public string $token_exp;

    /**
     * @var BaseDatos $db La conexión a la base de datos.
     */
    private BaseDatos $db;

    /**
     * Constructor de Usuario.
     *
     * Inicializa una nueva instancia de la clase Usuario.
     */
    public function __construct(string|null $id, string $nombre, string $apellidos, string $email, string $password, string $rol, int $confirmado, string $token, string $token_exp)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->apellidos = $apellidos;
        $this->email = $email;
        $this->password = $password;
        $this->rol = $rol;
        $this->confirmado = $confirmado;
        $this->token = $token;
        $this->token_exp = $token_exp;
        $this->db = new BaseDatos();
    }

    // Aquí van los métodos getter y setter con su respectiva documentación...


    public function getId(): ? string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    public function getApellidos(): string
    {
        return $this->apellidos;
    }

    public function setApellidos(string $apellidos): void
    {
        $this->apellidos = $apellidos;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getRol(): string
    {
        return $this->rol;
    }

    public function setRol(string $rol): void
    {
        $this->rol = $rol;
    }

    public function getConfirmado(): int
    {
        return $this->confirmado;
    }

    public function setConfirmado(int $confirmado): void
    {
        $this->confirmado = $confirmado;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    public function getToken_exp(): string
    {
        return $this->token_exp;
    }

    public function setToken_exp(string $token_exp): void
    {
        $this->token_exp = $token_exp;
    }

    /**
     * Crea una nueva instancia de la clase Usuario a partir de un array de datos.
     *
     * @param array $data Los datos del usuario.
     * @return Usuario La nueva instancia de Usuario.
     */
    public static function fromArray(array $data):usuario{
        return new Usuario(
            $data['id'] ?? null,
            $data['nombre'] ?? '',
            $data['apellidos'] ?? '',
            $data['email'] ?? '',
            $data['password'] ?? '',
            $data['rol'] ?? '',
            $data['confirmado'] ?? 0,
            $data['token'] ?? '',
            $data['token_exp'] ?? '',

        );
    }

    /**
     * Desconecta la conexión a la base de datos.
     */
    public function desconecta(){
        $this->db->close();
    }

}