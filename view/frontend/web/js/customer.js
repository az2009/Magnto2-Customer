require([
    'jquery',
], function($){
	$(function(){
		
		$('input[name="postcode"]').mask('99999-999');
		$('input[name="postcode"]').keypress(function(){
			var postcode = $(this).val();
			if(postcode.length == 8) {
				$.ajax({
					url   : '/jbpcustomer/findaddress/index',
					data  : {postcode:postcode},
					type : 'POST',
					dataType : 'json',
					cache:false,
					beforeSend: function(){
						
					},
					success: function(response){
						
						console.log(response);
						
						var logradouro = response.logradouro;
						var bairro = response.bairro;
						var cidade = response.cidade;
						var uf = response.uf;
						
						$('#street_1').val(logradouro);
						$('#street_4').val(bairro);
						$('#city').val(cidade);
						$('#region_id option:eq('+uf+')').prop('selected',true);
						
						
					},
					error:function(response){
						
					}
				});
			}
		});
	});
});