@extends('auth.emails.base')

@section('conteudo')
    <h3>Contato através do site Dr. Saúde!</h3>
    <hr>

    <strong>Nome: </strong>{{ $data['name'] }}<br>
    <strong>Email: </strong>{{ $data['email'] }}<br>
    <strong>Assunto: </strong>{{ $data['subject'] }}<br>
    <strong>Telefone: </strong>{{ $data['phone'] }}<br>
    <strong>Mensagem: </strong>{{ $data['msg'] }}<br>
@stop