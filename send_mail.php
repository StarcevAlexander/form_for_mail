<?php

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require "PHPMailer/src/Exception.php";
    require "PHPMailer/src/PHPMailer.php";

    $mail = new PHPMailer(true);
	
    $mail->CharSet = "UTF-8";
    $mail->IsHTML(true);

    $name = $_POST["name"];
    $email = $_POST["email"];
	$phone = $_POST["phone"];
    $message = $_POST["message"];
	$email_template = "template_mail.html";

    $body = file_get_contents($email_template);
	$body = str_replace('%name%', $name, $body);
	$body = str_replace('%email%', $email, $body);
	$body = str_replace('%phone%', $phone, $body);
	$body = str_replace('%message%', $message, $body);

    if (!empty($_FILES["image"]["tmp_name"])) {
        // путь загрузки файла
        //НЕ ЗАБЫТЬ В ДИРЕКТОРИИ ХОСТА СОЗДАТЬ ПАПКУ "images"
        $filePath = __DIR__ . "/images/" . $_FILES["image"]["name"];
        // грузим файл
        if (copy($_FILES["image"]["tmp_name"], $filePath)) {
            $fileAttach = $filePath;
            $mail->addAttachment($fileAttach);
        }
    }
//наше мыло, куда слать
    $mail->addAddress("79140050089@ya.ru");
//наше мыло с которого будет идти
	$mail->setFrom("mail@polina-star.ru");
    $mail->Subject = "Заявка с формы";
    $mail->MsgHTML($body);

    if (!$mail->send()) {
        $message = "Ошибка отправки";
    } else {
        $message = "Данные отправлены!";
    }
	
	$response = ["message" => $message];

    header('Content-type: application/json');
    echo json_encode($response);


?>