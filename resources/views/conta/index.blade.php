@extends('adminlte::page')

@section('title', 'Cadastro de Conta')

@section('content_header')
    <h1>Cadastro de Conta</h1>
@stop

@section('content')
    <x-adminlte-card class="shadow">
        @if (session('message'))
            <x-adminlte-alert theme="{{ session('type') }}" icon="" dismissable>
                {{ session('message') }}
            </x-adminlte-alert>
        @endif

        <form action="{{ route('admin.conta.cadastrar') }}" id="form_cadastrar_atualizar" method="POST">
            @csrf
            <div class="row">
                <div class="col-sm-6">
                    <x-adminlte-select name="pessoa_id" label="Pessoa" enable-old-support>
                        <option class="d-none" value="">Selecione uma Pessoa</option>
                        @forelse ($pessoas as $pessoa)
                            <option value="{{ $pessoa->id }}">{{ $pessoa->nome }} - {{ mascara($pessoa->cpf, '###.###.###-##') }}</option>
                        @empty
                            <option value="">Nenhuma Pessoa cadastrado</option>
                        @endforelse
                    </x-adminlte-select>
                </div>
                <div class="col-sm-6">
                    <x-adminlte-input type="number" name="numero" id="numero" label="Número da Conta" placeholder="Número da Conta" enable-old-support>
                        <x-slot name="bottomSlot">
                            <span class="text-sm text-gray">
                                Digite apenas números
                            </span>
                        </x-slot>
                    </x-adminlte-input>
                </div>
            </div>

            <x-adminlte-button label="Cadastrar" id="btn_cadastrar_atualizar" type="submit" theme="success" icon="fas fa-save" class="mr-1" />
            <x-adminlte-button label="Cancelar" icon="fas fa-ban" onclick="location.href=''" />
        </form>
    </x-adminlte-card>

    <x-adminlte-card title="Listagem de Contas" header-class="text-uppercase rounded-bottom" icon="fas fa-lg fa-wallet"
        class="shadow">

        <x-slot name="toolsSlot">
            <div class="input-group input-group-sm" style="width: 150px;">
                <div class="spinner-border mr-3 d-none" role="carregando_listar_conta"></div>
            </div>
        </x-slot>

        <table id="listar_conta" class="table table-responsive">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>Número da Conta</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody id="tbody_listar_conta"></tbody>
        </table>
    </x-adminlte-card>

    <x-adminlte-modal id="modal_delete" title="Exclusão de Registro" theme="danger" icon="fas fa-trash"
        static-backdrop>
        <p class="text-center">O registro <strong><span id="modal_nome"></span></strong> será deletado da base de
            dados. <br> Deseja realmente executar esta ação?</p>
        <x-slot name="footerSlot">
            <div class="justify-content-end">
                <form action="{{ route('admin.pessoa.deletar') }}" method="POST" id="form_delete">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="id" id="conta_id" value="">
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
    <script src="{{ asset('/js/scriptsConta.js') }}"></script>
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
