@extends('adminlte::page')

@section('title', 'Usuários')

@section('content')

<div class="container">

    <div class="row">
        <div class="col-md-12">
            <div class="float-right">
                @include('flash::message')
                <div id="form-update-errors"></div>
            </div>
        </div>
    </div>

    <section class="row">
        <div class="col-md-3 text-center">
            <img style="border-radius: 100%" width="180px" height="180px"
                src="{{URL::asset('/images/profiles/'.$user->profile_pic)}}">
            <button data-toggle="modal" data-target="#modal-pic" class="btn btn-principal text-light mt-2">Alterar
                Imagem</button>
        </div>
        <div class="col-md-9">
            <form action="{{route('user.update', $user->id)}}" method="POST" id="form-update">
                <div class="row">
                    @csrf
                    <div class="col-md-6">
                        <label class="label" for="name">Nome Completo</label>
                        <input value="{{$user->name}}" name="name" type="text" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="label" for="name">Email</label>
                        <input value="{{$user->email}}" name="email" type="text" class="form-control">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label class="label" for="name">Senha</label>
                        <input type="password" name="password" placeholder="******" class="form-control">
                    </div>
                </div>
  
                @if($user->nivel_acesso == 2 || Auth::user()->nivel_acesso == 1)
                <div class="row">
                    <div class="col-md-6">
                        <strong class="text-primary">Dados para reembolso:</strong><br/>
                            <label for="banco">Banco</label>
                            <select class="form-control" name="banco" id="">
                                @foreach ($bancos as $b)
                                    <option   {{ ($user->banco == $b->banco) ? 'selected' : '' }}   value="{{$b->banco}}">{{$b->banco}}</option>
                                @endforeach

                     
                            </select>
                            <br/>
                            <label  for="agencia">Agência</label>
                            <input value="{{$user->agencia}}" class="form-control" name="agencia" type="text">
                            <br/>
                            <label for="conta">Conta</label>
                            <input value="{{$user->conta}}" class="form-control" name="conta" type="text">
                            <br/>
                    </div>
                </div>
                @endif
        </form>
            <div style="margin-top: 35px;" class="col-md-6 text-center">
                <button id="btn-update" class="w-50 btn btn-principal text-light">Salvar</button>

        </div>
    </div>

</section>


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

                        <strong>Selecionar imagem:</strong>

                        <input class="form-control" type="file" id="image">

                        <button class="btn btn-principal btn-block text-light upload-image" style="margin-top:2%">Trocar
                            Foto
                            de
                            Perfil</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</div>

<link rel="stylesheet" href="{{URL::asset('/js/croppie/croppie.css')}}">
<script src="{{URL::asset('/js/croppie/croppie.min.js')}}"></script>
<script src="{{URL::asset('/js/usuario-edit.js')}}"></script>
<script>
    $('#btn-update').click(function () {
        var dados = $('#form-update').serialize();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '/usuarios/validarUpdate',
            type: 'post',
            dataType: 'json',
            data: dados,
            success: function (data) {
                console.log('cheogu');
                $('#form-update').submit();
            },
            error: function (data) {
                var errors = data.responseJSON;
                var errorsHtml = '<div class="alert alert-danger"> <ul>';

                $.each(errors.errors, function (key, value) {
                    errorsHtml += '<li style="color:white;">' + value +
                        '</li>'; //showing only the first error.
                });
                errorsHtml += '</ul></div> ';
                console.log(errors);
                console.log(errorsHtml);
                $('#form-update-errors').html(errorsHtml);
            }
        })

    });

    $('#cpf').mask('000.000.000-00', {reverse: true});
</script>

@endsection
