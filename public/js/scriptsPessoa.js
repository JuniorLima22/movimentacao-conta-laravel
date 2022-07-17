$(document).ready(function() {
    $('.cpf').inputmask("999.999.999-99");
    $('.cep').inputmask("99999-999");

    listarPessoa();
});

$(document).on('keyup', '#nome', function(){
    let palavras = $(this).val().toLowerCase().split(" ");
    for (let a = 0; a < palavras.length; a++) {
        let w = palavras[a];
        palavras[a] = w[0].toUpperCase() + w.slice(1);
    }
    $(this).val(palavras.join(" "));
});

$(document).on('keyup', '#cep', function(){
    //CEP somente com dígitos.
    let cep = $(this).val().replace(/\D/g, '');

    if (cep.length == 8) {

        //Expressão regular para validar o CEP.
        var validacep = /^[0-9]{8}$/;

        //Valida o formato do CEP.
        if(validacep.test(cep)) {

            $("#carregando_cep").removeClass('d-none')

            axios.get("https://viacep.com.br/ws/"+ cep +"/json/")
            .then(response => {
                if (!("erro" in response.data)) {
                    $("#municipio").val(response.data.localidade);
                    $("#estado").val(response.data.uf);
                    $("#bairro").val(response.data.bairro);
                    $("#logradouro").val(response.data.logradouro);
                } else {
                    //CEP pesquisado não foi encontrado.
                    limpaFormularioCep();
                    $(document).Toasts('create', {
                        title: 'Error!',
                        body: 'CEP não encontrado.',
                        class: 'bg-danger',
                        autohide: true,
                        delay: 1000
                    });
                }
            })
            .catch(error => {
                console.log(error);
                $(document).Toasts('create', {
                    title: error.name,
                    body: error.message,
                    class: 'bg-danger'
                });
            })
            .finally(() => {
                $('#carregando_cep').addClass('d-none');
            });
        } else {
            //CEP é inválido.
            limpaFormularioCep();
            $(document).Toasts('create', {
                title: 'Error!',
                body: 'Formato de CEP inválido.',
                class: 'bg-danger',
                autohide: true,
                delay: 750
            });
        }
    } else {
        //CEP sem valor, limpa formulário.
        limpaFormularioCep();
    }
});

function limpaFormularioCep(){
    $("#municipio").val("");
    $("#estado").val("");
    $("#bairro").val("");
    $("#logradouro").val("");
    $("#numero").val("");
}

function listarPessoa() {
    $("div[role='carregando_listar_pessoa']").removeClass('d-none');
    axios.get('/admin/pessoa/listar', {
        params: {
            limit: $('#limite_paginacao').val(),
        }
    })
    .then(response => {
        if (response.data.status) {
            return tbodyListarPessoa(response);
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
        $("div[role='carregando_listar_pessoa']").addClass('d-none');
    });
}

function tbodyListarPessoa(response) {
    let pessoas = response.data.dataApi;

    let table = document.getElementById('tbody_listar_pessoa');
    $('#tbody_listar_pessoa').empty();

    if (pessoas.length == 0) {
        let tr = table.insertRow();

        let td = tr.insertCell(); 
        td.innerText = 'Nenhuma pessoa encontrada';
        td.setAttribute('colspan', 4);
        $('#limite_paginacao').prop('disabled', true);
    }else{
        for (let i = 0; i < pessoas.length; i++) {
            const pessoa = pessoas[i];
    
            let tr = table.insertRow();
    
            let td_nome = tr.insertCell();
            let td_cpf = tr.insertCell();
            let td_endereco = tr.insertCell();
            let td_acoes = tr.insertCell();
    
            td_nome.classList.add('text-nowrap');
            td_endereco.classList.add('text-nowrap');
            
            td_nome.innerText = pessoa.nome;
            td_cpf.innerText = pessoa.cpf;
            td_endereco.innerText = `${pessoa.logradouro} ${pessoa.numero}, ${pessoa.bairro}, ${pessoa.municipio}-${pessoa.estado}`;
            td_acoes.innerHTML = `
                <nobr>
                    <button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Editar">
                        <i class="fa fa-lg fa-fw fa-pen"></i>
                    </button>
                    
                    <button class="btn btn-xs btn-default text-danger mx-1 shadow delete" title="Deletar" data-toggle="modal" data-target="#modal_delete_pessoa" data-id="${pessoa.id}" data-nome="${pessoa.nome}">
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
    $('#modal_nome_pessoa').text(nome);
    $('#pessoa_id').val(id);
});