<?php 
class WC_Cielo_Webservice_Loja5_Admin {
	
	public function __construct() {
		if (!defined('LOJA5_CIELO_WEBSERVICE_WOO_MODULO_COMERCIAL')){
			die('Violacao de licenca comercial loja5.com.br');
		}
		add_action( 'admin_menu', array( $this, 'menu' ) );
		add_action( 'wp_ajax_processar_pedidos_cielo_webservice', array($this,'processar_pedidos_cielo_webservice'));
		add_action( 'wp_ajax_logs_cielo_webservice_api_loja5', array($this,'logs'));
		add_filter( 'woocommerce_order_data_store_cpt_get_orders_query',  array($this,'handle_custom_query_var_cielo'), 10, 2 );
	}
	
	public function handle_custom_query_var_cielo( $query, $query_vars ) {
		if ( ! empty( $query_vars['_processado_cielo_loja5'] ) ) {
			$query['meta_query'][] = array(
				'key' => '_processado_cielo_loja5',
				'value' => esc_attr( $query_vars['_processado_cielo_loja5'] ),
			);
		}
		return $query;
	}
	
	public function logs(){
		global $wpdb;
		if(is_admin()){
			//configs
			$config = new WC_Gateway_Loja5_Woo_Cielo_Webservice();
			if($config->testmode=='yes'){
				$provider = 'Simulado';
				$urlweb = "https://apiquerysandbox.cieloecommerce.cielo.com.br/1/";
			}else{
				$provider = 'Cielo';
				$urlweb = "https://apiquery.cieloecommerce.cielo.com.br/1/";
			}
			//faz o include das config cielo
			include_once(CIELO_WEBSERVICE_WOO_PATH.'/include/restclient.php');
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
			$response = $api->get("sales/".$_GET['id']."");
			$dados_pedido = @json_decode($response->response,true);
			echo '<pre>';
			echo print_r($dados_pedido,true);
			echo '</pre>';
		}
		exit;
	}
	
	public function processar_pedidos_cielo_webservice(){
		global $wpdb;
		if(is_admin()){
			//configs
			$config = new WC_Gateway_Loja5_Woo_Cielo_Webservice();
			if($config->testmode=='yes'){
				$provider = 'Simulado';
				$urlweb = "https://apisandbox.cieloecommerce.cielo.com.br/1/";
			}else{
				$provider = 'Cielo';
				$urlweb = "https://api.cieloecommerce.cielo.com.br/1/";
			}
			//faz o include das config cielo
			include_once(CIELO_WEBSERVICE_WOO_PATH.'/include/restclient.php' );
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
			if(isset($_POST['acoes']) && count($_POST['acoes']) > 0){
				$log = '';
				foreach($_POST['acoes'] as $dados){
					//valida os requests
					if(sha1(md5($dados['pedido']))==$dados['hash']){
						//dados cielo mysql
						$cielo = (array)$wpdb->get_row("SELECT * FROM `wp_cielo_api_loja5` WHERE `pedido` = '".(int)($dados['pedido'])."' ORDER BY id DESC;");
						if(isset($cielo['id_pagamento'])){
							$log .= 'Requisicao de '.strtoupper($dados['acao']).' feita via ADMIN em '.date('d/m/Y H:i:s').' ao Pedido #'.(int)($dados['pedido']).'';
							
							//acao de capturar
							if($dados['acao']=='capturar'){
								$response = $api->put("sales/".$cielo['id_pagamento']."/capture");
								
								//debug
								if ( 'yes' === $config->debug ) {
									$logs = new WC_Logger();
									$logs->add( 'loja5_woo_cielo_capturas', 'Log Captura Cielo: '.$response->response );
								}
								
								if(isset($response->status)){
									$dados_pedido = json_decode($response->response,true);
									if(isset($dados_pedido['Status']) && $dados_pedido['Status']==2){
										//atualiza o pedido na loja
										$detalhes = 'Pedido #'.(int)($dados['pedido']).' Capturado com sucesso via ADMIN em '.date('d/m/Y H:i:s').'';
										if(isset($dados_pedido['ProofOfSale'])){
											$detalhes .= ' / NSU: '.$dados_pedido['ProofOfSale'];
										}
										if(isset($dados_pedido['AuthorizationCode'])){
											$detalhes .= ' / Auth: '.$dados_pedido['AuthorizationCode'];
										}
										$order = wc_get_order((int)($dados['pedido']));
										$order->add_order_note($detalhes);
										$order->update_status($config->pago);
										//adiciona ao log
										$log .= ' / TID: '.$dados_pedido['Tid'].' realizada com sucesso!<br>';
										//atualiza o pedido no banco de dados
										$wpdb->query("UPDATE `wp_cielo_api_loja5` SET  `lr` =  '".$dados_pedido['ReturnCode']."',  `lr_log` =  '".$dados_pedido['ReturnMessage']."', `status` =  '".$dados_pedido['Status']."' WHERE `pedido` = '".(int)($dados['pedido'])."';");
										//exibe
										echo $detalhes.'<br>';
									}else{
										echo $response->response;
										$log .= ' retornou um problema: '.$response->response.'<br>';
									}
								}
							}
							
							//acao de cancelamento
							if($dados['acao']=='cancelar'){
								$response = $api->put("sales/".$cielo['id_pagamento']."/void");
								
								//debug
								if ( 'yes' === $config->debug ) {
									$logs = new WC_Logger();
									$logs->add( 'loja5_woo_cielo_cancelamentos', 'Log Cancelamento Cielo: '.$response->response );
								}
								
								if(isset($response->status)){
									$dados_pedido = json_decode($response->response,true);
									if(isset($dados_pedido['Status']) && ($dados_pedido['Status']==10 || $dados_pedido['Status']==11 || $dados_pedido['Status']==9)){
										//atualiza o pedido na loja
										$detalhes = 'Pedido #'.(int)($dados['pedido']).' Cancelado com sucesso via ADMIN em '.date('d/m/Y H:i:s').'';
										if(isset($dados_pedido['ProofOfSale'])){
											$detalhes .= ' / NSU: '.$dados_pedido['ProofOfSale'];
										}
										if(isset($dados_pedido['AuthorizationCode'])){
											$detalhes .= ' / Auth: '.$dados_pedido['AuthorizationCode'];
										}
										$order = wc_get_order((int)($dados['pedido']));
										$order->add_order_note($detalhes);
										$order->update_status($config->cancelado);
										//adiciona ao log
										$log .= ' / TID: '.$dados_pedido['Tid'].' realizada com sucesso!<br>';
										//atualiza o pedido no banco de dados
										$wpdb->query("UPDATE `wp_cielo_api_loja5` SET  `lr` =  '".$dados_pedido['ReturnCode']."',  `lr_log` =  '".$dados_pedido['ReturnMessage']."', `status` =  '".$dados_pedido['Status']."' WHERE `pedido` = '".(int)($dados['pedido'])."';");
										//volta o estoque
										if(CIELO_WEBSERVICE_WOO_REESTOCK==true){
											$config->restore_order_stock((int)($dados['pedido']));
										}
										//exibe
										echo $detalhes.'<br>';
									}else{
										echo $response->response;
										$log .= ' retornou um problema: '.$response->response.'<br>';
									}
								}
							}
							
						}
					}
				}
				//salva os logs
				if(!empty($log)){
					$logs = new WC_Logger();
					$logs->add( 'loja5_woo_cielo_webservice', $log );
				}
			}else{
				die('Acesso negado!');
			}
		}else{
			die('Acesso negado!');
		}
		exit;
	}
	
