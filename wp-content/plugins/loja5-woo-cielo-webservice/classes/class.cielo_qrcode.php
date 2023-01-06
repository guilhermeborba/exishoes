<?php
    class WC_Gateway_Loja5_Woo_Cielo_Webservice_QrCode extends WC_Payment_Gateway {
        
        public function __construct() {
            global $woocommerce;
            $this->id           = 'loja5_woo_cielo_webservice_qrcode';
            $this->icon         = apply_filters( 'woocommerce_loja5_woo_cielo_webservice_qrcode', plugins_url().'/loja5-woo-cielo-webservice/images/cielo.png' );
            $this->has_fields   = false;
            $this->supports   = array('products');
            $this->description = true;
			$this->method_description = __( 'Ativa o pagamento por QrCode via Cielo.', 'loja5-woo-cielo-webservice-qrcode' );
            $this->method_title = 'Cielo API - QrCode';
            $this->init_settings();
            $this->init_form_fields();
			$this->instalar_mysql_cielo_webservice();
			if(!isset($this->title) || empty($this->title)){
			$this->title = 'informe o titulo';
		}
            
            foreach ( $this->settings as $key => $val ) $this->$key = $val;
            
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array( &$this, 'process_admin_options' ) );
            add_action( 'woocommerce_thankyou_' . $this->id, array( $this, 'thankyou_page' ) );
            
            if ( !$this->is_valid_for_use() ) $this->enabled = false;
        }
        
        public function thankyou_page( $order_id ) {
            global $wpdb;
            //pega o pedido
			$order = wc_get_order((int)($order_id));
			$total_pedido = $order->get_total();
			
			//custom
			$dados_pedido = $order->get_meta( '_dados_cielo_api' );

            //dados cielo mysql
            $cielo = (array)$wpdb->get_row("SELECT * FROM `wp_cielo_api_loja5` WHERE `pedido` = '".(int)($order_id)."' ORDER BY id DESC;");

			//define o status do pedido
			$status_cielo = isset($cielo['status'])?$cielo['status']:'0';
			switch($status_cielo){
				case '2':
					$status = '<span style="color: #20bb20;">Aprovada</span>';
				break;
				case '1':
					$status = '<span style="color: #2196f3;">Autorizada</span>';
				break;
				case '3':
					$status = '<span style="color: red;">Negada</span>';
				break;
				case '10':
				case '13':
					$status = '<span style="color: red;">Cancelada</span>';
				break;
				default:
					$status = 'Aguardando Pagamento';
			}

			//url ver
			$url_ver_qrcode = admin_url('admin-ajax.php').'?action=retorno_qrcode_cielo_webservice&tipo=qrcode';
            
            //layout
            include_once(dirname(__FILE__) . '/cupom_qrcode.php'); 
        }
	
        public function is_valid_for_use() {
            if ( ! in_array( get_woocommerce_currency(), apply_filters( 'woocommerce_loja5_woo_cielo_webservice_qrcode_supported_currencies', array( 'BRL' ) ) ) ) return false;
            return true;
        }
		
		public function instalar_mysql_cielo_webservice(){
            global $wpdb;
            $wpdb->query("CREATE TABLE IF NOT EXISTS `wp_cielo_api_loja5` (
            `id` int(10) NOT NULL AUTO_INCREMENT,
			`metodo` varchar(40) NOT NULL,
			`id_pagamento` varchar(40) NOT NULL,
            `tid` varchar(40) NOT NULL,
            `pedido` varchar(40) NOT NULL,
            `bandeira` varchar(40) NOT NULL,
            `parcela` varchar(40) NOT NULL,
            `lr` varchar(20) NOT NULL,
			`lr_log` varchar(180) NOT NULL,
            `total` float(10,2) NOT NULL,
            `status` varchar(40) NOT NULL,
            `bin` varchar(40) NOT NULL,
			`link` varchar(255) NOT NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");
        }
	
        public function get_status_pagamento(){
            if(function_exists('wc_get_order_statuses')){
                return wc_get_order_statuses();
            }else{
                $taxonomies = array( 
                    'shop_order_status',
                );
                $args = array(
                    'orderby'       => 'name', 
                    'order'         => 'ASC',
                    'hide_empty'    => false, 
                    'exclude'       => array(), 
                    'exclude_tree'  => array(), 
                    'include'       => array(),
                    'number'        => '', 
                    'fields'        => 'all', 
                    'slug'          => '', 
                    'parent'         => '',
                    'hierarchical'  => true, 
                    'child_of'      => 0, 
                    'get'           => '', 
                    'name__like'    => '',
                    'pad_counts'    => false, 
                    'offset'        => '', 
                    'search'        => '', 
                    'cache_domain'  => 'core'
                ); 
                foreach(get_terms( $taxonomies, $args ) AS $status){
                    $s[$status->slug] = __( $status->slug, 'woocommerce' );
                }
                return $s;
            }
        }
	
        public function admin_options() {
            ?>
            <?php if ( $this->is_valid_for_use() ) : ?>
                <table class="form-table">
                <?php
                    $this->generate_settings_html();
                ?>
                </table>
            <?php else : ?>
                <div class="inline error"><p><strong><?php _e( 'Gateway Desativado', 'woocommerce' ); ?></strong>: <?php _e( 'Cielo Webservice n&atilde;o aceita o tipo e moeda de sua loja, apenas BRL.', 'woocommerce' ); ?></p></div>
            <?php
                endif;
        }

        public function init_form_fields() {
            //especifico por versao
			if(file_exists(CIELO_WEBSERVICE_WOO_PATH.'/include/licenciamento.php')){
				include_once(CIELO_WEBSERVICE_WOO_PATH.'/include/licenciamento.php');
				$this->form_fields = gateway_woo_cielo_webservice_loja5_licensa_qrcode($this);
			}else{
				$file = CIELO_WEBSERVICE_WOO_PATH.'/include/licenciamento.php';
				$this->form_fields = array(
					'titulo_erro' => array(
						'title' => "ERRO DE LICENCIAMENTO",
						'type' 			=> 'hidden',
						'description' => "Erro no arquivo de licenciamento criptografado, o arquivo $file correspondente ao sistema de licençiamento do plugin não existe no diretorio, não foi enviado ou está sendo removido por sua hospedagem por ser criptografado (adicione o arquivo a lista de falso positivo), siga as instruções <a href='https://loja5.zendesk.com/hc/pt-br/articles/5147862975117-Problema-de-Alerta-de-Arquivos-com-Eval-Base64-no-Wordfence-Sucuri-Cpanel-e-Outros' target='_blank'>clicando aqui</a>.",
						'default' => ''
					),
				);
			}
        }
	
        public function payment_fields() {
            global $woocommerce;
            if(!isset($_GET['pay_for_order'])){
				$total_cart = number_format($this->get_order_total(), 2, '.', '');
				$hash = 'C'.WC()->cart->get_cart_hash();
				$cliente = WC()->cart->get_customer();
				$currency_code = get_woocommerce_currency();
			}else{
				$order_id = wc_get_order_id_by_order_key($_GET['key']);
				$order = wc_get_order( $order_id );
				$total_cart = number_format($order->get_total(), 2, '.', '');
				$hash = 'O'.$order->get_order_key();
				$cliente = $order;
				$currency_code = $order->get_currency();
			}
			$obj = new WC_Gateway_Loja5_Woo_Cielo_Webservice();
			$meios = $obj->meios;
            include(dirname(__FILE__) . '/layout_qrcode.php'); 
        }
	
        public function validate_fields() {
            global $woocommerce;
            if($_POST['payment_method']=='loja5_woo_cielo_webservice_qrcode'){
                $erros = 0;
                if($this->get_post('bandeira')==''){
					$this->tratar_erro("Selecione a bandeira de pagamento!");
					$erros++;
                }
                if($this->get_post('parcela')==''){
					$this->tratar_erro("Selecione o valor a pagar!");
					$erros++;
                }
                if($erros>0){
                    return false;
                }
            }
            return true;
        }
        
        private function get_post( $name ) {
                if (isset($_POST['cielo_webservice_qrcode'][$name])) {
                    return $_POST['cielo_webservice_qrcode'][$name];
                }
                return null;
        }
        
        public function tratar_erro($erro){
            global $woocommerce;
            if(function_exists('wc_add_notice')){
				wc_add_notice($erro,$notice_type = 'error' );
            }else{
				$woocommerce->add_error($erro);
            }
        }
        
        public function process_payment($order_id) {
            global $woocommerce,$wpdb;
			$order = wc_get_order( $order_id );

			//moeda
			$currency_code = $order->get_currency();
			$currency_symbol = get_woocommerce_currency_symbol( $currency_code );

			//keys 
			$afiliacao = trim($this->settings['afiliacao']);
			$chave = trim($this->settings['chave']);
            
            //trata a parcela
            $dados = explode('|',base64_decode($this->get_post('parcela')));
			if(!isset($dados[5])){
				$this->tratar_erro("Ops, problema ao enviar dados de parcelas, tente novamente!");
                return false;
			}
            $parcela = $dados[0];
            $tipo_parcela = $dados[1];
            $total = number_format($dados[2],2,'.','');
            $bandeira = ucfirst(base64_decode($dados[3]));
			if(md5($total.$afiliacao)!=$dados[5]){
				$this->tratar_erro("Ops, valores de parcela divergente do real, tente novamente!");
                return false;
			}
			
			//bloquear bandeiras não aceitas
			$credito = new WC_Gateway_Loja5_Woo_Cielo_Webservice();			
			$bandeiras = $credito->settings['meios'];
			if(!in_array(strtolower(trim($bandeira)), $bandeiras)){
				$this->tratar_erro("Ops, a bandeira selecionada não é aceita na loja, escolha outra e tente novamente!");
                return false;
			}
			
			//valor menor que o real 
			$desconto = CIELO_WEBSERVICE_WOO_DESCONTO_AV_CREDITO;
			$total_pedido_loja = $order->get_total();
			if((float)$desconto > 0){
				$tl = $order->get_total();
				$total_pedido_loja = $tl-(($tl/100)*$desconto);
			}else{
				$total_pedido_loja = $order->get_total();
			}
			if($total < number_format($total_pedido_loja,2,'.','')){
				$this->tratar_erro("Ops, valor do pedido menor que o valor total real, tente novamente!");
                return false;
			}
			
			//ambiente
			if($this->settings['testmode']=='yes'){
				$provider = 'Simulado';
				$urlweb = "https://apisandbox.cieloecommerce.cielo.com.br/1/";
			}else{
				$provider = 'Cielo';
				$urlweb = "https://api.cieloecommerce.cielo.com.br/1/";
			}

			//fix bandeira
			if($bandeira=='Mastercard' || $bandeira=='mastercard'){
				$bandeira = 'Master';
			}

			//headers
			$headers = array(
				"Content-Type" => "application/json",
				"Accept" => "application/json",
				"MerchantId" =>trim($this->settings['afiliacao']),
				"MerchantKey" => trim($this->settings['chave']),
				"RequestId" => "",
			);

			//dados
			$dados = array();
			$dados['MerchantOrderId'] = $order->get_id();
			$dados['Customer'] = array(
				'Name' => $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(),
			);
			$dados['Payment'] = array(
				'Type' => 'qrcode',
				'Amount' => number_format($total, 2, '', ''),
				'Installments' => (int)$parcela,
				'Capture' =>  (($this->settings['captura']=='automatica')?'true':'false'),				
			);
			
			//rest
			include_once(CIELO_WEBSERVICE_WOO_PATH.'/include/restclient.php' );
			$api = new RestClientCielo(array(
				'base_url' => $urlweb, 
				'headers' => $headers, 
			));
			$response = $api->post("sales",json_encode($dados));
			$dados_pedido = @json_decode($response->response,true);

			//debug se ativo ou erro de transação
			if ( 'yes' === $this->settings['debug'] || $response->status==401 || $response->status==500 || $response->status==400 || $response->status==404  || $response->status==0 ) {
				$logs = new WC_Logger();
				$logs->add( $this->id, 'Debug Cielo HTTP: '.$response->status );
				$logs->add( $this->id, 'Debug Cielo Enviados: '.print_r($dados,true) );
				$logs->add( $this->id, 'Debug Cielo Recebido: '.print_r($response,true) );
			}
			
			//se credenciais invalidas 
			if($response->status==401){
				$erro = "HTTP ".$response->status." - Cred&ecirc;nciais de integra&ccedil;&atilde;o Cielo inv&aacute;lidas ou n&atilde;o corresponde o ambiente configurado, revise-as!";
				$this->tratar_erro($erro);
				update_post_meta($order_id, 'erro_cielo', $erro);
				return false;
			}elseif($response->status==400 || $response->status==404){
				$erro = "HTTP ".$response->status." - Problema de processamento dos dados junto Cielo, verifique os logs de sua loja para mais detalhes!";
				$this->tratar_erro($erro);
				update_post_meta($order_id, 'erro_cielo', $erro);
				return false;
			}elseif($response->status==500){
				$erro = "HTTP ".$response->status." - Problema de processamento dos dados junto Cielo, erro interno, verifique os logs de sua loja para mais detalhes!";
				$this->tratar_erro($erro);
				update_post_meta($order_id, 'erro_cielo', $erro);
				return false;
			}elseif($response->status==200 || $response->status==201){
				//se erros 
				if(isset($dados_pedido['Code'])){
					$erro = "".$dados_pedido['Code']." - Problema de processamento dos dados junto Cielo: ".$dados_pedido['Message']."";
					$this->tratar_erro($erro);
					update_post_meta($order_id, 'erro_cielo', $erro);
					return false;
				}elseif(isset($dados_pedido[0]['Code'])){
					$erro = "".$dados_pedido[0]['Code']." - Problema de processamento dos dados junto Cielo: ".$dados_pedido[0]['Message']."";
					$this->tratar_erro($erro);
					update_post_meta($order_id, 'erro_cielo', $erro);
					return false;
				}elseif(isset($dados_pedido['Payment']['QrCodeId'])){
					//cria meta com a resposta
					update_post_meta($order_id,'_dados_cielo_api',$dados_pedido);
					update_post_meta($order_id,'_processado_cielo_loja5','true');

					//limpa a session 
					if(isset($_SESSION['session_cielo_loja_5'])){
						unset($_SESSION['session_cielo_loja_5']);
					}
					//cria o pedido para enviar o e-mail 
					//$order->update_status('wc-on-hold');
					
					//cria no banco de dados 
					$wpdb->query("INSERT INTO `wp_cielo_api_loja5` (`id`, `metodo`, `id_pagamento`, `tid`, `pedido`, `bandeira`, `parcela`, `lr`, `lr_log`, `total`, `status`, `bin`, `link`) VALUES (NULL, 'qrcode', '".$dados_pedido['Payment']['PaymentId']."', '".$dados_pedido['Payment']['QrCodeId']."', '".$order->get_id()."', '".ucfirst($bandeira)."', '".$parcela."', '".(isset($dados_pedido['Payment']['ReturnCode'])?$dados_pedido['Payment']['ReturnCode']:'')."', '".(isset($dados_pedido['Payment']['ReturnMessage'])?$dados_pedido['Payment']['ReturnMessage']:'')."', '".$total."', '".$dados_pedido['Payment']['Status']."', '', '');");
					
					//cria uma nota no pedido
					$log = "Transa&ccedil;&atilde;o QrCode Cielo - ID ".$dados_pedido['Payment']['PaymentId']." em ".$parcela."x no ".ucfirst($bandeira)."";
					$order->add_order_note($log);	 

					//limpa o carrinho
					WC()->cart->empty_cart();
						
					//se precisar autenticar
					$urlAutenticacaoLink = $this->get_return_url( $order );
					
					//reduz um estoque se aprovado ou autorizado
					if(CIELO_WEBSERVICE_WOO_BAIXA_STOCK && ($dados_pedido['Payment']['Status']==1 || $dados_pedido['Payment']['Status']==2)){
						wc_reduce_stock_levels( $order->get_id() );
					}
					
					//cria um desconto ou juros no pedido 
					$total_real = $order->get_total();
					if($total < $total_real && $desconto > 0){
						//desconto
						$descontos = ($total_real-$total);
						wc_order_add_discount_tax_cielo( $order_id, 'Desconto', -$descontos );
					}elseif(($total-$total_real) > 0.10){
						//juros
						$juros = ($total-$total_real);
						wc_order_add_discount_tax_cielo( $order_id, 'Juros', $juros );
					}
					
					return array(
						'result' 	=> 'success',
						'redirect'	=>  $urlAutenticacaoLink
					);
					
				}elseif($response->status==201 && !isset($dados_pedido['Payment']['Tid'])){
					$erro = "".$response->status." - Problema de processamento dos dados junto Cielo desconhecido, suas credenciais de produção encontra-se bloqueadas junto a Cielo, contate a Cielo para resolução.";
					$this->tratar_erro($erro);
					update_post_meta($order_id, 'erro_cielo', $erro);
					return false;
				}else{
					$erro = "".$response->status." - Problema de processamento dos dados junto Cielo desconhecido, tente novamente e se persistir contate o suporte da loja.";
					$this->tratar_erro($erro);
					update_post_meta($order_id, 'erro_cielo', $erro);
					return false;
				}
			}else{
				$erro = "HTTP ".$response->status." - Problema de processamento dos dados junto Cielo desconhecido, tente novamente e se persistir contate o suporte da loja.";
				$this->tratar_erro($erro);
				update_post_meta($order_id, 'erro_cielo', $erro);
				return false;
			}
        }
            
        public function obj2array($obj){
            return json_decode(json_encode($obj),true);
        }
        
        public function json2array($obj){
            return json_decode($obj,true);
        }
        
        public function restore_order_stock($order_id) {
			$order = wc_get_order( $order_id );
			if ( ! get_option('woocommerce_manage_stock') == 'yes' && ! sizeof( $order->get_items() ) > 0 ) {
				wc_increase_stock_levels($order_id);
				return;
			}
		}
    }    
?>