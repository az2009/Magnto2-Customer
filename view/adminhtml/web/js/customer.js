require([
    'jquery',    
    'mage/translate',
    'Jbp_Customer/js/jqueryMask',
    'jquery/validate',
], function($,$t){
	
	/*Phone*/
		$('input[name="telephone"], input[name="cellphone"], input[name="fax"]').mask('(99) 9999-9999?9');		
	/*Phone*/
	
	/*Postcode*/
		$('input[name="postcode"]').mask('99999-999');
	
		$('input[name="address[6][postcode]"]').mask('99999-999');
		
		$('body').on('focusout','input[name="postcode"]',function(){		
			var postcode = $(this).val();
			var postcode = $(this);
			var postcodeval = postcode.val().match(/[0-9]/g).join([]);
			if(postcodeval.length == 8) {
				$.ajax({
					url   : '/jbpcustomer/findaddress/index',
					data  : {postcode:postcodeval},
					type : 'POST',
					dataType : 'json',
					cache:false,
					beforeSend: function(){
						
					},
					success: function(response){						
						$('#street_1, .street_0 input').val(response.logradouro).trigger('keyup');
						$('#street_4, .street_3 input').val(response.bairro).trigger('keyup');
						$('input[name="city"]').val(response.cidade).trigger('keyup');
						$('select[name="region_id"]').val(response.uf).trigger('change');
						removeErrorInput(postcode);
					},
					error:function(response){						
						addErrorInput(postcode, $t(JSON.parse(response.responseText)).error);
					}
				});
			}
		});
	/*Postcode*/
		
	/*Taxvat*/
		
		$.validator.addMethod('validate-taxvat', function (value) 
		{ 
			return validaCPF(value);
					
		}, $t('Document Invalid'));
		
		
	/*Taxvat*/	
	
	/*Person Type*/
		
		$('input[name="typeperson"]').each(function(){
			var val = $(this).val();
			if(val == 1){
				showTypePersonLegal();
			}else{
				showTypePersonNatural();
			}
		});
		
		$('input[name="typeperson"]').change(function(){
			var val = $(this).val();
			if(val == 1){
				showTypePersonLegal();
			}else{
				showTypePersonNatural();
			}
		});
		
		function showTypePersonNatural()
		{
			$('input[name="firstname"]').attr('placeholder', $t('Nome')).closest('div.control').prev('label').children('span').text($t('Nome'));
			$('input[name="lastname"]').attr('placeholder',$t('Sobrenome')).closest('div.control').prev('label').children('span').text($t('Sobrenome'));
			$('input[name="taxvat"]').attr('placeholder',$t('CPF')).closest('div.control').prev('label').children('span').text($t('CPF'));
			$('input[name="rg_stateenrollment"]').attr('placeholder',$t('RG')).closest('div.control').prev('label').children('span').text($t('RG'));
			$('input[name="dob"], select[name="gender"], .customer-dob').show().closest('div.control').prev('label').show();
			$('input[name="taxvat"]').mask('999.999.999-99');
		}
		function showTypePersonLegal()
		{
			
			$('input[name="firstname"]').attr('placeholder',$t('Razão Social')).closest('div.control').prev('label').children('span').text($t('Razão Social'));
			$('input[name="lastname"]').attr('placeholder',$t('Nome Fantasia')).closest('div.control').prev('label').children('span').text($t('Nome Fantasia'));
			$('input[name="taxvat"]').attr('placeholder',$t('CNPJ')).closest('div.control').prev('label').children('span').text($t('CNPJ'));
			$('input[name="rg_stateenrollment"]').attr('placeholder',$t('Inscrição Estadual')).closest('div.control').prev('label').children('span').text($t('Inscrição Estadual'));
			$('input[name="dob"], select[name="gender"], .customer-dob').hide().closest('div.control').prev('label').hide();
			$('input[name="taxvat"]').mask('99.999.999/9999-99');
		}
	/*Person Type*/
	
	/*Taxvat*/
		function validaCPF(cpf){

			 var pType = 0;
			
			 var cpf_filtrado = "", valor_1 = " ", valor_2 = " ", ch = "";
			 var valido = false;

			 for (i = 0; i < cpf.length; i++){
			 ch = cpf.substring(i, i + 1);
			 if (ch >= "0" && ch <= "9"){
			 cpf_filtrado = cpf_filtrado.toString() + ch.toString();
			 valor_1 = valor_2;
			 valor_2 = ch;
			 }
			 if ((valor_1 != " ") && (!valido)) valido = !(valor_1 == valor_2);
			 }

			 if (!valido) cpf_filtrado = "12345678912";

			 if (cpf_filtrado.length < 11){
			 for (i = 1; i <= (11 - cpf_filtrado.length); i++){cpf_filtrado = "0" + cpf_filtrado;}
			 }

			 if(pType <= 1){
			 if ( ( cpf_filtrado.substring(9,11) == checkCPF( cpf_filtrado.substring(0,9) ) ) && ( cpf_filtrado.substring(11,12)=="") ){return true;}
			 }

			 if((pType == 2) || (pType == 0)){
			 if (cpf_filtrado.length >= 14){
			 if ( cpf_filtrado.substring(12,14) == checkCNPJ( cpf_filtrado.substring(0,12) ) ){ return true;}
			 }
			 }

			 return false;
		}

		function checkCNPJ(vCNPJ){
				 var mControle = "";
				 var aTabCNPJ = new Array(5,4,3,2,9,8,7,6,5,4,3,2);
				 for (i = 1 ; i <= 2 ; i++){
				 mSoma = 0;
				 for (j = 0 ; j < vCNPJ.length ; j++)
				 mSoma = mSoma + (vCNPJ.substring(j,j+1) * aTabCNPJ[j]);
				 if (i == 2 ) mSoma = mSoma + ( 2 * mDigito );
				 mDigito = ( mSoma * 10 ) % 11;
				 if (mDigito == 10 ) mDigito = 0;
				 mControle1 = mControle ;
				 mControle = mDigito;
				 aTabCNPJ = new Array(6,5,4,3,2,9,8,7,6,5,4,3);
				 }
				 return( (mControle1 * 10) + mControle );
		}

		function checkCPF(vCPF){
				 var mControle = "";
				 var mContIni = 2, mContFim = 10, mDigito = 0;
				 for (j = 1 ; j <= 2 ; j++){
				 mSoma = 0;
				 for (i = mContIni ; i <= mContFim ; i++)
				 mSoma = mSoma + (vCPF.substring((i-j-1),(i-j)) * (mContFim + 1 + j - i));
				 if (j == 2 ) mSoma = mSoma + ( 2 * mDigito );
				 mDigito = ( mSoma * 10 ) % 11;
				 if (mDigito == 10) mDigito = 0;
				 mControle1 = mControle;
				 mControle = mDigito;
				 mContIni = 3;
				 mContFim = 11;
				 }
				 return( (mControle1 * 10) + mControle );
		}
	/*Taxvat*/
	
	/*General*/
		function addErrorInput(elem, message){
			$('div#'+elem.attr('name')).remove();
			elem.addClass('mage-error').attr('generated',true);
			elem.after('<div id="'+elem.attr('name')+'" class="mage-error" generated="true">'+ message + '</div>');			
		}
		
		function removeErrorInput(elem){
			elem.removeClass('mage-error').removeAttr('generated');
			$('div#'+elem.attr('name')).remove();
		}		
	/**/
		
});