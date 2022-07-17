@extends('adminlte::page')

@section('title', 'Pessoa')

@section('content_header')
    <h1>Cadastro de Pessoa</h1>
@stop

@section('content')
    <x-adminlte-card class="shadow">
        @if (session('message'))
            <x-adminlte-alert theme="{{ session('type') }}" icon="" dismissable>
                {{ session('message') }}
            </x-adminlte-alert>
        @endif

        <form action="{{ route('admin.pessoa.cadastrar') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-sm-6">
                    <x-adminlte-input type="text" name="nome" id="nome" label="Nome" placeholder="Nome da Pessoa" enable-old-support></x-adminlte-input>
                </div>
                <div class="col-sm-6">
                    <x-adminlte-input type="text" name="cpf" id="cpf" class="cpf" label="CPF" placeholder="123.456.789-00" enable-old-support></x-adminlte-input>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-4">
                    <x-adminlte-input type="text" name="cep" id="cep" class="cep" label="CEP" placeholder="CEP" enable-old-support>
                        <x-slot name="bottomSlot">
                            <span class="text-sm text-gray">
                                Digite apenas números
                            </span>
                            <span class="spinner-border spinner-border-sm d-none" id="carregando_cep"></span>
                        </x-slot>
                    </x-adminlte-input>
                </div>
                <div class="col-sm-6">
                    <x-adminlte-input type="text" name="municipio" id="municipio" label="Cidade" placeholder="Cidade" enable-old-support readonly></x-adminlte-input>
                </div>

                <div class="col-sm-2">
                    <x-adminlte-input type="text" name="estado" id="estado" label="Estado" placeholder="Estado" enable-old-support readonly></x-adminlte-input>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-4">
                    <x-adminlte-input type="text" name="bairro" id="bairro" label="Bairro" placeholder="Bairro" enable-old-support readonly></x-adminlte-input>
                </div>
                <div class="col-sm-6">
                    <x-adminlte-input type="text" name="logradouro" id="logradouro" label="Endereço" placeholder="Rua" enable-old-support readonly></x-adminlte-input>
                </div>

                <div class="col-sm-2">
                    <x-adminlte-input type="text" name="numero" id="numero" label="Nº" placeholder="10 ou S/N" enable-old-support></x-adminlte-input>
                </div>
            </div>

            {{-- <x-adminlte-select name="permission" label="Nível de acesso" enable-old-support>
            <x-adminlte-options :options="$permissoes" placeholder="Selecione nível do usuário"/>
        </x-adminlte-select> --}}

            <x-adminlte-button label="Cadastrar" type="submit" theme="success" icon="fas fa-save" class="mr-1" />
            <x-adminlte-button label="Cancelar" icon="fas fa-ban" onclick="location.href=''" />
        </form>
    </x-adminlte-card>

    <x-adminlte-card title="Listagem de Pessoas" header-class="text-uppercase rounded-bottom" icon="fas fa-lg fa-users"
        class="shadow">

        <x-slot name="toolsSlot">
            <div class="input-group input-group-sm" style="width: 150px;">
                <div class="spinner-border mr-3 d-none" role="carregando_listar_pessoa"></div>
                <select id="limite_paginacao" class="custom-select w-auto form-control-border bg-light"
                    placeholder="Limite">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
        </x-slot>

        <table id="listar_cliente" class="table table-responsive">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>Endereço</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody id="tbody_listar_pessoa"></tbody>
        </table>

        {{-- <x-slot name="footerSlot">
            <ul class="pagination m-0 float-right">
                    <li class="page-item"><a class="page-link pagination-left" href="#"><i class="fas fa-arrow-left text-dark"></i></a></li>
                    <li class="page-item"><a class="page-link pagination-right" href="#"><i class="fas fa-arrow-right text-dark"></i></a></li>
            </ul>
        </x-slot> --}}
    </x-adminlte-card>

    <x-adminlte-modal id="modal_delete_pessoa" title="Exclusão de Registro" theme="danger" icon="fas fa-trash"
        static-backdrop>
        <p class="text-center">O registro <strong><span id="modal_nome_pessoa"></span></strong> será deletado da base de
            dados. <br> Deseja realmente executar esta ação?</p>
        <x-slot name="footerSlot">
            <div class="justify-content-end">
                <form action="" method="POST">
                    @csrf
                    @method('DELETE')
                    <x-adminlte-button theme="danger" label="Cancelar" data-dismiss="modal" />
                    <x-adminlte-button type="submit" theme="outline-success" label="Deletar" class="ml-2" />
                </form>
            </div>
        </x-slot>
    </x-adminlte-modal>
@stop

@section('css')
@stop

@section('js')
    <script src="{{ asset('/js/scriptsPessoa.js') }}"></script>
    @if(Session::has('messageSystem'))
    <script>
    let title = "<strong>{{ Session::get('title') }}</strong>";
    let body = "{{ Session::get('messageSystem') }}";
    let type = "{{ Session::get('type') }}";
    $(document).ready(function() {
        $(document).Toasts('create', {
            title: title,
            body: body,
            class: type,
            autohide: true,
            delay: 5000
        });
    });
    </script>
    @endif
@stop
