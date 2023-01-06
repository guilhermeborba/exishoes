<div style="padding: 10px;
    border: 1px solid #CCC;
    border-radius: 5px;" class="tela_conclusao_pix">

<p>Sua transa&ccedil;&atilde;o relacionada ao pedido <b>#<?php echo $order_id;?></b> foi processada junto a operadora, agora conclua o seu pagamento via o App de seu banco preferido, caso esteja finalizado via Mobile use o c&oacute;digo copiar/colar exibido abaixo para pagamento.</p>

<?php echo '<b>Status:</b>  '.strtoupper($status).'<br>';?>
<?php echo '<b>Transa&ccedil;&atilde;o:</b>  '.$cielo['tid'].'<br>';?>
<?php echo '<b>Meio:</b> Pix / &agrave; vista<br>';?>
<?php echo '<b>ID do Pagamento:</b>  '.$cielo['id_pagamento'].'<br>';?>

<p><b>QrCode Pix:</b><br>
<img style="border:1px solid #CCC; border-radius:5px;" src="<?php echo home_url( '/' );?>wp-content/plugins/loja5-woo-cielo-webservice/pix.php?codigo=<?php echo $pix;?>">
</p>

<p><b>Copiar/colar:</b><br>
<code title="Clique para copiar!" id="selectablepix" onclick="selectText()"><?php echo $pix;?></code></p>

</div>
<input type="hidden" id="pix-copiar-colar" value="<?php echo $pix;?>">
<script>
	function selectText() {
		copyToClipboard();
		var containerid = 'selectablepix';
		if (document.selection) { // IE
			var range = document.body.createTextRange();
			range.moveToElementText(document.getElementById(containerid));
			range.select();
		} else if (window.getSelection) {
			var range = document.createRange();
			range.selectNode(document.getElementById(containerid));
			window.getSelection().removeAllRanges();
			window.getSelection().addRange(range);
		}
	}
	function copyToClipboard() {
	  var element = jQuery('#pix-copiar-colar');
	  var $temp = jQuery("<input>");
	  jQuery("body").append($temp);
	  $temp.val(element.val()).select();
	  document.execCommand("copy");
	  $temp.remove();
	  console.log('copiado: '+element.val()+'');
	}
    function verificar_pagamento_pix_cielo(){
		//verifica a cada 10 segundos se o qrcode foi pago
		setInterval(function (){
			//consulta a preferencia de pagamento 
			jQuery.ajax({
				method: "POST",
				url: "<?php echo $url_ver_qrcode; ?>",
				dataType: "JSON",
				data: { id: "<?php echo $cielo['id_pagamento'];?>", hash: "<?php echo sha1($cielo['id_pagamento']);?>" }
			}).done(function( json ) {
				if(json.atualizar==true){
					location.reload(true);
				}
			});
		}, 10000);
	}
	verificar_pagamento_pix_cielo();
</script>