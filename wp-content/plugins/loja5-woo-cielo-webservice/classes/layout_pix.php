<?php
if ( ! defined( 'ABSPATH' ) ) {
exit;
}
//se pf ou pj
$fiscal = '';
if(isset($_REQUEST['post_data']) && !empty($_REQUEST['post_data'])){
	parse_str(trim($_REQUEST['post_data']), $output);
	if(isset($output['billing_cpf']) && !empty($output['billing_cpf'])){
		$fiscal = preg_replace('/\D/', '',$output['billing_cpf']);
	}elseif(isset($output['billing_cnpj']) && !empty($output['billing_cnpj'])){
		$fiscal = preg_replace('/\D/', '',$output['billing_cnpj']);
	}
}
?>

<div id="tela-cielo-webservice-pix" style="width:100%;">

<p style="margin-bottom: 5px;">Conclua seu pagamento via Pix e lembre-se de pagar o mesmo ao finalizar o pedido na loja.</p>

<input type="hidden" name="cielo_webservice_pix[hash_total_cielo]" value="<?php echo sha1(number_format($total_cart, 2, '.', ''));?>">
<input type="hidden" name="cielo_webservice_pix[total_cielo]" value="<?php echo number_format($total_cart, 2, '.', '');?>">

<fieldset class="wc-credit-card-form wc-payment-form">

<?php if(strlen($fiscal)==11 || strlen($fiscal)==14){ ?>

<input type="hidden" id="fiscal-cielo-webservice" name="cielo_webservice_pix[fiscal]" value="<?php echo $fiscal;?>">

<?php }else{ ?>

<p class="form-row form-row-wide woocommerce-validated campos_cielo_webservice">
<label style="padding: 5px 0 5px 5px;">CPF/CNPJ:</label>
<input style="box-shadow: inset 2px 0 0 #0f834d;height:40px;background-color: #f2f2f2;"  onkeyup="this.value=this.value.replace(/[^\d]/,'')" maxlength="14" type="text" class="input-text mascaras_campos_cielo_webservice" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" placeholder="CPF ou CNPJ" id="fiscal-cielo-webservice-pix" name="cielo_webservice_pix[fiscal]" value="<?php echo $fiscal;?>">
</p>

<?php } ?>

</fieldset>

</div>	