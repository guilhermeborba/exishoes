<?php
class WC_Cielo_Webservice_Loja5_Metabox {

	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'register_metabox' ) );
		add_action( 'save_post', array( $this, 'save' ) );
	}
	
	public function save(){
		//
	}

	public function register_metabox() {
		add_meta_box(
			'loja5_woo_cielo_webservice_metabox',
			'Transa&ccedil;&atilde;o Cielo API',
			array( $this, 'metabox_content' ),
			'shop_order',
			'side',
			'high'
		);
	}

	public function metabox_content( $post ) {
		global $wpdb;

		include_once(CIELO_WEBSERVICE_WOO_PATH.'/include/restclient.php' );
		
		//dados do pedido
		$order = wc_get_order( $post->ID );

		//consulta os dados cielo relacionado ao pedido
		$dados = (array)$wpdb->get_row("SELECT * FROM `wp_cielo_api_loja5` WHERE pedido = '".$order->get_id()."' ORDER BY id DESC;");

		//se o pedido for pago com cielo webservice
		if ( 'loja5_woo_cielo_webservice' == $order->get_payment_method() || 'loja5_woo_cielo_webservice_debito' == $order->get_payment_method() || 'loja5_woo_cielo_webservice_tef' == $order->get_payment_method() || 'loja5_woo_cielo_webservice_boleto' == $order->get_payment_method() || 'loja5_woo_cielo_webservice_qrcode' == $order->get_payment_method() || 'loja5_woo_cielo_webservice_pix' == $order->get_payment_method() ) {

			//se retornou tid
            $status = '';
			if ( isset( $dados['id_pagamento'] ) && !empty( $dados['id_pagamento'] ) ) {
				
				//detalhes
				$html = '<style>#loja5_woo_cielo_webservice_metabox.postbox {display: ;}</style>';
				if(isset($dados['id_pagamento'])){
					$html .= '<p><strong>ID Pagamento</strong> <a href="'.admin_url( 'admin-ajax.php' ).'?action=logs_cielo_webservice_api_loja5&id='.$dados['id_pagamento'].'" target="_blank">'.$dados['id_pagamento'].'</a></p>';
				}
                $html .= '<p><strong>Total:</strong> ' . $dados['total'] . '</p>';
                if($dados['metodo']=='credito' || $dados['metodo']=='debito'){
                    $html .= '<p><strong>TID:</strong> ' . $dados['tid'] . '</p>';
                    $html .= '<p><strong>Bandeira:</strong> '.strtoupper($dados['bandeira']).' / '.ucfirst($dados['metodo']).'</p>';
					$html .= '<p><strong>BIN:</strong> '.$dados['bin'].'</p>';
					$html .= '<p><strong>Parcela:</strong> '.$dados['parcela'].'x</p>';
                }else{
					if($dados['metodo']=='boleto'){
						$html .= '<p><strong>Nosso N&uacute;mero:</strong> '.strtoupper($dados['tid']).'</p>';
					}elseif($dados['metodo']=='pix'){
						$html .= '<p><strong>ID:</strong> '.strtoupper($dados['tid']).'</p>';
					}
					$html .= '<p><strong>Banco:</strong> '.strtoupper($dados['bandeira']).' / '.ucfirst($dados['metodo']).'</p>';
				}
				
				//link de pagamento
				if(!empty($dados['link'])){
					$html .= '<p><strong>Link de Pagamento:</strong> <a target="_blank" href="'.$dados['link'].'">'.$dados['link'].'</a></p>';
				}
		
				//busca as configuracoes cielo webservice
                if($order->get_payment_method()=='loja5_woo_cielo_webservice'){
                    $config = new WC_Gateway_Loja5_Woo_Cielo_Webservice();
                }elseif($order->get_payment_method()=='loja5_woo_cielo_webservice_debito'){
                    $config = new WC_Gateway_Loja5_Woo_Cielo_Webservice_Debito();
                }elseif($order->get_payment_method()=='loja5_woo_cielo_webservice_tef'){
                    $config = new WC_Gateway_Loja5_Woo_Cielo_Webservice_TEF();
                }elseif($order->get_payment_method()=='loja5_woo_cielo_webservice_boleto'){
                    $config = new WC_Gateway_Loja5_Woo_Cielo_Webservice_Boleto();
                }
                
                //pega a data do pedido
				$data_pedido = strtotime($order->get_date_created());

				//cielo api
				if($config->testmode=='yes'){
					$provider = 'Simulado';
					$urlweb = "https://apiquerysandbox.cieloecommerce.cielo.com.br/1/";
				}else{
					$provider = 'Cielo';
					$urlweb = "https://apiquery.cieloecommerce.cielo.com.br/1/";
				}
				$objResposta = array();
				$headers = array(
					"Content-Type" => "application/json",
					"Accept" => "application/json",
					"MerchantId" =>trim($config->afiliacao),
					"MerchantKey" => trim($config->chave),
					"RequestId" => "",
				);
				$api = new RestClientCielo(array(
					'base_url' => $urlweb, 
					'headers' => $headers, 
				));
				$response = $api->get("sales/".$dados['id_pagamento']."");
				$dados_pedido = @json_decode($response->response,true);
				if(($response->status==200 || $response->status==201) && isset($dados_pedido['Payment']['Status'])){
					$status_id = $dados_pedido['Payment']['Status'];
					switch($dados_pedido['Payment']['Status']){
						case '2':
							$status = 'Aprovado';
						break;
						case '1':
							$status = 'Autorizado';
						break;
						case '3':
							$status = 'Negado';
						break;
						case '11':
							$status = 'Devolvido';
						break;
						case '10':
						case '13':
							$status = 'Cancelado';
						break;
						default:
							$status = 'Aguardando Pagamento';
					}
					if($dados['metodo']=='boleto' && $status_id==1){
						$html .= '<p><strong>Status Cielo:</strong> Aguardando Pagamento</p>';
					}else{
						$html .= '<p><strong>Status Cielo:</strong> ' . $status . '</p>';
					}
					if(!empty($dados['lr'])){
						$html .= '<p><strong>LR:</strong> ' . $dados['lr'] . ' ' . $dados['lr_log'] . '</p>';
					}
					if(isset($dados_pedido['Payment']['FraudAnalysis']['Id'])){
						$html .= '<p><strong>ID Consulta Anti-fraude:</strong> ' . $dados_pedido['Payment']['FraudAnalysis']['Id'] . '</p>';
						if(isset($dados_pedido['Payment']['FraudAnalysis']['Status'])){
							if($dados_pedido['Payment']['FraudAnalysis']['Status']==1){
								$html .= '<p><strong>Status Anti-fraude Atual:</strong> ' . $dados_pedido['Payment']['FraudAnalysis']['Status'] . ' - Aceito</p>';
							}elseif($dados_pedido['Payment']['FraudAnalysis']['Status']==2){
								$html .= '<p><strong>Status Anti-fraude Atual:</strong> ' . $dados_pedido['Payment']['FraudAnalysis']['Status'] . ' - Rejeitado</p>';
							}elseif($dados_pedido['Payment']['FraudAnalysis']['Status']==3){
								$html .= '<p><strong>Status Anti-fraude Atual:</strong> ' . $dados_pedido['Payment']['FraudAnalysis']['Status'] . ' - Revis&atilde;o</p>';
							}elseif($dados_pedido['Payment']['FraudAnalysis']['Status']==4){
								$html .= '<p><strong>Status Anti-fraude Atual:</strong> ' . $dados_pedido['Payment']['FraudAnalysis']['Status'] . ' - Abortado</p>';
							}elseif($dados_pedido['Payment']['FraudAnalysis']['Status']==5){
								$html .= '<p><strong>Status Anti-fraude Atual:</strong> ' . $dados_pedido['Payment']['FraudAnalysis']['Status'] . ' - N&atilde;o Finalizado</p>';
							}else{
								$html .= '<p><strong>Status Anti-fraude Atual:</strong> ' . $dados_pedido['Payment']['FraudAnalysis']['Status'] . ' - Desconhecido</p>';
							}
						}
						if(isset($dados_pedido['Payment']['FraudAnalysis']['FraudAnalysisReasonCode'])){
							$html .= '<p><strong>Anti-fraude Raz&atilde;o:</strong> ' . $dados_pedido['Payment']['FraudAnalysis']['FraudAnalysisReasonCode'] . ' (<a href="https://loja5.zendesk.com/hc/pt-br/articles/360046047971" target="_blank">ver lista</a>)</p>';
						}
						if(isset($dados_pedido['Payment']['FraudAnalysis']['ReplyData']['Score'])){
							$html .= '<p><strong>Anti-fraude Score:</strong> ' . $dados_pedido['Payment']['FraudAnalysis']['ReplyData']['Score'] . '</p>';
						}
						if(isset($dados_pedido['Payment']['FraudAnalysis']['Provider'])){
							$html .= '<p><strong>Provedor:</strong> ' . $dados_pedido['Payment']['FraudAnalysis']['Provider'] . '</p>';
						}
					}
				}else{
					$status_id = $dados['status'];
					switch($dados['status']){
						case '2':
							$status = 'Aprovado';
						break;
						case '1':
							$status = 'Autorizado';
						break;
						case '3':
							$status = 'Negado';
						break;
						case '11':
							$status = 'Devolvido';
						break;
						case '10':
						case '13':
							$status = 'Cancelado';
						break;
						default:
							$status = 'Aguardando Pagamento';
					}
					if($dados['metodo']=='boleto' && $status_id==1){
						$html .= '<p><strong>Status Cielo:</strong> Aguardando Pagamento</p>';
					}else{
						$html .= '<p><strong>Status Cielo:</strong> ' . $status . '</p>';
					}
					if(!empty($dados['lr'])){
						$html .= '<p><strong>LR:</strong> ' . $dados['lr'] . ' ' . $dados['lr_log'] . '</p>';
					}
				}
				
				//autorizar ou capturar
				if($dados['metodo']=='credito' || $dados['metodo']=='debito'){
					if($status_id==1 || $status_id==2){
						$html .= '<a class="button button-primary" href="admin.php?page=loja5-woo-cielo-webservice-pedidos&pedido='.$order->get_id().'">Detalhes / Capturar / Cancelar</a>';
					}else{
						$html .= '<a class="button button-primary" href="admin.php?page=loja5-woo-cielo-webservice-pedidos&pedido='.$order->get_id().'">Detalhes</a>';
					}
				}else{
					$html .= '<a class="button button-primary" href="admin.php?page=loja5-woo-cielo-webservice-pedidos&pedido='.$order->get_id().'">Detalhes</a>';
				}
			}else{
                $html = '<style>#loja5_woo_cielo_webservice_metabox.postbox {display: none;}</style>';
			}
		}else{
            $html .= '<style>#loja5_woo_cielo_webservice_metabox.postbox {display: none;}</style>';
		}
		echo $html;
	}
}
