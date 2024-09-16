<?php

namespace src\Application\Commands;

use yii\console\Controller;
use src\Domain\Entities\Usuario;
use src\Domain\Repositories\UsuarioRepository;
use Yii;

class UsuarioController extends Controller
{
    private $usuarioRepository;

    public function __construct($id, $module, UsuarioRepository $usuarioRepository, $config = [])
    {
        $this->usuarioRepository = $usuarioRepository;
        parent::__construct($id, $module, $config);
    }

    public function actionCreate($login, $senha, $nome)
    {
        $usuario = new Usuario($login, $senha, $nome);

        if ($this->usuarioRepository->save($usuario)) {
            echo "Usuário criado com sucesso\n";
        } else {
            echo "Erro ao criar o usuário\n";
        }
    }
}
