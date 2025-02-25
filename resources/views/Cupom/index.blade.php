@extends('adminlte::page')

@section('title', 'Despesas')

@section('content')

<div class="container">

    <section class="row">
        <div class="col-3">
            <a data-toggle="modal" data-target="#modal-novo" class="btn btn-principal  text-light btn-flat">Adicionar <i
                    class="fa fa-plus"></i>
            </a>
        </div>
        <div class="col-9">
            <div class="float-right text-center w-50">
                @include('flash::message')
            </div>
        </div>
    </section>

   <form method="GET" action="{{route('cupom.index')}}" class="row mt-2">
        <div class="col-md-3 mt-1 col-6">
            <input placeholder="DE" name="inicio_filtro" type="text" data-toggle="datepicker" class="form-control date">
        </div>
        <div class="col-md-3 mt-1 col-6">
            <input placeholder="ATÉ" name="fim_filtro" type="text" data-toggle="datepicker" class="form-control date">
        </div>
        @if (Auth::user()->nivel_acesso == 1)
            <div class="col-md-3 mt-1 col-12">
                <select class="form-control" name="user_filtro" id="">
                    <option disable select value>--Todos--</option>
                    @foreach ($users as $u)
                        <option value="{{$u->id}}">{{$u->name}}</option>
                    @endforeach
                </select>
            </div>
        @endif
      
        <div class="col-md-3 mt-1 col-12 text-center">
            <input type="hidden" name="acao" value="filtro" >
            <button class="btn btn-principal text-light" type="submit">Filtrar</button>
        </div>

   </form>

    <section class="row mt-2">
        <div class="col-12">
            <table class="table w-100 table-responsive">
                <thead class="thead-dark">
                    <tr>
                        <th class="w-50 text-center">Funcionário</th>
                        <th class="w-25 text-center">Cidade</th>
                        <th class="w-25 text-center">Valor</th>
                        <th class="w-25 text-center">Periodo</th>
                        @if (Auth::user()->nivel_acesso == 1)
                        <th class="text-center">Pago?</th>
                        <th class="text-center">Excluir</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cupons as $c)
                        <tr data-href="{{route('cupom.visualizar', $c->id)}}" class="clickable" style="cursor:pointer" >
                            <td class="text-center">{{$c->usuario->name}}</td>
                            <td class="text-center">{{$c->cidade}}</td>
                            <td class="text-center">R$ {{number_format($c->valor_total, 2, ',', '.')}}</td>
                            <td class="text-center">{{$c->inicio->format('d/m/Y') .' - '.$c->fim->format('d/m/Y') }}</td>
                            @if (Auth::user()->nivel_acesso == 1)
                            <td  onclick="event.stopPropagation()" class="text-center">
                                <label id="lbl-pago" class="switch">
                                    <input onclick="altera({{$c->id}})" {{($c->pago == 1) ? 'checked' : ''}} id="btn-pago_{{$c->id}}" name="pago" type="checkbox">
                                    <span class="slider round"></span>
                                  </label>
                            </td>
                                <td onclick="event.stopPropagation()"  class="text-center"><a  href="{{route('cupom.excluir', $c->id)}}" onclick="return confirm('Tem certeza que deseja excluir esse registro?')"><i class="fa fa-times text-danger"></i></a></td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $cupons->appends(request()->query())->links() }}
    </section>
    </section>

</div>


<div class="modal" id="modal-novo" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header text-light">
                <h5 class="modal-title ">NOVO</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i style="font-size: 16px" class="fas text-danger fa-circle"> </i> </span>
                </button>
            </div>
            <div class="modal-body">
                <div id="form-errors"></div>
                <form id="form-novo" action="{{Route('cupom.store')}}" method="POST">
                    @csrf
                    <section class="row">
                        <div class="col-12">
                            <label class="label" for="name">Cidade sede*</label>
                            <input name="cidade" type="text" class="form-control">
                        </div>
                    </section>
                    <section class="row mt-2">
                        <div class="col-md-12">
                            <strong>Periodo</strong>
                        </div>
                       
                        <div class="col-6">
                            <label class="label" for="email">De*</label>
                            <input data-toggle="datepicker" autocomplete="off" name="inicio"  type="text" class="form-control date">
                        </div>
                        <div class="col-6">
                            <label class="label" for="password">Até*</label>
                            <input data-toggle="datepicker" autocomplete="off" name="fim" type="text" class="form-control date">
                        </div>
                        <div class="col-md-6 col-6">
                            <label class="label" for="email">Kilometragem Inicial*</label>
                            <input name="km_inicial" type="text" class="form-control">
                        </div>
                        <div class="col-md-6 col-6">
                            <label class="label" for="email">Kilometragem final*</label>
                            <input name="km_final" type="text" class="form-control">
                        </div>
                    </section>
      

               
            </div>
            <div style="background: #eee" class="modal-footer">
                <button id="btn-save" class="btn botao-save ">Salvar</button>
            </form>
                <button type="button" class="btn botao-close" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<style>
    tr:hover{
         background-color: aquamarine;
     }

     .switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

/* Hide default HTML checkbox */
.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

/* The slider */
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}

</style>

<script>

$.fn.datepicker

jQuery(document).ready(function($) {
    $(".clickable").click(function() {
        window.location = $(this).data("href");
    });


});

$(".date").mask('99/99/9999');

$('[data-toggle="datepicker"]').datepicker({
    dateFormat: 'dd/mm/yy',
        dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo'],
        dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
        dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
        monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
        monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez']


});

function altera(id){
    

  if ( $("#btn-pago_"+id).is(":checked")){
       var status = 1;
    }else{
      var status = 0
    }

    if(confirm("Tem certeza que deseja realizar essa ação?")){
        $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
        });
        $.ajax({
            url: '/cupom/altera-status/'+id,
            data: {status: status},
            dataType: 'json',
            type: 'POST',
            success: function (data){
              
            }
        });
    }

}


</script>





@endsection
