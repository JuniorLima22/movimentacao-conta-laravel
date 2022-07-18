<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RequestConta;
use App\Models\Conta;
use App\Models\Historico;
use App\Models\Pessoa;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContaController extends Controller
{

    protected $conta;
    protected $pessoa;
    protected $historico;

    public function __construct(Conta $conta, Pessoa $pessoa, Historico $historico)
    {
        $this->conta = $conta;
        $this->pessoa = $pessoa;
        $this->historico = $historico;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pessoas = $this->pessoa->get();
        return view('conta.index', compact('pessoas'));
    }

    public function listarConta()
    {
        try {

            $contas = $this->conta->with('pessoa')->get();

            $res = [
                'status' => true,
                'data' => $contas,
            ];
            return response()->json($res);
        } catch (Exception $e) {
            $response = [
                'status' => false,
                'error' => [
                    'title' => 'Erro!',
                    'message' => 'Erro ao listar contas. Código de erro: ' . $e->getMessage(),
                    'type' => 'bg-danger',
                ]
            ];
            return response()->json($response);
        }
    }

    public function listarContaPessoa($id)
    {
        try {
            $contas = $this->conta->where('pessoa_id', $id)->get();

            foreach ($contas as $obj) {
                $entrada = $this->historico->where('conta_id', $id)->where('tipo', 'E')->sum('valor');
                $saida = $this->historico->where('conta_id', $id)->where('tipo', 'S')->sum('valor');
                $saldo = $entrada - $saida;
                $obj->saldo = $saldo;
            }

            $res = [
                'status' => true,
                'data' => $contas,
            ];
            return response()->json($res);
        } catch (Exception $e) {
            $response = [
                'status' => false,
                'error' => [
                    'title' => 'Erro!',
                    'message' => 'Erro ao listar contas de pessoa. Código de erro: ' . $e->getMessage(),
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
    public function store(RequestConta $request)
    {
        try {
            DB::BeginTransaction();

            $conta = $this->conta::create([
                'pessoa_id' => $request->input('pessoa_id'),
                'numero' => $request->input('numero'),
            ]);

            if ($conta) {

                DB::commit();

                $notification = [
                    'title' => 'Sucesso',
                    'messageSystem' => 'Número da Conta cadastrado com sucesso!',
                    'type' => 'bg-success',
                ];
                return back()->with($notification);
            }

        } catch (\Exception $e) {
            DB::rollback();

            $notification = [
                'title' => 'Erro do Sistema',
                'messageSystem' => 'Erro ao cadastrar número da conta. Código de erro: '. $e->getMessage(),
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

            $conta = $this->conta->find($id);

            $res = [
                'status' => true,
                'data' => $conta,
            ];
            return response()->json($res);
        } catch (Exception $e) {
            $response = [
                'status' => false,
                'error' => [
                    'title' => 'Erro!',
                    'message' => 'Erro ao listar conta. Código de erro: ' . $e->getMessage(),
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
    public function update(RequestConta $request, $id)
    {
        try {
            DB::BeginTransaction();

            $conta = $this->conta->where('id', $id)->update([
                'pessoa_id' => $request->input('pessoa_id'),
                'numero' => $request->input('numero'),
            ]);

            if ($conta) {

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
                'messageSystem' => 'Erro ao atualizar conta',
                'type' => 'bg-danger',
            ];
            return back()->with($notification);

        } catch (\Exception $e) {
            DB::rollback();

            $notification = [
                'title' => 'Erro do Sistema',
                'messageSystem' => 'Erro ao atualizar conta. Código de erro: '. $e->getMessage(),
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

            $conta = $this->conta->where('id', $request->id)->delete();

            if ($conta) {

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
                'messageSystem' => 'Erro ao deletar conta',
                'type' => 'bg-danger',
            ];
            return back()->with($notification);

        } catch (\Exception $e) {
            DB::rollback();

            $notification = [
                'title' => 'Erro do Sistema',
                'messageSystem' => 'Erro ao deletar conta. Código de erro: '. $e->getMessage(),
                'type' => 'bg-danger',
            ];
            return back()->with($notification);
        }
    }
}
