@extends('adminlte::page')

@section('title', 'Cadastro de Movimentação')

@section('content_header')
    <h1>Cadastro de Movimentação</h1>
@stop

@section('content')
    <x-adminlte-card class="shadow">
        @if (session('message'))
            <x-adminlte-alert theme="{{ session('type') }}" icon="" dismissable>
                {{ session('message') }}
            </x-adminlte-alert>
        @endif

        <form action="{{ route('admin.historico.cadastrar') }}" id="form_cadastrar_atualizar" method="POST">
            @csrf
            <div class="row">
                <div class="col-sm-6">
                    <x-adminlte-select name="pessoa_id" label="Pessoa" enable-old-support>
                        <option class="d-none" value="">Selecione</option>
                        @forelse ($pessoas as $pessoa)
                            <option value="{{ $pessoa->id }}">{{ $pessoa->nome }} - {{ mascara($pessoa->cpf, '###.###.###-##') }}</option>
                        @empty
                            <option value="">Nenhuma Pessoa cadastrado</option>
                        @endforelse
                    </x-adminlte-select>
                </div>
                <div class="col-sm-6">
                    <x-adminlte-select name="conta_id" label="Número da Conta" enable-old-support>
                        <option class="d-none" value="">Selecione</option>
                    </x-adminlte-select>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <x-adminlte-input type="number" name="valor" id="valor" label="Valor" placeholder="R$ 00,00" step="0.010" enable-old-support>
                        <x-slot name="bottomSlot">
                            <span class="text-sm text-gray">
                                Digite apenas números
                            </span>
                        </x-slot>
                    </x-adminlte-input>
                </div>
                <div class="col-sm-6">
                    <x-adminlte-select name="tipo" label="Depositar/Retirar" enable-old-support>
                        <option class="d-none" value="">Selecione</option>
                        @forelse ($tipos as $key => $tipo)
                            <option value="{{ $key }}">{{ $tipo}}</option>
                        @empty
                            <option value="">Nenhuma tipo cadastrado</option>
                        @endforelse
                    </x-adminlte-select>
                </div>
            </div>

            <x-adminlte-button label="Cadastrar" id="btn_cadastrar_atualizar" type="submit" theme="success" icon="fas fa-save" class="mr-1" />
            <x-adminlte-button label="Cancelar" icon="fas fa-ban" onclick="location.href=''" />
        </form>
    </x-adminlte-card>

    <x-adminlte-card title="Extrato" header-class="text-uppercase rounded-bottom" icon="fas fa-lg fa-chart-bar"
        class="shadow">

        <x-slot name="toolsSlot">
            <div class="input-group input-group-sm" style="width: 150px;">
                <div class="spinner-border mr-3 d-none" role="carregando_listar_historico"></div>
            </div>
        </x-slot>

        <table id="listar_historico" class="table table-responsive">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Valor</th>
                    {{-- <th>Ações</th> --}}
                </tr>
            </thead>
            <tbody id="tbody_listar_historico"></tbody>
        </table>

        <x-slot name="footerSlot">
            <h3 id="saldo"></h3>
        </x-slot>
    </x-adminlte-card>

    <x-adminlte-modal id="modal_delete" title="Exclusão de Registro" theme="danger" icon="fas fa-trash"
        static-backdrop>
        <p class="text-center">O registro <strong><span id="modal_nome"></span></strong> será deletado da base de
            dados. <br> Deseja realmente executar esta ação?</p>
        <x-slot name="footerSlot">
            <div class="justify-content-end">
                <form action="{{ route('admin.historico.deletar') }}" method="POST" id="form_delete">
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
    <script src="{{ asset('/js/scriptshistorico.js') }}"></script>
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
