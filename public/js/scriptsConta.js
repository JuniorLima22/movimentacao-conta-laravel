$(document).ready(function() {
    listarConta();
});

function limpaFormulario(){
    $("#pessoa_id").val("");
    $("#numero").val("");
}

function listarConta() {
    $("div[role='carregando_listar_conta']").removeClass('d-none');
    axios.get('/admin/conta/listar')
    .then(response => {
        if (response.data.status) {
            return tbodyListarConta(response);
        } else {
            $(document).Toasts('create', {
                title: response.data.error.title,
                body: response.data.error.message,
                class: response.data.error.type
            });
        }
    })
    .catch(error => {
        console.log(error);
        $(document).Toasts('create', {
            title: error.name,
            body: `${error.message} <br> Exception: ${error.response.data.message} <br> File: ${error.response.data.file} <br> Line: ${error.response.data.line} <br> Message: ${error.response.data.message}`,
            class: 'bg-danger'
        });
    })
    .finally(() => {
        $("div[role='carregando_listar_conta']").addClass('d-none');
    });
}

function tbodyListarConta(response) {
    let contas = response.data.data;

    let table = document.getElementById('tbody_listar_conta');
    $('#tbody_listar_conta').empty();

    if (contas.length == 0) {
        let tr = table.insertRow();

        let td = tr.insertCell(); 
        td.innerText = 'Nenhuma conta encontrada';
        td.setAttribute('colspan', 4);
    }else{
        for (let i = 0; i < contas.length; i++) {
            const conta = contas[i];
    
            let tr = table.insertRow();
    
            let td_nome = tr.insertCell();
            let td_cpf = tr.insertCell();
            let td_numero = tr.insertCell();
            let td_acoes = tr.insertCell();
    
            td_nome.classList.add('text-nowrap');
            td_numero.classList.add('text-nowrap');
            
            td_nome.innerText = conta.pessoa.nome;
            td_cpf.innerText = conta.pessoa.cpf;
            td_numero.innerText = conta.numero;
            td_acoes.innerHTML = `
                <nobr>
                    <button class="btn btn-xs btn-default text-primary mx-1 shadow edit" title="Editar" data-id="${conta.id}">
                        <i class="fa fa-lg fa-fw fa-pen"></i>
                    </button>
                    
                    <button class="btn btn-xs btn-default text-danger mx-1 shadow delete" title="Deletar" data-toggle="modal" data-target="#modal_delete" data-id="${conta.id}" data-nome="${conta.numero}">
                        <i class="fa fa-lg fa-fw fa-trash"></i>
                    </button>
                </nobr>
            `;
        }
    }
}

$(document).on('click', '.delete', function(){
    let id = $(this).data('id');
    let nome = $(this).data('nome');
    $('#modal_nome').text(nome);
    $('#conta_id').val(id);
});

$(document).on('click', '.edit', function(){
    let id = $(this).data('id');

    axios.get('/admin/conta/editar/'+id)
    .then(response => {
        if (response.data.status) {
            return popularFormularioConta(response.data.data);
        } else {
            $(document).Toasts('create', {
                title: response.data.error.title,
                body: response.data.error.message,
                class: response.data.error.type,
                autohide: true,
                delay: 5000
            });
        }
    })
    .catch(error => {
        console.log(error);
        $(document).Toasts('create', {
            title: error.name,
            body: `${error.message} <br> Exception: ${error.response.data.message} <br> File: ${error.response.data.file} <br> Line: ${error.response.data.line} <br> Message: ${error.response.data.message}`,
            class: 'bg-danger',
            autohide: true,
            delay: 8000
        });
    });
});

function popularFormularioConta(response){
    limpaFormulario();

    for (const campo in response) {
        $('#'+campo).val("");
        $('#'+campo).val(response[campo]);
    }

    $('#btn_cadastrar_atualizar').html('<i class="fas fa-save"></i> Atualizar');
    $("#form_cadastrar_atualizar input[name='_method']").remove();
    $('#form_cadastrar_atualizar').prepend('<input type="hidden" name="_method" value="PUT"></input>');

    let form = $('#form_cadastrar_atualizar').attr('action');

    if (form.indexOf('cadastrar') !== -1) {
        let newAction = form.slice(0, form.indexOf('cadastrar'))+"atualizar/"+response.id;
        $('#form_cadastrar_atualizar').attr('action', newAction);
    }

    if (form.indexOf('atualizar') !== -1) {
        let newAction = form.slice(0, form.indexOf('atualizar'))+"atualizar/"+response.id;
        $('#form_cadastrar_atualizar').attr('action', newAction);
    }
    
    $('html, body').animate({
        scrollTop: 100
    }, 800);
}