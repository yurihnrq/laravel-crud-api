<?php

namespace App\Http\Controllers;

use PDO;
use Illuminate\Http\Request;
use Exception;

class UserController extends Controller {
    private static function verifyCPF($cpfToVerify) {
        // Extrai somente os números
        $cpf = preg_replace('/[^0-9]/is', '', $cpfToVerify);

        // Verifica se foi informado todos os dígitos corretamente.
        if (strlen($cpf) != 11)
            return false;

        // Verifica se foi informada uma sequência de dígitos repetidos. Ex: 111.111.111-11.
        if (preg_match('/(\d)\1{10}/', $cpf))
            return false;

        // Faz o calculo para validar o CPF.
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }

        return true;
    }

    private static function PDOConnection() {
        // Obtém as variáveis de ambiente no arquivo .env que estiver
        // na raiz do projeto.
        $DB_CONNECTION = env('DB_CONNECTION');
        $DB_HOST = env('DB_HOST');
        $DB_PORT = env('DB_PORT');
        $DB_DATABASE = env('DB_DATABASE');
        $DB_USERNAME = env('DB_USERNAME');
        $DB_PASSWORD = env('DB_PASSWORD');

        // DSN é apenas um acrônimo de database source name.
        $DSN = "$DB_CONNECTION:host=$DB_HOST;port=$DB_PORT;dbname=$DB_DATABASE";

        $options = [
            // Desativa a execução emulada de prepared statements.
            PDO::ATTR_EMULATE_PREPARES => false,
            // Ativa o modo de erros para lançar exceções.
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            // Altera o modo padrão do método fetch para FETCH_ASSOC.
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ];

        try {
            $pdo = new PDO($DSN, $DB_USERNAME, $DB_PASSWORD, $options);
        } catch (Exception $e) {
            throw new Exception('PDOConnection error: ' . $e->getMessage());
        }
        return $pdo;
    }

    public function getAllUsers() {
        try {
            // Realizando conexão com o banco de dados.
            $pdo = $this->PDOConnection();
            // Estruturando query.
            $sql = <<<SQL
                SELECT * FROM users ORDER BY users.id ASC
            SQL;
            // Realiza query.
            $stmt = $pdo->query($sql);

            // Converte os dados obtidos para JSON.
            $users = json_encode($stmt->fetchAll());
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }

        return response($users, 200);
    }

    public function getUsersByInfo($info) {
        try {
            $pdo = $this->PDOConnection();
            $sql = <<<SQL
                SELECT * FROM users WHERE upper(users.name) LIKE ? or upper(users.email) LIKE ?
                ORDER BY users.id ASC LIMIT 10
            SQL;
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['%' . strtoupper($info) . '%', '%' . strtoupper($info) . '%']);

            $users = json_encode($stmt->fetchAll());
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }

        return response($users, 200);
    }

    public function getUser($id) {
        try {
            $pdo = $this->PDOConnection();
            $sql = <<<SQL
                SELECT * FROM users WHERE users.id = ?
            SQL;
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);

            if ($stmt->rowCount() < 1) {
                return response()->json([
                    'message' => 'user not found'
                ], 404);
            }

            $users = json_encode($stmt->fetchAll());
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }

        return response($users, 200);
    }

    public function createUser(Request $request) {
        try {
            $userName = $request->name;
            $userCPF = preg_replace('/[^0-9]/is', '', $request->cpf);
            $userBirth = $request->birth;
            $userPhone = $request->phone;
            $userEmail = $request->email;
            $userAddress = $request->address;
            $userNote = $request->note;

            if (!$this->verifyCPF($userCPF) || strlen($userNote) > 300) {
                return response()->json([
                    'message' => 'invalid user credentials'
                ], 400);
            }

            $pdo = $this->PDOConnection();
            $sql = <<<SQL
                INSERT INTO users (
                    name, 
                    cpf,
                    birth, 
                    phone, 
                    email, 
                    address, 
                    note, 
                    created_at, 
                    updated_at
                )
                VALUES (?,?,?,?,?,?,?,?,?)
            SQL;
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $userName,
                $userCPF,
                $userBirth,
                $userPhone,
                $userEmail,
                $userAddress,
                $userNote,
                date('Y-m-d H:i:s'),
                date('Y-m-d H:i:s')
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }

        return response()->json([
            'message' => 'user record created'
        ], 201);
    }

    public function updateUser(Request $request, $id) {
        try {
            $pdo = $this->PDOConnection();
            $sql = <<<SQL
                SELECT * FROM users WHERE users.id=?
            SQL;
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            if ($stmt->rowCount() < 1) {
                return response()->json([
                    'message' => 'user not found'
                ], 404);
            }

            $userName = $request->name;
            $userCPF = preg_replace('/[^0-9]/is', '', $request->cpf);
            $userBirth = $request->birth;
            $userPhone = $request->phone;
            $userEmail = $request->email;
            $userAddress = $request->address;
            $userNote = $request->note;

            if (!$this->verifyCPF($userCPF) || strlen($userNote) > 300) {
                return response()->json([
                    'message' => 'invalid user credentials'
                ], 400);
            }

            $sql = <<<SQL
                UPDATE users SET 
                    name=?, 
                    cpf=?, 
                    birth=?, 
                    phone=?, 
                    email=?, 
                    address=?, 
                    note=?, 
                    updated_at=?
                WHERE users.id=?
            SQL;

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $userName,
                $userCPF,
                $userBirth,
                $userPhone,
                $userEmail,
                $userAddress,
                $userNote,
                date('Y-m-d H:i:s'),
                $id
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }

        return response()->json([
            "message" => "records updated successfully"
        ], 200);
    }

    public function deleteUser($id) {
        try {
            $pdo = $this->PDOConnection();
            $sql = <<<SQL
                SELECT * FROM users WHERE users.id=?
            SQL;
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            if ($stmt->rowCount() < 1) {
                return response()->json([
                    'message' => 'user not found'
                ], 404);
            }

            $sql = <<<SQL
                DELETE FROM users WHERE users.id=?
            SQL;

            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }

        return response()->json([
            "message" => "record deleted successfully"
        ], 200);
    }
}
