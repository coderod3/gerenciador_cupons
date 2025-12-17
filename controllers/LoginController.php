<?php
class LoginController {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function login() {
        $erro = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $raw_login = $_POST['login'];
            
            // limpa tudo que não for número para análise
            $clean_login = preg_replace('/\D/', '', $raw_login);
            $len = strlen($clean_login);
            $formatted_login = '';
            $tipo = '';

            // decide se é cpf ou cnpf pelo tamanho e formata para o padrão do banco
            if ($len === 11) {
                // se cpf aplica máscara 000.000.000-00
                $tipo = 'associado';
                $formatted_login = preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "$1.$2.$3-$4", $clean_login);
                $sql = "SELECT cpf, senha_hash FROM associado WHERE cpf = ?";
                
            } elseif ($len === 14) {
                // aplica máscara 00.000.000/0000-00
                $tipo = 'comerciante';
                $formatted_login = preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "$1.$2.$3/$4-$5", $clean_login);
                $sql = "SELECT cnpj, senha_hash FROM comercio WHERE cnpj = ?";
                
            } else {
                $erro = "Documento inválido. Verifique os números digitados.";
            }

            // se passou na validação de formato, busca no banco
            if (!$erro) {
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param("s", $formatted_login);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($row = $result->fetch_assoc()) {
                    if (password_verify($_POST['senha'], $row['senha_hash'])) {
                        $_SESSION['perfil'] = $tipo;
                        $_SESSION['id'] = ($tipo === 'associado') ? $row['cpf'] : $row['cnpj'];
                        
                        if ($tipo === 'associado') {
                            header("Location: ../public/associado/home.php");
                        } else {
                            header("Location: ../public/comercio/home.php");
                        }
                        exit;
                    } else {
                        $erro = "Senha incorreta.";
                    }
                } else {
                    $erro = "Usuário não encontrado.";
                }
            }
        }

        include '../views/auth/login.php';
    }
}