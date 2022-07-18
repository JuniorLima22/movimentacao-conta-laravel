<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RequestHistorico;
use App\Models\Conta;
use App\Models\Historico;
use App\Models\Pessoa;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HistoricoController extends Controller
{
    protected $historico;
    protected $conta;
    protected $pessoa;

    public function __construct(Historico $historico, Conta $conta, Pessoa $pessoa)
    {
        $this->historico = $historico;
        $this->conta = $conta;
        $this->pessoa = $pessoa;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pessoas = $this->pessoa->get();
        $tipos = [
            'E' => 'Entrada',
            'S' => 'Saída'
        ];
        return view('historico.index', compact('pessoas', 'tipos'));
    }

    public function listarHistorico($id)
    {
        try {
            $historicos = $this->historico->where('conta_id', $id)->get();
            $entrada = $this->historico->where('conta_id', $id)->where('tipo', 'E')->sum('valor');
            $saida = $this->historico->where('conta_id', $id)->where('tipo', 'S')->sum('valor');
            $saldo = $entrada - $saida;

            $res = [
                'status' => true,
                'data' => $historicos,
                'saldo' => $saldo,
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
    public function store(RequestHistorico $request)
    {
        try {
            DB::BeginTransaction();

            $historico = $this->historico::create([
                'pessoa_id' => $request->input('pessoa_id'),
                "conta_id" => $request->input('conta_id'),
                "valor" => $request->input('valor'),
                "tipo" => $request->input('tipo')
            ]);

            if ($historico) {

                DB::commit();

                $notification = [
                    'title' => 'Sucesso',
                    'messageSystem' => 'Movimentação cadastrado com sucesso!',
                    'type' => 'bg-success',
                ];
                return back()->with($notification);
            }

        } catch (\Exception $e) {
            DB::rollback();

            $notification = [
                'title' => 'Erro do Sistema',
                'messageSystem' => 'Erro ao cadastrar movimentação na conta. Código de erro: '. $e->getMessage(),
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
