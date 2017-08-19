@extends('auth.emails.base')

@section('conteudo')
			<p>Solicitação de nova senha</p>  <br />
			<a style="font-size:20px; text-decoration:none; background:#571C36; color:#FFFFFF; padding:10px;" href="{{ $link = url('password/reset', $token).'?email='.urlencode($user->getEmailForPasswordReset()) }}">
				<strong>Criar nova senha </strong>
			</a>
			<br /><br /><br />
			<p>Caso não tenha solicitado uma nova senha entre em contato com nossa equipe pelo e-mail contato@embelezzo.com.br</p>
			<br />
@stop