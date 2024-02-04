<?php

namespace Models;
use Lib\BaseDatos;
use Exception;
use PDO;
use PDOException;
class Equipo {

    /**
     * @var int $id
     * @var string $nombre
     * @var string $ciudad
     * @var string $division
     * @var string $color
     * @var string $redes
     * @var BaseDatos $db
     */
    private $id;
    private $nombre;
    private $ciudad;
    private $division;
    private $color;
    private $redes;
    private $db;
    public function __construct($id = "", $nombre = "", $ciudad = "", $division = "", $color = "", $redes = "") {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->ciudad = $ciudad;
        $this->division = $division;
        $this->color = $color;
        $this->redes = $redes;
        $this->db = new BaseDatos();
    }

    // Getters y setters

    public function getId() {
        return $this->id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getCiudad() {
        return $this->ciudad;
    }

    public function getDivision() {
        return $this->division;
    }

    public function getColor() {
        return $this->color;
    }

    public function getRedes() {
        return $this->redes;
    }

    public function setId($id): void {
        $this->id = $id;
    }

    public function setNombre($nombre): void {
        $this->nombre = $nombre;
    }

    public function setCiudad($ciudad): void {
        $this->ciudad = $ciudad;
    }

    public function setDivision($division): void {
        $this->division = $division;
    }

    public function setColor($color): void {
        $this->color = $color;
    }

    public function setRedes($redes): void {
        $this->redes = $redes;
    }

    /**
     * Función que se encarga de convertir el objeto a un array
     * 
     * @return array
     */
    public function toArray(): array {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'ciudad' => $this->ciudad,
            'division' => $this->division,
            'color' => $this->color,
            'redes' => $this->redes
        ];
    }

    /**
     * Función que se encarga de crear un equipo
     * 
     * @return bool
     */
    public function create(): bool {
        try {
            $sql = $this->db->prepara("INSERT INTO equipos (nombre, ciudad, division, color, redes) VALUES (:nombre, :ciudad, :division, :color, :redes)");
            $sql->bindValue(':nombre', $this->nombre);
            $sql->bindValue(':ciudad', $this->ciudad);
            $sql->bindValue(':division', $this->division);
            $sql->bindValue(':color', $this->color);
            $sql->bindValue(':redes', $this->redes);
            $sql->execute();
            $sql->closeCursor();
            $this->db->close();
            return true;

        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Función que se encarga de obtener todos los equipos
     * 
     * @return array
     */
    public function read() {
        $sql = $this->db->prepara("SELECT * FROM equipos");
        $sql->execute();
        $equipos = $sql->fetchAll(PDO::FETCH_ASSOC);
        $sql->closeCursor();
    
        $this->db->close();
        return $equipos;
    }

    /**
     * Función que se encarga de actualizar un equipo
     * 
     * @return bool
     */
    public function update(): bool {
        $sql = $this->db->prepara("UPDATE equipos SET nombre = :nombre, ciudad = :ciudad, division = :division, color = :color, redes = :redes WHERE id = :id");
        $sql->bindValue(':id', $this->id);
        $sql->bindValue(':nombre', $this->nombre);
        $sql->bindValue(':ciudad', $this->ciudad);
        $sql->bindValue(':division', $this->division);
        $sql->bindValue(':color', $this->color);
        $sql->bindValue(':redes', $this->redes);
        $sql->execute();
        $filasAfectadas = $sql->rowCount();
        $sql->closeCursor();
        
        $this->db->close();
        return $filasAfectadas > 0;

    }

    /**
     * Función que se encarga de eliminar un equipo
     * 
     * @return bool
     */
    public function delete(): bool {
        try {
            $sql = $this->db->prepara("DELETE FROM equipos WHERE id = :id");
            $sql->bindValue(':id', $this->id);
            $sql->execute();
    
            // Comprueba si se eliminó alguna fila
            $filasAfectadas = $sql->rowCount();
            $sql->closeCursor();
            $this->db->close();
    
            // Si se eliminó alguna fila, devuelve true. De lo contrario, devuelve false.
            return $filasAfectadas > 0;
        } catch (PDOException $e) {
            // En caso de error, cierra la conexión y devuelve false
            $this->db->close();
            return false;
        }
    }

    /**
     * Función que se encarga de obtener un equipo por su id
     * 
     * @return array|null
     */
    public function getById() {
        $sql = $this->db->prepara("SELECT * FROM equipos WHERE id = :id");
        $sql->bindValue(':id', $this->id);
        $sql->execute();
        $equipo = $sql->fetch(PDO::FETCH_ASSOC);
        $sql->closeCursor();

        $this->db->close();
        return $equipo ?: null;
    }

    /**
     * Función que se encarga de obtener la fecha de expiración del token
     * 
     * @param string $token
     * @return mixed
     */
    public function obtenerFechaExpiracion($email) {
        $sql = $this->db->prepara("SELECT token_exp FROM usuarios WHERE email = :email");
        $sql->bindValue(':email', $email);
        $sql->execute();
        $fecha = $sql->fetch(PDO::FETCH_ASSOC);
        $sql->closeCursor();

        $this->db->close();
        return $fecha ?: null;
        header("Location:".BASE_URL."peticiones");
    }
    
}