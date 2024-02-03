<?php
namespace Services;
use Repositories\UsuarioRepository;

class UsuarioService {
    private $usuarioRepository;

    public function __construct(UsuarioRepository $usuarioRepository) {
        $this->usuarioRepository = $usuarioRepository;
    }

    public function create($usuario) {
        return $this->usuarioRepository->create($usuario);
    }

    public function confirmarCuenta($token){
        return $this->usuarioRepository->confirmarCuenta($token);
    }

    public function verificarFechaExpiracion($token){
        return $this->usuarioRepository->verificarFechaExpiracion($token);
    }

    public function obtenerToken($id){
        return $this->usuarioRepository->obtenerToken($id);
    }

    public function updateToken($id, $token) {
        return $this->usuarioRepository->updateToken($id, $token);
    }

    public function verTodos() {
        return $this->usuarioRepository->verTodos();
    }

    public function login($usuario) {
        return $this->usuarioRepository->login($usuario);
    }

    public function buscaMail($email) {
        return $this->usuarioRepository->buscaMail($email);
    }

    public function getById($id) {
        return $this->usuarioRepository->getById($id);
    }

    public function update($usuario) {
        return $this->usuarioRepository->update($usuario);
    }

    public function delete($id) {
        return $this->usuarioRepository->delete($id);
    }

}
