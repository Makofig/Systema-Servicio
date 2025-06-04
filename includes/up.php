
<header class="bar-up">
    <div class="search">
        <form accept-charset="utf-8" action="/buscar" method="POST">
            <input type="text" name="busqueda" placeholder="Buscar..." />
            <button>
                <img src="/assets/img/buscador2.png" alt="Enviar" width="20" height="20">
            </button>
        </form>
    </div>    
    <div class="conteiner">
        <div class="block color-2"><h3>Clientes: <?= ClienteTotal($db)?></h3></div>
        <div class="block color-2"><h3>5mb: <?= ClienteVip($db)?></h3></div>
        <div class="block color-2"><h3>3mb: <?= ClientePremiun($db)?></h3></div>
        <div class="block color-4"><h3>Total: $<?= number_format(Total($db), 2)?></h3></div>
        <div class="block color-1">
            <a href="/cliente/pagado/1">
                <h3>Pagado: $<?= number_format(Recaudado($db), 2)?></h3>
            </a>
        </div>
        <div class="block color-3">
            <a href="/cliente/deudores/1">
                <h3>Deuda: $<?= number_format(deuda($db), 2)?></h3>
            </a>
        </div>
    </div>
</header>
