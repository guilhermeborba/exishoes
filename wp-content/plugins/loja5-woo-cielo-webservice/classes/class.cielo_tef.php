<?php
    class WC_Gateway_Loja5_Woo_Cielo_Webservice_TEF extends WC_Payment_Gateway {
        
        public function __construct() {
            global $woocommerce;
            $this->id           = 'loja5_woo_cielo_webservice_tef';
            $this->icon         = apply_filters( 'woocommerce_loja5_woo_cielo_webservice_tef', plugins_url().'/loja5-woo-cielo-webservice/images/cielo.png' );
            $this->has_fields   = false;
            $this->supports   = array('products');
            $this->description = true;
			$this->method_description = __( 'Ativa o pagamento por Transferência Online via Cielo.', 'loja5-woo-cielo-webservice' );
            $this->method_title = 'Cielo API - TEF';
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
            
            //layout
            include_once(dirname(__FILE__) . '/cupom_tef_boleto.php'); 
        }
	
        public function is_valid_for_use() {
            if ( ! in_array( get_woocommerce_currency(), apply_filters( 'woocommerce_loja5_woo_cielo_webservice_tef_supported_currencies', array( 'BRL' ) ) ) ) return false;
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
				$this->form_fields = gateway_woo_cielo_webservice_loja5_licensa_tef($this);
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
			}else{
				$order_id = wc_get_order_id_by_order_key($_GET['key']);
				$order = wc_get_order( $order_id );
				$total_cart = number_format($order->get_total(), 2, '.', '');
			}
            include(dirname(__FILE__) . '/layout_tef.php'); 
        }
	
        public function validate_fields() {
            global $woocommerce;
            if($_POST['payment_method']=='loja5_woo_cielo_webservice_tef'){
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
                if($this->get_post('bandeira')==''){
					$this->tratar_erro("Selecione o banco qual deseja concluir o pagamento!");
					$erros++;
                }
                if($erros>0){
                    return false;
                }
            }
            return true;
        }
        
        private function get_post( $name ) {
                if (isset($_POST['cielo_webservice_tef'][$name])) {
                    return $_POST['cielo_webservice_tef'][$name];
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
		
		public function limpar($str) {
			$replaces = array(
				'S'=>'S', 's'=>'s', 'Z'=>'Z', 'z'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
				'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
				'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
				'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
				'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y'
			);
			
			return preg_replace('/[^0-9A-Za-z;,.\- ]/', '', strtoupper(strtr(trim($str), $replaces)));
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
			
			//bandeira
			$bandeira = $this->get_post('bandeira');

			//ambiente
			if($this->settings['testmode']=='yes'){
				$provider = 'Simulado';
				$urlweb = "https://apisandbox.cieloecommerce.cielo.com.br/1/";
			}else{
				$provider = 'Cielo';
				$urlweb = "https://api.cieloecommerce.cielo.com.br/1/";
			}
			
			//header
			$headers = array(
				"Content-Type" => "application/json",
				"Accept" => "application/json",
				"MerchantId" =>trim($this->settings['afiliacao']),
				"MerchantKey" => trim($this->settings['chave']),
				"RequestId" => "",
			);

			//custom cobranca
			$celular = $order->get_meta( '_billing_cellphone' );
			$num_cob = $order->get_meta( '_billing_number' );
			$bairro_cob = $order->get_meta( '_billing_neighborhood' );
			$cpf_cob = preg_replace('/\D/', '', $order->get_meta( '_billing_cpf' ));
			$cnpj_cob = preg_replace('/\D/', '', $order->get_meta( '_billing_cnpj' ));
			
			//trata o telefone
			$telefone = '';
			if($order->get_billing_phone()!=""){
				$telefone = preg_replace('/\D/', '', $order->get_billing_phone());
			}elseif(!empty($celular)){
				$telefone = preg_replace('/\D/', '', $celular);
			}

			//dados
			$dados = array();
			$dados['MerchantOrderId'] = $order->get_id();
			$fiscal_valor = preg_replace('/\D/', '', $this->get_post('fiscal'));
			$dados['Customer'] = array(
				'Name'=>$this->limpar($order->get_billing_first_name()) . ' ' . $this->limpar($order->get_billing_last_name()),
				"Identity" => $fiscal_valor,
				'Email'=>$order->get_billing_email(),
				"IdentityType" => (strlen($fiscal_valor)==11?'CPF':'CNPJ'),
				'Address'=>array(
					'Street'=>$this->limpar($order->get_billing_address_1()),
					'Number'=>(isset($num_cob)?$num_cob:'*'),
					'District'=>$this->limpar((isset($bairro_cob)?$bairro_cob:$order->get_billing_address_2())),
					'Complement' => $this->limpar((!empty($bairro_cob)?$order->get_billing_address_2():'')),
					'ZipCode'=>preg_replace('/\D/', '', $order->get_billing_postcode()),
					'City'=>$this->limpar($order->get_billing_city()),
					'State'=>$this->limpar($order->get_billing_state()),
					'Country'=>substr($order->get_billing_country(),0,2),
				)
			);
			$dados['Payment'] = array(
				'Type' => 'EletronicTransfer',
				'Provider' => $bandeira,
				'Amount' => number_format($order->get_total(), 2, '', ''),
				'ReturnUrl' => admin_url('admin-ajax.php').'?tipo=tef&action=retorno_debito_cielo_webservice',
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
				}elseif(isset($dados_pedido['Payment']['PaymentId'])){
					//cria meta com a resposta
					update_post_meta($order_id,'_dados_cielo_api',$dados_pedido);
					update_post_meta($order_id,'_processado_cielo_loja5','true');

					//cria o pedido para enviar o e-mail 
					$order->update_status('wc-on-hold');
					
					//cria no banco de dados 
					$wpdb->query("INSERT INTO `wp_cielo_api_loja5` (`id`, `metodo`, `id_pagamento`, `tid`, `pedido`, `bandeira`, `parcela`, `lr`, `lr_log`, `total`, `status`, `bin`, `link`) VALUES (NULL, 'tef', '".$dados_pedido['Payment']['PaymentId']."', '".(isset($dados_pedido['Payment']['Tid'])?$dados_pedido['Payment']['Tid']:'')."', '".$order->get_id()."', '".ucfirst($bandeira)."', '1', '".(isset($dados_pedido['Payment']['ReturnCode'])?$dados_pedido['Payment']['ReturnCode']:'')."', '".(isset($dados_pedido['Payment']['ReturnMessage'])?$dados_pedido['Payment']['ReturnMessage']:'')."', '".$order->get_total()."', '".$dados_pedido['Payment']['Status']."', '', '".(isset($dados_pedido['Payment']['Url'])?$dados_pedido['Payment']['Url']:'')."');");
					
					//cria uma nota no pedido
					$log = "Transa&ccedil;&atilde;o TEF Cielo - ID ".$dados_pedido['Payment']['PaymentId']." ".ucfirst($bandeira)."";
					$order->add_order_note($log);
					update_post_meta($order_id, 'log_cielo', $log);

					//limpa o carrinho
					WC()->cart->empty_cart();
						
					//se precisar autenticar
					$urlAutenticacaoLink = $this->get_return_url( $order );
					
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