<?php
class ComercianteController {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // home
    public function home() {
        if (!isset($_SESSION['perfil']) || $_SESSION['perfil'] !== 'comerciante') {
            header("Location: ../index.php");
            exit;
        }

        $cnpj = $_SESSION['id'];
        $filtro = isset($_GET['filtro']) ? $_GET['filtro'] : '';
        $busca = isset($_GET['q']) ? "%".$_GET['q']."%" : null;

        // Buscar nome do associado (se não estiver na sessão)
        if (!isset($_SESSION['nome_fantasia'])) {
            $stmtNome = $this->conn->prepare("SELECT nome_fantasia FROM comercio WHERE cnpj = ?");
            $stmtNome->bind_param("s", $cnpj);
            $stmtNome->execute();
            $resNome = $stmtNome->get_result()->fetch_assoc();
            $_SESSION['nome_fantasia'] = $resNome ? $resNome['nome_fantasia'] : "Comerciante";
        }

        $sql = "SELECT 
                    c.num_cupom,
                    c.titulo,
                    c.data_inicio,
                    c.data_termino,
                    c.percentual_desc,
                    COALESCE(c.total, c.quantidade) AS total,
                    GREATEST(COALESCE(c.total, c.quantidade) - c.quantidade, 0) AS utilizados,
                    LEAST(c.quantidade, COALESCE(c.total, c.quantidade)) AS disponiveis
                FROM cupom c
                WHERE c.comercio_cnpj = ?";
        $params = [$cnpj];
        $types = "s";

        if ($busca) {
            $sql .= " AND (c.titulo LIKE ? OR c.num_cupom LIKE ?)";
            $params[] = $busca;
            $params[] = $busca;
            $types .= "ss";
        }

        // Filtros com base em quantidade (estoque atual)
        if ($filtro === 'ativos') {
            $sql .= " AND CURDATE() BETWEEN c.data_inicio AND c.data_termino AND c.quantidade > 0";
        } elseif ($filtro === 'esgotados') {
            $sql .= " AND CURDATE() BETWEEN c.data_inicio AND c.data_termino AND c.quantidade = 0";
        } elseif ($filtro === 'vencidos') {
            $sql .= " AND c.data_termino < CURDATE()";
        } elseif ($filtro === 'agendados') {
            $sql .= " AND c.data_inicio > CURDATE()";
        }

        $sql .= " ORDER BY c.data_inicio DESC, c.titulo";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();

        $cupons = [];
        while ($row = $result->fetch_assoc()) {
            $cupons[] = $row;
        }

        include __DIR__ . '/../views/comerciante/home.php';
    }



    public function cadastrarCupom() {
        if (!isset($_SESSION['perfil']) || $_SESSION['perfil'] !== 'comerciante') {
            header("Location: ../index.php");
            exit;
        }

        $msg = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titulo = $_POST['titulo'];
            $data_inicio = $_POST['data_inicio'];
            $data_termino = $_POST['data_termino'];

            try {
                $dt_inicio = new DateTime($data_inicio);
                $dt_termino = new DateTime($data_termino);
                $data_inicio = $dt_inicio->format('Y-m-d');
                $data_termino = $dt_termino->format('Y-m-d');
            } catch (Exception $e) {
                $msg = "Datas inválidas.";
            }

            $percentual = floatval($_POST['percentual_desc']);
            $quantidade = intval($_POST['quantidade']);
            $cnpj = $_SESSION['id'];

            if ($data_inicio <= $data_termino && $data_inicio >= date('Y-m-d')) {
                $num_cupom = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 12));

                $stmt = $this->conn->prepare(
                    "INSERT INTO cupom (num_cupom, comercio_cnpj, titulo, data_emissao, data_inicio, data_termino, percentual_desc, quantidade, total)
                    VALUES (?, ?, ?, CURDATE(), ?, ?, ?, ?, ?)"
                );
                // s s s s s d i i
                $stmt->bind_param("sssssdii", $num_cupom, $cnpj, $titulo, $data_inicio, $data_termino, $percentual, $quantidade, $quantidade);

