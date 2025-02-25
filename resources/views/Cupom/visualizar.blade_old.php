@extends('adminlte::page')

@section('title', 'Visualizar')

@section('content')

@php
    $ativado = 0;
    $datetimeObj1 = $cupom->created_at;
    $datetimeObj2 = $dataAtual;
    $interval = $datetimeObj1->diff($datetimeObj2);

   
    $hour1 = $interval->format('%a')*24;
    
  
    $hour2 = $interval->format('%h');
    

    $dif = $hour1 + $hour2;

  
@endphp

@if (Auth::user()->nivel_acesso == 1 or $dif < 24)
    @php
        $ativado = 1
    @endphp
@endif

<div class="container">

    <section class="row">
        <div class="col-12">
            <div class="float-right text-center w-50">
                @include('flash::message')
            </div>
        </div>
    </section>

    <div class="row">
        <h3>Funcionário: <span style="font-weight: 200">{{$cupom->usuario->name}}</span></h3>
    </div>
    <form id="form-update" action="{{route('cupom.update', $cupom->id)}}" method="POST" class="row">
        @csrf
        <div class="col-md-6">
            <label for="Cidade" class="label">Cidade</label>
            <input class="form-control" type="text" name="cidade" value="{{$cupom->cidade}}">
        </div>
        <div class="col-md-3 col-6">
            <label for="de" class="label">De</label>
            <input data-toggle="datepicker" class="form-control date" type="text" name="inicio" value="{{$cupom->inicio->format('d/m/Y')}}">
        </div> 
        <div class="col-md-3 col-6">
            <label for="Cidade" class="label">Até</label>
            <input data-toggle="datepicker"  class="form-control date" type="text" name="fim" value="{{$cupom->fim->format('d/m/Y')}}">
        </div>
        <div class="col-md-3 col-6">
            <label for="Cidade" class="label">Kilometragem inicial</label>
            <input   class="form-control" type="text" name="km_inicial" value="{{$cupom->km_inicial}}">
        </div>
        <div class="col-md-3 col-6">
            <label for="Cidade" class="label">Kilometragem final</label>
            <input class="form-control" type="text" name="km_final" value="{{$cupom->km_final}}">
        </div>
 
    </form>

    <form id="form-item" class="row mt-2">
        <div class="col-md-3 col-6">
            @csrf
            <select class="form-control" name="descricao" id="">
                @foreach ($servicos as $s)
                <option value="{{$s->nome}}">{{$s->nome}}</option>
                @endforeach
               
            </select>
        </div>     
        <div class="col-md-2 col-6">
            <input class="form-control" type="text" id="valor" name="valor" placeholder="Valor">
            <input type="hidden" name="cupom" id="cupom-id" value="{{$cupom->id}}">
        </div>  
       
        <div class="col-md-1 text-center">
            @if ($ativado == 1)      
            <a style="cursor: pointer" id="btn-adicionar" onclick="addItem()" data-placement="top" title="Adicionar Item" data-toggle="tooltip"  class="btn btn-primary "><i class="text-light fa fa-plus"></i></a>
            @endif
        </div>   
      
        
        <div class="col-md-6 text-center">
            <h3 id="lbl1" class="">Adicionar Fotos Comprovantes</h3>
        </div>
    </form>

    <div class="row mt-2">

        <div class="col-md-6">

            <table class="table table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th class="text-center">Item</th>
                        <th class="text-center">Descrição</th>
                        <th class="text-center">Valor</th>
                        @if ($ativado == 1)
                        <th></th>
                        @endif
                    </tr>
                </thead>
                <tbody id="tbody-itens">

                    @php
                        $i = 1;
                    @endphp

                   @foreach($itens as $it)
                        <tr id="tr-itens">
                            <td class="text-center">{{$i}}</td>
                            <td class="text-center">{{$it->descricao}}</td>
                            <td class="text-center">R$ {{number_format($it->valor, 2, ',', '.')}}</td>
                            @if ($ativado == 1)
                            <td class="text-center"><a  onclick="removeItem({{$it->id}})" style="cursor: pointer"> <i class="fa fa-times text-danger"></i></a></td>
                            @endif
                        </tr>
                        @php
                            $i++;   
                        @endphp
                   @endforeach
                </tbody>
                <tr>
                    <td colspan="2" class="text-left"><strong>TOTAL</strong></td>
                    <td colspan="2" id="total-value" class="text-right">R$ {{number_format($cupom->valor_total, 2, ',', '.')}}</td>
                </tr>
            </table>


        </div>


        <div class="col-md-6 text-center">
            <h3 id="lbl2" class="">Adicionar Fotos Comprovantes</h3>
                <table id="table-ft" class="table ">
                    <thead class="thead-dark">
                        <tr >
                            <th class="text-center">Foto</th>
                            @if ($ativado == 1)
                            <th class="text-center">Excluir</th>
                            @endif
                        </tr>
                    </thead>
                        <tbody class="tb-foto">
                            @foreach($imagens as $f)
                            <tr class="tr-foto">
                                <td class="text-center"><a data-toggle="modal" class="badge badge-primary text-light" style="font-size:12pt; cursor: pointer" data-target="#modal-foto" onclick="viewFoto('{{$f->img}}')" style="cursor: pointer"  >{{$f->nome}}.png</a></td>
                                @if ($ativado == 1)
                                <td class="text-center"><a onclick="removeFoto({{$f->id}})" style="cursor: pointer" ><i class="fa fa-times text-danger"></i></a></td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>             
                </table>

                @if ($ativado == 1)

                <button data-toggle="modal" data-target="#modal-pic" class="btn btn-principal text-light mt-2">Enviar Imagem
                </button>

                @endif

            
            
        </div>

        <div class="col-md-6 text-center">
            <h3 id="lbl2" class="">Adicionar Arquivos(PDF)</h3>
                <table id="table-ft" class="table ">
                    <thead class="thead-dark">
                        <tr >
                            <th class="text-center">Arquivo</th>
                            @if ($ativado == 1)
                            <th class="text-center">Excluir</th>
                            @endif
                        </tr>
                    </thead>
                        <tbody class="tb-foto">
                            @foreach($docs as $d)
                            <tr class="tr-foto">
                                <td class="text-center"><a href="{{route('cupom.download', $d->arquivo)}}" class="badge badge-primary text-light" style="font-size:12pt; cursor: pointer"  style="cursor: pointer"  >{{$d->nome}}</a></td>
                                @if ($ativado == 1)
                                <td class="text-center"><a href="{{route('cupom.deletedoc', $d->id)}}" onclick="return confirm('Tem certeza que deseja Excluir?')" style="cursor: pointer" ><i class="fa fa-times text-danger"></i></a></td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>             
                </table>

                @if ($ativado == 1)

                <button data-toggle="modal" data-target="#modal-doc" id="btn-doc" class="btn btn-principal text-light mt-2">Enviar PDF
                </button>

                @endif
            
            
            
            
        </div>

         {{-- <div class="row mt-2"> --}}
            <div class="col-md-6 text-center">

                <h3>Dados para reembolso</h3>
                <p> <strong>Banco: {{$cupom->usuario->banco}} </strong></p>
                <p> <strong>Agência: {{$cupom->usuario->agencia}} </strong></p>
                <p> <strong>Conta: {{$cupom->usuario->conta}} </strong></p>


            </div>
        {{-- </div> --}}
    
        @if ($ativado == 1)
    
        {{-- <div class="row mt-2"> --}}
    
    
            <div class="col-md-6 text-center mt-2 mb-2">
                <label for="Cidade" class="label">**Discriminar outros</label>
                <textarea form="form-update" class="form-control" name="observacao" id="" rows="2">{{$cupom->observacao}}</textarea>
                <a  style="cursor:  pointer; " id="btn-enviar"class="mt-2 btn btn-primary text-light">GRAVAR DADOS</a>
            </div>
           
        {{-- </div> --}}
    
        @endif
        
    </div>

   

    <div class="modal fade" id="modal-pic" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-light" id="exampleModalCenterTitle">Selecionar Imagem</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-5 text-center">
                        <div id="upload-demo"></div>
                    </div>
                    <div class="col-md-7" style="padding:5%;">

                        <input class="form-control" placeholder="Nome da imagem" type="text" id="nome-imagem" name="nome_imagem">
        
                        <p><strong>Selecionar imagem:</strong></p>

                        <input class="form-control"  accept="image/*"  type="file" id="image">

                        <button class="btn btn-principal btn-block text-light upload-image" style="margin-top:2%">Enviar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<div class="modal fade" id="modal-doc" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
