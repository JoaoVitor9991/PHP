<?php
require 'conexao.php';
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $conn->real_escape_string($_POST['email']);

    $sql = "SELECT id, nome FROM usuarios WHERE email = '$email' LIMIT 1";
    $res = $conn->query($sql);

    
    if ($res->num_rows > 0) {
        $user = $res->fetch_assoc();
        $idUsuario = $user['id'];
        $nome = $user['nome'];

        
        $novaSenhaPlana = substr(md5(uniqid(rand(), true)), 0, 8);

        
        $novaSenhaHash = password_hash($novaSenhaPlana, PASSWORD_DEFAULT);

        $sqlUpdate = "UPDATE usuarios SET senha = '$novaSenhaHash' WHERE id = $idUsuario";

        
        if ($conn->query($sqlUpdate)) {
            
            $mail = new PHPMailer(true);
            try {
                
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = '';
                $mail->Password = ""; 
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;
                $mail->CharSet = 'UTF-8';

                $mail->setFrom('joaozardmachado@gmail.com', 'Suporte - Sistema');
                $mail->addAddress($email, $nome);

                $mail->isHTML(true);
                $mail->Subject = 'Recuperação de Senha';
                $mail->Body = "Olá <b>$nome</b>. <br><br> Sua nova senha é: <b>$novaSenhaPlana</b><br><br> Recomendamos que altere a senha após o login.";
                $mail->AltBody = "Olá $nome, \n\nSua Nova senha é: $novaSenhaPlana\n\nAltere após o login.";

                $mail->send();
                echo "Uma nova senha foi enviada para seu e-mail.";
            } catch (Exception $e) { 
                
                echo "Sua senha foi redefinida, mas houve um erro ao enviar o e-mail: {$mail->ErrorInfo}";
            }
        } else {
            
            echo "Erro ao atualizar a senha no banco de dados.";
        }
    } else {
        
        echo "E-mail não encontrado em nossa base de dados.";
    }
}
?>