                if ($stmt->execute()) {
                    $msg = "Cupom cadastrado com sucesso!";
                } else {
                    $msg = "Erro ao cadastrar cupom: " . $this->conn->error;
                }
            } else {
                $msg = "Período inválido. Data início deve ser hoje ou futura e anterior à data término.";
            }
        }

        include __DIR__ . '/../views/comerciante/cadastrar_cupom.php';
    }



    // lista de cupons do comerciante
    public function meusCupons() {
        if (!isset($_SESSION['perfil']) || $_SESSION['perfil'] !== 'comerciante') {
            header("Location: ../index.php");
            exit;
        }

        $cnpj = $_SESSION['id'];
        $filtro = isset($_GET['filtro']) ? $_GET['filtro'] : '';
        $busca = isset($_GET['q']) ? "%".$_GET['q']."%" : null;

        $sql = "SELECT 
                    c.num_cupom,
                    c.titulo,
                    c.data_inicio,
                    c.data_termino,
                    c.percentual_desc,
                    COALESCE(c.total, c.quantidade) AS total,
                    GREATEST(COALESCE(c.total, c.quantidade) - c.quantidade, 0) AS utilizados,
                    LEAST(c.quantidade, COALESCE(c.total, c.quantidade)) AS disponiveis
                FROM cupom c
                WHERE c.comercio_cnpj = ?";
        $params = [$cnpj];
        $types = "s";

        if ($busca) {
            $sql .= " AND (c.titulo LIKE ? OR c.num_cupom LIKE ?)";
            $params[] = $busca;
            $params[] = $busca;
            $types .= "ss";
        }

        if ($filtro === 'ativos') {
            $sql .= " AND CURDATE() BETWEEN c.data_inicio AND c.data_termino AND c.quantidade > 0";
        } elseif ($filtro === 'esgotados') {
            $sql .= " AND CURDATE() BETWEEN c.data_inicio AND c.data_termino AND c.quantidade = 0";
        } elseif ($filtro === 'vencidos') {
            $sql .= " AND c.data_termino < CURDATE()";
        } elseif ($filtro === 'agendados') {
            $sql .= " AND c.data_inicio > CURDATE()";
        }

        $sql .= " ORDER BY c.data_inicio DESC, c.titulo";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();

        $cupons = [];
        while ($row = $result->fetch_assoc()) {
            $cupons[] = $row;
        }

        include __DIR__ . '/../views/comerciante/meus_cupons.php';
    }


    public function validarCupom() {
        if (!isset($_SESSION['perfil']) || $_SESSION['perfil'] !== 'comerciante') {
            header("Location: ../index.php");
            exit;
        }

        $msg = null;
        $cupom = null;

        if (isset($_GET['codigo'])) {
            $codigo = $_GET['codigo'];

            $stmt = $this->conn->prepare(
                "SELECT ac.*, a.nome, c.titulo, c.data_inicio, c.data_termino, com.nome_fantasia
                FROM associado_cupom ac
                JOIN associado a ON ac.associado_cpf = a.cpf
                JOIN cupom c ON ac.num_cupom = c.num_cupom
                JOIN comercio com ON c.comercio_cnpj = com.cnpj
                WHERE ac.codigo_reserva = ? AND com.cnpj = ?"
            );
            $stmt->bind_param("ss", $codigo, $_SESSION['id']);
            $stmt->execute();
            $res = $stmt->get_result();
            $cupom = $res->fetch_assoc();

            if (!$cupom) {
                $msg = "Cupom não encontrado ou não pertence a este comércio.";
            } else {
                // avalia o status
                if (!empty($cupom['data_uso'])) {
                    $msg = "Este cupom já foi utilizado em " . $cupom['data_uso'] . ".";
                } elseif ($cupom['data_termino'] < date('Y-m-d')) {
                    $msg = "Este cupom venceu em " . $cupom['data_termino'] . ".";
                }
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['codigo'])) {
            $codigo = $_POST['codigo'];

            // só tenta validar se ainda não tiver mensagem de erro
            if (!$msg) {
                $upd = $this->conn->prepare("
                    UPDATE associado_cupom ac
                    JOIN cupom c ON ac.num_cupom = c.num_cupom
                    SET ac.data_uso = CURDATE()
                    WHERE ac.codigo_reserva = ?
                    AND ac.data_uso IS NULL
                    AND CURDATE() BETWEEN c.data_inicio AND c.data_termino
                ");
                $upd->bind_param("s", $codigo);
                if ($upd->execute() && $upd->affected_rows > 0) {
                    $msg = "Cupom validado com sucesso!";
                } else {
                    $msg = "Não foi possível validar o cupom. Verifique se já foi usado ou se está vencido.";
                }
            }
        }

        include __DIR__ . '/../views/comerciante/validar_cupom.php';
    }

}
