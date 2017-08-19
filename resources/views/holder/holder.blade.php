<!DOCTYPE html>
<html>
<head lang="pt-br">
    <meta charset="UTF-8">
    <title>{{ getenv("HOLDER_TITLE") }}</title>
    <link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">

    @include("holder.styles")
</head>

<body>


<div id="container-geral">
    <div class="container">
        <section id="section1">

            <img class="logo img-responsive" src="{{ getenv("HOLDER_IMAGE") }}">

        </section>

        <section id="section2">
            <div class="title">
                <h2>{{ getenv("HOLDER_PHRASE") }}</h2>
            </div>
        </section>

        <footer id="section4">
            <div class="area-text-p">
                <p>{{ getenv("HOLDER_CONTACT") }}</p>
            </div>
        </footer>

    </div>
</div>

</body>
</html>