$(document).ready(function() {
	$("#tipoCondominio").change(function(){
		if($(this).val() == 'V'){
			document.getElementById('qtdeBlocos').style.display = 'block';
			document.getElementById('qtdeCasas').style.display = 'none';
		} else if($(this).val() == 'H') {
			document.getElementById('qtdeBlocos').style.display = 'none';
			document.getElementById('qtdeCasas').style.display = 'block';
		} else {
			document.getElementById('qtdeBlocos').style.display = 'none';
			document.getElementById('qtdeCasas').style.display = 'none';
		}
	});
	
	$(".resetaSenha").click(function(){
		var id = $(this).attr("id");
		$.ajax({
			 type: "POST",
			 url: "/sgpi/cadastros/usuario/resetar_senha.php?id="+id, /* endereço do script PHP */
			 async: true,
			 data: {id: id}, /* informa Url */
			 success: function(data) { /* sucesso */
				 alert(data);
			 },
			 beforeSend: function() { /* antes de enviar */
				 $('.loading').fadeIn('fast'); 
			 },
			 complete: function(){ /* completo */
				 $('.loading').fadeOut('fast'); //wow!
			 }
		 });
	});
	
	$("table").on("mouseover", "input.nomeCliente", function(event){
		var obj = $(this);
		$(this).autocomplete({
			minLength: 2,
			source: function( request, response ) {
				$.ajax({
					url: "/sgpi/libs/searchCliente.php",
					dataType: "json",
					data: {
						nome: $(this.element).val()
					},
					success: function(data) {
						//alert(data[0].nome);
						response($.map(data, function(item){ 
							return {
								id: item.id, 
								label: item.nome.toUpperCase(), 
								value: item.nome.toUpperCase()
							}
						}));
					}
				});
			},
			select: function( event, ui ) {
				obj.prev().val(ui.item.id);
				carregaImoveis();
			}
		});
	});
	
	/*$( ".nomeCliente" ).on("focus", function(){
		$(this).autocomplete({
			minLength: 2,
			source: function( request, response ) {
				$.ajax({
					url: "/sgpi/libs/searchCliente.php",
					dataType: "json",
					data: {
						nome: $(this.element).val()
					},
					success: function(data) {
						//alert(data[0].nome);
						response($.map(data, function(item){ 
							return {
								id: item.id, 
								label: item.nome.toUpperCase(), 
								value: item.nome.toUpperCase()
							}
						}));
					}
				});
			},
			select: function( event, ui ) {
				$(this).closest(".idCliente").val(ui.item.id);
				carregaImoveis();
			}
		});
	});*/
	
	var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
	(function(){
	var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
	s1.async=true;
	s1.src='https://embed.tawk.to/573a0f7d630a1e2b5eace3e3/default';
	s1.charset='UTF-8';
	s1.setAttribute('crossorigin','*');
	s0.parentNode.insertBefore(s1,s0);
	})();
	
	function limpa_formulário_cep() {
		// Limpa valores do formulário de cep.
		$("#rua").val("");
		$("#bairro").val("");
		$("#cod_cidades").val("");
		$("#cod_estados").val("");
	}

	//Quando o campo cep perde o foco.
	$("#cep").blur(function() {
		//Nova variável "cep" somente com dígitos.
		var cep = $(this).val().replace(/\D/g, '');

		//Verifica se campo cep possui valor informado.
		if (cep != "") {

			//Expressão regular para validar o CEP.
			var validacep = /^[0-9]{8}$/;

			//Valida o formato do CEP.
			if(validacep.test(cep)) {

				//Preenche os campos com "..." enquanto consulta webservice.
				$("#rua").val("...")
				$("#bairro").val("...")
				$("#cod_cidades").val("...")
				$("#cod_estados").val("...")
				
				//Consulta o webservice viacep.com.br/
				$.getJSON("//viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {

					if (!("erro" in dados)) {
						//Atualiza os campos com os valores da consulta.
						$("#rua").val(dados.logradouro);
						$("#bairro").val(dados.bairro);
						//$("#cod_cidades").val(dados.localidade);
						$('#cidade').html('<input type="text" name="cod_cidades" value='+dados.localidade+' />');
						$("#cod_estados").val(dados.uf);
					} //end if.
					else {
						//CEP pesquisado não foi encontrado.
						limpa_formulário_cep();
						alert("CEP não encontrado.");
					}
				});
			} //end if.
			else {
				//cep é inválido.
				limpa_formulário_cep();
				alert("Formato de CEP inválido.");
			}
		} //end if.
		else {
			//cep sem valor, limpa formulário.
			limpa_formulário_cep();
		}
	});

	//Quando o campo cep perde o foco.
	$("#cepConj").blur(function() {
		//Nova variável "cep" somente com dígitos.
		var cep = $(this).val().replace(/\D/g, '');

		//Verifica se campo cep possui valor informado.
		if (cep != "") {

			//Expressão regular para validar o CEP.
			var validacep = /^[0-9]{8}$/;

			//Valida o formato do CEP.
			if(validacep.test(cep)) {

				//Preenche os campos com "..." enquanto consulta webservice.
				$("#ruaConj").val("...")
				$("#bairroConj").val("...")
				$("#cod_cidadesConj").val("...")
				$("#cod_estadosConj").val("...")
				
				//Consulta o webservice viacep.com.br/
				$.getJSON("//viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {

					if (!("erro" in dados)) {
						//Atualiza os campos com os valores da consulta.
						$("#ruaConj").val(dados.logradouro);
						$("#bairroConj").val(dados.bairro);
						//$("#cod_cidades").val(dados.localidade);
						$('#cidadeConj').html('<input type="text" name="cod_cidadesConj" value='+dados.localidade+' />');
						$("#cod_estadosConj").val(dados.uf);
					} //end if.
					else {
						//CEP pesquisado não foi encontrado.
						limpa_formulário_cep();
						alert("CEP não encontrado.");
					}
				});
			} //end if.
			else {
				//cep é inválido.
				limpa_formulário_cep();
				alert("Formato de CEP inválido.");
			}
		} //end if.
		else {
			//cep sem valor, limpa formulário.
			limpa_formulário_cep();
		}
	});

	//Quando o campo cep perde o foco.
	$("#cepPart").blur(function() {
		//Nova variável "cep" somente com dígitos.
		var cep = $(this).val().replace(/\D/g, '');

		//Verifica se campo cep possui valor informado.
		if (cep != "") {

			//Expressão regular para validar o CEP.
			var validacep = /^[0-9]{8}$/;

			//Valida o formato do CEP.
			if(validacep.test(cep)) {

				//Preenche os campos com "..." enquanto consulta webservice.
				$("#ruaPart").val("...")
				$("#bairroPart").val("...")
				$("#cod_cidadesPart").val("...")
				$("#cod_estadosPart").val("...")
				
				//Consulta o webservice viacep.com.br/
				$.getJSON("//viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {

					if (!("erro" in dados)) {
						//Atualiza os campos com os valores da consulta.
						$("#ruaPart").val(dados.logradouro);
						$("#bairroPart").val(dados.bairro);
						//$("#cod_cidades").val(dados.localidade);
						$('#cidadePart').html('<input type="text" name="cod_cidadesPart" value='+dados.localidade+' />');
						$("#cod_estadosPart").val(dados.uf);
					} //end if.
					else {
						//CEP pesquisado não foi encontrado.
						limpa_formulário_cep();
						alert("CEP não encontrado.");
					}
				});
			} //end if.
			else {
				//cep é inválido.
				limpa_formulário_cep();
				alert("Formato de CEP inválido.");
			}
		} //end if.
		else {
			//cep sem valor, limpa formulário.
			limpa_formulário_cep();
		}
	});

	$('.finalizarAtendimento').click(function(){
		if(confirm("Confirma conclusão do atendimento " + $(this).attr("id") + "?")){
			var iden = $(this).attr("id");
			$.getJSON('/sgpi/libs/finalizaAtendimento.ajax.php?search=',{id: iden, ajax: 'true'}, function(j){
				if(j === "OK"){
					alert("Atendimento " + iden + " concluído com sucesso!");
					var today = new Date();
					var dd = today.getDate();
					var mm = today.getMonth()+1;
					var yyyy = today.getFullYear();
					if(dd<10)
						dd='0'+dd
					if(mm<10)
						mm='0'+mm
					var today = dd+'/'+mm+'/'+yyyy;
					$('#dt'+iden).html(today);
					$('#dt'+iden).html(new Date().format('d/m/Y'));
				} else {
					alert("Erro: Não foi possível concluir o atendimento " + iden + "! Contate o administrador do sistema.");
				} 
			});
		}
		
		
		$('#target').submit(function(e) {
			var pattern = /^(\d{1,2})\/(\d{1,2})\/(\d{4})$/;
			var arrayDate = $('#dtPrev').val().match(pattern);
			var date = new Date(arrayDate[3], arrayDate[2] - 1, arrayDate[1]);

			var today = new Date();
			var weekDay = date.getDay();
			today.setHours(0,0,0,0);
			
			if ($('#dtPrev').val() === ''){
				alert('Por favor informe a data de previsão.');
				e.preventDefault();
			} else if (date < today) {
				alert('A data de previsão não pode ser inferior a data atual.');
				e.preventDefault();
			}
		});
		
	});
	
	$("#idCondominio").change(function() {
		var tipoCondominio = $(this).val().split("-")[1];

		if(tipoCondominio === 'V'){
			$(".enderecoImovel").hide();
			$(".tdBloco").css("display", "block");
		} else {
			$(".enderecoImovel").show();
			$(".tdBloco").css("display", "none");
		}
	});
	
	$('.email').blur(function() {
		usuario = $(this).val().substring(0, $(this).val().indexOf("@"));
		dominio = $(this).val().substring($(this).val().indexOf("@")+ 1, $(this).val().length);

		if (!(usuario.length >=1) ||
			!(dominio.length >=3) || 
			!(usuario.search("@")==-1) || 
			!(dominio.search("@")==-1) ||
			!(usuario.search(" ")==-1) || 
			!(dominio.search(" ")==-1) ||
			!(dominio.search(".")!=-1) ||      
			!(dominio.indexOf(".") >=1)|| 
			!(dominio.lastIndexOf(".") < dominio.length - 1)) {
			alert("E-mail invalido");
			$(this).val('').focus();
		}
	});
	
	$('.phone1').keyup(function() {
		var data = $(this).val();
		if(data.length == 2)
		{
			data += ') ';
			data = '(' + data;
		}
		if(data.length == 9)
			data += '-';
		$(this).val(data);
	});
	
	$('.phone2').keyup(function() {
		var data = $(this).val();
		if(data.length == 2)
		{
			data += ') ';
			data = '(' + data;
		}
		if(data.length == 6)
			data += ' ';
		if(data.length == 11)
			data += ' ';
		$(this).val(data);
	});
	
	$('.cep').keyup(function() {
		var data = $(this).val();
		if(data.length == 2)
			data += '.';
		if(data.length == 6)
			data += '-';
		$(this).val(data);
	});
	
	$('#cpf').keyup(function() {
		var data = $(this).val();
		if(data.length == 3)
			data += '.';
		if(data.length == 7)
			data += '.';
		if(data.length == 11)
			data += '-';
		$(this).val(data);
	});
	
	$('.data').on({
		keyup: function() { 
			var data = $(this).val();
			if(data.length == 2)
				data += '/';
			if(data.length == 5)
				data += '/';
			$(this).val(data); 
		},
		blur:  function() { 
			if(!/^(0?[1-9]|[12][0-9]|3[01])[\/\-](0?[1-9]|1[012])[\/\-]\d{4}$/.test($(this).val())){
				 alert('Data Inválida!');
				 $(this).val('');
			}
		}
	});
	
	$(".cpf").blur(function() {
		var Soma;
		var Resto;
		Soma = 0;
		var strCPF = $(this).val().replace('.', '').replace('.', '').replace('-','');
		if (strCPF == "00000000000"){
			alert('CPF Inválido!');
			$('#nome').val('');
			$(this).val('').focus();
		} else {
		
			for (i=1; i<=9; i++)
				Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (11 - i);
			Resto = (Soma * 10) % 11;
			
			if ((Resto == 10) || (Resto == 11))  
				Resto = 0;
			if (Resto != parseInt(strCPF.substring(9, 10)) ) {
				alert('CPF Inválido!');
				$('#nome').val('');
				$(this).val('').focus();
			} else {
				Soma = 0;
				for (i = 1; i <= 10; i++) 
					Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (12 - i);
				Resto = (Soma * 10) % 11;
				
				if ((Resto == 10) || (Resto == 11))  
					Resto = 0;
				if (Resto != parseInt(strCPF.substring(10, 11) ) ) {
					alert('CPF Inválido!');
					$('#nome').val('');
					$(this).val('').focus();
				}
				
				$(this).val($(this).val().replace(/(\d{3})(\d{3})(\d{3})(\d{2})/g,"\$1.\$2.\$3\-\$4"));
				
				$('#nome').hide();
				$('.carregando').show();
				$.getJSON('/sgpi/libs/cpfCnpj.ajax.php?search=',{cpf: $(this).val(), ajax: 'true'}, function(j){
					$('#nome').val(j).show();
					$('.carregando').hide();
				});
			}	
		}
	});
	
	$(".cnpj").blur(function() {
		$(this).val($(this).val().replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/g,"\$1.\$2.\$3\/\$4\-\$5"));
	});
	
	$('.cod_estados').change(function(){
		if( $(this).val() ) {
			$('#cidade').hide();
			$('.carregando').show();
			$.getJSON('/sgpi/libs/cidades.ajax.php?search=',{cod_estados: $(this).val(), ajax: 'true'}, function(j){
				var options = '<select name="cod_cidades" id="cod_cidades"><option value=""></option>';	
				for (var i = 0; i < j.length; i++) {
					options += '<option value="' + j[i].nome + '">' + j[i].nome + '</option>';
				}	
				$('#cidade').html(options + "</select>").show();
				$('.carregando').hide();
			});
		} else {
			$('#cidade').html('<select name="cod_cidades" id="cod_cidades"><option value="">– Escolha um estado –</option></select>');
		}
	});
	
	$('.clone').click(function() {
		var $tr    = $(this).closest('.linha');
		var $clone = $tr.clone();
		$clone.find("img").attr("src", "/sgpi/_img/reprovado.png");
		$clone.find("img").click(function(){
			$clone.remove();
		});
		$tr.after($clone);
		$clone.find(':text.formatareais').maskMoney({prefix:'R$ ', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});
	});
	
	$('.datavelha').blur(function(){
		var parts = $(this).val().split('/');
		var dt = new Date(parseInt(parts[2], 10), parseInt(parts[1], 10) - 1, parseInt(parts[0], 10));
		if(dt < new Date()){
			alert('Data Retorno não pode ser anterior a atual!');
			$(this).val('');
			$(this).focus();
		}
	});
	
	$('.formatareais').maskMoney({prefix:'R$ ', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});
	
	function carregaImoveis(){
		if( $('.imovel') ) {
			$.getJSON('/sgpi/libs/imoveis.ajax.php?search=',{id: $('.idCliente').val(), ajax: 'true'}, function(j){
				var options = '<option value="-"></option>';	
				for (var i = 0; i < j.length; i++) {
					var condominioBloco = j[i].condominio !== null ? j[i].condominio + ' - ' + j[i].bloco + ' - ' : '';
					var complemento = j[i].complemento !== '' ? ' - ' + j[i].complemento : '';
					options += '<option value="' + j[i].id + '">' + condominioBloco + j[i].cep + ' - ' + j[i].estado + ' - ' + j[i].cidade + ' - ' + j[i].bairro + ' - ' + j[i].logradouro + ' - ' + j[i].numero + complemento + '</option>';
				}
				$('.imovel').html(options + "").show();
			});
		} 
	}
	
	$('.preencheBlocos').blur(function(){
		var qtde = $(this).val();
		$('.myTableRow').remove();
		for(i = 0; i < parseInt(qtde); i++){
			$('#tabela > tbody > tr').eq(3).after("<tr class='myTableRow'><td><input type=text name='andares[]' placeHolder='Andares' title='Quantidade de Andares' /></td><td><input type=text name='descricao[]' placeHolder='Descrição' title='Descrição' /></td><td><input type=text name='unids[]' placeHolder='Unidades por Andar' title='Unidades por Andar' /></td><td><select name='possuiTerreo[]' alt='Possui Térreo?' title='Possui Térreo?'><option value='N'>Não</option><option value='S'>Sim</option></select></td></tr>");
		}
		$('#tabela > tbody > tr').eq(3).after("<tr class='myTableRow'><td colspan=3 align=center><ins><b>Blocos</b></ins></td></tr>");
	});
	
	$('.bloco').change(function(){
		if( $(this).val() !== '-' ) {
			$.getJSON('/sgpi/libs/complementos.ajax.php?search=',{id: $(this).val(), ajax: 'true'}, function(j){
				var options = '<option value="-"></option>';
				for (var i = 0; i < Object.keys(j).length; i++) {
					if(j[i] !== undefined)
						options += '<option value="' + j[i] + '">' + j[i] + '</option>';
				}
				$('#complementos').html("<select name='complemento' class='complemento'>" + options + "</select>").show();
			});
		} 
	});
	
	$("#btnExport").click(function (e) {
		var clone = $("#testTable").clone();
		$("table td.link a").remove();
		var htmltable = document.getElementById('testTable');
		var html = htmltable.outerHTML;
		var result = "data:application/vnd.ms-excel," + escape(html);
        this.href = result;
        this.download = "Resultado.xls";
		$("#testTable").html(clone.html());
        return true;
    });
	
	$("#exportButton").click(function () {
		//var doc = new jsPDF('l', 'pt');
		var doc = new jsPDF('l', 'pt', [1366, 768]);
		doc.setFontSize(12);
		var elem = document.getElementById("testTable");
		var res = doc.autoTableHtmlToJson(elem);
		doc.autoTable(res.columns, res.data);
		doc.save("table.pdf")
	});
	
	$(".voltar").click(function (e) {
		e.preventDefault();
		window.history.back(-1);
	});
});