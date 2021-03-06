<?php
	require "conexao.php";    
	
	$idLogin = $_SESSION['idUsuario'];
	$idPaciente = $_POST['idPaciente'];
    
	$docDesc = $_POST['documentName'];
		
	$spaceLimit = $_POST['space_limit'];
	$directorySize = 0;
	$avaiable_space = 0;

	if(isset($_FILES['document']) && $_FILES['document']['size'] > 0) {  
 
		$extensoes_aceitas = array('docx' ,'pdf', 'ppt', 'txt', 'xlsx', 'zip');
		$array_extensoes   = explode('.', $_FILES['document']['name']);
	    $extensao = strtolower(end($array_extensoes));
 
		$arquivo = $_FILES['document'];				
		$docHash = md5(date("d/m/y H:i:s"));	
		//nome da imagem com hash
        $docNameWithHash = $docHash . $arquivo['name'];
        //extensao da imagem        
        $ext = pathinfo($arquivo['name'], PATHINFO_EXTENSION);

		// Validamos se a extensão do arquivo é aceita
	    if (array_search($extensao, $extensoes_aceitas) === false) {
				  
			//ERRO - EXTENSAO NAO SUPORTADA
			echo('<div class="alert alert-danger alert-dismissible fade show mb-0" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="fa fa-times mx-2"></i><strong>Erro!</strong> Extensão não suportada! </div>');

	       exit(); 
		}
	
		// Verifica se o upload foi enviado via POST   
		if(is_uploaded_file($arquivo['tmp_name'])) {  
			
			//Se a pasta documents nao existir
			if(!file_exists("../assets/documents")) {
				mkdir("../assets/documents");  
			}

			//Se a pasta patients-documents nao existir
			if(!file_exists("../assets/documents/patients-documents")) {
				mkdir("../assets/documents/patients-documents");  
			}

			if(!file_exists("../assets/documents/patients-documents/".$idLogin)) {
				mkdir("../assets/documents/patients-documents".$idLogin);  
			}

			if(!file_exists("../assets/documents/patients-documents/".$idLogin.'/'.$idPaciente)) {
				mkdir("../assets/documents/patients-documents/".$idLogin.'/'.$idPaciente);  
			}
			
			// CALCULA ESPAÇO DISPONÍVEL
			foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator("../assets/documents/patients-documents/".$idLogin.'/'.$idPaciente, FilesystemIterator::SKIP_DOTS)) as $object){
				$directorySize += $object->getSize();
			}

			$avaiable_space = $spaceLimit - ($directorySize/1000000);

			if (($arquivo['size']/1000000) > $avaiable_space) {
				// ERRO - TAMANHO DO ARQUIVO MAIOR QUE ESPAÇO DISPONÍVEL				
				echo('<div class="alert alert-danger alert-dismissible fade show mb-0" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="fa fa-times mx-2"></i><strong>Erro!</strong> Não há espaço suficiente para este arquivo!</div>');
				exit();  
			}

						
			// Essa função move_uploaded_file() copia e verifica se o arquivo enviado foi copiado com sucesso para o destino  
			if (!move_uploaded_file($arquivo['tmp_name'], '../assets/documents/patients-documents/'.$idLogin.'/'.$idPaciente .'/'.$docNameWithHash)){  
				
				//ERRO - ARQUIVO NAO COPIADO
				echo('<div class="alert alert-danger alert-dismissible fade show mb-0" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="fa fa-times mx-2"></i><strong>Erro!</strong> Algo não occoreu como o esperado!</div>');
				exit();  
			}

            $query = "INSERT INTO tb11_documentos_paciente (tb11_id_paciente, tb11_documento, tb11_nome, tb11_extensao, tb11_id_usuario) VALUES ('$idPaciente', '$docNameWithHash', '$docDesc', '$ext', '$idLogin');";
			$result = mysqli_query($conexao, $query);	

			//SUCESSO - TUDO SAIU COMO ESPERADO
			echo('<div class="alert alert-success alert-dismissible fade show mb-0" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="fa fa-check mx-2"></i><strong>Sucesso!</strong> Documento adicionado!</div>');
		}		
	} else { //ERRO - UPLOAD SEM ARQUIVO
		
		echo('<div class="alert alert-danger alert-dismissible fade show mb-0" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="fa fa-times mx-2"></i><strong>Erro!</strong> Escolha um documento!</div>');
	}
?>
