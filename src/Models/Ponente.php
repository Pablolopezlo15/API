<?php

namespace Models;
use Lib\BaseDatos;
use Exception;
use PDO;
use PDOException;
class Ponente {

    private $id;
    private $nombre;
    private $apellidos;
    private $imagen;
    private $tags;
    private $redes;
    private $db;
    public function __construct($id = "", $nombre = "", $apellidos = "", $imagen = "", $tags = "", $redes = "") {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->apellidos = $apellidos;
        $this->imagen = $imagen;
        $this->tags = $tags;
        $this->redes = $redes;
        $this->db = new BaseDatos();
    }

    public function getId() {
        return $this->id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getApellidos() {
        return $this->apellidos;
    }

    public function getImagen() {
        return $this->imagen;
    }

    public function getTags() {
        return $this->tags;
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

    public function setApellidos($apellidos): void {
        $this->apellidos = $apellidos;
    }

    public function setImagen($imagen): void {
        $this->imagen = $imagen;
    }

    public function setTags($tags): void {
        $this->tags = $tags;
    }

    public function setRedes($redes): void {
        $this->redes = $redes;
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'apellidos' => $this->apellidos,
            'imagen' => $this->imagen,
            'tags' => $this->tags,
            'redes' => $this->redes
        ];
    }


    public function create(): bool {
        try {
            $sql = $this->db->prepara("INSERT INTO ponentes (nombre, apellidos, imagen, tags, redes) VALUES (:nombre, :apellidos, :imagen, :tags, :redes)");
            $sql->bindValue(':nombre', $this->nombre);
            $sql->bindValue(':apellidos', $this->apellidos);
            $sql->bindValue(':imagen', $this->imagen);
            $sql->bindValue(':tags', $this->tags);
            $sql->bindValue(':redes', $this->redes);
            $sql->execute();
            $sql->closeCursor();
            return true;
        } catch (Exception $e) {
            // Aquí puedes manejar el error como prefieras
            error_log($e->getMessage());
            return false;
        } finally {
            $this->db->close();
        }
    }


    // public function read() {
    //     $sql = $this->db->prepara("SELECT * FROM ponentes");
    //     $sql->execute();
    //     $ponentes = $sql->fetchAll();
    //     $sql->closeCursor();
    
    //     $this->db->close();
    //     return $ponentes ?: null;
    // }

    public function read() {
        $sql = $this->db->prepara("SELECT * FROM ponentes");
        $sql->execute();
        $ponentes = $sql->fetchAll(PDO::FETCH_ASSOC);
        $sql->closeCursor();
    
        $this->db->close();
        return $ponentes;
    }

    public function update(): bool {
        $sql = $this->db->prepara("UPDATE ponentes SET nombre = :nombre, apellidos = :apellidos, imagen = :imagen, tags = :tags, redes = :redes WHERE id = :id");
        $sql->bindValue(':id', $this->id);
        $sql->bindValue(':nombre', $this->nombre);
        $sql->bindValue(':apellidos', $this->apellidos);
        $sql->bindValue(':imagen', $this->imagen);
        $sql->bindValue(':tags', $this->tags);
        $sql->bindValue(':redes', $this->redes);
        $sql->execute();
        $sql->closeCursor();

        $this->db->close();
        return $this->db->filasAfectadas();
    }

    public function delete(): bool {
        try {
            $sql = $this->db->prepara("DELETE FROM ponentes WHERE id = :id");
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

    // public function delete(): bool {
    //     $sql = $this->db->prepara("DELETE FROM ponentes WHERE id = :id");
    //     $sql->bindValue(':id', $this->id);
    //     $sql->execute();
    //     $sql->closeCursor();

    //     $this->db->close();
    //     return $this->db->filasAfectadas();
    // }

    public function getById() {
        $sql = $this->db->prepara("SELECT * FROM ponentes WHERE id = :id");
        $sql->bindValue(':id', $this->id);
        $sql->execute();
        $ponente = $sql->fetch(PDO::FETCH_ASSOC);
        $sql->closeCursor();

        $this->db->close();
        return $ponente ?: null;
    }
    
}