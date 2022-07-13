### Router

Controla rotas do sistema

    use Mizi/Router

---
### Criando rotas automaticamente
A classe permite criar rotas automaticamente mapeando um diretório de action

    Router::map('path');

O diretório deve conter uma estrutura de pastas que corresponde as rotas criadas
Para uma rota responder a uma requisição com um numero indefinido de parametros, utilize o nome **\[...]**
Para uma rota responder a uma requisição com nome do deiretório principal, utilize o nome **\[-]**

ex:

    app
     - website
     - blog
      - post
       - [IdPost]
      - [...]
     - contact

seria o mesmo que

    Router::add('website',...);
    Router::add('blog',...);
    Router::add('blog/post',...);
    Router::add('blog/post/[IdPost]',...);
    Router::add('blog/post/...',...);
    Router::add('contact',...);

Dentro dos diretórios, deve conter um arquivo com nome **action.php**. Este arquivo deve retornar a resposta da view

    return new class{
        function get(){
            ...
        }
        function post(){
            ...
        }
    };

No diretório, tambem pode conter aquivos da view da rota.
A view da rota será automativamente mapeada para a referencia  **TRUE** (view(true);)

Veja mais [view](https://github.com/mizi-php/middleware/tree/main/.doc/view.md)

Views automáticas, podem manipular as middlewares, adicionando ou removendo conforme a nescessidade.
Para isso, utilize a classe [middleware](https://github.com/mizi-php/middleware/tree/main/.doc/middleware.md) antes de retornar a resposta

    Middleware::add('novaMiddlewre');
    Middleware::remove('middlewareAntiga');

    return new class{
        function get(){
            ...
        }
        function post(){
            ...
        }
    };

---

### Criando rotas manualmente
A classe conta um metodo para adiconar rotas manualmente

 - **Router::add**: Adiciona uma rota para todas as requisições

    Router::add($template,$response);

> As ordem de declaração das rotas não importa pra a interpretação. A classe vai organizar as rotas da maneira mais segura possivel. 

Para resolver as rotas, utilize o metodo **solve**

    Router::solve();

### Grupo de rotas
Para adicionar um grupo de rotas, utilize o metodo **group**

    Router::group('user',function(){
        Router::add(...)
    });

As rotas definidas dentro do grupo serão adicionadas como **grupo**/**route**

Pode-se mapear diretórios de rota dentro de um grupo

    Router::group('website',function(){
        Router::map('web');
    });

### Template
O template é a forma como a rota será encontrada na URL.

    Router::add('shop')// Reponde a URL /shop
    Router::add('blog')// Reponde a URL /blog
    Router::add('blog/post')// Reponde a URL /blog/post
    Router::add('')// Reponde a URL em branco

Para definir um parametro dinamico no template, utilize **[#]**

    Router::add('blog/[#]')// Reponde a URL /blog/[alguma coisa]
    Router::add('blog/post/[#]')// Reponde a URL /blog/post/[alguma coisa]

Caso a rota deva aceitar mais parametros alem do definido no template, utilize o sufixo **...**

    Router::add('blog...')// Reponde a URL /blog/[qualquer numero de parametros]

Os parametros dinamicos podem ser recuperados utilizando a classe **Router**

    Router::data();

Para nomear os parametros dinamicos, pasta adicionar um nome ao **[#]**

    Router::add('blog/[#postId]')
    Router::add('blog/post/[#imageId]')

Os parametros nomeados, tambem podem ser recuperados da mesma forma dos não nomeados. Estes são tambem adicionados diretamente ao **data** da **Router**

    Router::data()['postId'];
    Router::data()['imageId'];
    
    ou
    
    Router::data('postId');
    Router::data('imageId');

### Resposta
A classe está preparada pra receber 7 tipos de repostas diferentes para as rotas

**null**
Trata a rota como não existente (404)

**callable**
Responda a rota com uma função anonima
A respota será o retorno da função anonima

    Router::get('', function (){
        return ...
    });

Pode recuperar um parametro dinamico informando-o como parametro para a função

    Router::get('blog/[#postId]', function ($postId){
        return ...
    });

**string iniciada em (#)**
Retorna uma string de dbug

**string iniciada em (@)**
Resolve a rota como uma action

**string iniciada em (!)**
Retorna um arquivo

**string iniciada em (>)**
Redireciona o backend para outra URL

**string**
Resolve a rota como uma class controller

---

### Middlewares
Para adicionar um middleware você deve utilizar o metodod **middleware**

    Router::middleware($route,'middleware');

Pode-se adicionar varias middlewares de uma vez enviando um array para a action

    Router::middleware($route,['middleware1','middleware2','middleware3','middleware4']);

**veja**: [middleware](https://github.com/mizi-php/server-back/tree/main/.doc/middleware.md)