aria-hidden="true">
<div class="modal-dialog modal-dialog-centered modal-sm" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title text-light" id="exampleModalCenterTitle">Selecionar Arquivo</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-12" style="padding:5%;">
                    <form id="form-doc" enctype="multipart/form-data"  method="POST" action="{{route('cupom.doc', $cupom->id)}}">
                        @csrf
                        <input class="form-control" placeholder="Nome do arquivo" type="text" id="nomedoc" name="nome">
        
                        <p><strong>Selecionar Arquivo:</strong></p>

                        <input class="form-control" name="arquivo"   type="file" id="doc">

                        <button id="btn-envia-doc" type="button" class="btn btn-principal btn-block text-light"  style="margin-top:2%">Enviar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>





<div class="modal fade" id="modal-foto" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
aria-hidden="true">
<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title text-light" id="exampleModalCenterTitle">Visualizar</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-6 col-12 text-center">
                    <img id="foto-view" width="300"  alt="">
                </div>
                <div class="remove-mobile" class="col-md-6">
                    <div id="myresult" class="img-zoom-result mt-5"></div>
                </div>
            </div>
           
        </div>
    </div>
</div>
</div>

<link rel="stylesheet" href="{{URL::asset('/js/croppie/croppie.css')}}">
<script src="{{URL::asset('/js/croppie/croppie.min.js')}}"></script>
  

    <script>
        $('#btn-envia-doc').click(function(){
            if($('#nomedoc').val() == ''){
                alert('Preencha o nome');
                return false;
            }else{
                $('#form-doc').submit();
            }
        });
 

    if( /Android|webOS|iPhone|iPad|Mac|Macintosh|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
       $("#table-ft").addClass("table-responsive")
    }else{
        function imageZoom(imgID, resultID) {
       
       var img, lens, result, cx, cy;
       img = document.getElementById(imgID);
       result = document.getElementById(resultID);
       /* Create lens: */
       lens = document.createElement("DIV");
       lens.setAttribute("class", "img-zoom-lens");
       /* Insert lens: */
       img.parentElement.insertBefore(lens, img);
       /* Calculate the ratio between result DIV and lens: */
       cx = result.offsetWidth / lens.offsetWidth;
       cy = result.offsetHeight / lens.offsetHeight;
       /* Set background properties for the result DIV */
       result.style.backgroundImage = "url('" + img.src + "')";
       result.style.backgroundSize = (img.width * cx) + "px " + (img.height * cy) + "px";
       /* Execute a function when someone moves the cursor over the image, or the lens: */
       lens.addEventListener("mousemove", moveLens);
       img.addEventListener("mousemove", moveLens);
       /* And also for touch screens: */
       lens.addEventListener("touchmove", moveLens);
       img.addEventListener("touchmove", moveLens);
       function moveLens(e) {
           var pos, x, y;
           /* Prevent any other actions that may occur when moving over the image */
           e.preventDefault();
           /* Get the cursor's x and y positions: */
           pos = getCursorPos(e);
           /* Calculate the position of the lens: */
           x = pos.x - (lens.offsetWidth / 2);
           y = pos.y - (lens.offsetHeight / 2);
           /* Prevent the lens from being positioned outside the image: */
           if (x > img.width - lens.offsetWidth) {x = img.width - lens.offsetWidth;}
           if (x < 0) {x = 0;}
           if (y > img.height - lens.offsetHeight) {y = img.height - lens.offsetHeight;}
           if (y < 0) {y = 0;}
           /* Set the position of the lens: */
           lens.style.left = x + "px";
           lens.style.top = y + "px";
           /* Display what the lens "sees": */
           result.style.backgroundPosition = "-" + (x * cx) + "px -" + (y * cy) + "px";
}
           function getCursorPos(e) {
               var a, x = 0, y = 0;
               e = e || window.event;
               /* Get the x and y positions of the image: */
               a = img.getBoundingClientRect();
               /* Calculate the cursor's x and y coordinates, relative to the image: */
               x = e.pageX - a.left;
               y = e.pageY - a.top;
               /* Consider any page scrolling: */
               x = x - window.pageXOffset;
               y = y - window.pageYOffset;
               return {x : x, y : y};
           }


}
    }


            $.fn.datepicker

        $(".date").mask('99/99/9999');
        
            
        $('[data-toggle="datepicker"]').datepicker({
            dateFormat: 'dd/mm/yy',
                dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo'],
                dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
                dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
                monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
                monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez']
        });
     
     

   


        var resize = $('#upload-demo').croppie({

        enableExif: true,

        enableOrientation: true,

        viewport: {

            width: 300,

            height: 450,

            type: 'square'

        },

        boundary: {

            width: 320,

            height: 470

        }

        });

        $('#image').on('change', function () {

        var reader = new FileReader();

        reader.onload = function (e) {

            resize.croppie('bind', {

                url: e.target.result

            }).then(function () {

                console.log('jQuery bind complete');

            });

        }

        reader.readAsDataURL(this.files[0]);

        });


        function viewFoto(img){
  
            $('#foto-view').attr('src', '/images/cupons/'+img);

            $("#foto-view").on("mouseover", function () {
                imageZoom("foto-view", "myresult");
            });

           
        }


        $('#btn-enviar').click(function(){
            $('#form-update').submit();
        });

        $('#valor').mask('000.000.000.000.000,00', {reverse: true});

        function addItem(){

            if($("#valor").val() == ''){
                alert('Insira o valor do item');
                return false;
            }else{
                $.ajax({
                url: '/cupom/add-item',
                data: $('#form-item').serialize(),
                dataType: 'json',
                type: 'POST',
                success: function (data){
                    if(data['cod'] == 1){
                        $("#total-value").html("R$ "+data['valor_total']);
                        $('#tbody-itens').append('<tr><td class="text-center">'+data['contador']+'</td> <td class="text-center">'+data['descricao']+'</td><td class="text-center">R$ '+data['valor']+'</td><td class="text-center"><a onclick="removeItem('+data['id']+')" style="cursor: pointer"> <i class="fa fa-times text-danger"></i></a></td></tr>    ');
                    }else{
                        alert(data['msg']);
                    }
                }
            });
            }
            
        }

        function removeItem(id){

            var confirmar = confirm('Tem certeza que deseja apagar esse item?');

            if (confirmar==true) {
                $.ajax({
                url: '/cupom/remove-item/'+id,
                dataType: 'json',
                type: 'GET',
                success: function (data){
            
                  location.reload();
                  return false;
                }
            });
            }else{
                return false;
            }
        }


        function removeFoto(id){

        var confirmar = confirm('Tem certeza que deseja apagar essa imagem?');

        if (confirmar==true) {
            $.ajax({
            url: '/cupom/remove-foto/'+id,
            dataType: 'json',
            type: 'GET',
            success: function (data){

            location.reload();
            return false;
            }
        });
        }else{
            return false;
        }
        }


        $('.upload-image').on('click', function (ev) {

        resize.croppie('result', {

            type: 'canvas',
            size: {height: 1200, width: 800},
            format: 'png',
        }).then(function (img) {


            if($('#nome-imagem').val() == ''){
                alert('Preencha o nome');
                return false;
            }else{

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            }); 

            $.ajax({
                url: "/cupom/crop",
                type: "POST",
                //processData: false,
                contentType: 'application/json',
                data:JSON.stringify({
                    "img": img,
                    "nome": $('#nome-imagem').val(),
                    'cupom': $('#cupom-id').val(),         
                }),
                success: function (data) {
                    $('#modal-pic').modal('toggle');
                    $('.tb-foto').append("<tr><td class='text-center'><a data-toggle='modal' class='badge badge-primary text-light' style='font-size:12pt; cursor: pointer' data-target='#modal-foto' onclick='viewFoto("+data['img']+")' style='cursor: pointer'  >"+data['nome']+".png</a></td><td class='text-center'><a onclick='removeFoto("+data['id'] +")' style='cursor: pointer' ><i class='fa fa-times text-danger'></i></a></td></tr>")
                }


            });
        }

        });

        });

        $('#tr-itens').click(function(){

        });


     

        
    </script>

   <style>

    @media (max-width: 600px)
    {
        #btn-adicionar{
            margin-top: 5px;
            float: right;
        }

        #lbl1{
            display:none;
        }

        .remove-mobile{
            display: none;
        }

        .img-zoom-container .img-zoom-lens .img-zoom-result {
            display: none;
        }
  
    
   
    }

    @media (min-width: 600px){
        #lbl2{
            display:none;
        }
    }


    .tr-foto:hover{
         background-color: aquamarine;
     }



     * {box-sizing: border-box;}

    .img-zoom-container {
    position: relative;
    }

    .img-zoom-lens {
    position: absolute;
    border: 1px solid #d4d4d4;
    /*set the size of the lens:*/
    width: 40px;
    height: 40px;
    }

    .img-zoom-result {
    border: 1px solid #d4d4d4;
    /*set the size of the result div:*/
    width: 300px;
    height: 300px;
    }
        


   </style>



@endsection
