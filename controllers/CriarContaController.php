<?php
class CriarContaController {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function criarConta() {
        $msg = null;
        $categorias = [];

        // busca categorias no banco
        $res = $this->conn->query("SELECT id, nome FROM categoria ORDER BY nome");
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $categorias[] = $row;
            }
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $perfil = $_POST['perfil'];
            $senha = $_POST['senha'];
            $confirmar = $_POST['confirmar_senha'];

            if ($senha !== $confirmar) {
                $msg = "As senhas não conferem.";
            } else {
                $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

                if ($perfil === 'associado') {
                    $cpf = $_POST['cpf'];
                    $nome = $_POST['nome'];
                    $data_nascimento = $_POST['data_nascimento'];
                    $celular = $_POST['celular'];
                    $email = $_POST['email'];
                    $endereco = $_POST['endereco'];
                    $bairro = $_POST['bairro'];
                    $cidade = $_POST['cidade'];
                    $uf = $_POST['uf'];
                    $cep = $_POST['cep'];

                    // ---- VALIDAÇÃO CPF ----
                    if (!$this->validarCPF($cpf)) {
                        $msg = "CPF inválido.";
                    } else {
                        $stmt = $this->conn->prepare(
                            "INSERT INTO associado (cpf, nome, data_nascimento, celular, email, senha_hash, endereco, bairro, cidade, uf, cep)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
                        );
                        $stmt->bind_param("sssssssssss", $cpf, $nome, $data_nascimento, $celular, $email, $senha_hash, $endereco, $bairro, $cidade, $uf, $cep);

                        if ($stmt->execute()) {
                            $msg = "Conta de associado criada com sucesso!";
                        } else {
                            $msg = "Erro ao criar conta de associado: " . $this->conn->error;
                        }
                    }

                } elseif ($perfil === 'comerciante') {
                    $cnpj = $_POST['cnpj'];
                    $razao_social = $_POST['razao_social'];
                    $nome_fantasia = $_POST['nome_fantasia'];
                    $email = $_POST['email'];
                    $contato = $_POST['contato'];
                    $endereco = $_POST['endereco'];
                    $bairro = $_POST['bairro'];
                    $cidade = $_POST['cidade'];
                    $uf = $_POST['uf'];
                    $cep = $_POST['cep'];
                    $categoria_id = intval($_POST['categoria_id']);

                    // ---- VALIDAÇÃO CNPJ ----
                    if (!$this->validarCNPJ($cnpj)) {
                        $msg = "CNPJ inválido.";
                    } else {
                        $stmt = $this->conn->prepare(
                            "INSERT INTO comercio (cnpj, razao_social, nome_fantasia, email, senha_hash, contato, endereco, bairro, cidade, uf, cep, categoria_id)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
                        );
                        $stmt->bind_param("sssssssssssi", $cnpj, $razao_social, $nome_fantasia, $email, $senha_hash, $contato, $endereco, $bairro, $cidade, $uf, $cep, $categoria_id);

                        if ($stmt->execute()) {
                            $msg = "Conta de comerciante criada com sucesso!";
                        } else {
                            $msg = "Erro ao criar conta de comerciante: " . $this->conn->error;
                        }
                    }

                } else {
                    $msg = "Perfil inválido.";
                }
            }
        }

        include __DIR__ . '/../views/auth/criar_conta.php';
    }



    // metodos de validação

    private function validarCPF(string $cpf): bool {
        $cpf = preg_replace('/\D/', '', $cpf);
        if (strlen($cpf) != 11) return false;
        if (preg_match('/^(\d)\1{10}$/', $cpf)) return false;

        $soma = 0;
        for ($i = 0; $i < 9; $i++) {
            $soma += $cpf[$i] * (10 - $i);
        }
        $resto = $soma % 11;
        $dv1 = ($resto < 2) ? 0 : (11 - $resto);

        if ($cpf[9] != $dv1) return false;

        $soma = 0;
        for ($i = 0; $i < 10; $i++) {
            $soma += $cpf[$i] * (11 - $i);
        }
        $resto = $soma % 11;
        $dv2 = ($resto < 2) ? 0 : (11 - $resto);

        return $cpf[10] == $dv2;
    }



    private function validarCNPJ(string $cnpj): bool {
        $cnpj = preg_replace('/[^0-9A-Za-z]/', '', $cnpj);
        if (strlen($cnpj) != 14) return false;

        $cnpj = strtoupper($cnpj);

        if (preg_match('/^([0-9A-Z])\1{13}$/', $cnpj)) return false;

        $valores = [];
        for ($i = 0; $i < 14; $i++) {
            $valores[$i] = ord($cnpj[$i]) - 48;
        }

        $pesos_dv1 = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        $soma = 0;
        for ($i = 0; $i < 12; $i++) {
            $soma += $valores[$i] * $pesos_dv1[$i];
        }
        $resto = $soma % 11;
        $dv1 = ($resto < 2) ? 0 : (11 - $resto);

        if ($valores[12] != $dv1) return false;

        $pesos_dv2 = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        $soma = 0;
        for ($i = 0; $i < 13; $i++) {
            $soma += $valores[$i] * $pesos_dv2[$i];
        }
        $resto = $soma % 11;
        $dv2 = ($resto < 2) ? 0 : (11 - $resto);

        return $valores[13] == $dv2;
    }
}