	public function menu() {
        add_submenu_page(
            'woocommerce',
            'Pedidos Cielo API',
			'Pedidos Cielo API',
            CIELO_WEBSERVICE_WOO_ROLE,
            'loja5-woo-cielo-webservice-pedidos',
            array( $this, 'admin' )
        );
    }
	
	public function status_cielo($id){
		switch($id){
			case '2':
				$status = '<span style="color: #20bb20;">Aprovada</span>';
			break;
			case '1':
				$status = '<span style="color: #2196f3;">Autorizada</span>';
			break;
			case '3':
				$status = '<span style="color: red;">Negada</span>';
			break;
			case '11':
				$status = '<span style="color: red;">Devolvida</span>';
			break;
			case '10':
			case '13':
				$status = '<span style="color: red;">Cancelada</span>';
			break;
			default:
				$status = 'Aguardando Pagamento';
		}
		return $status;
	}
	
	public function registro_cielo($order_id){
		global $wpdb;
		return (array)$wpdb->get_row("SELECT * FROM `wp_cielo_api_loja5` WHERE `pedido` = '".(int)($order_id)."' ORDER BY id DESC;");
	}
	
	public function admin() {
		$pagina = (int)isset($_GET['pg'])?$_GET['pg']:1;
		$tipo = isset($_GET['tipo'])?$_GET['tipo']:'';
		$args = array(
			'_processado_cielo_loja5' => 'true',
			'limit' => get_option( 'posts_per_page' ),
			'orderby' => 'ID',
			'payment_method' => array('loja5_woo_cielo_webservice','loja5_woo_cielo_webservice_debito','loja5_woo_cielo_webservice_tef','loja5_woo_cielo_webservice_boleto','loja5_woo_cielo_webservice_qrcode','loja5_woo_cielo_webservice_pix'),
			'order' => 'DESC',
			'paged' => $pagina,
			'paginate' => true
		);
		//filta por tipo de pagamento
		if(!empty($tipo)){
			$args = array_merge($args, array('payment_method' => trim($tipo)));
		}
		
		//se visualiza pedido individual ou geral
		if(isset($_GET['pedido'])){
			$orders = new stdClass;
			$orders->max_num_pages = 1;
			$orders->total = 1;
			$orders->orders[] = wc_get_order((int)($_GET['pedido']));
		}else{
			$orders = wc_get_orders( $args );
		}
		
		$page_links = paginate_links( array(
			'base' => add_query_arg( 'pg', '%#%' ),
			'format' => '',
			'prev_text' => __( '&laquo;', 'loja5-woo-cielo-webservice' ),
			'next_text' => __( '&raquo;', 'loja5-woo-cielo-webservice' ),
			'total' => $orders->max_num_pages,
			'current' => $pagina
		) );
		$total = $orders->total;
		$pedidos = $orders->orders;
		include_once(CIELO_WEBSERVICE_WOO_PATH.'/include/lista_pedidos.php');
	}

}
?>