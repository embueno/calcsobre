<!DOCTYPE html>
<html>
   <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Calculo Sobreaviso CLT</title>

        <!-- CSS -->
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
        <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/form-elements.css">
        <link rel="stylesheet" href="assets/css/style.css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

        <!-- Favicon and touch icons -->
        <link rel="shortcut icon" href="assets/ico/favicon.png">
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/ico/apple-touch-icon-144-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/ico/apple-touch-icon-114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/ico/apple-touch-icon-72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" href="assets/ico/apple-touch-icon-57-precomposed.png">
        <script type="text/javascript">
          
        function notperm(){
        alert("Permissão não consedida!");
        window.location.href('http://calculesobreaviso.pe.hu')
      };
        </script>

    </head>
<body>

<?php
session_start();
if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['code'])){

  // Informe o seu App ID abaixo
  $appId = '186160508480675';

  // Digite o App Secret do seu aplicativo abaixo:
  $appSecret = 'b0326d2c77ab484597d628767e7515ba';

  // Url informada no campo "Site URL"
  $redirectUri = urlencode('http://calculesobreaviso.pe.hu/desboard.php');

  // Obtém o código da query string
  $code = $_GET['code'];

  // Monta a url para obter o token de acesso e assim obter os dados do usuário
  $token_url = "https://graph.facebook.com/oauth/access_token?"
  . "client_id=" . $appId . "&redirect_uri=" . $redirectUri
  . "&client_secret=" . $appSecret . "&code=" . $code;

  //pega os dados
  $response = @file_get_contents($token_url);
  if($response){
    $params = null;
    parse_str($response, $params);
    if(isset($params['access_token']) && $params['access_token']){
      $graph_url = "https://graph.facebook.com/me?access_token="
      . $params['access_token'];
      $user = json_decode(file_get_contents($graph_url));

	// nesse IF verificamos se veio os dados corretamente
      if(isset($user->email) && $user->email){

	/*
	*Apartir daqui, você já tem acesso aos dados usuario, podendo armazená-los
	*em sessão, cookie ou já pode inserir em seu banco de dados para efetuar
	*autenticação.
	*No meu caso, solicitei todos os dados abaixo e guardei em sessões.
	*/

        $_SESSION['email'] = $user->email;
        $_SESSION['nome'] = $user->name;
        $_SESSION['localizacao'] = $user->location->name;
        $_SESSION['uid_facebook'] = $user->id;
        $_SESSION['user_facebook'] = $user->username;
        $_SESSION['link_facebook'] = $user->link;
      }
    }else{
      echo "Erro de conexão com Facebook";
      exit(0);
    }

  }else{
    echo "Erro de conexão com Facebook";
    exit(0);
  }
}else if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['error'])){
  echo '<script>function notperm();</script>';
}
?>
</body>
</html>