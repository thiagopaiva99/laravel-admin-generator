@extends('auth.emails.base')

@section('conteudo')
			<p>Solicitação de nova senha</p>  <br />
			<a style="font-size:20px; text-decoration:none; background:#1BAB7D; color:#FFFFFF; padding:10px;" href="{{ $link = url('password/reset', $token).'?email='.urlencode($user->getEmailForPasswordReset()) }}">
				<strong>Criar nova senha </strong>
			</a>
			<br /><br /><br /><br />
			<p>Caso não tenha solicitado uma nova senha entre em contato com nossa equipe pelo e-mail contato@drsaude.com.br</p>
			<br />
@stop