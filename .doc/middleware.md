# Middleware

Ações executadas antes da resposta da requisição

    use Mizi/Middleware

### Estrutura

As middlewares são funções que recebem um valor, realizam uma ação e chamam a proxima. 
O template basico de uma middleware é o seguinte

    function ($next){
        ...action
        return $next();
    }

### Adicionando Middlewares

    php mx create.middleware [nomeDaMiddleware]

Isso vai criair um arquivo dentro do namespace **Middleware** com o nome fornecido

### Executando Middlewares
Para executar middlewares, utilize o metodo estatiico **run** informando a ação que deve ser tomada no final das middlewares

    $md = new InstanceMiddlewareQueue();
    $md->add('md1','md2','md3'...);

    result = $md->run('action');
