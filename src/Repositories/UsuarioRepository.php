<?php
namespace Repositories;
use Lib\BaseDatos;
use Lib\Email;
use Lib\Security;
use PDO;
use PDOException;

class UsuarioRepository {
    private BaseDatos $db;
    private Security $security;
    private Email $email;
    public function __construct() {
        $this->db = new BaseDatos();
        $this->security = new Security();
            
    }

    public function create($usuario): bool{
        $id = $usuario->getId();
        $nombre = $usuario->getNombre();
        $apellidos = $usuario->getApellidos();
        $email = $usuario->getEmail();
        $password = $usuario->getPassword();
        if ($usuario->getRol() == 'admin'){
            $rol = 'admin';
        } else{
            $rol = 'user';
        }
        $confirmado = 0;
        $data = [];
        array_push($data, $id, $nombre, $email, $rol, $confirmado);
        $token = $this->security->crearToken($data);
        $token_exp = date('Y-m-d H:i:s', strtotime('+1 day'));

        try {
            $ins = $this->db->prepara("INSERT INTO usuarios (id, nombre, apellidos, email, password, rol, confirmado, token, token_exp) values (:id, :nombre, :apellidos, :email, :password, :rol, :confirmado, :token, :token_exp)");

            $ins->bindValue(':id', $id);
            $ins->bindValue(':nombre', $nombre);
            $ins->bindValue(':apellidos', $apellidos);
            $ins->bindValue(':email', $email);
            $ins->bindValue(':password', $password);
            $ins->bindValue(':rol', $rol);
            $ins->bindValue(':confirmado', $confirmado);
            $ins->bindValue(':token', $token);
            $ins->bindValue(':token_exp', $token_exp);

            $ins->execute();

            $result = true;
            $this->email = new Email($email, $token);
            $this->email->enviarConfirmacion();

        } catch (PDOException $error){
            $result = false;
        }

        $ins->closeCursor();
        $ins=null;

        return $result;
    }

    public function confirmarCuenta($token){
        if ($token) {
            $tokenDecoded = $this->security->decodeToken($token);
            $email = $tokenDecoded->data[2];
    
            $this->executeQuery("UPDATE usuarios SET confirmado = 1 WHERE email = :email", $email);
            $this->executeQuery("UPDATE usuarios SET token = null WHERE email = :email", $email);
    
            $this->db->close();
    
            return true;
        } else {
            echo "No se ha podido confirmar la cuenta";
            return false;
        }
    }
    
    private function executeQuery($sql, $email) {
        $stmt = $this->db->prepara($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    
        if (!$stmt->execute()) {
            throw new Exception('Error al ejecutar la consulta SQL: ' . $stmt->errorInfo()[2]);
        }
    
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function verTodos(){
        $sql = "SELECT * FROM usuarios";
        $this->db->consulta($sql);
        $this->db->close();
        return $this->db->extraer_todos();
    }

    public function login($usuario){
        $email = $usuario->getEmail();
        $password = $usuario->getPassword();

        try {
            $datosUsuario = $this->buscaMail($email);

            if ($datosUsuario !== false && $datosUsuario !== null){
                $verify = password_verify($password, $datosUsuario->password);
                
                if ($verify){
                    $result = $datosUsuario;
                } else {
                    $result = false;
                }
            } else {
                $result = false;
            }
        } catch (PDOException $error){
            $result = false;
        }

        return $result;
    }

    public function buscaMail($email){
        $select = $this->db->prepara("SELECT * FROM usuarios WHERE email=:email");
        $select->bindValue(':email', $email, PDO::PARAM_STR);

        try {
            $select->execute();
            if ($select && $select->rowCount() == 1){
                $usuario = $select->fetch(PDO::FETCH_OBJ);

                if ($usuario !== false) {
                    $result = $usuario;
                } else {
                    $result = null;
                }
            } else {
                $result = null;
            }
        } catch (PDOException $err){
            $result = false;
        }
        return $result;
    }

    public function getById($id){
        $sql = "SELECT * FROM usuarios WHERE id = :id";
        $stmt = $this->db->prepara($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->db->close();
        return $usuario;
    }

    public function update($usuario){
        $id = $usuario->getId();
        $nombre = $usuario->getNombre();
        $apellidos = $usuario->getApellidos();
        $email = $usuario->getEmail();
        $rol = $usuario->getRol();

        // var_dump($id, $nombre, $apellidos, $email, $rol);
        // die();
        try {
            $ins = $this->db->prepara("UPDATE usuarios SET nombre=:nombre, apellidos=:apellidos, email=:email, rol=:rol WHERE id=:id");

            $ins->bindValue(':id', $id);
            $ins->bindValue(':nombre', $nombre);
            $ins->bindValue(':apellidos', $apellidos);
            $ins->bindValue(':email', $email);
            $ins->bindValue(':rol', $rol);

            $ins->execute();

            $result = true;
        } catch (PDOException $error){
            $result = false;
        }

        $ins->closeCursor();
        $ins=null;

        return $result;
    }

    public function delete($id){
        $sql = "DELETE FROM usuarios WHERE id = :id";
        $stmt = $this->db->prepara($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->db->close();
        return $usuario;
    }

}