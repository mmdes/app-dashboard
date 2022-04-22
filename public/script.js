//somente após o carregamento do DOM
$(document).ready(() => {

	$('#documentacao').on('click', ()=>{
        //$('#pagina').load('documentacao.html')
        /*$.get('documentacao.html', data => {
            $('#pagina').html(data)
        })*/
        $.post('documentacao.html', data => {
            $('#pagina').html(data)
        })
    })

    $('#suporte').on('click', ()=>{
        //$('#pagina').load('suporte.html')
        /*$.get('suporte.html', data => {
            $('#pagina').html(data)
        })*/

        $.post('suporte.html', data => {
            $('#pagina').html(data)
        })

    })

    //Implementação do método ajax
    //selecionar a competência (mes/ano) dispara a requisição assíncrona



    $('#competencia').on('change', e =>{
        //console.log($(e.target).val())
        //recebe um objeto literal
        let competencia = $(e.target).val()
        //método, url, dados, sucesso, erro
        $.ajax({
            type: 'GET',
            url: 'app.php',
            data:`competencia=${competencia}`, //x-www-form-urlenconded
            dataType:'json',
            success: dados => {
                $('#numeroVendas').html(dados.numeroVendas)
                $('#totalVendas').html(dados.totalVendas)
                $('#totalClientesAtivos').html(dados.totalClientesAtivos)
                $('#totalClientesInativos').html(dados.totalClientesInativos)
                
                //console.log(dados.totalClientesAtivos)
            },
            error: erro => {console.log(erro)}
        })
    })

})