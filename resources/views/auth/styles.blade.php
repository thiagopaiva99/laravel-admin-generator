<style>
    * {
        box-sizing: border-box;
    }

    *:focus {
        outline: none;
    }
    body {
        font-family: Arial;
        background-color: {{ getenv("COLOR_PRIMARY") }} !important;
        padding: 50px;
    }
    .login {
        margin: 20px auto;
        width: 300px;
    }
    .login-screen {
        background-color: #FFF;
        padding: 20px;
        border-radius: 5px
    }

    .app-title {
        text-align: center;
        color: #777;
    }

    .login-form {
        text-align: center;
    }
    .control-group {
        margin-bottom: 10px;
    }

    input {
        text-align: center;
        background-color: #ECF0F1;
        border: 2px solid transparent;
        border-radius: 3px;
        font-size: 16px;
        font-weight: 200;
        padding: 10px 0;
        width: 250px;
        transition: border .5s;
    }

    input:focus {
        border: 2px solid {{ getenv("COLOR_PRIMARY") }};
        box-shadow: none;
    }

    .btn {
        border: 2px solid transparent;
        background: {{ getenv("COLOR_PRIMARY") }};
        color: #ffffff;
        font-size: 16px;
        line-height: 25px;
        padding: 10px 0;
        text-decoration: none;
        text-shadow: none;
        border-radius: 3px;
        box-shadow: none;
        transition: 0.25s;
        display: block;
        width: 250px;
        margin: 0 auto;
    }

    .btn:hover {
        background-color: {{ getenv("COLOR_PRIMARY_DARK") }} !important;
        border: none !important;
    }

    .login-link {
        font-size: 12px;
        color: #444;
        display: block;
        margin-top: 12px;
    }
</style>