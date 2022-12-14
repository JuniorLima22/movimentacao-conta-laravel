<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RequestPessoa;
use App\Models\Pessoa;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PessoaController extends Controller
{
    protected $pessoa;

    public function __construct(Pessoa $pessoa)
    {
        $this->pessoa = $pessoa;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pessoa.index');
    }

    public function listarPessoa()
    {
        try {

            $pessoas = $this->pessoa->get();

            $res = [
                'status' => true,
                'dataApi' => $pessoas,
            ];
            return response()->json($res);
        } catch (Exception $e) {
            $response = [
                'status' => false,
                'error' => [
                    'title' => 'Erro!',
                    'message' => 'Erro ao listar pessoas. Código de erro: ' . $e->getMessage(),
                    'type' => 'bg-danger',
                ]
            ];
            return response()->json($response);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RequestPessoa $request)
    {
        try {
            DB::BeginTransaction();

            $cpf = preg_replace('/[^0-9]/', '', $request->input('cpf'));
            $cep = preg_replace('/[^0-9]/', '', $request->input('cep'));

            $pessoa = $this->pessoa::create([
                'nome' => $request->input('nome'),
                'cpf' => $cpf,
                'cep' => $cep,
                'numero' => $request->input('numero'),
                'logradouro' => $request->input('logradouro'),
                'bairro' => $request->input('bairro'),
                'estado' => $request->input('estado'),
                'municipio' => $request->input('municipio')
            ]);

            if ($pessoa) {

                DB::commit();

                $notification = [
                    'title' => 'Sucesso',
                    'messageSystem' => 'Pessoa cadastrado com sucesso!',
                    'type' => 'bg-success',
                ];
                return back()->with($notification);
            }

        } catch (\Exception $e) {
            DB::rollback();

            $notification = [
                'title' => 'Erro do Sistema',
                'messageSystem' => 'Erro ao cadastrar pessoa. Código de erro: '. $e->getMessage(),
                'type' => 'bg-danger',
            ];
            return back()->with($notification);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {

            $pessoa = $this->pessoa->find($id);

            $res = [
                'status' => true,
                'data' => $pessoa,
            ];
            return response()->json($res);
        } catch (Exception $e) {
            $response = [
                'status' => false,
                'error' => [
                    'title' => 'Erro!',
                    'message' => 'Erro ao listar pessoa. Código de erro: ' . $e->getMessage(),
                    'type' => 'bg-danger',
                ]
            ];
            return response()->json($response);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RequestPessoa $request, $id)
    {
        try {
            DB::BeginTransaction();

            $cpf = preg_replace('/[^0-9]/', '', $request->input('cpf'));
            $cep = preg_replace('/[^0-9]/', '', $request->input('cep'));
            
            $pessoa = $this->pessoa->where('id', $id)->update([
                'nome' => $request->input('nome'),
                'cpf' => $cpf,
                'cep' => $cep,
                'numero' => $request->input('numero'),
                'logradouro' => $request->input('logradouro'),
                'bairro' => $request->input('bairro'),
                'estado' => $request->input('estado'),
                'municipio' => $request->input('municipio')
            ]);

            if ($pessoa) {

                DB::commit();
                
                $notification = [
                    'title' => 'Sucesso',
                    'messageSystem' => 'Registro atualizado com sucesso.',
                    'type' => 'bg-success',
                ];
                return back()->with($notification);
            }

            $notification = [
                'title' => 'Erro',
                'messageSystem' => 'Erro ao atualizar pessoa',
                'type' => 'bg-danger',
            ];
            return back()->with($notification);

        } catch (\Exception $e) {
            DB::rollback();

            $notification = [
                'title' => 'Erro do Sistema',
                'messageSystem' => 'Erro ao atualizar pessoa. Código de erro: '. $e->getMessage(),
                'type' => 'bg-danger',
            ];
            return back()->with($notification);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try {
            DB::BeginTransaction();

            $pessoa = $this->pessoa->where('id', $request->id)->delete();

            if ($pessoa) {

                DB::commit();
                
                $notification = [
                    'title' => 'Sucesso',
                    'messageSystem' => 'Registro deletado com sucesso.',
                    'type' => 'bg-success',
                ];
                return back()->with($notification);
            }

            $notification = [
                'title' => 'Sucesso',
                'messageSystem' => 'Erro ao deletar pessoa',
                'type' => 'bg-danger',
            ];
            return back()->with($notification);

        } catch (\Exception $e) {
            DB::rollback();

            $notification = [
                'title' => 'Erro do Sistema',
                'messageSystem' => 'Erro ao deletar pessoa. Código de erro: '. $e->getMessage(),
                'type' => 'bg-danger',
            ];
            return back()->with($notification);
        }
    }
}
