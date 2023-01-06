<?php
    class WC_Gateway_Loja5_Woo_Cielo_Webservice_Pix extends WC_Payment_Gateway {
        
        public function __construct() {
            global $woocommerce;
            $this->id           = 'loja5_woo_cielo_webservice_pix';
            $this->icon         = apply_filters( 'woocommerce_loja5_woo_cielo_webservice_pix', plugins_url().'/loja5-woo-cielo-webservice/images/cielo.png' );
            $this->has_fields   = false;
            $this->supports   = array('products');
			$this->description = true;
            $this->method_description = __( 'Ativa o pagamento por Pix via Cielo.', 'loja5-woo-cielo-webservice' );
            $this->method_title = 'Cielo API - Pix';
            $this->init_settings();
            $this->init_form_fields();
			$this->instalar_mysql_cielo_webservice();
			if(!isset($this->title) || empty($this->title)){
			$this->title = 'informe o titulo';
		}
            
            foreach ( $this->settings as $key => $val ) $this->$key = $val;
            
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array( &$this, 'process_admin_options' ) );
			add_action( 'woocommerce_email_after_order_table', array( $this, 'email_instructions' ), 10, 2 );
			add_action( 'woocommerce_order_details_after_order_table', array( $this, 'segunda_via_pix' ), 10, 1 );
            add_action( 'woocommerce_thankyou_' . $this->id, array( $this, 'thankyou_page' ) );
            
            if ( !$this->is_valid_for_use() ) $this->enabled = false;
        }
		
		public function segunda_via_pix($order){
			if ( 'loja5_woo_cielo_webservice_pix' !== $order->get_payment_method() ) {
				return;
			}
			$data = get_post_meta( $order->get_id(), '_dados_cielo_api', true );
			if(!$data){
				return;
			}
			if(isset($data['Payment']['QrCodeString']) && !empty($data['Payment']['QrCodeString'])){
				$html = '<div>';
				$html .= '<p class="order_details">';
				$html .= 'Pague o pix a data de vencimento para que possamos enviar o(s) item(s) de seu pedido.<br />';
				if(!empty($data['Payment']['ProofOfSale'])){
					$html .= '' . sprintf( __( '<b>Transa&ccedil;&atilde;o:</b> %s', 'loja5-woo-cielo-webservice' ), $data['Payment']['ProofOfSale'] ) . '<br />';
				}
				if(!empty($data['Payment']['QrCodeString'])){
					$html .= '' . sprintf( __( '<b>Pix Copiar/Colar:</b> %s', 'loja5-woo-cielo-webservice' ), $data['Payment']['QrCodeString'] ) . '<br />';
				}
				$html .= '</p>';
				$html .= '</div>';
				echo $html;
			}
		}
		
		public function email_instructions( $order, $sent_to_admin ) {
			if ( $sent_to_admin || 'on-hold' !== $order->get_status() || 'loja5_woo_cielo_webservice_pix' !== $order->get_payment_method() ) {
				return;
			}
			$data = get_post_meta( $order->get_id(), '_dados_cielo_api', true );
			if(!$data){
				return;
			}
			if(isset($data['Payment']['QrCodeString']) && !empty($data['Payment']['QrCodeString'])){
				$html = '<h2>' . __( 'Pagamento', 'loja5-woo-cielo-webservice' ) . '</h2>';
				$html .= '<p class="order_details">';
				$html .= 'Pague o pix a data de vencimento para que possamos enviar o(s) item(s) de seu pedido.<br />';
				if(!empty($data['Payment']['ProofOfSale'])){
					$html .= '' . sprintf( __( '<b>Transa&ccedil;&atilde;o:</b> %s', 'loja5-woo-cielo-webservice' ), $data['Payment']['ProofOfSale'] ) . '<br />';
				}
				if(!empty($data['Payment']['QrCodeString'])){
					$html .= '' . sprintf( __( '<b>Pix Copiar/Colar:</b> %s', 'loja5-woo-cielo-webservice' ), $data['Payment']['QrCodeString'] ) . '<br />';
				}
				$html .= '</p>';
				echo $html;
			}else{
				return;
			}
		}
        
        public function thankyou_page( $order_id ) {
            global $wpdb;
            //pega o pedido
			$order = wc_get_order((int)($order_id));
			
			//custom
			$dados_pedido = $order->get_meta( '_dados_cielo_api' );
			$pix = $order->get_meta( '_dados_cielo_api_pix_qrcode' );
			$img = $order->get_meta( '_dados_cielo_api_pix_img' );
			
            //dados cielo mysql
            $cielo = (array)$wpdb->get_row("SELECT * FROM `wp_cielo_api_loja5` WHERE `pedido` = '".(int)($order_id)."' ORDER BY id DESC;");

			//define o status do pedido
			$status_cielo = isset($cielo['status'])?$cielo['status']:'0';
			switch($status_cielo){
				case '2':
					$status = '<span style="color: #20bb20;">Aprovado</span>';
				break;
				case '1':
					$status = '<span style="color: #2196f3;">Autorizada</span>';
				break;
				case '3':
					$status = '<span style="color: red;">Negada</span>';
				break;
				case '11':
					$status = '<span style="color: red;">Estornado</span>';
				break;
				case '10':
				case '13':
					$status = '<span style="color: red;">Cancelada</span>';
				break;
				default:
					$status = 'Aguardando Pagamento';
			}
			
			//url ver
			$url_ver_qrcode = admin_url('admin-ajax.php').'?action=retorno_qrcode_cielo_webservice&tipo=pix';
            
            //layout
            include_once(dirname(__FILE__) . '/cupom_pix.php'); 
        }
	
        public function is_valid_for_use() {
            if ( ! in_array( get_woocommerce_currency(), apply_filters( 'woocommerce_loja5_woo_cielo_webservice_pix_supported_currencies', array( 'BRL' ) ) ) ) return false;
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
				$this->form_fields = gateway_woo_cielo_webservice_loja5_licensa_pix($this);
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
		
		public function limpar($str) {
			$replaces = array(
				'S'=>'S', 's'=>'s', 'Z'=>'Z', 'z'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
				'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
				'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
				'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
				'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y'
			);
			$trin = preg_replace('/\s\s+/', ' ',preg_replace('/[^0-9A-Za-z ]/', '', strtoupper(strtr(trim($str), $replaces))));
			return trim($trin);
		}
	
        public function payment_fields() {
            global $woocommerce;
            if(!isset($_GET['pay_for_order'])){
				$total_cart = number_format($this->get_order_total(), 2, '.', '');
			}else{
				$order_id = wc_get_order_id_by_order_key($_GET['key']);
				$order = wc_get_order( $order_id );
				$total_cart = number_format($order->get_total(), 2, '.', '');
			}
            include(dirname(__FILE__) . '/layout_pix.php'); 
        }
	
        public function validate_fields() {
            global $woocommerce;
            if($_POST['payment_method']=='loja5_woo_cielo_webservice_pix'){
                $erros = 0;
                if($this->get_post('fiscal')==''){
					$this->tratar_erro("Informe um CPF/CNPJ v&aacute;lido!");
					$erros++;
				}
				$cpf_cnpj = new ValidaCPFCNPJCielo($this->get_post('fiscal'));
				if(!$cpf_cnpj->valida()){
					$this->tratar_erro("O CPF/CNPJ n&atilde;o &eacute; v&aacute;lido!");
					$erros++;
				}
                if($erros>0){
                    return false;
                }
            }
            return true;
        }
        
        private function get_post( $name ) {
                if (isset($_POST['cielo_webservice_pix'][$name])) {
                    return $_POST['cielo_webservice_pix'][$name];
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
			//valida o total do carrinho com o total do pedido 
			$order_total = $order->get_total();
			if($this->get_post('hash_total_cielo')!=sha1(number_format($order_total, 2, '.', ''))){
				$erro = 'O valor do carrinho (R$'.$this->get_post('total_cielo').') esta divergente do valor total do pedido ('.number_format($order_total, 2, '.', '').'), atualize a página de tente novamente!';
				update_post_meta($order_id, 'erro_cielo', $erro);
				$this->tratar_erro($erro);
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
			$fiscal_valor = preg_replace('/\D/', '', $this->get_post('fiscal'));
			$dados['Customer'] = array(
				'Name'=>$this->limpar($order->get_billing_first_name()) . ' ' . $this->limpar($order->get_billing_last_name()),
				"Identity" => $fiscal_valor,
				'Email'=>$order->get_billing_email(),
				"IdentityType" => (strlen($fiscal_valor)==11?'CPF':'CNPJ'),
			);
			$dados['Payment'] = array(
				'Type' => 'Pix',
				'Amount' => number_format($order->get_total(), 2, '', ''),
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
				}elseif(isset($dados_pedido['Payment']['QrCodeString'])){
					//cria meta com a resposta
					update_post_meta($order_id,'_dados_cielo_api',$dados_pedido);
					update_post_meta($order_id,'_processado_cielo_loja5','true');
					update_post_meta($order_id,'_dados_cielo_api_pix_qrcode',$dados_pedido['Payment']['QrCodeString']);
					update_post_meta($order_id,'pix_copiar_colar',$dados_pedido['Payment']['QrCodeString']);
					update_post_meta($order_id,'_dados_cielo_api_pix_img',$dados_pedido['Payment']['QrCodeBase64Image']);

					//cria o pedido para enviar o e-mail 
					//$order->update_status('wc-on-hold');
					
					//cupom
					$urlAutenticacaoLink = $this->get_return_url( $order );
					
					//cria no banco de dados 
					$wpdb->query("INSERT INTO `wp_cielo_api_loja5` (`id`, `metodo`, `id_pagamento`, `tid`, `pedido`, `bandeira`, `parcela`, `lr`, `lr_log`, `total`, `status`, `bin`, `link`) VALUES (NULL, 'boleto', '".$dados_pedido['Payment']['PaymentId']."', '".(isset($dados_pedido['Payment']['ProofOfSale'])?$dados_pedido['Payment']['ProofOfSale']:'')."', '".$order->get_id()."', 'Pix', '1', '".(isset($dados_pedido['Payment']['ReturnCode'])?$dados_pedido['Payment']['ReturnCode']:'')."', '".(isset($dados_pedido['Payment']['ReturnMessage'])?$dados_pedido['Payment']['ReturnMessage']:'')."', '".$order->get_total()."', '0', '', '".$urlAutenticacaoLink."');");
					
					//cria uma nota no pedido
					$log = "Transa&ccedil;&atilde;o Pix Cielo - ".$dados_pedido['Payment']['PaymentId']."";
					$order->add_order_note($log);
					update_post_meta($order_id, 'log_cielo', $log);

					//limpa o carrinho
					WC()->cart->empty_cart();
					
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