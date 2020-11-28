jQuery(function ($) {
	$('.tokenize-callable-cid').tokenize2({
		searchMinLength: 3, // pesquisa com minimo de 2 caracteres
		placeholder: 'CID',
		dataSource: function(search, object){
			$.ajax($('#baseUrlDefault').data('url') + 'getCid/',
			{
				data: { search: search, start: 0},
				dataType: 'json',
				success: function(data){
					var $items = [];
					$.each(data, function(k, v){
						console.log('kkkkk',k);
						console.log('vvvvv',v);
						$items.push(v);
					});
					console.log('$items',$items);
					object.trigger('tokenize:dropdown:fill', [$items]);
				},
                error:function(){
                    object.trigger('tokenize:dropdown:fill', [[]]);
                }
			});
		}
	});



	$('.tokenize-cid-agendamento').tokenize2({
		searchMinLength: 3,
		placeholder: 'CID',
		tokensMaxItems: 1, // permitir apenas  1 cid
		dataSource: function(search, object){
			$.ajax($('#baseUrlDefault').data('url') + 'getCid/',
				{
					data: { search: search, start: 0},
					dataType: 'json',
					success: function(data){
						var $items = [];
						$.each(data, function(k, v){
							$items.push(v);
						});
						object.trigger('tokenize:dropdown:fill', [$items]);
					},
					error:function(){
						object.trigger('tokenize:dropdown:fill', [[]]);
					}
				});
		},
		tokenChange: function (element){
			var arrVal = $(element).val();
			if(arrVal == null || arrVal.length == 0 || arrVal[0] == 'undefined'){
				$(element).trigger('tokenize:clear');
			}
            $('#AgendamentoCidId').change();
		}
	});

			
});
