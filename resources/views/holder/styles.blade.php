<style>
    *{
        margin: 0;
        padding: 0;
    }

    body, html {
        height: 100%;
        width: 100%;
    }

    body {
        background: none no-repeat scroll center center / cover {{ getenv("HOLDER_COLOR") }};
    }


    #container-geral {
        display: table;
        height: 100%;
        position: absolute;
        vertical-align: middle;
        width: 100%;
    }


    .container {
        display: table-cell;
        margin: 0 auto;
        position: relative;
        vertical-align: middle;
        width: 100%;
    }

    #section1 {
        width: 100%;
        text-align: center;
        overflow: auto;
        margin-bottom: 5.2vw;
    }

    .logo {
        text-align: center;
        width: 23.43vw;
        height: 7.81vw;
        max-width: 450px;
        max-height: 150px;
    }

    #text-section1 {
        width: 47.36vw;
    }

    #section2 {
        overflow: auto;
        margin: 1.57vw 3vw 5.26vw;
    }

    #section2 .title {
        color: #fff;
        font-family: 'Ubuntu', sans-serif;
        font-weight: 100;
        text-align: center;
        font-size: 2.3vw;
    }

    #section4 {
        width: 100%;
    }

    #section4 .area-text {
        float: left;
        margin: 40px 0 0 40px;
        overflow: auto;
    }

    #section4 .area-text-p {
        width: 100%;
        text-align: center;
        margin-top: 2.63vw;
        color: #fff;
        line-height: 1.90vw;
    }

    #section4 p {
        font-family: 'Ubuntu', sans-serif;
        font-size: 1.15vw;
    }

    @media only screen and (max-width: 1024px) {

        #section4 p {
            font-size: 2.5vw;
        }
    }
</style>