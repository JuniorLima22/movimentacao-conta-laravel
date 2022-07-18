$(document).ready(function() {
    $('#conta_id').prop('disabled', true);
});

function limpaFormulario(){
    $("#pessoa_id").val("");
    $("#conta_id").val("");
}

$(document).on('change', '#pessoa_id', function(){
    let id = $(this).val();
    listarContaPessoa(id);
    $('#tbody_listar_historico').empty();
    $('#saldo').empty();
    $('#tipo').val('').prop('disabled', false);
});

function listarContaPessoa(id) {
    axios.get(`/admin/conta/listar-conta-pessoa/${id}`)
    .then(response => {
        if (response.data.status) {
            return popularSelectNumeroConta(response);
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
    });
}

function popularSelectNumeroConta(response){
    let historicos = response.data.data;
    
    let option = '<option class="d-none" value="">Selecione</option>';
    
    if (historicos.length == 0) {
        option = '<option class="d-none" value="">Nenhuma conta cadastrado</option>';
        $('#conta_id').prop('disabled', true);
    }else{
        $('#conta_id').prop('disabled', false);
        $.each(historicos, function(index, value){
            let saldo = value.saldo.toLocaleString('pt-br', {minimumFractionDigits: 2});

            let dataSaldo = (value.saldo == 0) ? false : true ;
            
            option += `<option value="${value.id}" data-saldo="${dataSaldo}">${value.numero} - Saldo: R$ ${saldo}</option>`;
        });
    }
    $('#conta_id').html(option);
}

$(document).on('change', '#conta_id', listarHistorico);

function listarHistorico() {
    let id = $('#conta_id').val();
    let dataSaldo = $("#conta_id option:selected").data('saldo');
    
    if(!dataSaldo){
        $('#tipo').val('E').prop('selected', true);
    }else{
        $('#tipo').val('').prop('selected', false);
    }

    $("div[role='carregando_listar_historico']").removeClass('d-none');
    axios.get(`/admin/historico/listar/${id}`)
    .then(response => {
        if (response.data.status) {
            return tbodyListarHistorico(response);
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
        $("div[role='carregando_listar_historico']").addClass('d-none');
    });
}

function tbodyListarHistorico(response) {
    let historicos = response.data.data;
    let saldo = response.data.saldo;

    $('#saldo').text('Saldo: R$ ' + saldo.toLocaleString('pt-br', {minimumFractionDigits: 2}));

    let table = document.getElementById('tbody_listar_historico');
    $('#tbody_listar_historico').empty();

    if (historicos.length == 0) {
        let tr = table.insertRow();

        let td = tr.insertCell(); 
        td.innerText = 'Nenhuma movimentação encontrada';
        td.setAttribute('colspan', 4);
    }else{
        for (let i = 0; i < historicos.length; i++) {
            const historico = historicos[i];
    
            let tr = table.insertRow();
    
            let td_data = tr.insertCell();
            let td_valor = tr.insertCell();
            // let td_acoes = tr.insertCell();
    
            td_data.classList.add('text-nowrap');
            td_valor.classList.add('text-nowrap');

            let tipo = (historico.tipo == 'S') ? 'R$ -' : 'R$ ';
            let tipoBadge = (historico.tipo == 'S') ? 'danger' : 'primary';
            
            td_data.innerText = historico.created_at;
            let valor = tipo + historico.valor.toLocaleString('pt-br', {minimumFractionDigits: 2});
            td_valor.innerHTML = `<span class="badge bg-${tipoBadge}">${valor}</span>`;
            // td_acoes.innerHTML = `
            //     <nobr>
            //         <button class="btn btn-xs btn-default text-primary mx-1 shadow edit" title="Editar" data-id="${historico.id}">
            //             <i class="fa fa-lg fa-fw fa-pen"></i>
            //         </button>
                    
            //         <button class="btn btn-xs btn-default text-danger mx-1 shadow delete" title="Deletar" data-toggle="modal" data-target="#modal_delete" data-id="${historico.id}" data-nome="${historico.valor}">
            //             <i class="fa fa-lg fa-fw fa-trash"></i>
            //         </button>
            //     </nobr>
            // `;
        }
    }
}

$(document).on('click', '.delete', function(){
    let id = $(this).data('id');
    let nome = $(this).data('nome');
    $('#modal_nome').text(nome);
    $('#historico_id').val(id);
});

$(document).on('click', '.edit', function(){
    let id = $(this).data('id');

    axios.get('/admin/historico/editar/'+id)
    .then(response => {
        if (response.data.status) {
            return popularFormularioHistorico(response.data.data);
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

function popularFormularioHistorico(response){
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